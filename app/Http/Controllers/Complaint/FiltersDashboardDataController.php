<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\RequestService;
use App\Models\DepartmentHod;
use App\Models\Society;
use App\Models\SubDepartmentSupervisor;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class FiltersDashboardDataController extends Controller
{
    use HelperTrait;
    public function getComplaints($society_id, $complaint_type) {
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
    	if($complaint_type == 'resolved_complaint'){
    		$complaints = Complaint::where('society_id', $society_id)->where('complaint_status','closed')->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();

    	}else if($complaint_type == 'inprocess_complaint'){

    		$complaints = Complaint::where('society_id', $society_id)->whereNotIn('complaint_status', ['open','closed'])->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();

    	}else if($complaint_type == 'pending_complaint'){
    		$complaints = Complaint::where('society_id', $society_id)->where('complaint_status','open')->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
    	}else{
    		$complaints = Complaint::where('society_id', $society_id)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
    	}
    	$departments = Department::where('society_id', $society_id)->whereHas('complaints')->with('subdepartments')->get();
        $societies = Society::where('id', $society_id)->whereHas('complaints')->get();
        
        $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $society_id)->get(['id','name','society_id','department_id']);
        
        if($user_detail->user_level_id == 3){
            $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $complaints = $complaints->where('society_id',$user_detail->society_id);
            }else{
                $hod_deps = $this->hodDepartments();
    	        $complaints = $complaints->whereIn('department_id', $hod_deps);
            }
            
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $society_id)->whereIn('department_id',$this->hodDepartments())->get(['id','name','society_id','department_id']);
            
	    }else if($user_detail->user_level_id == 4){
	        $sub_ids = $this->managerSubDepartments();
	        $complaints = $complaints->whereIn('sub_department_id', $sub_ids); 
	        
	        $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $society_id)->whereIn('id',$this->managerSubDepartments())->get(['id','name','society_id']);
	        
	    } else if($user_detail->user_level_id == 5){
	         $supervisors_subdeps = $this->supervisorDepartments();
	         $complaints = $complaints->whereIn('sub_department_id', $supervisors_subdeps); 
	    }else{
	        $complaints = $complaints;
	    }
	    
	    $subdepartments_ids = array();
    	if($subdepartments !=''){
    	    $subdepartments = $subdepartments->toArray();
    	   // dd($subdepartments);
    	    foreach ($subdepartments as $key => $value) {
    	       // dd($value['id']);
               array_push($subdepartments_ids, $value['id']);
            }
    	}
        if(count($subdepartments_ids) > 0){
            $subdep_sups = SubDepartmentSupervisor::whereIn('sub_department_id',$subdepartments_ids)->get();
        }else{
            $subdep_sups = [];
        }
    	
    	$count = $complaints->count();
        if($count > 0){
            $message = 'yes';
        }else{
            $message = 'no';
        }
        if($this->webLogUser() !=''){
            // return view('complaints.index', compact('complaints'));
            return view('complaints.index', compact('complaints','departments', 'subdep_sups','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $count,
                'complaints' =>$complaints
            ], 200);
        }
        
    
    }
    //service filter
    public function getServices($society_id, $service_type) {
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
    	if($service_type == 'resolved_service'){
    		$service_requests = RequestService::where('service_type','!=','monthly')->where('society_id', $society_id)->where('status','closed')->with('service','user:id,name,resident_id')->orderBy('id','DESC')->get();
    	}else if($service_type == 'inprocess_service'){
    		$service_requests = RequestService::where('service_type','!=','monthly')->where('society_id', $society_id)->whereNotIn('status', ['open','closed'])->with('service','user')->orderBy('id','DESC')->get();
    	}else if($service_type == 'pending_service'){
    		$service_requests = RequestService::where('service_type','!=','monthly')->where('society_id', $society_id)->where('status','open')->with('service','user:id,name,user_level_id,resident_id')->orderBy('id','DESC')->get();
    	}else{
    		$service_requests = RequestService::where('service_type','!=','monthly')->where('society_id', $society_id)->with('service','user')->orderBy('id','DESC')->get();
    	}
    	
    	
    	if($user_detail->user_level_id == 3){
    	    
    	    $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $service_requests = $service_requests->where('society_id',$user_detail->society_id);
            }else{
                $hod_deps = $this->hodDepartments();
    	        $service_requests = $service_requests->whereIn('type_id', $hod_deps); 
            }
            
            
             
	    }else if($user_detail->user_level_id == 4){
	        $sub_ids = $this->managerSubDepartments();
	        $service_requests = $service_requests->whereIn('sub_type_id', $sub_ids); 
	    } else if($user_detail->user_level_id == 5){
	         $supervisors_subdeps = $this->supervisorDepartments();
	         $service_requests = $service_requests->whereIn('sub_type_id', $supervisors_subdeps); 
	    }else{
	        $service_requests = $service_requests;
	    }

    	$subdep_sups = SubDepartmentSupervisor::with('subdepartment')->get();
        $departments = Department::where('society_id',$society_id)->whereHas('hod')->where('is_service', 1)->get();
        
        $societies = Society::where('id', $society_id)->whereHas('complaints')->get();
        $count = $service_requests->count();
        if($count > 0){
            $message = 'yes';
        }else{
            $message = 'no';
        }
        if($this->webLogUser() !=''){
            // return view('servicemanagement.service_request.index', compact('service_requests','subdep_sups','departments'));
            return view('servicemanagement.service_request.index', compact('service_requests','subdep_sups','departments','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $count,
                'service_requests' =>$service_requests
            ], 200);
        }	
    }
    
    public function getComplaintsForApi($complaint_type)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        // pending_complaint
        
        $complaint_status = array();
        if($complaint_type == 'resolved_complaint'){
            $complaint_status = ['closed','satisfied'];
        }else if($complaint_type == 'inprocess_complaint'){
            $complaint_status = ['completed','incorrect','in_process','un_satisfied','re_assign','change_deparment'];
        }else if($complaint_type == 'pending_complaint'){
            $complaint_status = ['open'];
        }else{
            $complaint_status = ['open','in_process','incorrect','re_assign','change_deparment','completed','satisfied','un_satisfied','closed'];
        }
        
        
        // dd($complaint_status);
        
        if($user_detail->user_level_id < 2){
            $complaints = Complaint::whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies Complaints
            $admin_soctities = $this->adminSocieties();
            $complaints = Complaint::whereIn('society_id', $admin_soctities)->whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 3){
            $complaints  = Complaint::where('society_id', $user_detail->society_id)->whereIn('department_id',$this->hodDepartments())->whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 4){
            $complaints  = Complaint::where('society_id', $user_detail->society_id)->whereIn('sub_department_id',$this->managerSubDepartments())->whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 5){
           $user_id = $user_detail->id;
            $complaints = Complaint::whereHas('reffers', function ($query) use ($user_id) {
			    return $query->where('refer_to', '=', $user_id);
			})->whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }else{
            $user_id = $user_detail->id;
            $complaints = Complaint::where('addedby',$user_id)->whereIn('complaint_status', $complaint_status)->with('department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')->get();
        }
        
        $message = 'no';
        $total = $complaints->count();
        if($total > 0){
            $message = 'yes';
        }

        return response()->json([
            'message' => $message,
            // 'user_id' => $user_id,
            'total_complaint' => $total,
            'complaints' =>$complaints
        ], 200);
    }
    
    public function getSimpleServicesStatusFilter($get_status)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        $userId = $user_detail->id;
        $user_level = $user_detail->user_level_id;
        
        $service_status = [];
        if($get_status == 'resolved_services'){
            $service_status = ['closed'];
        }else if($get_status == 'inprocess_services'){
            $service_status = ['completed','in_correct','incorrect','in_process','un_satisfied','re_assign','approved','modified','paid','resolved'];
        }else if($get_status == 'pending_services'){
            $service_status = ['open'];
        }else{
            $service_status = ['open','in_process','in_correct','incorrect','re_assign','modified','completed','approved','satisfied','un_satisfied','closed','paid','resolved'];
        }
        
        
        if($user_level > 5){
            $services = RequestService::where('service_type','!=','monthly')->where('user_id', $userId)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 5){
            $services = RequestService::where('service_type','!=','monthly')->where('refer_to', $userId)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = RequestService::where('service_type','!=','monthly')->whereIn('sub_type_id', $sub_ids)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 3){
            $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $services = RequestService::where('service_type','!=','monthly')->where('society_id', $user_detail->society_id)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
            }else{
                $user = User::with('departments')->find($userId);
                $hod_departments = array(); 
                foreach($user->departments as $key => $value)
                { 
                   $hod_departments[] = $value['department_id'];
                }
                $services = RequestService::where('service_type','!=','monthly')->whereIn('type_id', $hod_departments)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
            }
            
        }else if($user_level == 2){
            $admin_soctities = $this->adminSocieties();
            $services = RequestService::where('service_type','!=','monthly')->whereIn('society_id', $admin_soctities)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();

        }else{
            $services = RequestService::where('service_type','!=','monthly')->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        }
        
        $message = 'no';
        $total = $services->count();
        if($total > 0){
            $message = 'yes';
        }

        return response()->json([
            'message' => $message,
            'total_services' => $total,
            'user_level' => $user_level,
            'user_id' => $userId,
            'service_status' => $service_status,
            'services' =>$services
        ], 200);
    }
    
    public function getSmartServicesStatusFilter($service_status)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        $userId = $user_detail->id;
        
        $service_status = [];
        if($service_status == 'resolved_services'){
            $service_status = ['closed'];
        }else if($service_status == 'inprocess_services'){
            $service_status = ['completed','in_correct','incorrect','in_process','un_satisfied','re_assign','approved','modified','paid','resolved'];
        }else if($service_status == 'pending_services'){
            $service_status = ['open'];
        }else{
            $service_status = ['open','in_process','in_correct','incorrect','re_assign','modified','completed','approved','satisfied','un_satisfied','closed','paid','resolved'];
        }
        
        if($user_level > 5){
            $services = RequestService::where('service_type', 'monthly')->where('user_id', $userId)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 5){
            $services = RequestService::where('service_type', 'monthly')->where('refer_to', $userId)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = RequestService::where('service_type','monthly')->whereIn('sub_type_id', $sub_ids)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        
            
        }else if($user_level == 3){
            $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $services = RequestService::where('service_type', 'monthly')->where('society_id', $user_detail->society_id)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
            }else{
                $user = User::with('departments')->find($userId);
                $hod_departments = array(); 
                foreach($user->departments as $key => $value)
                { 
                   $hod_departments[] = $value['department_id'];
                }
                $services = RequestService::where('service_type', 'monthly')->whereIn('type_id', $hod_departments)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
            }
            
        }else if($user_level == 2){
            $admin_soctities = $this->adminSocieties();
            $service_requests = RequestService::whereIn('society_id', $admin_soctities)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
            $services = RequestService::where('service_type', 'monthly')->whereIn('society_id', $admin_soctities)->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();

        }else{
            $service_requests = RequestService::with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
            $services = RequestService::where('service_type', 'monthly')->whereIn('status', $service_status)->with('service','user:id,name,user_level_id')->orderBy('id','DESC')->get();
        }
        
        $message = 'no';
        $total = $services->count();
        if($total > 0){
            $message = 'yes';
        }

        return response()->json([
            'message' => $message,
            'total_services' => $total,
            'services' =>$services
        ], 200);
    }
}
