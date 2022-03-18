<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFourSeven extends Model
{
    protected $guarded = [];
    public $table = 'two_four_sevens';
    
    public function societies()
    {
        return $this->belongsToMany('App\Models\Society','societies_two_four_sevens','two_four_seven_id','society_id')->withTimestamps();
    }
}
