<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $table = 'service_packages';
    protected $guarded = [];

    protected $appends = ['total_tax','price_include_tax','price_format'];

    public function service()
    {
    	return $this->belongsTo('App\Models\Service', 'service_id');
    }

    public function tax_details(){
        return $this->belongsToMany('App\Models\Tax', 'tax_details', 'package_id','tax_id')->withTimestamps()->withPivot(['type']);
    }

    public function getTotalTaxAttribute()
    {
    	return $this->tax_details->sum('tax_percentage');
    }
    
    public function getPriceFormatAttribute()
    {
    	return number_format($this->price,0);
    }
    
    public function getTaxAmountAttribute()
    {
    	return $this->total_tax/100*$this->price;
    }
    public function getPriceIncludeTaxAttribute()
    {
    	return number_format($this->tax_amount+$this->price ,0);
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->service->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->service->society_id;
        });
    }
}
