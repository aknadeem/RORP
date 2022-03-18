<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentVehicleRequest;
use App\Models\ResidentData;
use App\Models\ResidentVehicleInfo;
use App\Models\VehicleType;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class ResidentVehicleController extends Controller
{
    use HelperTrait;
    
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id < 2){
            $societies = Society::get(['id','code','name']);
            $residentvehicles = ResidentVehicleInfo::with('residentdata:id,name,society_id','society')->get();
        }elseif($user_detail->user_level_id == 2){
            $admin_soc = $this->adminSocieties();
            $societies = Society::whereIn('id',$admin_soc)->get(['id','code','name']);
            
            $residentvehicles = ResidentVehicleInfo::whereIn('society_id',$admin_soc)->with('society:id,code,name','residentdata')->get();

        }elseif($user_detail->user_level_id >= 6){
            $societies = Society::where('id',$user_detail->society_id)->get(['id','code','name']);
            $residentvehicles = ResidentVehicleInfo::where('resident_data_id', $user_detail->resident_id)->with('society')->get();
        }else{
            $societies = [];
            $residentvehicles = [];
        }
        $message = "No Data Found";
        $counts = count($residentvehicles);

        if($counts > 0){
            $message = "Success";
        }
        if($this->webLogUser()){
            return view('residentmanagement.residentvehicle.index', compact('residentvehicles','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residentvehicles' => $residentvehicles
            ], 201);
        }
    }

    public function create()
    {
        $user = new ResidentVehicleInfo();
        $vehicle_types = VehicleType::get();
        // dd($vehicle_types->toArray());
        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id >= 6){
            $residents = ResidentData::where('id', $user_detail->resident_id)->where('is_active',1)->get();
        }else{
            $residents = [];
        }
        return view('residentmanagement.residentvehicle.create', compact('user', 'residents','vehicle_types'));
    }

    public function myvehicle($id)
    {
        $is_int = is_numeric($id);
        if($is_int){
            $residentVehicleInfo = ResidentVehicleInfo::where('resident_data_id',$id)->get();
            $message = "No Data Found";
            
            if($residentVehicleInfo ==''){
                $message = "No Data Found";
            }
    
            $counts = $residentVehicleInfo->count();
    
            if($counts > 0){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }

        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            // return view('residentmanagement.residentdata.index', compact('residents'));
            dd('true');
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residentVehicleInfo' => $residentVehicleInfo
            ], 201);
        }
    }

    public function store(ResidentVehicleRequest $request)
    {

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        $image_location = 'uploads/resident/vehicleimage';
        if($request->vehicle_image){
            $vehicle_image = $request->file('vehicle_image');
            $extension = $request->file('vehicle_image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $vehicle_image->move($image_location,$image_name);
        }else{
           $image_name =''; 
        }

        $residentvehicle = ResidentVehicleInfo::create([
            'vehicle_type_id' => $request->vehicle_type_id,
            'vehicle_name' => $request->vehicle_name,
            'model_year' => $request->model_year,
            'make' => $request->make,
            'vehicle_number' => $request->vehicle_number,
            'vehicle_image' => $image_name,
            'resident_data_id' => $request->resident_data_id,
            'addedby' => $user_detail->id,
        ]);
        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Created successfully']); 
            return redirect()->route('residentvehicle.index');
        }else{
            return response()->json([
                'message' => 'Resident Vehicle successfully Registered',
                'residentvehicle' => $residentvehicle
            ], 200);
        }
    }

    public function show($id)
    {
        $residentvehicle = ResidentVehicleInfo::with('vehicleTpe')->find($id);
        $message = "No Data Found";

        if($residentvehicle != ''){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'residentvehicle' => $residentvehicle
        ], 201);
    }

    public function edit($id)
    {

        $user = ResidentVehicleInfo::with('vehicleType')->find($id);
        $vehicle_types = VehicleType::get();

        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id >= 2){
            $residents = ResidentData::where('id', $user_detail->resident_id)->where('is_active',1)->get();
        }else{
            $residents = [];
        }
        return view('residentmanagement.residentvehicle.create', compact('user', 'residents','vehicle_types'));
    }

    public function update(ResidentVehicleRequest $request, $id)
    {
       if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $residentvehicle = '';

        $is_int = is_numeric($id);
        if($is_int){
            $residentvehicle = ResidentVehicleInfo::find($id);
            if($residentvehicle == '') {
                $message = 'No data Found';

            }else{
                // dd($request->toArray());
                $image_location = 'uploads/resident/vehicleimage';
                if($request->vehicle_image){
                    $vehicle_image = $request->file('vehicle_image');
                    $extension = $request->file('vehicle_image')->extension();
                    $image_name = time().mt_rand(10,99).'.'.$extension;
                    $file = $vehicle_image->move($image_location,$image_name);
                }else{
                   $image_name = $residentvehicle->vehicle_image; 
                }
                // dd($request->toArray());
                $message = 'Resident Servent Data successfully Updated';
                $residentvehicle = ResidentVehicleInfo::find($id)->update([
                    'vehicle_type_id' => $request->vehicle_type_id,
                    'vehicle_name' => $request->vehicle_name,
                    'model_year' => $request->model_year,
                    'make' => $request->make,
                    'vehicle_number' => $request->vehicle_number,
                    'vehicle_image' => $image_name,
                    'resident_data_id' => $request->resident_data_id,
                    'updatedby' => $user_detail->id,

                ]);
            }
        }else{
            $message = 'id Must Be Integer';
        }

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Created successfully']); 
            return redirect()->route('residentvehicle.index');
        }else{
            return response()->json([
                'message' => $message,
                'residentvehicle' => $residentvehicle
            ], 200);        
        }
    }

    public function destroy($id)
    {
        $residentvehicle = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residentvehicle = ResidentVehicleInfo::find($id);
            $message = "No Data Found";
            if($residentvehicle != ''){
                $residentvehicle->delete();
                $message = "Vehicle Delete Successfully";
            }
        }else{
          $message = "Id Must be Integet";  
        }

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
            return redirect()->route('residentvehicle.index');
        }else{
            return response()->json([
                'message' => $message,
                'residentvehicle' => $residentvehicle
            ], 200);     
        } 
    }
    
    public function vehicleTypes()
    {
        $vehicle_types = VehicleType::get();
        $message = 'no';
        $count = $vehicle_types->count();
        if($count > 0){
            $message = 'yes';
        }
        return response()->json([
            'message' => $message,
            'count' => $count,
            'vehicle_types' => $vehicle_types
        ], 200); 
    }
}

