<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDOException;

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
        return Inertia::render('Projects/Connections', [
            'project' => $project,
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

    public function testConnection(Request $request)
    {
        $type = $request->input('type');
        if($type == 'live') {
            $url = $request->input('live_url');
            $username = $request->input('live_username');   
            $password = $request->input('live_password');
            $port = $request->input('live_port');
        } elseif ($type == 'dev') {
            $url = $request->input('dev_url');
            $username = $request->input('dev_username');   
            $password = $request->input('dev_password');
            $port = $request->input('dev_port');
        } else {
            return redirect()->back()->with('error', 'Invalid connection type.');
        }

        $config = [
            'driver' => 'mysql',
            'host' => $url,
            'port' => $port ?? 3306,
            'database' => 'information_schema', // lightweight default DB
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];
    
        try {
            // Create connection on-the-fly
            DB::purge('mysql_dynamic');
            config(['database.connections.mysql_dynamic' => $config]);
    
            // Force Laravel to reconnect with new config
            DB::connection('mysql_dynamic')->getPdo();
    
            return response()->json(['success' => true, 'message' => 'Connection successful']);
        } catch (PDOException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return redirect()->back()->with('success', 'Connection successful.');

    }
}
