<?php

namespace App\Models;

use App\Models\UserLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'user_level_id','id');
    }

}
