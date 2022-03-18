<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{
    protected $table = 'invoices';
    protected $guarded = [];
    protected $dates = ['pay_date','due_date'];

    protected $casts = [
        'number' => 'integer',
    ];


    protected $appends = ['last_date','payment_date','price_format','final_price_format','paid_amount_format','remaining_amount_format','soc_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id');
    }


    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->user->society_id;
            $model->number = Invoice::where('society_id', $model->user->society_id)->max('number')+1;
            $model->invoice_number = $model->user->society->code.'-'.str_pad($model->number,5,'0',STR_PAD_LEFT);
        });
    }

    public function items()
    {
        return $this->hasMany('App\Models\InvoiceDetail','invoice_id', 'id');
    }

    public function getLastDateAttribute(){
        if($this->due_date !=''){
    	   return $this->due_date->format('d M, Y');
        }else{
           return $this->due_date; 
        }
    }

    public function getSocIdAttribute(){
        if ($this->user) {
            return $this->user->society_id;
        }else{
            return 0;
        }
    }

    public function getPaymentDateAttribute(){
    	if($this->pay_date !=''){
    		return $this->pay_date->format('d M, Y');
    	}
    	return $this->pay_date;
    }
    
    public function getPriceFormatAttribute(){
        return number_format($this->price,0);
    }
    
    public function getFinalPriceFormatAttribute(){
        return number_format($this->final_price,0);
    }
    
    public function getPaidAmountFormatAttribute(){
        return number_format($this->paid_amount,0);
        
    }
    
    public function getRemainingAmountFormatAttribute(){
        return number_format($this->remaining_amount,0);
    }

    public function invoicepayment()
    {
        return $this->hasMany('App\Models\InvoicePayment','invoice_id','id');
    }
}