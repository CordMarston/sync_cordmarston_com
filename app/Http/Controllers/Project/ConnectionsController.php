<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDOException;
use Illuminate\Support\Facades\DB;
use App\Models\Project\ProjectConnection;

class ConnectionsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'nullable|integer',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $projectConnection = ProjectConnection::create([
            'project_id' => $validated['project_id'],
            'name' => $validated['name'],
            'host' => $validated['host'],
            'port' => $validated['port'] ?? 3306,
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);


        return redirect()->route('projects.connections', ['project' => $validated['project_id']])->with('success', 'Connection created successfully.');
    }

    public function testConnection(Request $request)
    {
        $validated = (object) $request->validate([
            'url' => 'required|string|max:255',
            'port' => 'nullable|integer',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $config = [
            'driver' => 'mysql',
            'host' => $validated->url,
            'port' => $port ?? 3306,
            'database' => 'information_schema', // lightweight default DB
            'username' => $validated->username,
            'password' => $validated->password,
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
