<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


class ProjectController extends Controller
{
    public function create()
    {
        return Inertia::render('Projects/New');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    public function tables(Project $project) 
    {
        return Inertia::render('Projects/Tables', [
            'project' => $project,
        ]);
    }

    public function connections(Project $project) 
    {
        $project->load([
            'connections' => function ($query) {
                $query->withCount('databases');
                $query->withSum('databases', 'size');
            }
        ]);

        return Inertia::render('Projects/Connections', [
            'project' => $project,
            'connections' => $project->connections,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'live_url' => 'required|max:255',
            'live_username' => 'required|string|max:255',
            'live_password' => 'required|string|max:255',
            'live_port' => 'required|integer',
            'dev_url' => 'required|max:255',
            'dev_username' => 'required|string|max:255',
            'dev_password' => 'required|string|max:255',
            'dev_port' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function index()
    {
        $projects = Project::all();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }
}
