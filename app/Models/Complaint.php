<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Complaint extends Model
{
    public $table = 'complaints';
    protected $guarded = [];

    protected $dates = ['created_at','updated_at'];

    protected $appends = ['complaint_date','update_date', 'dep_hod_id','sub_manager_id'];

    public function getComplaintDateAttribute(){
        if($this->created_at){
            return $this->created_at->format('h:i A d/m/Y');
        }
        return $this->created_at;
    }
       
    public function getUpdateDateAttribute(){
        if($this->updated_at){
            return $this->updated_at->format('h:i A d/m/Y');
        }
        return $this->updated_at;
    }

    public function getDepHodIdAttribute(){
        if($this->department){
            return $this->department->hod->hod_id ?? '';
        }
        return '';
    }

    public function getSubManagerIdAttribute(){
        if($this->subdepartment){
            return $this->subdepartment->asstmanager->manager_id ?? '';
        }
        return '';
    }
    
    public function getStatusColorAttribute(){
        $color = '';
        if($this->complaint_status == 'closed'){
            $color = 'success'; 
        }else if($this->complaint_status == 'open'){
            $color = 'danger'; 
        }else if($this->complaint_status == 'in_correct'){
            $color = 'secondary'; 
        }else if($this->complaint_status == 'in_process'){
            $color = 'warning'; 
        }else{
            $color = 'info'; 
        }
        return $color;
    }

    public function complaints_logs()
    {
        return $this->hasMany('App\Models\ComplaintLog','complaint_id')->where('log_type', '=', 'external')->orderBy('id','DESC');
    }
    
    public function complaint_internal_logs()
    {
        return $this->hasMany('App\Models\ComplaintLog','complaint_id')->where('log_type', '=', 'internal')->orderBy('id','DESC');
    }
    
    public function reffers()
    {
        return $this->hasMany('App\Models\ComplaintRefer','complaint_id');
    }

    public function reffer()
    {
        return $this->hasOne('App\Models\ComplaintRefer','complaint_id')->where('is_active',1)->withDefault(['id' => 0]);
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id');
    }
    
    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id');
    }
    
    public function subdepartment()
    {
        return $this->belongsTo('App\Models\SubDepartment','sub_department_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','addedby','id')->withDefault(['id' => 0]);
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->department->society_id;
            $soc_code =  $model->department->society->code;
            $model->ta_time = $model->subdepartment->ta_time;
            $model->expire_time = $model->taTimeExpire($model->subdepartment->ta_time, Carbon::now());
            
            $model->code_number = Complaint::where('society_id', $model->society_id)->max('code_number')+1;
            $month = today()->format('my');
            $number_5dig = str_pad($model->code_number, 5, 0, STR_PAD_LEFT);
            $model->code = $soc_code.'-'.$month.'-'.$number_5dig;
        });
        
        static::updated(function($model){
            if ($model->complaint_status == 'close' OR $model->complaint_status == 'closed') {
                $model->complaint_status = 'closed';
            }
            $model->society_id = $model->department->society_id;
        });
    }
    
    public function taTimeExpire($input, $today_dateTime){
        $exp_time = '';
        $time = '';
        if($today_dateTime !=''){
            if($input == '30 Minutes'){
                $time = 30;
                $exp_time = $today_dateTime->addMinutes($time);
            }else if($input == '45 Minutes'){
                $time = 45;
                $exp_time = $today_dateTime->addMinutes($time);
            }else if($input == '1 Hour'){
                $time = 1;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '3 Hours'){
                $time = 3;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '6 Hours'){
                $time = 6;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '9 Hours'){
                $time = 9;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '1 Day'){
                $time = 1;
                $exp_time = $today_dateTime->addDays($time);
            }else if($input = '2 Days'){
                $time = 2;
                $exp_time = $today_dateTime->addDays($time);
            }else if($input == '3 Days'){
                $time == 3;
                $exp_time = $today_dateTime->addDays($time);
            }else{
                $time == 6;
                $exp_time = $today_dateTime->addDays($time);
            }
        }
        return $exp_time;
    }
}