<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentFamilyRequest;
use App\Http\Requests\ResidentVehicleRequest;
use App\Models\ResidentFamily;
use App\Models\ResidentHandyMan;
use App\Models\ResidentServent;
use App\Models\ResidentVehicleInfo;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


class ResidentFamilyController extends Controller
{
    public function index()
    {
      $resfamilies = ResidentFamily::get();
      $message = "No data Found";
      if($resfamilies){
        $message = "Resident Famiy Data";
      }

      return response()->json([
            'message' => $message,
            'residentfamliies' => $resfamilies
        ], 201);
    }

    
    public function store(ResidentFamilyRequest $request) {
      
        $residentfamliy = ResidentFamily::create([
            'name' => $request->name,
            'guardian_name' => $request->guardian_name,
            'cnic' => $request->cnic,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'relation' => $request->relation,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'message' => 'Resident Famliy successfully registered',
            'residentfamliy' => $residentfamliy
        ], 201);
    }

    public function storeServent(ResidentFamilyRequest $request) {

        $validator = Validator::make($request->all(), $request);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $residentservent = ResidentServent::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'cnic' => $request->cnic,
            'type' => $request->type,
            'gender' => $request->gender,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'occupation' => $request->occupation,
            'resident_data_id' => $request->resident_data_id

        ]);

        return response()->json([
            'message' => 'Resident Famliy successfully registered',
            'residentservent' => $residentservent
        ], 201);
    }



    public function storeHandyMen(ResidentFamilyRequest $request) {

        $validator = Validator::make($request->all(), $request);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $residentHandyMan = ResidentHandyMan::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'cnic' => $request->cnic,
            'type' => $request->type,
            'gender' => $request->gender,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'occupation' => $request->occupation,
            'resident_data_id' => $request->resident_data_id
        ]);

        return response()->json([
            'message' => 'Resident Handy Man successfully registered',
            'residentHandyMan' => $residentHandyMan
        ], 201);
    }


    public function storeVehicleInfo(ResidentVehicleRequest $request) {

        $validator = Validator::make($request->all(), $request);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $residentVehicleInfo = ResidentVehicleInfo::create([
            'vehicle_type' => $request->vehicle_type,
            'vehicle_name' => $request->vehicle_name,
            'model_year' => $request->model_year,
            'make' => $request->make,
            'vehicle_number' => $request->vehicle_number,
            'resident_data_id' => $request->resident_data_id

        ]);
        return response()->json([
            'message' => 'Resident Vehicle successfully registered',
            'residentVehicleInfo' => $residentVehicleInfo
        ], 201);
    }
}
