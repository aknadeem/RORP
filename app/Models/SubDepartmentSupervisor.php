<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubDepartmentSupervisor extends Model
{
    protected $guarded = [];
    public $table = 'sub_department_supervisors';

    public function subdepartment(){
        return $this->belongsTo('App\Models\SubDepartment', 'sub_department_id', 'id');
    }

    public function supervisor(){
        return $this->belongsTo('App\Models\User', 'supervisor_id', 'id');
    }


    public function user(){
        return $this->belongsTo('App\Models\User', 'supervisor_id', 'id');
    }

    public function complaint_refers(){
        return $this->hasMany('App\Models\ComplaintRefer', 'refer_to', 'supervisor_id');
    }

    public function service_requests(){
        return $this->hasMany('App\Models\RequestService', 'refer_to', 'supervisor_id');
    }

}
