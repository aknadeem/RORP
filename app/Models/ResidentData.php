<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentData extends Model
{
    protected $guarded = [];
    protected $hidden = ['password'];
    
    public $table = 'resident_data';

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function society() {
        return $this->belongsTo('App\Models\Society','society_id','id');
    }

    public function sector() {
        return $this->belongsTo('App\Models\SocietySector','society_sector_id','id');
    }

    public function user_data() {
        return $this->hasOne('App\Models\User','resident_id','id');
    }

    public function familes() {
        return $this->hasMany('App\Models\ResidentFamily','resident_data_id', 'id');
    }

    public function handymen() {
        return $this->hasMany('App\Models\ResidentHandyMan','resident_data_id', 'id');
    }
    
    public function servents() {
        return $this->hasMany('App\Models\ResidentServent','resident_data_id', 'id');
    }

    public function vehicles() {
        return $this->hasMany('App\Models\ResidentVehicleInfo','resident_data_id', 'id');
    }

    // public function tenants() {
    //     return $this->hasMany('App\Models\ResidentData','id', 'landlord_id');
    // }
    
    public function tenants() {
        return $this->hasMany('App\Models\ResidentData','landlord_id', 'id');
    }

    public function landlord() {
        return $this->hasOne('App\Models\ResidentData','id', 'landlord_id');
    }
}
