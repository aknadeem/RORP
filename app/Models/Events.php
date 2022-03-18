<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Events extends Model
{
    protected $guarded = [];
    protected $dates = ['event_date'];
    protected $appends = ['date_format','event_sectors'];
	public function getDateFormatAttribute()
    {
    	return $this->event_date->format('d M, Y');
    }

    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\SocietySector','sector_id');
    }
    
    public function sectors()
    {
        return $this->belongsToMany('App\Models\SocietySector','event_has_sectors','event_id','sector_id')->withTimestamps();
    }
    
    public function getEventSectorsAttribute()
    {
        $event_sectors ='';
       if($this->sectors->count() > 0){
            foreach($this->sectors as $sector){
                $event_sectors .=$sector->sector_name.', ';
            }
        }
        // substr_replace
        return substr_replace($event_sectors, "", -1);
        // return $event_sectors;
    }
}