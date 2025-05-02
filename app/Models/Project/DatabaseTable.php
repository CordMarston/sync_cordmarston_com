<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_database_id',
        'name',
        'engine',
        'table_type',
        'table_rows',
        'data_length',
        'index_length',
        'create_time',
        'update_time',
        'collation',
        'comment',
    ];

    public function database()
    {
        return $this->belongsTo(Database::class);
    }

    public function columns()
    {
        return $this->hasMany(TableColumn::class);
    }
}
