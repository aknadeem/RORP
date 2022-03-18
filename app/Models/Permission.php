<?php

namespace App\Models;

use App\Models\UserLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function userlevels()
    {
        return $this->belongsToMany(UserLevel::class);
    }

    public function module(){

        return $this->belongsTo('App\Models\Module', 'module_id', 'id');
    }
}
