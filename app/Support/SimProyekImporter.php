<?php

namespace App\Support;

use App\Http\Controllers\Admin\InvoiceController;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderClient;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * Imports an export of the old SIM-Proyek database (JSON) into the admin panel.
 * Safe to run twice: rows that already exist are skipped, not duplicated.
 */
class SimProyekImporter
{
    /** Accepted payload sources: the old SIM-Proyek app, and invoices recovered from the PDF archive. */
    public const SOURCES = ['SIM-Proyek', 'Arsip Invoice PDF'];

    private const SETTING_MAP = [
        'company_name' => 'invoice_company_name',
        'company_address' => 'invoice_company_address',
        'company_phone' => 'invoice_company_phone',
        'company_email' => 'invoice_company_email',
        'invoice_footer' => 'invoice_footer',
        'payment_info' => 'invoice_payment_info',
        'payment_note' => 'invoice_payment_note',
    ];

    /** @return array{clients:int, orders:int, invoices:int, items:int, logs:int, settings:int, skipped:int} */
    public function import(array $payload, ?int $userId = null): array
    {
        if (! in_array($payload['source'] ?? null, self::SOURCES, true)) {
            throw new InvalidArgumentException('File ini bukan ekspor SIM-Proyek atau arsip invoice.');
        }
        $this->validateStructure($payload);

        // An archive export may replace invoice settings that are already filled in.
        $overrideSettings = (bool) ($payload['override_settings'] ?? false);

        $count = ['clients' => 0, 'orders' => 0, 'invoices' => 0, 'items' => 0, 'logs' => 0, 'settings' => 0, 'skipped' => 0];

        DB::transaction(function () use ($payload, $userId, $overrideSettings, &$count) {
            $clientMap = [];
            foreach ($payload['clients'] ?? [] as $row) {
                $existing = OrderClient::where('name', $row['name'])
                    ->where('email', $row['email'] ?? null)
                    ->where('phone', $row['phone'] ?? null)
                    ->first();
                if ($existing) {
                    $clientMap[$row['id']] = $existing->id;
                    $count['skipped']++;
                    continue;
                }
                $client = $this->createWithTimestamps(OrderClient::class, Arr::only($row, ['name', 'email', 'phone', 'address', 'company', 'notes']), $row);
                $clientMap[$row['id']] = $client->id;
                $count['clients']++;
            }

            foreach ($payload['projects'] ?? [] as $row) {
                $exists = Order::where('client_name', $row['client_name'])
                    ->where('task_type', $row['task_type'])
                    ->whereDate('deadline', $row['deadline'])
                    ->where('created_at', $row['created_at'] ?? null)
                    ->exists();
                if ($exists) {
                    $count['skipped']++;
                    continue;
                }
                $status = in_array($row['status'] ?? null, Order::STATUSES, true) ? $row['status'] : 'Pending';
                $this->createWithTimestamps(Order::class, [
                    'order_client_id' => $clientMap[$row['client_id'] ?? 0] ?? null,
                    'client_name' => $row['client_name'],
                    'task_type' => $row['task_type'],
                    'description' => $row['description'] ?? null,
                    'deadline' => $row['deadline'],
                    'price' => $row['price'] ?? 0,
                    'status' => $status,
                    'payment_method' => $row['payment_method'] ?? null,
                    'payment_date' => $row['payment_date'] ?? null,
                    'payment_lunas_date' => $row['payment_lunas_date'] ?? null,
                ], $row);
                $count['orders']++;
            }

            foreach ($payload['invoices'] ?? [] as $row) {
                if (Invoice::where('invoice_number', $row['invoice_number'])->exists()) {
                    $count['skipped']++;
                    continue;
                }
                $invoice = $this->createWithTimestamps(Invoice::class, [
                    'order_client_id' => $clientMap[$row['client_id'] ?? 0] ?? null,
                    'invoice_number' => $row['invoice_number'],
                    'invoice_date' => $row['invoice_date'],
                    'client_name' => $row['client_name'],
                    'client_address' => $row['client_address'] ?? null,
                    'notes' => $row['notes'] ?? null,
                    'paid_amount' => $row['paid_amount'] ?? 0,
                    // SIM-Proyek and the archived PDFs charged PPN at 10%.
                    'ppn_rate' => (float) ($row['ppn_amount'] ?? 0) > 0 ? 10 : 0,
                    'ppn_amount' => $row['ppn_amount'] ?? 0,
                    'discount_amount' => $row['discount_amount'] ?? 0,
                    'discount_type' => ($row['discount_type'] ?? 'fixed') === 'percent' ? 'percent' : 'fixed',
                ], $row);
                foreach ($row['items'] ?? [] as $item) {
                    $invoice->items()->create([
                        'item_name' => $item['item_name'],
                        'item_qty' => max(1, (int) $item['item_qty']),
                        'item_price' => $item['item_price'],
                    ]);
                    $count['items']++;
                }
                // The old app kept only a total paid; it becomes one history entry on the invoice date.
                if ((float) ($row['paid_amount'] ?? 0) > 0) {
                    $invoice->payments()->create([
                        'amount' => $row['paid_amount'],
                        'paid_at' => $row['invoice_date'],
                        'note' => 'Diimpor (tanggal = tanggal invoice).',
                    ]);
                }
                $count['invoices']++;
            }

            foreach ($payload['activity_logs'] ?? [] as $row) {
                $description = '[SIM-Proyek] ' . ($row['description'] ?? '');
                $exists = ActivityLog::where('created_at', $row['created_at'] ?? null)
                    ->where('description', $description)
                    ->exists();
                if ($exists) {
                    $count['skipped']++;
                    continue;
                }
                $this->createWithTimestamps(ActivityLog::class, [
                    'user_id' => $userId,
                    'action' => mb_substr((string) ($row['action'] ?? 'updated'), 0, 20),
                    'model_type' => mb_substr((string) ($row['model_type'] ?? 'SIM-Proyek'), 0, 40),
                    'model_id' => null,
                    'description' => $description,
                ], $row);
                $count['logs']++;
            }

            foreach (self::SETTING_MAP as $from => $to) {
                $value = $payload['settings'][$from] ?? null;
                if (filled($value) && ($overrideSettings || blank(Setting::get($to)))) {
                    Setting::updateOrCreate(['key' => $to], ['value' => $value, 'group' => 'invoice', 'label' => InvoiceController::SETTINGS[$to] ?? $to]);
                    $count['settings']++;
                }
            }
        });

        return $count;
    }

    /** Reject a file with missing or wrong fields with a readable message instead of a server error. */
    private function validateStructure(array $payload): void
    {
        $money = ['nullable', 'numeric', 'min:0', 'max:' . Invoice::MAX_AMOUNT];
        $validator = Validator::make($payload, [
            'clients' => ['nullable', 'array'],
            'clients.*.id' => ['required', 'integer'],
            'clients.*.name' => ['required', 'string', 'max:255'],
            'clients.*.email' => ['nullable', 'string', 'max:255'],
            'clients.*.phone' => ['nullable', 'string', 'max:30'],
            'clients.*.address' => ['nullable', 'string', 'max:2000'],
            'clients.*.company' => ['nullable', 'string', 'max:255'],
            'clients.*.notes' => ['nullable', 'string', 'max:5000'],
            'clients.*.created_at' => ['nullable', 'date'],
            'clients.*.updated_at' => ['nullable', 'date'],
            'projects' => ['nullable', 'array'],
            'projects.*.client_id' => ['nullable', 'integer'],
            'projects.*.client_name' => ['required', 'string', 'max:255'],
            'projects.*.task_type' => ['required', 'string', 'max:255'],
            'projects.*.description' => ['nullable', 'string', 'max:5000'],
            'projects.*.deadline' => ['required', 'date'],
            'projects.*.price' => $money,
            'projects.*.payment_method' => ['nullable', 'string', 'max:30'],
            'projects.*.payment_date' => ['nullable', 'date'],
            'projects.*.payment_lunas_date' => ['nullable', 'date'],
            'projects.*.created_at' => ['nullable', 'date'],
            'projects.*.updated_at' => ['nullable', 'date'],
            'invoices' => ['nullable', 'array'],
            'invoices.*.client_id' => ['nullable', 'integer'],
            'invoices.*.invoice_number' => ['required', 'string', 'max:50'],
            'invoices.*.invoice_date' => ['required', 'date'],
            'invoices.*.client_name' => ['required', 'string', 'max:255'],
            'invoices.*.client_address' => ['nullable', 'string', 'max:2000'],
            'invoices.*.notes' => ['nullable', 'string', 'max:5000'],
            'invoices.*.paid_amount' => $money,
            'invoices.*.ppn_amount' => $money,
            'invoices.*.discount_amount' => $money,
            'invoices.*.created_at' => ['nullable', 'date'],
            'invoices.*.updated_at' => ['nullable', 'date'],
            'invoices.*.items' => ['nullable', 'array'],
            'invoices.*.items.*.item_name' => ['required', 'string', 'max:255'],
            'invoices.*.items.*.item_qty' => ['required', 'integer', 'min:1', 'max:100000'],
            'invoices.*.items.*.item_price' => ['required', 'numeric', 'min:0', 'max:' . Invoice::MAX_AMOUNT],
            'activity_logs' => ['nullable', 'array'],
            'activity_logs.*.description' => ['nullable', 'string', 'max:5000'],
            'activity_logs.*.created_at' => ['nullable', 'date'],
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->keys()[0];
            throw new InvalidArgumentException("Isi file tidak sesuai format ({$key}): " . $validator->errors()->first());
        }
    }

    /** Keep the original created_at / updated_at from SIM-Proyek. */
    private function createWithTimestamps(string $model, array $attributes, array $source)
    {
        $record = new $model();
        $record->forceFill($attributes);
        $record->timestamps = false;
        $record->created_at = $source['created_at'] ?? now();
        $record->updated_at = $source['updated_at'] ?? $record->created_at;
        $record->save();
        $record->timestamps = true;

        return $record;
    }
}
