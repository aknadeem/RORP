<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
	protected $table = 'user_services';
    protected $guarded = [];
    
    protected $dates = ['start_date','end_date','created_at','updated_at'];
    protected $appends = ['service_date','start_date_format','end_date_format'];
    
    public function getServiceDateAttribute(){
        return $this->created_at->format('h:i A d/m/Y');
    }
    
    public function getStartDateFormatAttribute(){
        if($this->start_date){
            return $this->start_date->format('d/m/Y');
        }
        return '';
    }
    
    public function getEndDateFormatAttribute(){
        if($this->end_date !=''){
            return $this->end_date->format('d/m/Y');
        }
        return '';
    }
    
    public function service(){
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function package(){
        return $this->belongsTo('App\Models\ServicePackage', 'package_id', 'id');
    }

    public function servicetype(){
        return $this->belongsTo('App\Models\Department', 'type_id', 'id')->where('is_service', 1);
    }

    public function subtype(){
        return $this->belongsTo('App\Models\SubDepartment', 'sub_type_id', 'id');
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->servicetype->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->servicetype->society_id;
        });
    }

}
