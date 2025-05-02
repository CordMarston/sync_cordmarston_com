<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{

    public function syncSchema(Project $project)
    {
        Log::info('Syncing schema for project: ' . $project->name);
        // return true;
        // Temporary dynamic connection
        $config = [
            'driver' => 'mysql',
            'host' => $project->dev_url,
            'port' => $project->dev_port,
            'username' => $project->dev_username,
            'password' => $project->dev_password,
            'database' => $project->name,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        config(['database.connections.dev_sync' => $config]);

        try {
            DB::purge('dev_sync');
            DB::reconnect('dev_sync');

            // Get tables and clear old ones
            $tables = DB::connection('dev_sync')->select('SHOW TABLE STATUS');
            $project->devTables()->delete();

            foreach ($tables as $tableInfo) {
                $devTable = $project->devTables()->create([
                    'name' => $tableInfo->Name,
                    'comment' => $tableInfo->Comment ?? null,
                    'row_count' => $tableInfo->Rows ?? null,
                ]);

                $columns = DB::connection('dev_sync')
                    ->select("SHOW FULL COLUMNS FROM `{$tableInfo->Name}`");

                foreach ($columns as $col) {
                    preg_match('/^(\w+)(?:\(([\d,]+)\))?/', $col->Type, $matches);
                    $type = $matches[1] ?? $col->Type;
                    $length = isset($matches[2]) ? intval(explode(',', $matches[2])[0]) : null;

                    $devTable->devColumns()->create([
                        'name' => $col->Field,
                        'type' => $type,
                        'length' => $length,
                        'nullable' => $col->Null === 'YES',
                        'default' => $col->Default,
                        'auto_increment' => str_contains(strtolower($col->Extra), 'auto_increment'),
                        'key' => $col->Key ?: null,
                        'comment' => $col->Comment ?: null,
                    ]);
                }
            }

            return response()->json(['message' => 'Metadata synced successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
