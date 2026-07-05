<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Project::with('translations')->orderBy('id', 'desc')->paginate(12);
        return view('portfolio.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::with('translations')->findOrFail($id);

        $related = Project::with('translations')->where('id', '!=', $id)->inRandomOrder()->take(3)->get();

        $prev = Project::with('translations')->where('id', '<', $id)->orderBy('id', 'desc')->first();
        $next = Project::with('translations')->where('id', '>', $id)->orderBy('id')->first();

        return view('portfolio.show', compact('project', 'related', 'prev', 'next'));
    }
}
