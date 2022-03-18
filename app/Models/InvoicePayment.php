<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $table = 'invoice_payments';
	protected $guarded = [];

    protected $dates = ['payed_date','created_at', 'updated_at'];
    protected $appends = ['paid_amount_format','paid_date_format'];
    protected $cast = ['payed_date' => 'date'];

    public function getPaidAmountFormatAttribute(){
        return number_format($this->payed_amount, 0);
    }
    
    public function getPaidDateFormatAttribute(){
    	if($this->payed_date !=''){
    		return $this->payed_date->format('d/m/Y');
    	}
    	return $this->payed_date;
    }
}
