<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentalServiceRequestLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','addedby','id');
    }

    public function scopeInternal($query)
    {
        return $query->where('log_type', 'Internal');
    }

    public function scopeExternal($query)
    {
        return $query->where('log_type', 'External');
    }

}
