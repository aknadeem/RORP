<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class CustomNotification extends Model
{
    protected $table = 'custom_notifications';
	protected $guarded = [];

    protected $dates = ['created_at','updated_at'];
    protected $appends = ['date_format','update_date'];

    public function getDateFormatAttribute(){
        if($this->created_at !=''){
            return $this->created_at->format('h:i A d/m/Y');
        }
        return $this->created_at;
    }
    
    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id', 'id')->withDefault(['id' => 0]);
    }

    public function getUpdateDateAttribute(){
    	if($this->updated_at !=''){
    		return $this->updated_at->format('h:i A d/m/Y');
    	}
        return $this->updated_at;
    }
    
    // public function society(){
    //     return 0;   
    // }
}
