<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Service extends Model
{
    protected $table = 'services';
    protected $guarded = [];

    protected $appends = ['total_tax','price_include_tax','installation_fee_format'];

    // protected $dates = ['start_date','end_date'];
    // protected $appends = ['deal_start','deal_end'];
    public function servicetype()
    {
        return $this->belongsTo('App\Models\Department','type_id', 'id')->withDefault(['id' => 0]);
    }

    public function subtype()
    {
        return $this->belongsTo('App\Models\SubDepartment','sub_type_id','id')->withDefault(['id' => 0]);
    }
    
    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id', 'id')->withDefault(['id' => 0]);
    }

    public function packages()
    {
        return $this->hasMany('App\Models\ServicePackage', 'service_id', 'id');
    }


    public function notifications()
    {
        return $this->hasMany(DatabaseNotification::class, 'data->service_id');
    }

    public function tax_details(){
        return $this->belongsToMany('App\Models\Tax', 'tax_details', 'service_id','tax_id')->withTimestamps()->withPivot(['type']);
    }


    public function getTotalTaxAttribute()
    {
        $total_tax = 0;
        if ($this->tax_details !=''){
            $total_tax =  $this->tax_details->sum('tax_percentage');
        }
        return $total_tax;
        
    }
    
    public function getInstallationFeeFormatAttribute()
    {
        return number_format($this->installation_fee,0);
    }

    public function getTaxAmountAttribute()
    {
        if($this->total_tax > 0){
            return $this->total_tax/100*$this->installation_fee;
        }
        return 0;
        
    }
    public function getPriceIncludeTaxAttribute()
    {
        $amount = $this->tax_amount+str_replace(',','',$this->installation_fee);
        
        return number_format($amount,0);
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->servicetype->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->servicetype->society_id;
        });
    }
}
