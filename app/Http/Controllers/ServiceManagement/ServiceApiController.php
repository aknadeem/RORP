<?php

namespace App\Http\Controllers\ServiceManagement;

use App\Http\Controllers\Controller;
use App\Models\DepartmentHod;
use App\Models\RequestService;
use App\Models\Service;
use App\Models\ServiceDevice;
use App\Models\ServicePackage;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Validator;
class ServiceApiController extends Controller
{
    use HelperTrait;
    public function services(){
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $services = Service::where('billing_type','!=','monthly')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            $services = Service::whereIn('society_id',$this->adminSocieties())->where('billing_type','!=','monthly')->orderBy('id','DESC')->get();
        }else{
            $services = Service::where('society_id',$user_detail->society_id)->where('billing_type','!=','monthly')->orderBy('id','DESC')->get();
        }
    // 	$services = Service::where('billing_type','!=','monthly')->orderBy('id','DESC')->get();
    	$message = 'No Data Found';
        $counts = count($services);
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'services' => $services
        ], 201);
    }

    public function smartServices(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $smart_services = Service::where('billing_type','=','monthly')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 2){
            $smart_services = Service::whereIn('society_id', $this->adminSocieties())->where('billing_type','=','monthly')->orderBy('id','DESC')->get();
        }else{
            $smart_services = Service::where('society_id', $user_detail->society_id)->where('billing_type','=','monthly')->orderBy('id','DESC')->get();
        }
        
        // $smart_services = Service::where('billing_type','=','monthly')->orderBy('id','DESC')->get();
        
    	$message = 'No Data Found';
        $counts = $smart_services->count();
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'smart_services' => $smart_services
        ], 201);
    }

    public function servicePackages($id){
    	$service_packages = ServicePackage::where('service_id',$id)->get();
    	$message = 'No Data Found';
        $counts = count($service_packages);
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_packages' => $service_packages
        ], 201);
    } 

    public function serviceDevices($id){
    	$service_devices = ServiceDevice::where('service_id',$id)->get();
    	$optional_devices = $service_devices->where('device_status','optional');
    	$required_devices = $service_devices->where('device_status','required');
    	$message = 'No Data Found';
        $counts = count($service_devices);
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_devices' => $service_devices,
            'optional_devices' => $optional_devices,
            'required_devices' => $required_devices,
        ], 201);
    }
	// Services Requests
    public function serviceRequests(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $userId = $user_detail->id;
            $user_name = $user_detail->name;
            $user_level = $user_detail->user_level_id;
        }else{
            $user_detail = $this->apiLogUser();
            $userId = $user_detail->id;
            $user_name = $user_detail->name;
            $user_level = $user_detail->user_level_id;
        }
        if($user_level == 4 AND $user_level == 5){
            $all_requests = RequestService::where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
        }else if($user_level > 5){
            $all_requests = RequestService::where('user_id', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }
        else if($user_level == 3){
            $user = User::with('departments')->find($userId);
            $hod_departments = array(); 
            foreach($user->departments as $key => $value)
            { 
               $hod_departments[] = $value['department_id'];
            }
            $all_requests = RequestService::whereIn('type_id', $hod_departments)->with('service','user','package')->orderBy('id','DESC')->get();
        }else{
            $all_requests = RequestService::with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }
        
        // $all_requests = RequestService::with('service')->get();
    	$service_requests = $all_requests->where('service_type','!=','monthly');
    	$smart_service_requests = $all_requests->where('service_type','=','monthly');
    	$message = 'No Data Found';
        $counts = count($all_requests);
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'all_requests' => $all_requests,
            'service_requests' => $service_requests,
            'smart_service_requests' => $smart_service_requests,
        ], 200);
    }
    
    // get all simple service requests against login user
    public function getSimpleServiceRequests(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }else{
            $user_detail = $this->apiLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }

        if($user_level == 1){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 2){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('society_id', $this->adminSocieties())->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 3){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('type_id', $this->hod_departments())->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 4){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('sub_type_id', $this->managerSubDepartments())->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 5){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('refer_to', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }else{
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }
        
        // if($user_level == 4 AND $user_level == 5){
        //     $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
        // }else if($user_level == 3){
        //     $user = User::with('departments')->find($userId);
        //     $hod_departments = array(); 
        //     foreach($user->departments as $key => $value)
        //     { 
        //       $hod_departments[] = $value['department_id'];
        //     }
        //     $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('type_id', $hod_departments)->with('service','user','package')->orderBy('id','DESC')->get();
        // }else if($user_level > 5){
        //     $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        // }else{
        //     $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        // }
        $counts = $simple_service_requests->count();
        $inprocess_requests = 0;
        $pending_requests = 0;
        $approved_requests = 0;
        $message = 'No Data Found';
    	$message = 'no';
    	if($counts > 0){
    	    $message = 'success';
            $inprocess_requests = $simple_service_requests->whereNotIn('status',['open','approved','closed'])->count();
            $pending_requests = $simple_service_requests->where('status','=','open')->count();
            $approved_requests = $simple_service_requests->where('status','=','approved')->count();
    	}
    	
    	$message = 'No Data Found';
        $counts = count($simple_service_requests);
        if($counts > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'total' => $counts,
            'inprocess_requests' => $inprocess_requests,
            'pending_requests' => $pending_requests,
            'approved_requests' => $approved_requests,
            'simple_service_requests' => $simple_service_requests,
        ], 200);
    }
    
    // get all smart service requests against login user
    public function getSmartServiceRequests(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }else{
            $user_detail = $this->apiLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }
        if($user_level == 1){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->with('service','user')->orderBy('id','DESC')->get();
        }else if($user_level == 2){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('society_id', $this->adminSocieties())->with('service','user')->orderBy('id','DESC')->get();
        }else if($user_level == 3){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('type_id', $this->hod_departments())->with('service','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 4){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('sub_type_id', $this->managerSubDepartments())->with('service','user','package')->orderBy('id','DESC')->get();
        }else if($user_level == 5){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
        }else{
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        }
        // if($user_level == 4 AND $user_level == 5){
        //     $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
        // }else if($user_level == 3){
        //     $user = User::with('departments')->find($userId);
        //     $hod_departments = array(); 
        //     foreach($user->departments as $key => $value)
        //     { 
        //       $hod_departments[] = $value['department_id'];
        //     }
        //     $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('type_id', $hod_departments)->with('service','user','package')->orderBy('id','DESC')->get();
        // }else if($user_level > 5){
        //     $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        // }
        // else{
        //     $smart_service_requests = RequestService::where('service_type','=', 'monthly')->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
        // }
        $counts = $smart_service_requests->count();
        $inprocess_requests = 0;
        $pending_requests = 0;
        $approved_requests = 0;
        $message = 'No Data Found';
    	$message = 'no';
    	if($counts > 0){
    	    $message = 'success';
            $inprocess_requests = $smart_service_requests->whereNotIn('status',['open','approved','closed'])->count();
            $pending_requests = $smart_service_requests->where('status','=','open')->count();
            $approved_requests = $smart_service_requests->where('status','=','approved')->count();
            // $verified_request = $smart_service_requests->where('is_verified','=',1)->count();
            // $total_invoiced = $smart_service_requests->where('is_invoiced','=',1)->count();
            // $first_invoiced_paid = $smart_service_requests->where('is_paid','=',1)->count();
            // $total_invoiced_not_created = $smart_service_requests->where('is_invoiced','=',0)->count();
    	}
    	
        return response()->json([
            'message' => $message,
            'total' => $counts,
            'pending_requests' => $pending_requests,
            'inprocess_requests' => $inprocess_requests,
            'approved_requests' => $approved_requests,
            'smart_service_requests' => $smart_service_requests,
        ], 200);
    }
    
    // User Request A smart Service
    public function requestSmartService(Request $request){

    	$validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'package_id' => 'required|integer',
            'device_id' => 'required|integer',
            'op_device_id' => 'nullable',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }

        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $message = '';
        $service_society_id=0;
        $service = Service::with('tax_details', 'servicetype')->find($request->service_id);
        $service_society_id = $service->servicetype->society_id;
        
        if($service !=''){
            $accounthod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($service_society_id){
                    $qry->where('society_id', 1);
            }])->first();
            
            // $dephod  = DepartmentHod::has('accountdepartment')->first();
            
            // if($accounthod->accountdepartment){
            //     $refer_to_id = $dephod->hod_id;
            // }else{
            //     $message .= 'No Head Of Department Found';
            //     $type = 'danger';
            // }
                
         
            // dd($dephod->toArray());
            if($accounthod->accountdepartment){
                $refer_to_id = $accounthod->hod_id;
                $user = User::where('id',$refer_to_id)->first();
            }else{
                return response()->json(['error' => 'No Head Of Department Found'], 404);
            }

            if($user !='' AND $message ==''){
            	// store service request in database
                $serviceRequest = RequestService::create([
                    'service_type' => $service->billing_type,
                    'service_id' => $request->service_id,
                    'type_id' => $service->type_id, 
                    'sub_type_id' => $service->sub_type_id,
                    'package_id' => $request->package_id,
                    'device_id' => $request->device_id,
                    'description' => $request->description,
                    'refer_to' => $refer_to_id,
                    'refer_by' => $userId,
                    'addedby' => $userId,
                    'user_id' => $userId, 
                    'created_at' => $this->currentDateTime(),
                ]);
                // attach Devices
                if($request->op_device_id !=''){
                    $serviceRequest->devices()->sync($request->op_device_id);
                }
                if($request->device_id > 0){
                    $serviceRequest->devices()->attach($request->device_id);
                }
               
                // send notification to refer to person 
                $details=[
                    'title' => 'User Request A Service',
                    'sender_name' => $user_name,
                    'sender_id' => $userId,
                    'service_id' => $request->service_id,
                    'service_request_id' => $serviceRequest->id,
                ];
                $user->notify(new \App\Notifications\ServiceNotification($details));
                
                if($user->fcm_token !=''){
                    $title_message = 'User Request A Service';
                    $tokens = array();
                    $tokens[] = $user->fcm_token;
                    $this->sendFCM($title_message, $tokens);  
                }
        
        
                // generate service log
                $service_log = DB::table('service_logs')->insert([
                    'service_id' => $request->service_id,
                    'service_request_id' => $serviceRequest->id,
                    'status' => 'open', 
                    'comments' => 'Service Request Created',
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);

                return response()->json([
                    'message' => $message,
                    'service_request' => $serviceRequest
                ], 200);
            }else{
            	return response()->json(['error' => 'No Head Of Department Found'], 404);
            }
        }
    }
    
    
    public function getSimpleServiceRequestsWithDepartments($department_id, $subdepid){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }else{
            $user_detail = $this->apiLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }
        
        if($user_level == 1){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }else if($user_level == 2){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('society_id', $this->adminSocieties())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }else if($user_level == 3){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('type_id', $this->hod_departments())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }else if($user_level == 4){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->whereIn('sub_type_id', $this->managerSubDepartments())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }else if($user_level == 5){
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('refer_to', $userId)->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }else{
            $simple_service_requests = RequestService::where('service_type','!=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }
        
        if($department_id == 'all' && $department_id != ''){
             $simple_service_requests =  $simple_service_requests->where('type_id', $department_id);
        }else{
            $simple_service_requests =  $simple_service_requests;
        }
        
        if($subdepid == 'all' && $subdepid != ''){
            $simple_service_requests =  $simple_service_requests->where('sub_type_id', $subdepid);
        }else{
            $simple_service_requests =  $simple_service_requests;
        }
        $counts = $simple_service_requests->count();
        $inprocess_requests = 0;
        $pending_requests = 0;
        $approved_requests = 0;
        $message = 'No Data Found';
    	$message = 'no';
    	if($counts > 0){
    	    $message = 'success';
            $inprocess_requests = $simple_service_requests->whereNotIn('status',['open','approved','closed'])->count();
            $pending_requests = $simple_service_requests->where('status','=','open')->count();
            $approved_requests = $simple_service_requests->where('status','=','approved')->count();
    	}
    	
        return response()->json([
            'message' => $message,
            'total' => $counts,
            'pending_requests' => $pending_requests,
            'inprocess_requests' => $inprocess_requests,
            'approved_requests' => $approved_requests,
            'simple_service_requests' => $simple_service_requests,
        ], 200);
    }
    
    public function getSmartServiceRequestsWithDepartments($department_id, $subdepid){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }else{
            $user_detail = $this->apiLogUser();
            $userId = $user_detail->id;
            $user_level = $user_detail->user_level_id;
        }
        
        if($user_level == 1){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }else if($user_level == 2){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('society_id', $this->adminSocieties())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }else if($user_level == 3){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('type_id', $this->hod_departments())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }else if($user_level == 4){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->whereIn('sub_type_id', $this->managerSubDepartments())->with('service','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }else if($user_level == 5){
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
        }else{
            $smart_service_requests = RequestService::where('service_type','=', 'monthly')->where('user_id', $userId)->with('service','servicetype.hod','user:id,name,unique_id,user_level_id', 'user.userlevel:id,title,slug','package')->orderBy('id','DESC')->get();
        }
        
        if($department_id == 'all' && $department_id != ''){
             $smart_service_requests =  $smart_service_requests->where('type_id', $department_id);
        }else{
            $smart_service_requests =  $smart_service_requests;
        }
        
        if($subdepid == 'all' && $subdepid != ''){
            $smart_service_requests =  $smart_service_requests->where('sub_type_id', $subdepid);
        }else{
            $smart_service_requests =  $smart_service_requests;
        }
        $counts = $smart_service_requests->count();
        $inprocess_requests = 0;
        $pending_requests = 0;
        $approved_requests = 0;
        $message = 'No Data Found';
    	$message = 'no';
    	if($counts > 0){
    	    $message = 'success';
            $inprocess_requests = $smart_service_requests->whereNotIn('status',['open','approved','closed'])->count();
            $pending_requests = $smart_service_requests->where('status','=','open')->count();
            $approved_requests = $smart_service_requests->where('status','=','approved')->count();
    	}
    	
        return response()->json([
            'message' => $message,
            'total' => $counts,
            'pending_requests' => $pending_requests,
            'inprocess_requests' => $inprocess_requests,
            'approved_requests' => $approved_requests,
            'smart_service_requests' => $smart_service_requests,
        ], 200);
    }
}