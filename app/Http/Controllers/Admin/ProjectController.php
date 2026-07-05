<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Client;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'description'];

    public function index()
    {
        $projects = Project::with('client')->with('translations')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('admin.projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'website_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('projects', 'public');
        }

        $project = Project::create($validated);
        $this->saveTranslations($request, $project, $this->translatable);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $clients = Client::orderBy('name')->get();
        $project->load('client')->load('translations');
        return view('admin.projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'website_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects', 'public');
        } elseif ($request->boolean('remove_image') && $project->image) {
            Storage::disk('public')->delete($project->image);
            $validated['image'] = null;
        }

        if ($request->hasFile('og_image')) {
            if ($project->og_image) {
                Storage::disk('public')->delete($project->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('projects', 'public');
        } elseif ($request->boolean('remove_og_image') && $project->og_image) {
            Storage::disk('public')->delete($project->og_image);
            $validated['og_image'] = null;
        }

        $project->update($validated);
        $this->saveTranslations($request, $project, $this->translatable);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->og_image) {
            Storage::disk('public')->delete($project->og_image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}