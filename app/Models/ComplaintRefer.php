<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ComplaintRefer extends Model
{
    protected $guarded = [];

    public function complaint(){

        return $this->belongsTo('App\Models\Complaint', 'complaint_id', 'id');
    }

    public function referto(){
        return $this->belongsTo('App\Models\User', 'refer_to', 'id')->withDefault(['id' => 0]);
    }
}
