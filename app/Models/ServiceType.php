<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
	protected $table = 'service_types';
    protected $guarded = [];

     public function subtypes()
    {
    	return $this->hasMany('App\Models\ServiceSubType', 'type_id');
    }
}
