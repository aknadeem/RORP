<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SopLaw extends Model
{

    protected $guarded = [];
    public $table = 'sop_laws';
    
    public function societies()
    {
        return $this->belongsToMany('App\Models\Society','sop_laws_societies','sop_law_id','society_id')->withTimestamps();
    }
}
