<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DepartmentHod extends Model
{
    protected $guarded = [];
    public $table = 'department_hods';
    // protected $with = ['hod','department'];
    public function hod(){
        return $this->belongsTo('App\Models\User', 'hod_id', 'id');
    }
    
    public function department(){
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }
    
    public function accountdepartment(){
        return $this->belongsTo('App\Models\Department', 'department_id', 'id')->where('slug','accounts-finance');
    }
}
