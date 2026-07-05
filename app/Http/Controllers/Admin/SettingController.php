<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::select('group')->distinct()->orderBy('group')->pluck('group');
        $settings = Setting::orderBy('group')->orderBy('id')->get();
        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'group' => 'required|string|max:100',
            'label' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        Setting::create($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Setting berhasil ditambahkan.');
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'key' => 'required|string|max:255|unique:settings,key,' . $setting->id,
            'value' => 'nullable|string',
            'group' => 'required|string|max:100',
            'label' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ];

        if ($setting->key === 'logo') {
            $rules['value'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048';
        }

        $validated = $request->validate($rules);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $setting->is_active;
        }

        if ($setting->key === 'logo' && $request->hasFile('value')) {
            $path = $request->file('value')->store('settings', 'public');
            $validated['value'] = 'storage/' . $path;
        }

        $setting->update($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Setting berhasil diperbarui.');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Setting berhasil dihapus.');
    }
}
