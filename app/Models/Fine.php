<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
	protected $guarded = [];
    public $table = 'fines';
    protected $dates = ['created_at','updated_at'];
    
    protected $appends = ['fine_amount_format','fine_date'];
    
    public function imposed()
    {
        return $this->hasMany('App\Models\ImposedFine', 'fine_id','id');
    }
    
    public function society()
    {
        return $this->belongsTo('App\Models\Society', 'society_id','id');
    }
    
    public function getFineAmountFormatAttribute(){
        return number_format($this->fine_amount, 0);
    }
    
    public function getFineDateAttribute(){
    	if($this->created_at !=''){
    		return $this->created_at->format('h:i A d/m/Y');
    	}
    	return $this->created_at;
    }

    public function finepayment()
    {
        return $this->hasMany('App\Models\FinePayment', 'fine_id','id');
    }
}