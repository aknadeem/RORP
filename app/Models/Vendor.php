<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $guarded = [];
    public $table = 'vendors';

    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }
}
