<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject,ShouldQueue
{
    use Notifiable;
    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['level_slug'];

    public function societies(){
        return $this->belongsToMany('App\Models\Society', 'socities_admins','user_id','society_id');
    }  

    // public function societies(){
    //     return $this->belongsToMany('App\Models\Society', 'socities_admins','user_id','society_id')->withTimestamps();
    // }

    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }

    public function sector(){
        return $this->belongsTo('App\Models\SocietySector', 'society_sector_id', 'id');
    }
    public function userlevel(){
        return $this->belongsTo('App\Models\UserLevel', 'user_level_id', 'id')->withDefault(['id' => 0]);
    }
    public function profile(){
        return $this->belongsTo('App\Models\ResidentData', 'resident_id', 'id')->withDefault(['id' => 0]);
    }

    public function userservices(){
        return $this->hasMany('App\Models\UserService', 'user_id', 'id');
    }

    public function services(){
        return $this->hasMany('App\Models\UserService', 'user_id', 'id');
    }

    public function getLevelSlugAttribute()
    {
        return $this->userlevel->slug;
    }

    // public function hod(){
    //     return $this->belongsTo('App\Models\DepartmentHod', 'hod_id', 'id');
    // }
    public function departments() {
        return $this->hasMany('App\Models\DepartmentHod','hod_id','id');
    }

    public function subdepartments() {
        return $this->hasMany('App\Models\SubDepartmentManager','manager_id','id');
    }
    
    public function supervisor_subdepartments() {
        return $this->hasMany('App\Models\SubDepartmentSupervisor','supervisor_id','id');
    }


    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function getStatusTypeAttribute(){
        $status = '';
        if($this->is_active == 1){
          $status = 'Active';
        }else{
          $status = 'Closed';
        }
        return $status;
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }
     
    public function getJWTCustomClaims() {
        return [];
    }
}
