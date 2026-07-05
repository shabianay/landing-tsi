<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'description'];

    public function index()
    {
        $services = Service::with('translations')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $service = Service::create($validated);
        $this->saveTranslations($request, $service, $this->translatable);

        return redirect()->route('admin.services.index')->with('success', __('Layanan berhasil ditambahkan.'));
    }

    public function edit(Service $service)
    {
        $service->load('translations');
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $service->is_active;
        }

        $service->update($validated);
        $this->saveTranslations($request, $service, $this->translatable);

        return redirect()->route('admin.services.index')->with('success', __('Layanan berhasil diperbarui.'));
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', __('Layanan berhasil dihapus.'));
    }
}
