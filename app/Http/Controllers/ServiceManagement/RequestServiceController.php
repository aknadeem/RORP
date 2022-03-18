<?php
namespace App\Http\Controllers\ServiceManagement;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Service;
use App\Models\Society;
use App\Models\Department;
use App\Models\ServiceType;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\DepartmentHod;
use App\Models\ServiceDevice;
use App\Models\SubDepartment;
use App\Models\RequestService;
use App\Models\RequestServiceInternalLog;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SubDepartmentManager;
use Illuminate\Support\Facades\Mail;
use App\Models\SubDepartmentSupervisor;
// use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use DataTables;


class RequestServiceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
        
        if($user_level >= 6){
            $service_requests = RequestService::where('user_id', $userId)->with('service','user')->orderBy('id','DESC')->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();

        }else if($user_level == 5){
            $service_requests = RequestService::where('refer_to', $userId)->with('service','user')->orderBy('id','DESC')->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();

        }else if($user_level == 4){
            $sub_ids = $this->managerSubDepartments();
            $service_requests = RequestService::whereIn('sub_type_id', $sub_ids)->with('service','user')->orderBy('id','DESC')->get();
            $departments = Department::where('is_service', 1)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->whereHas('hod')->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();

        }else if($user_level == 3){

            $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $service_requests = RequestService::where('society_id', $user_detail->society_id)->with('service','user','package')->orderBy('id','DESC')->get();
                $departments = Department::where('society_id', $user_detail->society_id)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            }else{
                $user = User::with('departments')->find($userId);
                $hod_departments = array(); 
                foreach($user->departments as $key => $value)
                { 
                   $hod_departments[] = $value['department_id'];
                }
                $service_requests = RequestService::whereIn('type_id', $hod_departments)->with('service','user','package')->orderBy('id','DESC')->get();
                $departments = Department::where('society_id', $user_detail->society_id)->whereIn('id', $hod_departments)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();   
            }
            $societies = Society::whereHas('request_services')->where('id', $user_detail->society_id)->get();

        }else if($user_level == 2){
            $admin_soctities = $this->adminSocieties();
            $service_requests = RequestService::whereIn('society_id', $admin_soctities)->with('service','servicetype.hod','user','package')->orderBy('id','DESC')->get();
            $departments = Department::whereIn('society_id', $admin_soctities)->whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();

            $societies = Society::whereHas('request_services')->whereIn('id', $admin_soctities)->get();
        }else{
            $service_requests = RequestService::with('service','servicetype.hod','user:id,name,user_level_id','package')->orderBy('id','DESC')->get();
            $departments = Department::whereHas('hod')->where('is_service', 1)->with('subdepartments')->get();
            $societies = Society::whereHas('request_services')->get();
        }

        // dd($service_requests->toArray());
        
        $subdepartments_ids = array();
    	if($departments !=''){
    	    $department_array = $departments->toArray();
    	   // dd($department_array);
    	    foreach ($department_array as $key => $value) {
    	       if($value['subdepartments'] != ''){
    	            foreach($value['subdepartments'] as $k => $sub_dep){
        	           array_push($subdepartments_ids, $sub_dep['id']);
        	       }   
    	       }
            }
    	}
    	
    	if(count($subdepartments_ids) > 0){
    	    $subdep_sups = SubDepartmentSupervisor::whereIn('sub_department_id',$subdepartments_ids)->with('subdepartment')->get();
        }else{
            $subdep_sups = [];
        }
    	
        $service_requests = $service_requests->where('service_type','!=','monthly');
        // dd($service_requests->toArray());
        $message = "No Data Found";
        $counts = count($service_requests);
        // dd($counts);
        if($counts > 0){
            $message = "success";
        }
        if($this->webLogUser() !=''){
            return view('servicemanagement.service_request.index', compact('service_requests','subdep_sups','departments','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_requests' => $service_requests
        ], 201);
    }

    public function getRequestServicesList()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_level_id = $user_detail->user_level_id;
        $service_requests = RequestService::with('service','servicetype.hod','user:id,name,user_level_id','package')->orderBy('id','DESC')
        ->when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('type_id',$this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('sub_type_id',$this->managerSubDepartments());
        })->when($user_level_id == 5, function ($qry) use ($user_detail){
            $qry->where('refer_to', $user_detail->id);
        })->when($user_level_id > 5, function ($qry) use ($user_detail){
            $qry->where('user_id', $user_detail->id);
        })->withCasts([
            'created_at' => 'date:d M, Y'
        ])->get();

        return DataTables::of($service_requests)
        ->addIndexColumn()
        ->addColumn('service_id', function($row){
            return '<a href="'.route("requestservice.show", $row->id).'" title="Click to view Detail"> '.$row->service->title.'</a>';

        })->addColumn('user_id', function($row){
            return '<span> '.$row->user->name.' <b>['.$row->user->userlevel->title.'  ]</b></span>';

        })->addColumn('status', function($row) use ($user_level_id){
            $modal_option = '';
            $status = '<span class="badge badge-'.$row["status_color"].'">'.$row->status.'</span>';
            if ($user_level_id < 5 ){
                if ($row->billing_type != "no_billing"){
                    if ($row->status != "approved" AND $row->package_id > 0){
                        $modal_option = '<span request-id="'.$row->id.'" data-pckg-price="'.$row->package->price.'" request_ispaid="'.$row->is_paid.'" class="btn btn-sm btn-clean btn-icon btn-icon-md openPckgModel" title="Update">
                            <i class="flaticon-edit"></i>
                        </span>';
                    }
                    if ($row->status != "closed"){
                    $modal_option = '<span data-toggle="modal" data-target="#close-re-assign"
                        data-target-id="'.$row->id.'"
                        class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand" title="Click to Update">
                        <i class="flaticon-edit"></i>
                        </span>';
                    }
                    if ($row->status != "completed"){
                        $modal_option = '<span data-toggle="modal" data-target="#todoWorking"
                            data-target-id="'.$row->id.'" service_status="'.$row->status.'"
                            class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand" title="Click to Update">
                            <i class="flaticon-edit"></i>
                            </span>';
                    }
                }
            }
            return  $status.'<br>'.$modal_option;
        })
        ->addColumn('Actions', function($row){
            return '<a href="'.route("requestservice.edit", $row->id).'" class="text-warning"> <i
            class="fa fa-edit fa-lg" title="Click to Edit Service Request"></i> </a>
            <a href="'.route('requestservice.destroy', $row->id).'" class="text-danger delete-confirm" del_title="Service Request '. $row->code .'"><i class="fa fa-trash-alt fa-lg" title="Click to Delete Service Request"></i></a>';
        })
        ->rawColumns(['service_id','user_id','status','Actions'])
        ->make(true);
    }

    public function create()
    {
        abort_if(Gate::denies('create-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $service_request = new RequestService();
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $departments = Department::has('services')->with('services')->with('subdepartments')->orderBy('id','DESC')->get();
        $services = Service::with('tax_details')->orderBy('id','DESC')->get();
        $service_devices = ServiceDevice::with('tax_details')->orderBy('id','DESC')->get();
        $services_packages = ServicePackage::with('tax_details')->orderBy('id','DESC')->get();
        $users = User::where([['is_active', 1],['user_level_id', '>', 5]])->get(['id','user_level_id','unique_id','name','society_id']);
        
        
        if($user_detail->user_level_id == 1){
            $departments = $departments;
        $services = $services;
        $service_devices = $service_devices;
        $services_packages = $services_packages;
        $users = $users;
        }
        else if($user_detail->user_level_id == 2){
            $departments = $departments->whereIn('society_id',$this->adminSocieties());
            $services = $services->whereIn('society_id',$this->adminSocieties());
            $service_devices = $service_devices->whereIn('society_id',$this->adminSocieties());
            $services_packages = $services_packages->whereIn('society_id',$this->adminSocieties());
            
            $users = $users->whereIn('society_id',$this->adminSocieties());
        }else if($user_detail->user_level_id == 3){
            $departments = $departments->where('society_id',$user_detail->society_id)->whereIn('id', $this->hodDepartments());
            
            $services = $services->where('society_id',$user_detail->society_id)->whereIn('type_id', $this->hodDepartments());
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $departments = Department::has('services')->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('services')->with('subdepartments')->orderBy('id','DESC')->get();
            
            $services = $services->where('society_id',$user_detail->society_id)->whereIn('sub_type_id', $sub_ids);
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
            
            
        }else if($user_detail->user_level_id == 5){
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $services = Service::where('society_id', $user_detail->society_id)->whereIn('sub_type_id', $sp_visors_subdepartments)->with('tax_details')->orderBy('id','DESC')->get();
                
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments','services')->get();
            
            }else{
                $services = collect();
                $departments = collect();
            }
            
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }else{
            $departments = collect();
            $services = collect();
            $service_devices = collect();
            $services_packages = collect();
            $users = collect();
        }
        return view('servicemanagement.service_request.create', compact('service_request','services','departments','services_packages','service_devices','users'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        // $service_request = RequestService::find($id);
        $service_request = RequestService::with('devices','servicetype','package','service')->find($id);

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $departments = Department::has('services')->with('services','subdepartments')->orderBy('id','DESC')->get();
        $services = Service::with('tax_details')->orderBy('id','DESC')->get();
        $service_devices = ServiceDevice::with('tax_details')->orderBy('id','DESC')->get();
        $services_packages = ServicePackage::with('tax_details')->orderBy('id','DESC')->get();
        $users = User::where([['is_active', 1],['user_level_id', '>', 5]])->get(['id','user_level_id','unique_id','name','society_id']);

        
        if($user_detail->user_level_id == 1){
            $departments = $departments;
            $services = $services;
            $service_devices = $service_devices;
            $services_packages = $services_packages;
            $users = $users;
        }else if($user_detail->user_level_id == 2){
            $departments = $departments->whereIn('society_id',$this->adminSocieties());
            $services = $services->whereIn('society_id',$this->adminSocieties());
            $service_devices = $service_devices->whereIn('society_id',$this->adminSocieties());
            $services_packages = $services_packages->whereIn('society_id',$this->adminSocieties());
            
            $users = $users->whereIn('society_id',$this->adminSocieties());
        }else if($user_detail->user_level_id == 3){
            $departments = $departments->where('society_id',$user_detail->society_id)->whereIn('id', $this->hodDepartments());
            
            $services = $services->where('society_id',$user_detail->society_id)->whereIn('type_id', $this->hodDepartments());
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $departments = $departments->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            });

            $services = $services->where('society_id',$user_detail->society_id)->whereIn('sub_type_id', $sub_ids);
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }else if($user_detail->user_level_id == 5){
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $services = Service::where('society_id', $soc_id)->whereIn('sub_type_id', $sp_visors_subdepartments)->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
                
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments')->get();
            
            }else{
                $services = collect();
                $departments = collect();
            }
            
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }else{
            $services = Service::where('society_id', $user_detail->society_id)->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
            $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->with('subdepartments')->get();
            $service_devices = $service_devices->where('society_id',$user_detail->society_id);
            $services_packages = $services_packages->where('society_id',$user_detail->society_id);
            $users =  $users->where('society_id', $user_detail->society_id);
        }

        return view('servicemanagement.service_request.create', compact('service_request','services','departments','services_packages','service_devices','users'));
    }
    
    public function storeInternalLog(Request $request){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            $this->validate($request,[
                'internal_comment' => 'bail|required|string|min:1',
            ]);
        }else{
            $user_detail = $this->apiLogUser();
        }

        $request_Service = RequestService::find($request->service_request_id);
        if($request_Service !=''){
            $internal_log = RequestServiceInternalLog::create([
                'service_id'=> $request_Service->service_id,
                'service_request_id'=> $request->service_request_id,
                'status'=> $request_Service->status,
                'log_type'=> 'internal',
                'comments'=> $request->internal_comment,
                'addedby'=> $user_detail->id
            ]);   
        }else{
          $internal_log = collect(); 
        }
        
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'success','message' => 'Comment Added successfully!']);
            return back();
        }else{
            if($internal_log){
                $message = 'yes';
            }else{
                $message = 'no';
            }
            return response()->json([
                'message' => $message,
                'request_service_internal_log' => $internal_log,
            ], 201); 
        }
    }
    
    public function store(Request $request)
    {
        abort_if(Gate::denies('create-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $validator = Validator::make($request->all(), [
            'service_id' => 'bail|required|integer',
            'type_id' => 'nullable',
            'sub_type_id' => 'nullable',
        ]);

        // dd($request->toArray());
        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }
        // get user loged in apis or web 
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        if($request->has('is_behalf')){
            $user_id = $request->behalf_user_id;
            $is_behalf = 1;
        }else{
            $user_id =  $userId;
            $is_behalf = 0;
        }
        $message = '';
        $pckg_id = null;
        $device_id = null;
        if ($request->has(['package_id'])) {
            if ($request->package_id > 0) {
                $pckg_id = $request->package_id;
            }  
        }
        if ($request->has(['device_id'])) {
            if ($request->device_id > 0) {
                $device_id = $request->device_id;
            }  
        }
        
        // echo $device_id;
        /**
         * find Service if serve billing type is no_billing, then it refer to super visor else  * refer to hod
         *
        */
        
        $service_society_id = 0;
        $not_title = '';
        $service = Service::with('tax_details', 'servicetype')->find($request->service_id);
        // dd($service->toArray());
        $service_society_id = $service->society_id;
        if($service !=''){
            if($service->billing_type == 'no_billing' OR $service->billing_type == 'one_time'){
                // find superviser having minimum requests
                
                if($service->billing_type == 'no_billing'){
                    $not_title = 'User Request a Simple Service';
                }else{
                    $not_title = 'User Request a One Time Service';
                }
                // dd('hello');
                $supervisors = SubDepartmentSupervisor::where('sub_department_id',$request->sub_type_id)->with('service_requests')->withCount([
                'service_requests as request_count' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get();
                // dd($supervisors->toArray());
                if($supervisors !=''){
                    $min_val = collect($supervisors)->min('request_count');
                    $min_requests = $supervisors->where('request_count', $min_val);
                    $minimum_Requests = $min_requests->all();
                    if($minimum_Requests){
                        $firstKey = array_key_first($minimum_Requests);
                        $refer_to_id = $minimum_Requests[$firstKey]->supervisor_id;
                    }
                }else{
                    $message .= '<br/> No Supervisor Found';
                    $type = 'danger';
                }
            }else{
                $not_title = 'User Request a Smart Service';
                // get account department HOD
                //  $dephod  = DepartmentHod::has(['accountdepartment' => function($qry){
                //     return $qry->where('slug','==','account');
                // }])->first();
                $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($service_society_id){
                    $qry->where('society_id', $service_society_id);
                }])->first();

                // dd($dephod->toArray());
                // $dephod  = DepartmentHod::has('accountdepartment')->first();
                if($dephod->accountdepartment){
                    $refer_to_id = $dephod->hod_id;
                }else{
                    $message .= '<br/> No Head Of Department Found';
                    $type = 'danger';
                }
            }
            
            // dd($refer_to_id);
            // get user to refer request{ supervisor or hod }
            $user = User::where('id',$refer_to_id)->first();
            // dd($user->toArray());
            if($user !='' AND $message == ''){
                // store service request in database
                $serviceRequest = RequestService::create([
                    'service_type' => $service->billing_type,
                    'service_id' => $request->service_id,
                    'type_id' => $request->type_id, 
                    'sub_type_id' => $request->sub_type_id, 
                    'description' => $request->description, 
                    'package_id' => $pckg_id,
                    'device_id' => $device_id,
                    'refer_to' => $refer_to_id,
                    'refer_by' => $userId,
                    'addedby' => $userId,
                    'user_id' => $user_id,
                    'is_behalf' => $is_behalf,
                    'created_at' => $this->currentDateTime(),
                ]);
                // attach optional Devices
                if($request->has(['op_device_id']) != '') {
                    $serviceRequest->devices()->sync($request->op_device_id);
                }
                // attach required Device
                if($request->has(['device_id']) != ''){
                    $serviceRequest->devices()->attach($device_id);
                }
                // send notification to refer to person 
                $details=[
                    'title' =>  $not_title,
                    'sender_name' => $user_name,
                    'sender_id' => $userId,
                    'service_id' => $request->service_id,
                    'service_request_id' => $serviceRequest->id,
                ];

                if($user->fcm_token != null){
                    $title_message = $not_title ?? 'Smart Service Notification';
                    $tokens = array();
                    $tokens[] = $user->fcm_token;
                    $this->sendFCM($title_message, $tokens);  
                }

                $user->notify(new \App\Notifications\ServiceNotification($details));
                
                // generate service log
                $service_log = DB::table('service_logs')->insert([
                    'service_id' => $request->service_id,
                    'service_request_id' => $serviceRequest->id,
                    'status' => 'open', 
                    'comments' =>  $not_title,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);
                $message .= 'Data Created Successfully';
                $type = 'success';
            }else{
                $message .= '<br/> No Person Found To assign This service';
                $type = 'danger';
            }
            if($web_user !=''){
                Session::flash('notify', ['type'=> $type,'message' => $message]);
                if($service->billing_type == 'monthly'){
                    return redirect()->route('smr.index');
                }else{
                    return redirect()->route('requestservice.index');
                }
            }else{
                return response()->json([
                    'message' => $message,
                    'service_request' => $serviceRequest
                ], 201);
            } 
        }
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $s_request = '';
        $is_int = is_numeric($id);
        if($is_int){
            $s_request = RequestService::with('logs.user:id,name,user_level_id','internallogs.user:id,name,user_level_id','service','devices','invoice:id,request_service_id','package','user.society:id,code,name', 'user.profile:id,name,address','servicetype','subtype','referto:id,name,email,user_level_id')->find($id);
            // echo $s_request->devices->sum('total_tax');
            $package_price = 0;
            $devices_sum = 0;
            $total_price = 0;
            // $total_tax = 
            $message = "No Data Found";
            if($s_request != ''){
                if($s_request->package !=''){
                    $package_price =  $s_request->package->price;
                }
                if($s_request->devices !=''){
                    $devices_sum = $s_request->devices->sum('device_price');
                }
                // dd($package_price);
                $actual_pckg_price = $package_price;

                // $total_days_in_month = today()->daysInMonth;
                // $current_day = today()->format('d');
                // $remaining_days_inMonth = $total_days_in_month - $current_day;

                // $get_packagePrice = $package_price / $total_days_in_month;
                // $get_packagePrice = round($get_packagePrice);
                // $pckg_price_first_time = $get_packagePrice * $remaining_days_inMonth;

                $total_price = $s_request->service->installation_fee + $package_price + $devices_sum;
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }

        if($web_user !=''){
            if($s_request == ''){
                Session::flash('notify', ['type'=>'danger','message' => 'No Data Found']);
                return redirect()->route('requestservice.index');
            }
            return view('servicemanagement.service_request.sr-detail', compact('s_request','total_price'));
        }else{
            return response()->json([
                'message' => $message,
                'service_request' => $s_request,
                'total_price' => $total_price
            ], 201);
        } 
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $pckg_id = null;
        if ($request->has(['package_id'])) {
            if ($request->package_id > 0) {
                $pckg_id = $request->package_id;
            }  
        }
        $message = '';

        $device_id = 0;
        if ($request->has(['device_id'])) {
            if ($request->device_id > 0) {
                $device_id = $request->device_id;
            }  
        }
        // dd($request->toArray());
        $serviceRequest = RequestService::find($id);
        $service_type = DB::table('request_services')->where('id', $id)
          ->update([
            'service_id' => $request->service_id, 
            'type_id' => $request->type_id, 
            'sub_type_id' => $request->sub_type_id, 
            'package_id' => $pckg_id,
            'updatedby' => $userId,
            'created_at' => $this->currentDateTime(),
            'description' => $request->description,
        ]);
        if($request->op_device_id !=''){
            $serviceRequest->devices()->sync($request->op_device_id);
        }

        if($device_id > 0){
            $serviceRequest->devices()->attach($device_id);
        }

        $service_log = DB::table('service_logs')->insert([
            'service_id' => $request->service_id,
            'service_request_id' => $serviceRequest->id,
            'status' => $serviceRequest->status, 
            'comments' => 'Service Request Updated',
            'addedby' => $userId,
            'created_at' => $this->currentDateTime(),
        ]);
        if($service_type){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
        }

        Session::flash('notify', ['type'=> $type,'message' => $message]);
        if($serviceRequest->billing_type == 'monthly'){
            return redirect()->route('smr.index');
        }else{
            return redirect()->route('requestservice.index');
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $delete_service = DB::table('request_services')->delete($id);
        if($delete_service){
            $message = 'Data Deleted successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found Against this id';
            $type = 'danger';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->back();
    }

    public function requestServiceRemarks(Request $request){
        $validator = Validator::make($request->all(), [
            'service_status' => 'required',
            'comments' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 401);
        }
        // dd($request->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $request_service = RequestService::where('id',$request->service_request_id)->first();
        $request_addedby = User::where('id',$request_service->user_id)->first();
        if($request->service_status == 'in_process'){
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => $request->service_status,
                'updatedby' => $userId,
            ]);
            // send notification to refer to person 
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
            
            if($request_addedby->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $request_addedby->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
                
        }elseif($request->service_status == 'incorrect') {
            $dep_manager = SubDepartmentManager::where('sub_department_id',$request_service->sub_type_id)->first();
            $manager = User::where('id',$dep_manager->manager_id)->first();
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => $request->service_status,
                'refer_to' => $dep_manager->manager_id,
                'refer_by' => $userId,
                'updatedby' => $userId,
            ]);
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $manager->notify(new \App\Notifications\ServiceNotification($details));
            
            if($manager->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $manager->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }else{
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => $request->service_status,
                'updatedby' => $userId,
            ]);
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
            
            if($request_addedby->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $request_addedby->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }
        // Addd service Log
        $service_log = DB::table('service_logs')->insert([
            'service_id' => $request_service->service_id,
            'service_request_id' => $request->service_request_id,
            'status' => $request->service_status, 
            'comments' => $request->comments,
            'addedby' => $userId,
            'created_at' => $this->currentDateTime(),
        ]);
        $message = 'no';
        if($web_user != ''){
            Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
            // return redirect()->route('users.index');
            return redirect()->back();
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'service_status' => $request->service_status,
            ], 201);
        }  
    }

    // service Working by Manager And Hod
    public function updateStatus(Request $request){
        // dd($request->toArray());
        $validator = Validator::make($request->all(), [
            'service_status' => 'required',
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
        $request_service = RequestService::where('id',$request->service_request_id)->first();
        $request_addedby = User::where('id',$request_service->user_id)->first();
        if($request->service_status == 're_assign'){
            $new_refer = User::where('id',$request->user_id)->first();
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
            ->update([
                'status' => $request->service_status,
                'refer_to' => $request->user_id,
                'refer_by' => $userId,
                'updatedby' => $userId,
            ]);
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $new_refer->notify(new \App\Notifications\ServiceNotification($details));
            
            if($new_refer->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $new_refer->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }elseif($request->service_status == 'modified'){
            
            $dep_hod = DepartmentHod::where('department_id',$request->department_id)->first();
            $hod_user = User::where('id',$dep_hod->hod_id)->first();
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
                ->update([
                'status' => $request->service_status,
                'refer_to' => $hod_user->id,
                'refer_by' => $userId,
                'updatedby' => $userId,
            ]);
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $hod_user->notify(new \App\Notifications\ServiceNotification($details));
            
            if($hod_user->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $hod_user->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }else{
            
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => $request->service_status,
                'is_active' => 0,
                'updatedby' => $userId,
            ]);
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
            
            if($request_addedby->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $request_addedby->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }
        // Addd service Log
        $service_log = DB::table('service_logs')->insert([
            'service_id' => $request_service->service_id,
            'service_request_id' => $request->service_request_id,
            'status' => $request->service_status, 
            'comments' => $request->comments,
            'addedby' => $userId,
            'created_at' => $this->currentDateTime(),
        ]);
        /////////////////
        $message = 'no';
        if($web_user != ''){
            Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
            // return redirect()->route('users.index');
            return redirect()->back();
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'service_status' => $request->service_status,
            ], 201);
        }  
    }

    public function feedBack(Request $request){
        $validator = Validator::make($request->all(), [
            'service_status' => 'required',
            'comments' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 401);
        }
        // dd($request->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $request_service = RequestService::where('id',$request->service_request_id)->first();
        // find resident who request for service
        $request_addedby = User::where('id',$request_service->user_id)->first();
        if($request->service_status == 'satisfied' OR $request->service_status == 'no_comment'){
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => 'closed',
                'is_active' => 0,
                'updatedby' => $userId,
            ]);
            // send notification to refer to person 
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
            
            if($request_addedby->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $request_addedby->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
            
        }else{
            $dep_manager = SubDepartmentManager::where('sub_department_id',$request_service->sub_type_id)->first();
            $manager = User::where('id',$dep_manager->manager_id)->first();
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
              ->update([
                'status' => $request->service_status,
                'refer_to' => $dep_manager->manager_id,
                'refer_by' => $userId,
                'updatedby' => $userId,
            ]);
            // send notification to refer to person 
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $manager->notify(new \App\Notifications\ServiceNotification($details));
            
            if($manager->fcm_token !=''){
                $title_message = $request->comments ?? 'Service Status Update Notification';
                $tokens = array();
                $tokens[] = $manager->fcm_token;
                $this->sendFCM($title_message, $tokens);  
            }
            
        }
        // Addd service Log
        $service_log = DB::table('service_logs')->insert([
            'service_id' => $request_service->service_id,
            'service_request_id' => $request->service_request_id,
            'status' => $request->service_status, 
            'comments' => $request->comments,
            'addedby' => $userId,
            'created_at' => $this->currentDateTime(),
        ]);
        $message = 'no';
        if($web_user != ''){
            Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
            // return redirect()->route('users.index');
            return redirect()->back();
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'service_status' => $request->service_status,
            ], 201);
        } 
   }

   // department head can approve or reject the service package request
   public function approveCancelRequest(Request $request){
        // dd($request->toArray());
        abort_if(Gate::denies('update-service-request'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $validator = Validator::make($request->all(), [
            'service_status' => 'required',
            'comments' => 'required',
            'pckg_start_date' => 'nullable|after_or_equal:'.$this->currentDate(),
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }
        // dd($request->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        // dd($request->toArray());
        $message = 'no';
        $errors = 'no';
        $type = 'danger';
        // Get Service Request Detail
        $request_service = RequestService::where('id',$request->service_request_id)->with('service:id,billing_type','package:id,price')->first();
        // Who  Request the Service
        $request_addedby = User::where('id',$request_service->user_id)->first();

        // dd($request_addedby->toArray());
        if($request->service_status == 'cancel'){
            // update the service request table status, etc
            $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
                ->update([
                'status' => $request->service_status,
                'refer_to' => $request_service->user_id,
                'refer_by' => $userId,
                'updatedby' => $userId,
            ]);
            // send notification to service requested person
            $details=[
                'title' => $request->comments,
                'sender_name' => $user_name,
                'sender_id' => $userId,
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
            ];
            $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
            
            $email_data = array(
                'sentto' => $request_addedby->email,
                'name' => $request_addedby->name,
                'service_status' => $request->service_status,
                'comment' => $request->comments,
                'subject' => 'Service Notification',
            );

            Mail::send('accountemail', $email_data, function ($message) use ($email_data) {
                $message
                    ->to($email_data['sentto'], $email_data['name'], $email_data['service_status'], $email_data['comment'], $email_data['subject'])
                    ->subject($email_data['subject']);
            });
            
            $message = 'Data Updated Successfully';
            $type = 'danger';
        }else{
            $price = $request_service->package->price;
            // dd($request_service->toArray());
            if($request->discount_amount > 0 ){
                $final_price = $price - $request->discount_amount;
            }else{
                $final_price = $price;
            }
            if($final_price > $price){
                $message = 'Discount Amount Cannot Be Greatet Than '.$price;
                $errors = 'yes';
                // discount_percentage
            }else{
                $service_request_update = DB::table('request_services')->where('id', $request->service_request_id)
                    ->update([
                    'status' => $request->service_status,
                    'is_verified' => 1,
                    'updatedby' => $userId,
                ]);
                $user_service = DB::table('user_services')->insert([
                    'service_request_id' => $request->service_request_id,
                    'billing_type' => $request_service->service->billing_type,
                    'price' => $request_service->package->price,
                    'status' => 1,
                    'discount_amount' => $request->discount_amount,
                    'final_price' => $final_price,
                    'start_date' => $request->pckg_start_date,
                    'user_id' => $request_service->user_id,
                    'service_id' => $request_service->service_id,
                    'type_id' => $request_service->type_id,
                    'sub_type_id' => $request_service->sub_type_id,
                    'package_id' => $request_service->package_id,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);
                // send notification to service requested person
                $details=[
                    'title' => $request->comments,
                    'sender_name' => $user_name,
                    'sender_id' => $userId,
                    'service_id' => $request_service->service_id,
                    'service_request_id' => $request->service_request_id,
                ];
                $request_addedby->notify(new \App\Notifications\ServiceNotification($details));
                
                $email_data = array(
                    'sentto' => $request_addedby->email,
                    'name' => $request_addedby->name,
                    'service_status' => $request->service_status,
                    'comment' => $request->comments,
                    'subject' => 'Service Notification',
                );
    
                Mail::send('servicemanagement.serice_notification', $email_data, function ($message) use ($email_data) {
                    $message
                        ->to($email_data['sentto'], $email_data['name'], $email_data['service_status'], $email_data['comment'], $email_data['subject'])
                        ->subject($email_data['subject']);
                });
                $message = 'Data Updated Successfully';
                $type = 'success';
            }
        }
        
        if($request_addedby->fcm_token !=''){
            $title_message = $request->comments ?? 'Service Status Update Notification';
            $tokens = array();
            $tokens[] = $request_addedby->fcm_token;
            $this->sendFCM($title_message, $tokens);  
        }
                

        if($errors != 'yes'){
            // Add service Log
            $service_log = DB::table('service_logs')->insert([
                'service_id' => $request_service->service_id,
                'service_request_id' => $request->service_request_id,
                'status' => $request->service_status, 
                'comments' => $request->comments,
                'addedby' => $userId,
                'created_at' => $this->currentDateTime(),
            ]);
        }
        if($web_user != ''){
            Session::flash('notify', ['type'=>$type,'message' => $message]);
            // return redirect()->route('users.index');
            return redirect()->back();
        }else{
            $message = 'yes';
            return response()->json([
                'message' => $message,
                'success' => 'true',
                'service_status' => $request->service_status,
            ], 201);
        } 
   }
}
