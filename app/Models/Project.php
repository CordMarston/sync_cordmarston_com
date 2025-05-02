<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\ProjectConnection;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notes',
    ];

    public function connections()
    {
        return $this->hasMany(ProjectConnection::class);
    }

    // public function setLivePasswordAttribute($value)
    // {
    //     $this->attributes['live_password'] = encrypt($value);
    // }

    // public function getLivePasswordAttribute($value)
    // {
    //     return decrypt($value);
    // }

    // public function setDevPasswordAttribute($value)
    // {
    //     $this->attributes['dev_password'] = encrypt($value);
    // }

    // public function getDevPasswordAttribute($value)
    // {
    //     return decrypt($value);
    // }
}
