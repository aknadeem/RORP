<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $guarded = [];
    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }
}
