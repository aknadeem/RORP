<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
	protected $table = 'incident_reports';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\SocietySector','society_sector_id');
    }
}
