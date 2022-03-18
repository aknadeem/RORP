<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentalService extends Model
{
    protected $guarded = [];
    public $table = 'departmental_services';
    protected $dates = ['created_at','updated_at'];
    protected $appends = ['type_value'];

    public function getTypeValueAttribute()
    {
        return \App\Helpers\Constant::CHARGES_TYPE_VAL[$this->charges_type];
    }

    public function subdepartment()
    {
        return $this->belongsTo('App\Models\SubDepartment','sub_department_id','id');
    }
    
    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id','id');
    }
    
    public function addedby()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->department->society_id;
        });
        
        static::updated(function($model){
            $model->society_id = $model->department->society_id;
        });
    }
}

