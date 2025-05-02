<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Project\Database;

class ProjectConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'host',
        'port',
        'username',
        'password',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function databases()
    {
        return $this->hasMany(Database::class);
    }
}
