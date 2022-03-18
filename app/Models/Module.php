<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function userlevels()
    {
        return $this->belongsToMany(UserLevel::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function submodules()
    {
        return $this->hasMany('App\Models\SubModule', 'module_id', 'id');
    }

}
