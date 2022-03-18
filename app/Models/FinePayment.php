<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinePayment extends Model
{
	protected $guarded = [];
    public $table = 'fine_payments';
    protected $dates = ['paid_date','created_at', 'updated_at'];
    protected $appends = ['paid_amount_format','paid_date_format'];
    protected $cast = ['paid_date' => 'date'];
    
    
    public function getPaidAmountFormatAttribute(){
        return number_format($this->paid_amount, 0);
    }
    
    public function getPaidDateFormatAttribute(){
    	if($this->paid_date !=''){
    		return $this->paid_date->format('d/m/Y');
    	}
    	return $this->paid_date;
    }
}
