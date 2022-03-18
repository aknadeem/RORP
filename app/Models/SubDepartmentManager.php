<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDepartmentManager extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('App\Models\User','manager_id','id');
    }

    public function subdepartment(){
        return $this->belongsTo('App\Models\SubDepartment', 'sub_department_id', 'id');
    }
}
