<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Support\SimProyekImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

/** One-time import of data exported from the old SIM-Proyek app. */
class SimImportController extends Controller
{
    public function create(): View
    {
        return view('admin.sim-import');
    }

    public function store(Request $request, SimProyekImporter $importer): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'max:10240', 'mimetypes:application/json,text/plain']], [], ['file' => 'file ekspor']);

        $payload = json_decode((string) file_get_contents($request->file('file')->getRealPath()), true);
        if (! is_array($payload)) {
            return back()->withErrors(['file' => 'Isi file bukan JSON yang valid.']);
        }

        try {
            $result = $importer->import($payload, $request->user()->id);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Nothing is saved: the import runs in one transaction.
            report($e);

            return back()->withErrors(['file' => 'Data di file tidak bisa disimpan (format nilai tidak sesuai). Tidak ada data yang diimpor.']);
        }

        ActivityLog::record('imported', 'SIM-Proyek', sprintf(
            'Impor SIM-Proyek: %d klien, %d proyek, %d invoice, %d log',
            $result['clients'], $result['orders'], $result['invoices'], $result['logs']
        ));

        return redirect()->route('admin.sim-import.create')->with('import_result', $result);
    }
}
