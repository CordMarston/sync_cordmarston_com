<?php

namespace App\Jobs\Connections;

use App\Models\Project\ProjectConnection;
use App\Models\Project\Database;
use App\Models\Project\DatabaseTable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetectSchema implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ProjectConnection $projectConnection;

    public function __construct(ProjectConnection $projectConnection)
    {
        $this->projectConnection = $projectConnection;
    }

    public function handle()
    {
        $conn = $this->projectConnection;

        $config = [
            'driver' => 'mysql',
            'host' => $conn->host,
            'port' => $conn->port ?? 3306,
            'database' => 'information_schema',
            'username' => $conn->username,
            'password' => $conn->password,
        ];

        config(['database.connections.dynamic' => $config]);

        try {
            $databases = DB::connection('dynamic')->table('SCHEMATA')
                ->select('SCHEMA_NAME', 'DEFAULT_CHARACTER_SET_NAME', 'DEFAULT_COLLATION_NAME')
                ->get();

            foreach ($databases as $dbRow) {
                $size = 0;
                $dbName = $dbRow->SCHEMA_NAME;

                if (in_array($dbName, ['information_schema', 'mysql', 'performance_schema', 'sys'])) {
                    continue;
                }

                $database = Database::create([
                    'project_connection_id' => $conn->id,
                    'name' => $dbName,
                    'character_set' => $dbRow->DEFAULT_CHARACTER_SET_NAME,
                    'collation' => $dbRow->DEFAULT_COLLATION_NAME,
                ]);

                // Fetch tables for this DB
                $tables = DB::connection('dynamic')->table('TABLES')
                    ->select(
                        'TABLE_NAME',
                        'ENGINE',
                        'TABLE_TYPE',
                        'TABLE_ROWS',
                        'DATA_LENGTH',
                        'INDEX_LENGTH',
                        'CREATE_TIME',
                        'UPDATE_TIME',
                        'TABLE_COLLATION',
                        'TABLE_COMMENT'
                    )
                    ->where('TABLE_SCHEMA', $dbName)
                    ->get();

                foreach ($tables as $tableRow) {
                    $size += $tableRow->DATA_LENGTH + $tableRow->INDEX_LENGTH;
                    $database->tables()->create([
                        'name' => $tableRow->TABLE_NAME,
                        'engine' => $tableRow->ENGINE,
                        'table_type' => $tableRow->TABLE_TYPE,
                        'table_rows' => $tableRow->TABLE_ROWS,
                        'data_length' => $tableRow->DATA_LENGTH,
                        'index_length' => $tableRow->INDEX_LENGTH,
                        'create_time' => $tableRow->CREATE_TIME,
                        'update_time' => $tableRow->UPDATE_TIME,
                        'collation' => $tableRow->TABLE_COLLATION,
                        'comment' => $tableRow->TABLE_COMMENT,
                    ]);
                }
                $database->size = $size;
                $database->save();
            }

        } catch (\Exception $e) {
            Log::error("DetectSchema failed: " . $e->getMessage());


        } catch (\Throwable $e) {
            Log::error("DetectSchema failed: " . $e->getMessage());
        }
    }
}
