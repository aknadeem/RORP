<?php

namespace App\Http\Controllers\Complaint;

use DB;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Society;
use App\Models\Complaint;
use App\Models\Department;
use App\Traits\HelperTrait;
use App\Models\ComplaintLog;
use Illuminate\Http\Request;
use App\Models\DepartmentHod;
use App\Models\DepSupervisor;
use App\Models\SubDepartment;
use App\Models\ComplaintRefer;
use App\Models\ComplaintFeedback;
use App\Http\Controllers\Controller;
use App\Models\SubDepartmentManager;
use Illuminate\Support\Facades\Mail;
use App\Models\SubDepartmentSupervisor;
// use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use DataTables;

class ComplaintController extends Controller
{
    use HelperTrait;
    public function index(){
        abort_if(Gate::denies('view-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $current_day = $this->today();
        if($user_detail->user_level_id < 2){
            $departments = Department::whereHas('complaints')->with('subdepartments')->get();
            $societies = Society::whereHas('complaints')->get();
            $subdepartments = SubDepartment::whereHas('supervisors')->get(['id','name','society_id','department_id','ta_time']);

            $forward_departments = Department::with('subdepartments')->get();

        }elseif($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies Complaints
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereHas('complaints')->whereIn('id', $admin_soctities)->get();
            $departments = Department::whereIn('society_id', $admin_soctities)->with('subdepartments')->get();
            $subdepartments = SubDepartment::whereHas('supervisors')->whereIn('society_id',$this->adminSocieties())->get(['id','name','society_id','department_id','ta_time']);
            $forward_departments = Department::whereIn('society_id', $admin_soctities)->with('subdepartments')->get();

        }else if($user_detail->user_level_id == 3){
            $societies = Society::whereHas('complaints')->where('id', $user_detail->society_id)->get();
            $departments = Department::whereHas('complaints')->whereIn('id', $this->hodDepartments())->with('subdepartments')->get();
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->whereIn('department_id',$this->hodDepartments())->get(['id','name','society_id','department_id']);
            $forward_departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments')->get();

        }elseif($user_detail->user_level_id == 4){
            $societies = Society::whereHas('complaints')->where('id', $user_detail->society_id)->get();
            $sub_ids = $this->managerSubDepartments();
            $departments = Department::whereHas('complaints')->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->whereHas('hod')->with('subdepartments')->get();
            // dd($this->managerSubDepartments());
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->whereIn('id',$this->managerSubDepartments())->get(['id','name','society_id']);
            $forward_departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments')->get();

        }elseif($user_detail->user_level_id >= 6){
            $departments = Department::whereHas('complaints')->where('society_id', $user_detail->society_id)->with('subdepartments')->get();
            $subdepartments = SubDepartment::whereHas('supervisors')->where('society_id', $user_detail->society_id)->get(['id','name','society_id']);
            $societies = Society::whereHas('complaints')->where('id', $user_detail->society_id)->get();
            $forward_departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments')->get();
        }else{
            $complaints = collect();
            $departments = collect();
            $subdepartments = collect();
            $societies = collect();
            $forward_departments = collect();
        }
        // Hod And Manager Saw All Their Department and submdepartmnet complaint
    	$subdepartments_ids = array();
    	if($subdepartments !=''){
    	    $subdepartments = $subdepartments->toArray();
    	    foreach ($subdepartments as $key => $value) {
               array_push($subdepartments_ids, $value['id']);
            }
    	}
        if(count($subdepartments_ids) > 0){
            $subdep_sups = SubDepartmentSupervisor::whereIn('sub_department_id',$subdepartments_ids)->get();
        }else{
            $subdep_sups = [];
        }
        // dd($complaints->toArray());
        return view('complaints.index', compact('subdep_sups','departments','societies','forward_departments'));
    }

    public function getComplaintsWithRefresh()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_level_id = $user_detail->user_level_id;
        $admin_soctities = $this->adminSocieties(); 

        $complaints = Complaint::with('society:id,code,name','user:id,name,user_level_id','department.hod','subdepartment.asstmanager','reffer')->orderBy('id','DESC')
        ->when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('department_id',$this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('sub_department_id',$this->managerSubDepartments());
        })->when($user_level_id == 5, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereHas('reffer', function ($query) use ($user_detail) {
			    return $query->where('refer_to', '=', $user_detail->id);
			});
        })->when($user_level_id > 5, function ($qry) use ($user_detail){
            $qry->where('addedby', $user_detail->id);
        })->withCasts([
            'created_at' => 'date:d M, Y'
        ])->get();

        return DataTables::of($complaints)
            ->addIndexColumn()
            ->addColumn('society_id', function($row){
                return '<span>'.$row->society->name.'<b>['.$row->society->code.']</b></span>';
            })
            ->addColumn('complaint_title', function($row){
                return '<a data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                title="Click to view Detail" href="'.route("complaints.show", $row["id"]).'"> '.$row["complaint_title"].'</a>';
            })->addColumn('addedby', function($row){
                return '<span> '.$row->user->name.' <b>['.$row->user->userlevel->title.'  ]</b></span>';
            })->addColumn('department_id', function($row){
                return '<span> '.$row->department->name.' </span>';
            })->addColumn('sub_department_id', function($row){
                return '<span> '.$row->subdepartment->name.'</span>';
            })->addColumn('complaint_status', function($row) use ($user_level_id){
                $modal_option = '';
                $button = '<button type="button"
                class="btn btn-'.$row["status_color"].' btn-sm"> '.$row["complaint_status"].'</button>';

                if($user_level_id < 6){
                    if($row["complaint_status"] == "in_process" OR
                    $row["complaint_status"] == "open" OR $row["complaint_status"]
                    == "re_assign"){
                        $modal_option = '<span data-toggle="modal" data-target="#todoWorking"
                        data-target-id="'.$row["id"].'"
                        complaint_status="'.$row["complaint_status"].'"
                        class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                        <i class="flaticon-edit"></i>
                        </span>';
                    }else if($row["complaint_status"] != "completed" &&
                    $row["complaint_status"] != "closed"){
                        $modal_option = '<span data-toggle="modal" data-target="#close-re-assign"
                        data-target-id="'.$row["id"].'"
                        class="btn btn-sm btn-clean btn-icon btn-icon-md"
                        title="Click to Update Status">
                        <i class="flaticon-edit"></i>
                        </span>';
                    }
                }
                return  $button.'<br>'.$modal_option;
            })
            ->addColumn('Actions', function($row){
                return '<a
                data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                title="Click to Edit" href="'.route("complaintedit", $row["id"]).'"
                class="btn btn-label-info btn-sm"><i class="fa fa-edit"></i></a>
                <br>
                <a href="'.route("complaints.destroy", $row["id"]).'"
                    class="btn btn-label-danger btn-sm delete-confirm" title="Click to Delete"
                    del_title="Complaint '. $row["complaint_title"].'"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['society_id','complaint_title','addedby','department_id','sub_department_id','complaint_status','Actions'])
            ->make(true);
    }

    public function create(){

        abort_if(Gate::denies('create-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 2){
            $departments = Department::whereHas('hod')->whereIn('society_id', $this->adminSocieties())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereHas('hod')->whereIn('id', $this->hodDepartments())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
           $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }elseif($user_detail->user_level_id == 5){
            
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            }else{
                $departments = collect();    
            }
        }else{
            $departments = Department::with('subdepartments','society')->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }
        $complaint = new Complaint();
        return view('complaints.create', compact('complaint','departments'));
    }

    public function store(Request $request){
        $userId = Auth::user()->id;
        $user_name = Auth::user()->name;
        $this->validate($request, [
            'complaint_title' => 'required|string|min:3',
            'poc_name' => 'required|string',
            'poc_number' => 'required|string',
            'user_type' => 'required|string',
            'complaint_description' => 'required|string',
            'department_id' => 'required|integer',
            'sub_department_id' => 'required|integer',
            'image' => 'string|nullable',
        ]);
        $userId = \Auth::user()->id;
        /**
        * Get sub department supervisor 
        * Count their complaints
        * Select Supervisor having Minimum Complaints And new Complaint reffer to them 
        */

        $complaint_hods = DepartmentHod::where('department_id',$request->department_id)->get(['id','department_id','hod_id'])->pluck('hod_id')->toArray();

        $managers = SubDepartmentManager::where('sub_department_id',$request->sub_department_id)->get(['id','sub_department_id','manager_id'])->pluck('manager_id')->toArray();

        $supervisors = SubDepartmentSupervisor::where('sub_department_id',$request->sub_department_id)->with('complaint_refers')->withCount([
        'complaint_refers as reffer_count' => function ($query) {
            $query->where('is_active', 1);
        }])
        ->get();

        // dd($supervisors->toArray()); 
        $refer_to_id = 0;
        if($supervisors->count() > 0){
            $min_val = collect($supervisors)->min('reffer_count');
            $min_complaints = $supervisors->where('reffer_count', $min_val);
            $minimum_Complaints = $min_complaints->all();
            if($minimum_Complaints){
                $firstKey = array_key_first($minimum_Complaints);
                $refer_to_id = $minimum_Complaints[$firstKey]->supervisor_id;
            }
        }else{
            Session::flash('notify', ['type'=>'warning','message' => "Can't Register Your Complaint Because Supervisor is required for complaint to Assign"]);
            return redirect()->back();
        }
        $userId = \Auth::user()->id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $fileName = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/complaint';
            $file = $image->move($loc,$fileName);

        }else{
            $fileName = '';
        }
        if($refer_to_id > 0){
            $complaint = Complaint::create([
                'complaint_title' => $request->complaint_title,
                'user_type' => $request->user_type,
                'poc_name' => $request->poc_name,
                'poc_number' => $request->poc_number,
                'complaint_description' => $request->complaint_description,
                'department_id' => $request->department_id,
                'sub_department_id' => $request->sub_department_id,
                'image' => $fileName,
                'addedby' => $userId,
            ]);
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' => $complaint->id,
                'refer_to' => $refer_to_id,
                'refer_by' => $userId,
            ]);

            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'comments' => 'complaint created',
                'addedby' => $userId,
            ]);

            $hod_managers = array_unique(array_merge($complaint_hods, $managers));
            $supervisor_id[] = $refer_to_id;
            $add_supervisor_too = array_unique(array_merge($hod_managers, $supervisor_id));
            $get_users = User::findMany($add_supervisor_too);

            if($get_users->count() > 0){
                $details=[
                    'title' => $request->complaint_title,
                    'by' => $user_name,
                    'complaint_id' => $complaint->id,
                ];

                foreach($get_users as $user){
        
                    $user->notify(new \App\Notifications\ComplaintNotification($details));
                    if($user->fcm_token != null){
                        $title_message = 'New Complaint Created';
                        $tokens = array();
                        $tokens[] = $user->fcm_token;
                        $not_send = $this->sendFCM($title_message, $tokens);  
                    }
                }
            }

            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            return redirect()->route('complaints.index');
        }else{
            Session::flash('notify', ['type'=>'warning','message' => 'No Supervisor Found']);
            return redirect()->route('complaints.create');
        }
    }

    public function show($id) {
        $message = 'no';
        $complaint = Complaint::with('complaints_logs.user:id,name,user_level_id','reffers','reffers.referto:id,name,user_level_id','user:id,user_level_id,unique_id,name,contact_no,resident_id','user.profile:id,name,father_name,mobile_number,society_id,society_sector_id,address','department','subdepartment','reffer.referto:id,name,user_level_id','complaint_internal_logs.user:id,name,user_level_id','society:id,code,name')->find($id);
        if($complaint !=''){
            $message = 'yes';
        }
        $api_user_id = Auth::guard('api')->user();
        if($api_user_id){
            return response()->json([
                'message' => $message,
                'complaint' => $complaint
            ], 201);
        }
        
        // dd($complaint->toArray());
        return view('complaints.complaint-detail', compact('complaint'));
    }

    public function edit($id) {
        abort_if(Gate::denies('update-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 2){
            $departments = Department::whereHas('hod')->whereIn('society_id', $this->adminSocieties())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereHas('hod')->whereIn('id', $this->hodDepartments())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
           $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }elseif($user_detail->user_level_id == 5){
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            }else{
                $departments = collect();    
            }
        }else{
            $departments = Department::with('subdepartments','society')->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
        }
        $complaint = Complaint::find($id);
        return view('complaint.create', compact('complaint','departments'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'user_type' => 'required',
            'poc_name' => 'required',
            'poc_number' => 'required',
            'department_id' => 'integer|nullable',
            'sub_department_id' => 'required',
            'complaint_title' => 'required',
            'complaint_description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 401);
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

        $complaint = Complaint::find($id);
        $complaint->user_type = $request->user_type;
        $complaint->poc_name = $request->poc_name;
        $complaint->poc_number = $request->poc_number;
        if($request->department_id != ''){
            $complaint->department_id = $request->department_id;
        }
        $complaint->sub_department_id = $request->sub_department_id;
        $complaint->complaint_title = $request->complaint_title;
        $complaint->complaint_description = $request->complaint_description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $fileName = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/complaint';
            $file = $image->move($loc,$fileName);
            $complaint->image = $fileName;
        }
        $complaint->updatedby = $userId;
        $complaint->updated_at = $this->currentDateTime();
        $complaint->save();
        $complaint_log = ComplaintLog::create([
            'complaint_id' => $id,
            'status' => 'modified',
            'comments' => 'complaint details modified',
            'addedby' => $userId,
        ]);

        $message = 'no';
        if($web_user != ''){
            Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
            return redirect()->route('complaints.index');
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'complaint' => $complaint,
            ], 201);
        } 
    }

    public function WorkingInComplaint(Request $request){

        $validator = Validator::make($request->all(), [
            'working_status' => 'required',
            'comments' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 401);
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
        // $complaint = Complaint::where('id',$request->complaint_id)->first();
        $complaint = Complaint::findOrFail($request->complaint_id);
        $dep_manager = SubDepartmentManager::where('sub_department_id',$complaint->sub_department_id)->first();
        $user = User::where('id',$dep_manager->manager_id)->first();
        $complaint_addedby = User::where('id',$complaint->addedby)->first();
        $complaint_update = $complaint->update([
            'complaint_status'=> $request->working_status
        ]);
        // dd($complaint);
        if ($request->working_status == 're_assign' OR $request->working_status == 'reassign') {
            $c_status = 're_assign';
        }else{
            $c_status = $request->working_status;
        }
        
        if ($request->working_status == 'close' OR $request->working_status == 'closed') {
            $c_status = 'closed';
        }else{
            $c_status = $request->working_status;
        }
        
        $complaint_log = ComplaintLog::create([
            'complaint_id'=> $complaint->id,
            'status'=> $c_status,
            'comments'=> $request->comments,
            'addedby'=> $userId
        ]);
        
        if ($request->working_status == 're_assign' OR $request->working_status == 'reassign') {
            
            $user = User::where('id',$request->user_id)->first();
            
            $refer_to = $request->user_id;
            $status = 're_assign';
            
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' =>  $complaint->id,
                'refer_to' => $refer_to,
                'refer_by' => $userId,
            ]);
            $complaint_update = $complaint->update([
                'complaint_status'=> $status
            ]);

            $details = [
                'title' => $request->comments,
                'by' => $user_name,
                'complaint_id' => $request->complaint_id,
            ];

            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($user->fcm_token !=''){
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $tokens = array();
                $tokens[] = $user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
        }
            
        if($request->working_status == 'in_process'){
            $details = [
                'title' => $request->comments,
                'by' => $user_name,
                'complaint_id' => $request->complaint_id,
            ];

            $complaint_addedby->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($complaint_addedby->fcm_token !=''){
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $tokens = array();
                $tokens[] = $complaint_addedby->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
        }
        
        if ($request->working_status == 'incorrect') {
            $complaintRefer = ComplaintRefer::where('complaint_id',$request->complaint_id)->where('refer_to',$userId)->orderBy('id', 'DESC')->first();
            if($complaintRefer != ''){
                $refer_update = $complaintRefer->update([
                    'is_active'=> 0
                ]);
            }
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' =>  $complaint->id,
                'refer_to' => $dep_manager->manager_id,
                'refer_by' => $userId,
            ]);
            
            $details = [
                'title' => $request->comments,
                'by' => $user_name,
                'complaint_id' => $request->complaint_id,
            ];
    
            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($user->fcm_token !=''){
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $tokens = array();
                $tokens[] = $user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        } elseif ($request->working_status == 'completed') {
                $complaint_refers = ComplaintRefer::create([
                    'complaint_id' =>  $complaint->id,
                    'refer_to' => $complaint->addedby,
                    'refer_by' => $userId,
                ]);
                $details = [
                    'title' => $request->comments,
                    'by' => $user_name,
                    'complaint_id' => $request->complaint_id,
                ];

            $complaint_addedby->notify(new \App\Notifications\ComplaintNotification($details));
            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($complaint_addedby->fcm_token !='' && $user->fcm_token !=''){
                $tokens = array($complaint_addedby->fcm_token,$user->fcm_token);
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $this->sendFCM($title_message, $tokens);  
            }
            
            $email_data = array(
                'sentto' => $complaint_addedby->email,
                'name' => $complaint_addedby->name,
                'complaint_status' => $request->working_status,
                'comment' => $request->comments,
                'subject' => 'Complaint Notification',
            );

            Mail::send('complaints.complaint_email', $email_data, function ($message) use ($email_data) {
                $message
                    ->to($email_data['sentto'], $email_data['name'], $email_data['complaint_status'], $email_data['comment'], $email_data['subject'])
                    ->subject($email_data['subject']);
            });
        }

        $message = 'no';
        if($web_user != ''){
            Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
            // return redirect()->route('users.index');
            return back();
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'complaint_status' => $request->working_status,
            ], 201);
        }        
    }

    public function complaintedit($id){
        
        abort_if(Gate::denies('update-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 2){
            $departments = Department::whereHas('hod')->whereIn('society_id', $this->adminSocieties())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            
            $subdepartments = SubDepartment::whereIn('society_id', $this->adminSocieties())->get();
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereHas('hod')->whereIn('id', $this->hodDepartments())->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            
            $subdepartments = SubDepartment::whereIn('department_id', $this->hodDepartments())->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
           $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            
            $subdepartments = SubDepartment::whereIn('id', $sub_ids)->get();
        }elseif($user_detail->user_level_id == 5){
            
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            
            $subdepartments = SubDepartment::whereIn('id', $sp_visors_subdepartments)->get();
            
            }else{
                $departments = collect();    
                $subdepartments = collect();    
            }
        }else{
            $departments = Department::with('subdepartments','society')->with('subdepartments:id,name,department_id')->get(['id','name','society_id']);
            $subdepartments = SubDepartment::get();
        }
        $complaint = Complaint::with('department:id,name','department.hod:id,department_id,hod_id')->find($id);
        $subdep_sups = SubDepartmentSupervisor::where('sub_department_id',$complaint->sub_department_id)->get();
        return view('complaints.edit', compact('complaint','subdep_sups','departments','subdepartments'));
    }

    public function complaintStatusChange(Request $request){
        $validator = Validator::make($request->all(), [
            'working_status' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 401);
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
        
        if ($request->working_status == 'close' OR $request->working_status == 'closed') {
            $c_status = 'closed';
        }else{
            $c_status = $request->working_status;
        }
        
        $complaint = Complaint::where('id',$request->complaint_id)->firstOrFail();
        if($request->working_status == 'closed'){
            $caddedBy = $complaint->addedby;
            $user = User::where('id',$caddedBy)->first();
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' =>  $complaint->id,
                'refer_to' => $caddedBy,
                'refer_by' => $userId,
            ]);
            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'status'=> $c_status,
                'comments' => $request->comments,
                'addedby' => $userId,
            ]);
            $details = [
                'title' => $request->comments,
                'by' => $user_name,
                'complaint_id' => $request->complaint_id,
            ];
            
            $complaint_update = $complaint->update([
                'complaint_status'=> $c_status
            ]);
            
            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($user->fcm_token !=''){
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $tokens = array();
                $tokens[] = $user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
            $message = 'no';
            if($web_user != ''){
                Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
                return redirect()->route('complaints.index');
            }else{
                $message = 'yes';
                return response()->json([
                    'message' => $message,
                    'success' => 'true',
                    'complaint_status' => $request->working_status,
                ], 201);
            }
        }else{
            $complaintRefer = ComplaintRefer::where('complaint_id',$complaint->id)->where('is_active',1)->firstOrFail();
            if($request->working_status == 'change_deparment'){
                $departmentHod = DepartmentHod::where('department_id', $request->department_id)->firstOrFail();
                $find_id = $departmentHod->hod_id;
            }else{
                $find_id = $request->user_id;
            }
            $user = User::where('id',$find_id)->first();
            
            $complaint_update = $complaint->update([
                'complaint_status'=> $c_status
            ]);
            
            if($complaintRefer){
                $complaint_refer_update = $complaintRefer->update([
                    'is_active'=> 0,
                ]);
            }
            
            if($request->working_status == 'change_deparment'){
                $refer_to = $departmentHod->hod_id;
                $status = 'change_deparment';
            } 
            if ($request->working_status == 're_assign') {
                $refer_to = $request->user_id;
                $status = 're_assign';
            }
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' =>  $complaint->id,
                'refer_to' => $refer_to,
                'refer_by' => $userId,
            ]);
            
            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'status'=> $c_status,
                'comments' => $request->comments,
                'addedby' => $userId,
            ]);

            $details = [
                'title' => $request->comments,
                'by' => $user_name,
                'complaint_id' => $request->complaint_id,
            ];

            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($user->fcm_token !=''){
                $title_message = $request->comments ?? 'Complaint Status Change Notification';
                $tokens = array();
                $tokens[] = $user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }

            $message = 'no';
            if($web_user != ''){
                Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
                return redirect()->route('complaints.index');
            }else{
                $message = 'yes';
                return response()->json([
                    'message' => $message,
                    'success' => 'true',
                    'complaint_status' => $request->working_status,
                ], 201);
            } 
        }

    }

    public function complaintfeedback(Request $request){

        $web_user = Auth::guard('web')->user();

        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $complaint = Complaint::with('subdepartment')->where('id',$request->complaint_id)->first();

         $complaint_logs = ComplaintFeedback::create([
            'complaint_id' => $complaint->id,
            'feedback_status' => $request->feedback_status,
            'comments' => $request->comments,
            'addedby' => $userId,
        ]);

        if ($request->feedback_status == 'satisfied' OR $request->feedback_status == 'no_comment') {
            $complaint_update = $complaint->update([
                'complaint_status'=> 'closed'
            ]);
            
            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'status'=> 'closed',
                'comments' => $request->comments,
                'addedby' => $userId,
            ]);
            
            // Get Complaint Reffer to Inactive from last supervisor
            $complaintRefer = ComplaintRefer::where('complaint_id',$request->complaint_id)->orderBy('id', 'DESC')->first();
            
            // complaint inactive from last supervisor
            if($complaintRefer != ''){
                $refer_update = $complaintRefer->update([
                    'is_active'=> 0
                ]);
            }

        }else{

            $complaint_update = $complaint->update([
                'complaint_status'=> $request->feedback_status,
            ]);

            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $request->complaint_id,
                'status'=> $request->feedback_status,
                'comments' => $request->comments,
                'addedby' => $userId,
            ]);

            $manager = $complaint->subdepartment->asstmanager->manager_id;
            $user = User::where('id',$manager)->first();
            
            // complaint inactive from last supervisor
            $complaintRefer = ComplaintRefer::where('complaint_id',$request->complaint_id)->update([
                    'is_active'=> 0
                ]);

            $asst_manager_id = $complaint->subdepartment->asstmanager->manager_id;
            if ($asst_manager_id > 0) {
                $user = User::where('id',$asst_manager_id)->first();
                $complaint_refers = ComplaintRefer::create([
                    'complaint_id' =>  $request->complaint_id,
                    'refer_to' => $asst_manager_id,
                    'refer_by' => $userId,
                ]);
                
                $details = [
                    'title' => $request->feedback_status.'<br>'.$request->comments,
                    'by' => $user_name,
                    'complaint_id' => $request->complaint_id,
                ];
                $user->notify(new \App\Notifications\ComplaintNotification($details));
                
                if($user->fcm_token !=''){
                    $title_message = $request->comments ?? 'Complaint Feedback Notification';
                    $tokens = array();
                    $tokens[] = $user->fcm_token;
                    $this->sendFCM($title_message, $tokens);  
                }
            } 
        }

        $message = 'no';
        if($web_user != ''){

            Session::flash('notify', ['type'=>'success','message' => 'Thanks For Your Feedback!']);
            return redirect()->route('complaints.index');
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'complaint_status' => $request->working_status,
            ], 201);
        } 
    }

    public function apiComplaint(Request $request) {
       $validator = Validator::make($request->all(), [
            'user_type' => 'required|string',
            'complaint_title' => 'required|string|min:3',
            'poc_name' => 'required|string',
            'poc_number' => 'required|string',
            'complaint_description' => 'required|string',
            'department_id' => 'required|integer',
            'sub_department_id' => 'required|integer',
            'reference_id' => 'nullable',
        ]);
        
        $reference_id = null;
        if($request->reference_id == 'Please Select' OR $request->reference_id == 0){
            $reference_id = null;
        }else{
           $reference_id = $request->reference_id;
        }
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $api_user_id = Auth::guard('api')->user();
        $userId = $api_user_id->id;
        $user_name = $api_user_id->name;
        $supervisor_id = 0;
        $supervisors = SubDepartmentSupervisor::where('sub_department_id',$request->sub_department_id)->with('complaint_refers')->withCount([
        'complaint_refers as reffer_count' => function ($query) {
            $query->where('is_active', 1);
        }])
        ->get();

        if($supervisors->count() > 0){
            $min_val = collect($supervisors)->min('reffer_count');
            $min_complaints = $supervisors->where('reffer_count', $min_val);
            $minimum_Complaints = $min_complaints->all();
            if($minimum_Complaints){
                $firstKey = array_key_first($minimum_Complaints);
                $supervisor_id = $minimum_Complaints[$firstKey]->supervisor_id;
            }
        }else{
            return response()->json([
                'error' => 'yes',
                'message' => 'No Supervisor Found',
            ], 400);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $fileName = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/complaint';
            $file = $image->move($loc,$fileName);

        }else{
            $fileName = '';
        }
        if($supervisor_id > 0){
            $complaint = Complaint::create([
                'complaint_title' => $request->complaint_title,
                'user_type' => $request->user_type,
                'poc_name' => $request->poc_name,
                'poc_number' => $request->poc_number,
                'reference_id' => $reference_id,
                'complaint_description' => $request->complaint_description,
                'department_id' => $request->department_id,
                'sub_department_id' => $request->sub_department_id,
                'image' => $fileName,
                'addedby' => $userId,
            ]);
            $complaint_refers = ComplaintRefer::create([
                'complaint_id' => $complaint->id,
                'refer_to' => $supervisor_id,
                'refer_by' => $userId,
            ]);
            $user = User::where('id',$supervisor_id)->first();
            $details=[
                        'title' => $request->complaint_title,
                        'by' => $user_name,
                        'complaint_id' => $complaint->id,
                    ];
            $user->notify(new \App\Notifications\ComplaintNotification($details));
            
            if($user->fcm_token !=''){
                $title_message = $request->complaint_title ?? 'Complaint Creation Notification';
                $tokens = array();
                $tokens[] = $user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
            $complaint_logs = ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'comments' => 'complaint created',
                'addedby' => $userId,
            ]);
            return response()->json([
                'error' => 'no',
                'success' => 'yes',
                'message' => 'Complaint Added Successfully!',
                'complaint' => $complaint,
            ], 201);

        }else{
            return response()->json([
                'error' => 'yes',
                'success' => 'no',
                'message' => 'No Supervisor Found!',
            ], 201);
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $message = 'no';
        $complaint = Complaint::find($id);
        if($complaint !=''){
            $message = 'No Complaint Found Against this id';
            $del_notifications = DB::table('notifications')->where('type', '=', 'App\Notifications\ComplaintNotification')->where('data->complaint_id','=',$id)->delete();
            $complaint_referers = ComplaintRefer::where('complaint_id', $id)->get();
            if($complaint_referers !=''){
                foreach($complaint_referers as $refer){
                    $refer->delete();
                }
            }
            $complaint->delete();
        }
        if($complaint !=''){
            $message = 'yes';
        }
        $api_user_id = Auth::guard('api')->user();
        if($api_user_id){
            return response()->json([
                'message' => $message,
                'complaint' => $complaint
            ], 201);
        }
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }

    public function storeComplaintImage(Request $request)
    {
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $complaint = Complaint::find($request
            ->id);

        $message = 'no';
        if($complaint !=''){
            if ($request->hasFile('image')) {
                $message = 'yes';
                $image = $request->file('image');
                $extension = $request->file('image')->extension();
                $fileName = time().mt_rand(10,99).'.'.$extension;
                $loc = 'uploads/complaint';
                $file = $image->move($loc,$fileName);
                $complaint->image = $fileName;
                $complaint->save();
            }else{
                $message = 'No Image found';
            }
        }
        return response()->json([
            'message' => $message,
            'complaint' => $$complaint,

        ], 201);
    }
    
    public function updateTAT(Request $request, $id)
    {
        abort_if(Gate::denies('update-tat-complaint-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $validator = Validator::make($request->all(), [
            'ta_time' => 'bail|required',
        ]);
        if ($validator->fails()) {
                return response()->json([ 'success' => 'no', 'error' => $request->toArray()
            ], 200);
        }else{
            if($this->webLogUser() !=''){
                $user_detail = $this->webLogUser();
            }else{
                $user_detail = $this->apiLogUser();
            }
            $complaint = Complaint::find($id);
            // $created_at = $complaint->created_at;
            $today_dateTime = $this->today();
            if($request->ta_time != ''){
                $expire_time = $this->taTimeExpire($request->ta_time, $today_dateTime);
                
            }else{
                $expire_time = $complaint->expire_time;
            }
            // dd($expire_time->toDateTimeString());
            $current_daytime = $this->today();
            if($complaint !=''){
                $complaint->ta_time = $request->ta_time;
                $complaint->expire_time = $expire_time;
                
                if($expire_time > $current_daytime){
                    $complaint->is_expired = 0;
                }
                
                $complaint->updatedby = $user_detail->id;
                $complaint->updated_at = $this->currentDateTime();
                $complaint->save();
                $complaint_logs = ComplaintLog::create([
                    'complaint_id' => $id,
                    'status'=> $complaint->complaint_status,
                    'comments' => 'Turnaround time(TAT) Updated',
                    'addedby' => $user_detail->id,
                ]);
            }

            return response()->json([ 'success' => 'yes', 'message' =>'Turnaround time(TAT) has been updated'
            ], 200);
        }  
    }
    
    public function taTimeExpire($input, $today_dateTime){
        $exp_time = '';
        $time = '';
        if($today_dateTime !=''){
            if($input == '30 Minutes'){
                $time = 30;
                $exp_time = $today_dateTime->addMinutes($time);
            }else if($input == '45 Minutes'){
                $time = 45;
                $exp_time = $today_dateTime->addMinutes($time);
            }else if($input == '1 Hour'){
                $time = 1;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '3 Hours'){
                $time = 3;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '6 Hours'){
                $time = 6;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '9 Hours'){
                $time = 9;
                $exp_time = $today_dateTime->addHours($time);
            }else if($input == '1 Day'){
                $time = 1;
                $exp_time = $today_dateTime->addDays($time);
            }else if($input = '2 Days'){
                $time = 2;
                $exp_time = $today_dateTime->addDays($time);
            }else if($input == '3 Days'){
                $time == 3;
                $exp_time = $today_dateTime->addDays($time);
            }else{
                $time == 6;
                $exp_time = $today_dateTime->addDays($time);
            }
        }
        return $exp_time;
    }
}
