<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SocietySos extends Model
{
    protected $guarded = [];
    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }
}
