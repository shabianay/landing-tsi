<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * - invoice_payments: each payment with its own date (DP, pelunasan, ...).
 *   invoices.paid_amount stays as the running sum, kept in sync by the app.
 * - invoices.order_id: the client project an invoice bills for.
 * - invoices.share_token: secret for the client-facing PDF link.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('paid_at')->index();
            $table->string('method', 30)->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('order_client_id')->constrained()->nullOnDelete();
            $table->string('share_token', 64)->nullable()->unique()->after('invoice_number');
        });

        // Existing payments become one history entry dated on the invoice date
        // (the real payment dates were never recorded).
        $now = now();
        DB::table('invoices')->where('paid_amount', '>', 0)->orderBy('id')
            ->each(function ($invoice) use ($now) {
                DB::table('invoice_payments')->insert([
                    'invoice_id' => $invoice->id,
                    'amount' => $invoice->paid_amount,
                    'paid_at' => $invoice->invoice_date,
                    'note' => 'Tercatat sebelum ada riwayat pembayaran (tanggal = tanggal invoice).',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropUnique(['share_token']);
            $table->dropColumn('share_token');
        });
        Schema::dropIfExists('invoice_payments');
    }
};
