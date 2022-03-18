<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use Auth;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use App\Http\Requests\ResidentDataRequest;

class ContactUsController extends Controller
{
    use HelperTrait;

    

    // public function index()
    // {
    //     $residents = ResidentData::get();
    //     $message = "No Data Found";

    //     $counts = $residents->count();

    //     if($counts > 0){
    //         $message = "Success";
    //     }
    //     return response()->json([
    //         'message' => $message,
    //         'counts' => $counts,
    //         'residents' => $residents
    //     ], 201);
    // }

    
}