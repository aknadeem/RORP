<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ImposedFine extends Model
{	
	protected $guarded = [];
    public $table = 'imposed_fines';
    protected $dates = ['fine_date', 'due_date'];
    protected $appends = ['fine_date_format','due_date_format'];

	public function getFineDateFormatAttribute()
    {
    	return $this->fine_date->format('d M, Y');
    }

    public function getDueDateFormatAttribute()
    {
    	return $this->due_date->format('d M, Y');
    }

    public function finepayment()
    {
        return $this->hasMany('App\Models\FinePayment', 'imposed_fine_id','id');
    }

	public function fine()
    {
        return $this->belongsTo('App\Models\Fine', 'fine_id','id')->withDefault(['id' => 0]);
    } 

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id','id')->where('user_level_id','>',5)->withDefault(['id' => 0]);
    }

    public function fineby()
    {
        return $this->belongsTo('App\Models\User', 'fine_by','id')->where('user_level_id','<',6)->withDefault(['id' => 0]);
    }
    
    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->society_id = $model->user->society_id;
        });
        static::updated(function($model){
            $model->society_id = $model->user->society_id;
        });
    }
}
