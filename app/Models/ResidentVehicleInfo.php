<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentVehicleInfo extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = 'resident_vehicle_infos';

    public function residentdata() {
        return $this->belongsTo('App\Models\ResidentData','resident_data_id', 'id');
    }

    public function vehicleType()
    {
    	return $this->belongsTo('App\Models\VehicleType','vehicle_type_id', 'id');
    }
    
    public function society() {
        return $this->belongsTo('App\Models\Society','society_id', 'id');
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->residentdata->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->residentdata->society_id;
        });
    }
}
