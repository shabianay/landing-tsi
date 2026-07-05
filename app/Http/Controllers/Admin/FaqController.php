<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['question', 'answer'];

    public function index()
    {
        $faqs = Faq::with('translations')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $faq = Faq::create($validated);
        $this->saveTranslations($request, $faq, $this->translatable);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        $faq->load('translations');
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $faq->is_active;
        }

        $faq->update($validated);
        $this->saveTranslations($request, $faq, $this->translatable);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus.');
    }
}
