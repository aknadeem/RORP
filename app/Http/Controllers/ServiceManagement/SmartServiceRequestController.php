<?php

namespace App\Http\Controllers\ServiceManagement;

use Auth;
use Validator;
use App\Models\User;
use App\Models\Society;
use App\Models\Department;
use App\Models\UserService;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\DepartmentHod;
use App\Models\RequestService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SubDepartmentSupervisor;
// use Illuminate\Support\Facades\Response;

use Symfony\Component\HttpFoundation\Response;
use Gate;

class SmartServiceRequestController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-smart-services-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        // dd($user_detail->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
            $user_level = $web_user->user_level_id;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
            $user_level = $api_user->user_level_id;
        }

        $account_hod_id = 0;
        if($user_level >= 6){
            $service_requests = RequestService::where('user_id', $userId)->with('service','user')->orderBy('status','DESC')->get();
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();

        }else if($user_level == 4 AND $user_level == 5){
            $service_requests = RequestService::where('refer_to', $userId)->with('service','user')->orderBy('status','DESC')->get();
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();

        }else if($user_level == 3){
            $check_is_AccountHod= Department::whereHas('hod')->where('slug', 'accounts-finance')->with(['hod' => function($qry) use ($userId){
                return $qry->where('hod_id', $userId);
            }])->first();
            if($check_is_AccountHod !=''){

                $account_hod_id =  $check_is_AccountHod->hod->hod_id ?? 0;
                $soc_id = $user_detail->society_id;
                $service_requests = RequestService::with(['servicetype' => function($qry) use ($soc_id){
                    return $qry->where('society_id', $soc_id);
                }])->with('service','user','package')->orderBy('status','DESC')->get();
            }else{
                $user = User::with('departments')->find($userId);
                $hod_departments = array(); 
                foreach($user->departments as $key => $value)
                { 
                   $hod_departments[] = $value['department_id'];
                }
                $service_requests = RequestService::whereIn('type_id', $hod_departments)->with('service','user','package')->orderBy('status','DESC')->get();
            }

            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
        }else if($user_level == 2){
            $admin_soctities = $this->adminSocieties();

            $service_requests = RequestService::whereIn('society_id', $admin_soctities)->with('service','servicetype.hod','user','package')->orderBy('status','DESC')->get();

            $departments = Department::whereIn('society_id', $admin_soctities)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->whereIn('id', $admin_soctities)->get();

        }else{
            $service_requests = RequestService::with('service','servicetype.hod','user','package')->orderBy('status','DESC')->get();
            $departments = Department::whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->get();
        }
        $subdep_sups = SubDepartmentSupervisor::with('subdepartment')->get();
        // $departments = Department::whereHas('hod')->where('is_service', 1)->get();
        $message = "No Data Found";
        $counts = count($service_requests);
        // dd($counts);
        if($counts > 0){
            $message = "success";
        }
        $service_requests = $service_requests->where('service_type','=','monthly');
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.service_request.smart-service-requests', compact('service_requests','subdep_sups','departments','societies', 'account_hod_id'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_requests' => $service_requests
        ], 201);
    }
    
    // smart service filter with user
    public function SmartfilterWithUser(){
        abort_if(Gate::denies('view-smart-services-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $smart_service_requests = RequestService::where('service_type','=','monthly')->get();
        
        $total_requests = $smart_service_requests->count();
        $inprocess_requests = 0;
        $pending_requests = 0;
        $approved_requests = 0;
        
    	$message = 'no';
    	if($total_requests > 0){
    	    
            $inprocess_requests = $smart_service_requests->whereNotIn('status',['open','approved','closed'])->count();
            $pending_requests = $smart_service_requests->where('status','=','open')->count();
            $approved_requests = $smart_service_requests->where('status','=','approved')->count();
    	}
    	
        return response()->json([
            'message' => $message,
            'total' => $total_requests,
            'inprocess_requests' => $inprocess_requests,
            'pending_requests' => $pending_requests,
            'approved_requests' => $approved_requests,
        ], 201);
    }
    
    public function filterComplaint($id) {
    	$user = User::find($id);
    	if($user->user_level_id > 5){
    		$complaints = Complaint::with('complaints_logs','user')->where('addedby',$id)->orderBy('id','DESC')->get();
    	}else{
    		$complaints = Complaint::whereHas('reffers', function ($query) use ($id) {
			    return $query->where('refer_to', '=', $id);
			})->with('reffers','complaints_logs','user')->orderBy('id','DESC')->get();
    	}
    	$counts = $complaints->count();
    	$inprogress_complaints = '';
    	$pending_complaints = '';
    	$resolved_complaints = '';
    	$message = 'no';
    	if($counts > 0){
    		$message = 'yes';
    		$inprogress_complaints = $complaints->where('complaint_status', '!=','closed')->where('complaint_status', '!=','open')->count();
    		$pending_complaints = $complaints->where('complaint_status', '=','open')->count();
    		$resolved_complaints = $complaints->where('complaint_status', '=','closed')->count();
    	}
    	return response()->json([
            'message' => $message,
            'total' => $counts,
            'inprogress_complaints' => $inprogress_complaints,
            'pending_complaints' => $pending_complaints,
            'resolved_complaints' => $resolved_complaints,
            'complaints' => $complaints
        ], 201);
    }
}