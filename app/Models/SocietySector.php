<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietySector extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }

    public function users(){
        return $this->hasMany('App\Models\User', 'society_sector_id', 'id');
    }
}
