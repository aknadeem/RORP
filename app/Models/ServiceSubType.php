<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubType extends Model
{
	protected $table = 'service_sub_types';
    protected $guarded = [];


    public function servicetype()
    {
    	return $this->belongsTo('App\Models\ServiceType', 'type_id');
    }
}
