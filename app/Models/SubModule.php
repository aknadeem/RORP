<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $guarded = [];
    
    public $table = 'sub_modules';

    // protected $with = ['hod','department'];

    public function module(){
        return $this->belongsTo('App\Models\Module', 'module_id', 'id');
    }
}
