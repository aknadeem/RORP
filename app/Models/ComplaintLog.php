<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $dates = ['created_at','updated_at'];

    protected $appends = ['log_date','log_update'];

    public function getLogDateAttribute(){

        return $this->created_at->format('h:i A d/m/Y');
    }
       
    public function getLogUpdateAttribute(){

        return $this->updated_at->format('h:i A d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }
}
