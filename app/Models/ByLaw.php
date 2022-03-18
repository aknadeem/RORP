<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ByLaw extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = 'by_laws';
    
    public function societies()
    {
        return $this->belongsToMany('App\Models\Society','by_laws_societies','by_law_id','society_id')->withTimestamps();
    }
}
