<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['name', 'description'];

    public function index()
    {
        $partners = Partner::with('translations')->orderBy('sort_order')->latest()->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'website_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $partner = Partner::create($validated);
        $this->saveTranslations($request, $partner, $this->translatable);

        return redirect()->route('admin.partners.index')->with('success', __('Partner berhasil ditambahkan.'));
    }

    public function edit(Partner $partner)
    {
        $partner->load('translations');
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'website_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $partner->update($validated);
        $this->saveTranslations($request, $partner, $this->translatable);

        return redirect()->route('admin.partners.index')->with('success', __('Partner berhasil diperbarui.'));
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', __('Partner berhasil dihapus.'));
    }
}
