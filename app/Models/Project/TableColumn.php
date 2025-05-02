<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'database_table_id',
        'name',
        'data_type',
        'column_type',
        'is_nullable',
        'default',
        'is_primary',
        'is_unique',
        'is_auto_increment',
        'collation',
        'comment',
        'extra',
    ];

    public function databaseTable()
    {
        return $this->belongsTo(DatabaseTable::class);
    }
}
