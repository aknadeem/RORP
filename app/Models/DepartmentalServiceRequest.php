<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentalServiceRequest extends Model
{
    protected $guarded = [];
    public $table = 'departmental_service_requests';
    protected $dates = ['created_at','updated_at'];
    protected $appends = ['request_status_val'];

    public function getRequestStatusValAttribute()
    {
        return \App\Helpers\Constant::REQUEST_STATUS_VAL[$this->request_status];
    }

    public function service()
    {
        return $this->belongsTo('App\Models\DepartmentalService', 'departmental_service_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id','id');
    }

    public function subdepartment()
    {
        return $this->belongsTo('App\Models\SubDepartment','sub_department_id','id');
    }

    public function RequestBy()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }

    public function referto()
    {
        return $this->belongsTo('App\Models\User','refer_to','id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\DepartmentalServiceRequestLog','request_id','id')->orderBy('id', 'DESC');
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


