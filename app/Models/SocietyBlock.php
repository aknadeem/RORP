<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietyBlock extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = 'society_blocks';

    // protected $with = ['hod','department'];

    public function society(){
        return $this->belongsTo('App\Models\Society', 'society_id', 'id');
    }

    public function sector(){
        return $this->belongsTo('App\Models\SocietySector', 'society_sector_id', 'id')->withDefault(0);
    }

}
