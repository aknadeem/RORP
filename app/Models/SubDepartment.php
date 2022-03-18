<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    protected $guarded = [];
    public $table = 'sub_departments';

    protected $appends = ['society_id','is_service','is_complaint'];


    public function department(){

        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }

    // public function department(){

    //     return $this->belongsToMany('App\Models\SubDepartmentSupervisor', 'sub_department_id', 'id');
    // }

    public function supervisors(){
        return $this->hasMany('App\Models\SubDepartmentSupervisor', 'sub_department_id', 'id');
    }

    public function asstmanager()
    {
        return $this->hasOne('App\Models\SubDepartmentManager','sub_department_id','id');
    }

    public function getSocietyIdAttribute(){
        if($this->department !=''){
            return $this->department->society_id;
        }else{
            return '';
        }
    }

    public function getIsServiceAttribute(){
         if($this->department !=''){
            return $this->department->is_service;
        }else{
            return '';
        }
    }

    public function getIsComplaintAttribute(){
         if($this->department !=''){
           return $this->department->is_complaint;
        }else{
            return '';
        }
    }

    public function services()
    {
        return $this->hasMany('App\Models\Service','sub_type_id','id');
    } 

}
