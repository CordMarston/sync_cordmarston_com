<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_connection_id',
        'name',
        'collation',
        'character_set',
        'comment',
        'size'
    ];

    public function projectConnection()
    {
        return $this->belongsTo(ProjectConnection::class);
    }

    public function tables()
    {
        return $this->hasMany(DatabaseTable::class, 'connection_database_id');
    }

}
