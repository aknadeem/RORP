<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickComplaint extends Model
{
    protected $guarded = [];
    public $table = 'quick_complaints';
    protected $dates = ['created_at','updated_at'];
    protected $appends = ['created_at_format','updated_at_format'];

    public function subdepartment()
    {
        return $this->belongsTo('App\Models\SubDepartment','sub_department_id','id');
    }
    
    public function addedby()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }
    public function getCreatedAtFormatAttribute(Type $var = null)
    {
        if($this->created_at !=''){
            return $this->created_at->format('d M, Y') ?? '';
        }else{
            return '';
        }
    } 
    public function getUpdatedAtFormatAttribute(Type $var = null)
    {
        if($this->updated_at !=''){
            return $this->updated_at->format('d M, Y') ?? '';
        }else{
            return '';
        }
        
    }

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->subdepartment->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->subdepartment->society_id;
        });
    }
}
