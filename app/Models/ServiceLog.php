<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{

    protected $table = 'service_logs';
    protected $guarded = [];
    
    protected $dates = ['created_at','updated_at'];
    protected $appends = ['log_date','log_update'];
    public function getLogDateAttribute(){
        return $this->created_at->format('h:i A d/m/Y');
    }
       
    public function getLogUpdateAttribute(){
        if($this->updated_at !=''){
            return $this->updated_at->format('h:i A d/m/Y');
        }else{
            return '';
        }
        
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }
}
