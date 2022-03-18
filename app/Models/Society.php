<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $guarded = [];
    public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function vendors(){
        return $this->hasMany('App\Models\Vendor', 'society_id', 'id');
    }

    public function province(){
        return $this->belongsTo('App\Models\Province', 'province_id', 'id')->withDefault(0);
    }
    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }
    
    public function sectors(){
        return $this->hasMany('App\Models\SocietySector', 'society_id', 'id');
    }

    public function departments(){
        return $this->hasMany('App\Models\Department', 'society_id', 'id');
    }
    
    // New Requirements changes
    
    public function complaints(){
        return $this->hasMany('App\Models\Complaint', 'society_id', 'id');
    }
    public function request_services(){
        return $this->hasMany('App\Models\RequestService', 'society_id', 'id');
    }
    public function smart_services(){
        return $this->hasMany('App\Models\UserService', 'society_id', 'id');
    }
    public function residents()
    {
       return $this->hasMany('App\Models\User', 'society_id', 'id')->where('user_level_id', '>', 5);
    }
    
    public function services()
    {
        return $this->hasManyThrough(
            'App\Models\Service',
            'App\Models\Department',
            'society_id', // Foreign key on the Department table...
            'type_id', // Foreign key on the Service table...
            'id', // Local key on the Society table...
            'id' // Local key on the Service table...
        );
    }
    
    public function getTotalServicesAttribute()
    {
        return $this->request_services->where('service_type','!=','monthly')->count();
    }
    
    public function getPendingServicesAttribute()
    {
        return $this->request_services->where('service_type','!=','monthly')->where('status','open')->count();
    }
    
    public function getInprocessServicesAttribute()
    {
        return $this->request_services->where('service_type','!=','monthly')->whereNotIn('status',['open','closed'])->count();
    }
    
    public function getResolvedServicesAttribute()
    {
        return  $this->request_services->where('service_type','!=','monthly')->where('status', 'closed')->count();
    }
    
    public function getTotalComplaintsAttribute()
    {
        return $this->complaints->count();
    }

    public function getPendingComplaintsAttribute()
    {
        return $this->complaints->where('complaint_status','open')->count();
    }

    public function getInprocessComplaintsAttribute()
    {
        return $this->complaints->whereNotIn('complaint_status',['open','closed'])->count();
    }

    public function getResolvedComplaintsAttribute()
    {
        return $this->complaints->where('complaint_status', 'closed')->count();
    }
}