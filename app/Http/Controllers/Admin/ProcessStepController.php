<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'description'];

    public function index()
    {
        $processSteps = ProcessStep::with('translations')->orderBy('step_number')->orderBy('id')->get();
        return view('admin.process-steps.index', compact('processSteps'));
    }

    public function create()
    {
        return view('admin.process-steps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string',
            'step_number' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $processStep = ProcessStep::create($validated);
        $this->saveTranslations($request, $processStep, $this->translatable);

        return redirect()->route('admin.process-steps.index')->with('success', __('Proses berhasil ditambahkan.'));
    }

    public function edit(ProcessStep $processStep)
    {
        $processStep->load('translations');
        return view('admin.process-steps.edit', compact('processStep'));
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string',
            'step_number' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $processStep->is_active;
        }

        $processStep->update($validated);
        $this->saveTranslations($request, $processStep, $this->translatable);

        return redirect()->route('admin.process-steps.index')->with('success', __('Proses berhasil diperbarui.'));
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return redirect()->route('admin.process-steps.index')->with('success', __('Proses berhasil dihapus.'));
    }
}
