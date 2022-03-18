<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintRefer;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\SubDepartmentSupervisor;
use App\Models\User;
use App\Models\UserLevel;
use Auth;
use Illuminate\Http\Request;
use App\Models\ComplaintLog;
use App\Traits\HelperTrait;
use Session;

class ComplaintReferController extends Controller
{
    use HelperTrait;
    public function index(){
        $message = 'no';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        if($user_detail->user_level_id == 2){
            $departments = Department::whereHas('hod')->whereIn('society_id', $this->adminSocieties())->get(['id','name','society_id']);
            $subdepartments = SubDepartment::whereHas('supervisors')->whereIn('society_id',$this->adminSocieties())->get(['id','name','society_id','department_id','ta_time']);
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereHas('hod')->where('society_id', $user_detail->society_id)->whereIn('id', $this->hodDepartments())->get(['id','name','society_id']);
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->whereIn('department_id',$this->hodDepartments())->get(['id','name','society_id','department_id']);
        }else if($user_detail->user_level_id == 4){
            $departments = Department::whereHas('hod')->where('society_id', $user_detail->society_id)->get(['id','name','society_id']);
            
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->whereIn('id',$this->managerSubDepartments())->get(['id','name','society_id']); 
        }elseif($user_detail->user_level_id > 4){
            $departments = Department::whereHas('hod')->where('society_id', $user_detail->society_id)->get(['id','name','society_id']);
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->get(['id','name','society_id']);
        }else{
            $departments = Department::whereHas('hod')->with('subdepartments','society')->get(['id','name','society_id']);
            // $subdepartments = SubDepartment::get(['id','name','society_id','department_id','ta_time']);
            $subdepartments = SubDepartment::whereHas('supervisors')->get(['id','name','society_id']);
        }
        
        // dd($subdepartments->toArray());
    	$departments = Department::whereHas('hod')->get();
    	$subdepartments_id = array();
    	if($subdepartments !=''){
    	    foreach ($subdepartments as $key => $value) {
               array_push($subdepartments_id, $value->id);
            }
    	}
    	
        // dd($subdepartments_id);
        if(count($subdepartments_id) > 0){
            $subdep_sups = SubDepartmentSupervisor::where('sub_department_id',$subdepartments_id)->with('supervisor:id,name,user_level_id')->get();
        }else{
            $subdep_sups = [];
        }
        
        // dd($subdep_sups->toArray());
    	$complaint_refers = ComplaintRefer::with('complaint','referto:id,name,user_level_id','referto.userlevel:id,title,slug')->where('refer_to',$user_detail->id)->where('is_active', 1)->orderBy('id','DESC')->get();
    	$counts = $complaint_refers->count();
    	if($counts > 0){
    		$message = 'yes';
    	}
    	if($this->webLogUser() !=''){
    		return view('complaints.refers.index', compact('complaint_refers','subdep_sups','departments'));
    	}else{
    		return response()->json([
                'message' => $message,
                'counts' => $counts,
                'complaint_refers' => $complaint_refers
            ], 201);
    	}
    }

    public function filterComplaint($id) {
        
         if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_id = $user_detail->id;
        if($user_detail->user_level_id == 1){
            $complaints = Complaint::with('complaints_logs','user')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 2){
            $complaints = Complaint::whereIn('society_id',  $this->adminSocieties())->with('complaints_logs','user')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 3){
            $complaints = Complaint::whereIn('department_id',  $this->hodDepartments())->with('complaints_logs','user')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 4){
            $complaints = Complaint::whereIn('sub_department_id',  $this->managerSubDepartments())->with('complaints_logs','user')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 5){
            $complaints = Complaint::whereHas('reffers', function ($query) use ($user_id) {
			    return $query->where('refer_to', '=', $user_id);
			})->with('reffers','complaints_logs','user')->orderBy('id','DESC')->get();
        }
        else{
            $complaints = Complaint::with('complaints_logs','user:id,name,user_level_id')->where('addedby',$id)->orderBy('id','DESC')->get();
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
    
    public function complaintWithDepartments($department_id, $sub_dep_id){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_id = $user_detail->id;
        if($user_detail->user_level_id == 1){
            $complaints = Complaint::with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 2){
            $complaints = Complaint::whereIn('society_id',  $this->adminSocieties())->with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 3){
            $complaints = Complaint::whereIn('department_id',  $this->hodDepartments())->with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 4){
            $complaints = Complaint::whereIn('sub_department_id',  $this->managerSubDepartments())->with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }
        else if($user_detail->user_level_id == 5){
            $complaints = Complaint::whereHas('reffers', function ($query) use ($user_id) {
			    return $query->where('refer_to', '=', $user_id);
			})->with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->orderBy('id','DESC')->get();
        }
        else{
            $complaints = Complaint::with('department:id,name,slug,is_complaint','department.hod:id,department_id,hod_id','subdepartment:id,name,slug,department_id,ta_time','subdepartment.asstmanager:id,sub_department_id,manager_id','complaints_logs','user:id,name,user_level_id', 'user.userlevel:id,title,slug')->where('addedby',$user_id)->orderBy('id','DESC')->get();
        }
        if($department_id != '' && $department_id  != 'all'){
            $complaints = $complaints->where('department_id', $department_id);
        }else if($department_id != '' && $department_id  != 'all' && $sub_dep_id != '' && $sub_dep_id  != 'all'){
            $complaints = $complaints->where('department_id', $department_id)->orWhere('sub_department_id',$sub_dep_id);
        }else{
            $complaints = $complaints;
        }
        
        $counts = $complaints->count();
    	$inprogress_complaints = 0;
    	$pending_complaints = 0;
    	$resolved_complaints = 0;
    	$message = 'no';
    	if($counts > 0){
    		$message = 'yes';
    		$inprogress_complaints = $complaints->where('complaint_status', '!=','closed')->where('complaint_status', '!=','open')->count();
    		$pending_complaints = $complaints->where('complaint_status', '=','open')->count();
    		$resolved_complaints = $complaints->where('complaint_status', '=','closed')->count();
    	}
    	return response()->json([
            // 'user_detail' => $user_detail,
            'message' => $message,
            'total' => $counts,
            'inprogress_complaints' => $inprogress_complaints,
            'pending_complaints' => $pending_complaints,
            'resolved_complaints' => $resolved_complaints,
            'complaints' => $complaints
        ], 201);
        
    }


    public function residentComplaints()
    {
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $user_iid = $web_user->id;
            $user_level_id = $web_user->user_level_id;
        }else{
            $api_user = Auth::guard('api')->user();
            $user_iid = $api_user->id;
            $user_level_id = $api_user->user_level_id;
        }
        if($user_level_id > 5){
            $complaints = Complaint::with('complaints_logs','user')->where('addedby',$id)->orderBy('id','DESC')->get();
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

    public function HodsSupervisors() {

        $subdep_sups = SubDepartmentSupervisor::with('supervisor')->get();
        $departments = Department::whereHas('hod')->get();

        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            $user_id = $web_user_id->id;
        }else{
            $api_user_id = Auth::guard('api')->user();
            $user_id = $api_user_id->id;
        }

        
        $message = 'No hods and Supervisor found';
        if($departments !='' AND $subdep_sups !=''){

            $message = 'yes';
        }
        if($api_user_id !=''){

           return response()->json([
                'message' => $message,
                'subdepartment_supervisors' => $subdep_sups,
                'departments' => $departments,
            ], 201); 
       }
    }
    
    public function complaintInternalLog(Request $request)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $this->validate($request,[
                'internal_comment' => 'bail|required|string|min:1',
            ]);
        }else{
            $user_detail = $this->apiLogUser();
        }

        $complaint = Complaint::find($request->complaint_id);
        $complaint_referrer = '';
        if($complaint !=''){
            $find_reffer = ComplaintRefer::where('complaint_id', $request->complaint_id)->orderBy('id', 'DESC')->first();
            if($find_reffer !=''){
                $complaint_referrer = User::find($find_reffer->refer_to);
            }
        }

        $complaint_log = ComplaintLog::create([
            'complaint_id'=> $request->complaint_id,
            'status'=> $complaint->complaint_status,
            'log_type'=> 'internal',
            'comments'=> $request->internal_comment,
            'addedby'=> $user_detail->id
        ]);

        if($complaint_referrer != ''){
            $details = [
                'title' => $request->internal_comment,
                'by' => $user_detail->name,
                'complaint_id' => $request->complaint_id,
            ];
            $complaint_referrer->notify(new \App\Notifications\ComplaintNotification($details));
            if($complaint_referrer !=''){
                if($complaint_referrer->fcm_token !=''){
                    $title_message = $request->internal_comment ?? 'Complaint Creation Notification';
                    $tokens = array();
                    $tokens[] = $complaint_referrer->fcm_token;
                    $this->sendFCM($title_message, $tokens);  
                }
            }
            
        }

        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'success','message' => 'Comment Added successfully!']);
            return back();
        }else{
            if($complaint_log){
                $message = 'yes';
            }else{
                $message = 'no';
            }
            return response()->json([
                'message' => $message,
                'complaint_internal_log' => $complaint_log,
            ], 201); 
        }
    } 
}
