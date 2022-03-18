<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ResidentServent extends Model
{

    protected $guarded = [];
    public $table = 'resident_servents';

    public function residentdata() {
        return $this->belongsTo('App\Models\ResidentData','resident_data_id', 'id');
    }
    
    public function servent_type() {
        return $this->belongsTo('App\Models\ServentType','servent_type_id', 'id');
    }
    
    public function society() {
        return $this->belongsTo('App\Models\Society','society_id', 'id');
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->residentdata->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->residentdata->society_id;
        });
    }
}
