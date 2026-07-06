<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'content', 'button_text'];

    public function index()
    {
        $popups = Popup::with('translations')->orderBy('id', 'desc')->get();
        return view('admin.popups.index', compact('popups'));
    }

    public function create()
    {
        return view('admin.popups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trans_title_id' => 'required|string|max:255',
            'trans_content_id' => 'required|string',
            'button_url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'display_delay' => 'nullable|integer|min:0|max:30',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $validated['title'] = $request->input('trans_title_id');
        $validated['content'] = $request->input('trans_content_id');
        $validated['is_active'] = $request->boolean('is_active');

        $popup = Popup::create($validated);
        $this->saveTranslations($request, $popup, $this->translatable);

        return redirect()->route('admin.popups.index')->with('success', 'Popup berhasil ditambahkan.');
    }

    public function edit(Popup $popup)
    {
        $popup->load('translations');
        return view('admin.popups.edit', compact('popup'));
    }

    public function update(Request $request, Popup $popup)
    {
        $validated = $request->validate([
            'trans_title_id' => 'required|string|max:255',
            'trans_content_id' => 'required|string',
            'button_url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'display_delay' => 'nullable|integer|min:0|max:30',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $validated['title'] = $request->input('trans_title_id');
        $validated['content'] = $request->input('trans_content_id');
        $validated['is_active'] = $request->boolean('is_active');

        $popup->update($validated);
        $this->saveTranslations($request, $popup, $this->translatable);

        return redirect()->route('admin.popups.index')->with('success', 'Popup berhasil diperbarui.');
    }

    public function destroy(Popup $popup)
    {
        $popup->delete();

        return redirect()->route('admin.popups.index')->with('success', 'Popup berhasil dihapus.');
    }
}
