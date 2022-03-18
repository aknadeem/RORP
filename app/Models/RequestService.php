<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
	protected $table = 'request_services';
    protected $guarded = [];
    protected $dates = ['created_at','updated_at'];
    protected $appends = ['request_date','total_price','total_tax','price_include_tax'];
    
    public function getRequestDateAttribute(){
        if($this->created_at){
           return $this->created_at->format('h:i A d/m/Y'); 
        }
        return $this->created_at;
    }
    
    public function logs(){
        return $this->hasMany('App\Models\ServiceLog', 'service_request_id', 'id');
    }
    public function internallogs(){
        return $this->hasMany('App\Models\RequestServiceInternalLog', 'service_request_id', 'id');
    }
    public function getStatusColorAttribute()
    {
        $color = 'brand';
        if ($this->status =='open'){
            $color = 'danger';
        }else if ($this->status =='approved'){
            $color = 'success';
        }else if ($this->status =='in_process'){
            $color = 'warning';
        }else if($this->status =='completed'){
            $color = 'brand';
        }else if($this->status =='closed'){
            $color = 'success';
        }
        return $color;
    }
    
    public function service(){
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function device(){
        return $this->belongsTo('App\Models\ServiceDevice', 'device_id', 'id');
    }

    public function devices(){
        return $this->belongsToMany('App\Models\ServiceDevice', 'requests_devices','request_service_id','device_id');
    }

    public function package(){
        return $this->belongsTo('App\Models\ServicePackage', 'package_id', 'id');
    }

    public function referto(){
        return $this->belongsTo('App\Models\User', 'refer_to', 'id');
    }

    public function referby(){
        return $this->belongsTo('App\Models\User', 'refer_by', 'id');
    }

    public function servicetype(){
        return $this->belongsTo('App\Models\Department', 'type_id', 'id')->where('is_service', 1);
    }

    public function subtype(){
        return $this->belongsTo('App\Models\SubDepartment', 'sub_type_id', 'id');
    }

    public function invoice(){
        return $this->hasOne('App\Models\Invoice', 'request_service_id', 'id');
    }

    public function getTotalPriceAttribute()
    {
        $package_price = 0;
        $devices_sum = 0;
        $installation_fee = 0;
        if($this->package !=''){
            $package_price =  $this->package->price;
        }
        if($this->devices !=''){
            $devices_sum = $this->devices->sum('device_price');
        }
        if($this->service){
            $installation_fee = $this->service->installation_fee;
        }
        return number_format($installation_fee + $package_price + $devices_sum,0);
    }

    public function getTotalTaxAttribute()
    {
        $service_tax = 0;
        $device_tax = 0;
        if($this->service){
            $service_tax = $this->service->total_tax;
        }
        
        $package_tax = 0;
        if($this->package !=''){
            $package_tax = $this->package->total_tax;
        }
        
        if($this->devices){
            $device_tax = $this->devices->sum('total_tax');
        }
        return $service_tax+$package_tax+$device_tax;
    }

    public function getPriceIncludeTaxAttribute()
    {
        $pckg_price_incl_tax = 0;
        $device_price_incl_tax = 0;
        $service_price_with_tax = 0;
        if($this->package !=''){
            $pckg_price_incl_tax =  str_replace(',', '', $this->package->price_include_tax);
        }
        if($this->devices !=''){
            $device_price_incl_tax = str_replace(',','',$this->devices->sum('price_without_tax'));
        }
    
        if($this->service){
            $service_price_with_tax = str_replace(',','',$this->service->price_include_tax);
        }
        // return $device_price_incl_tax;
        $total_price_incl_tax =
        $service_price_with_tax+$pckg_price_incl_tax+$device_price_incl_tax;
        return number_format($total_price_incl_tax,0);
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->servicetype->society_id;
            
            $soc_code =  $model->servicetype->society->code;
            $model->code_number = RequestService::where('society_id', $model->society_id)->max('code_number')+1;
            $month = today()->format('my');
            $number_5dig = str_pad($model->code_number, 5, 0, STR_PAD_LEFT);
            $model->code = $soc_code.'-'.$month.'-'.$number_5dig;
        });
        static::updated(function($model){
            $model->society_id = $model->servicetype->society_id;
        });
    }
}