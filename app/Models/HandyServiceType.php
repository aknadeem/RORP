<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandyServiceType extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = 'handy_service_type';
}
