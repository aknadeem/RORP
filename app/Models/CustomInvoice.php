<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomInvoice extends Model
{
	protected $table = 'custom_invoices';
    protected $guarded = [];
    protected $dates = ['due_date','created_at','updated_at'];
    protected $appends = ['due_date_format','custom_invoice_date'];
    
    
    public function getDueDateFormatAttribute(){
        return $this->due_date->format('d/m/Y');
    }
    
    public function getCustomInvoiceDateAttribute(){
        return $this->created_at->format('h:i A d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id')->withDefault(['id' => 0]);
    }

    public function fine()
    {
        return $this->belongsTo('App\Models\Fine', 'fine_id','id')->withDefault(['id' => 0]);
    }
    
    public function custominvoicepayment()
    {
        return $this->hasMany('App\Models\CustomInvoicePayment', 'custom_invoice_id','id');
    }

}