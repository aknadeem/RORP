<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];
    public $table = 'departments';

    // use SoftDeletes;

    public function hod(){

        return $this->hasOne('App\Models\DepartmentHod', 'department_id', 'id');
    }

    public function subdepartments(){
        return $this->hasMany('App\Models\SubDepartment', 'department_id', 'id');
    }

    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }


    public function services()
    {
        return $this->hasMany('App\Models\Service','type_id', 'id');
    }
    
    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint','department_id', 'id');
    }

    // public function departmental_services()
    // {
    //     return $this->hasMany('App\Models\DepartmentalService', 'department_id', 'id');
    // }
}
