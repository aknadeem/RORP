<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DealsDiscounts extends Model
{
    protected $table = 'deals_discounts';
    protected $guarded = [];
    protected $dates = ['start_date','end_date'];
    protected $appends = ['deal_start','deal_end','deal_sectors'];
    public function society()
    {
        return $this->belongsTo('App\Models\Society','society_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor','vendor_id');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\SocietySector','sector_id');
    }

    public function getDealStartAttribute()
    {
        return $this->start_date->format('d M, Y');
    }
    
    public function getDealSectorsAttribute()
    {
        $deal_sectors ='';
       if($this->sectors->count() > 0){
            foreach($this->sectors as $sector){
                $deal_sectors .=$sector->sector_name.', ';
            }
        }
        
        return substr_replace($deal_sectors, "", -1);
        // return $deal_sectors;
    }
    public function getDealEndAttribute()
    {
        return $this->end_date->format('d M, Y');
    }
    
    //deal hasmany sector, so belongsToManyRelation with Pivot table (deal_has_sectors)
    public function sectors()
    {
        return $this->belongsToMany('App\Models\SocietySector','deal_has_sectors','deal_id','sector_id')->withTimestamps();
    }
}
