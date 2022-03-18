<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $guarded = [];
    public $table = 'social_media';
    
    public function societies()
    {
        return $this->belongsToMany('App\Models\Society','social_media_has_societies','social_media_id','society_id')->withTimestamps();
    }
}
