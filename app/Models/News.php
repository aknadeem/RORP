<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $guarded = [];

    protected $dates = ['created_at','updated_at'];


    protected $appends = ['date_format'];

	public function getDateFormatAttribute()
    {
    	return $this->created_at->format('d M, Y');
    }
    
    public function societies()
    {
        return $this->belongsToMany('App\Models\Society','news_has_societies','news_id','society_id')->withTimestamps();
    }
}
