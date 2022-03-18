<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{

	protected $table = 'invoice_details';
    protected $guarded = [];
    
    protected $appends = ['discount_amount_format','price_format','final_price_format','description','item_type'];

	public function invoice() {
        return $this->belongsTo('App\Models\Invoice','invoice_id');
    }

    public function service() {
        return $this->belongsTo('App\Models\Service','service_id');
    }

    public function package() {
        return $this->belongsTo('App\Models\ServicePackage','package_id');
    }

    public function device() {
        return $this->belongsTo('App\Models\ServiceDevice','device_id');
    }


    public function getDescriptionAttribute(){
    	$description = '';
    	if($this->service_id > 0){
    		$description = $this->service->title;
    	}elseif($this->package_id > 0){
    		$description = $this->package->title;
    	}elseif($this->device_id > 0){
    		$description = $this->device->device_title;
    	}
    	return $description;
    }

    public function getItemTypeAttribute(){
    	$type = '';
    	if($this->service_id > 0){
    		$type = 'Installation Charges';
    	}elseif($this->package_id > 0){
    		$type = 'Package Price';
    	}elseif($this->device_id > 0){
    		$type = 'Device Charges [ '.$this->device->device_status.' ]';
    	}
    	return $type;
    }
    
    public function getDiscountAmountFormatAttribute(){
        return number_format($this->discount_amount,0);
    }
    
     public function getPriceFormatAttribute(){
        return number_format($this->price,0);
    }
    
    public function getFinalPriceFormatAttribute(){
        return number_format($this->final_price,0);
    }
    
}
