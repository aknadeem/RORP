<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Society;
use App\Models\SubDepartment;
use App\Models\SubDepartmentManager;
use App\Models\SubDepartmentSupervisor;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Gate;
class SubDepartmentController extends Controller
{
    use HelperTrait;

    public function index(){
        $web_user_id = Auth::guard('web')->user();
        $message = 'no';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $societies = Society::get();
            $departments = Department::with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $supervisors = User::where('user_level_id', 5)->get();
            $managers = User::where('user_level_id',4)->get();
            
            $subdepartments = SubDepartment::with('supervisors','department','department.society')->get();
        }
        else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get();
            $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $subdepartments = SubDepartment::whereIn('society_id',$this->adminSocieties())->with('supervisors','department','department.society')->get();
            
            $supervisors = User::whereIn('society_id',$this->adminSocieties())->where('user_level_id', 5)->get();
            $managers = User::whereIn('society_id',$this->adminSocieties())->where('user_level_id',4)->get();

        }elseif($user_detail->user_level_id ==3){
            $societies = Society::where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereIn('id',$this->hodDepartments())->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->whereIn('department_id',$this->hodDepartments())->with('asstmanager','supervisors','department','department.society')->get();
            $manager_ids = array();
            $supervisors_ids = array();
            if($subdepartments !=''){
                foreach ($subdepartments as $dep) {
                    if($dep->asstmanager){
                        array_push($manager_ids, $dep->asstmanager->manager_id);   
                    }
                    if($dep->supervisors !=''){
                        foreach ($dep->supervisors as $key => $value) {
                            array_push($supervisors_ids, $value->supervisor_id);
                        }
                    }
                }
            }
            $supervisors = User::find($supervisors_ids,['id','name']);
            $managers = User::find($manager_ids,['id','name']);
        }elseif($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            // dd($sub_ids);
            $societies = Society::where('id', $user_detail->society_id)->get();
            
            $departments = Department::where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->whereIn('id',$sub_ids)->with('asstmanager','supervisors','department','department.society')->get();
            
            
            $manager_ids = array();
            $supervisors_ids = array();
            if($subdepartments !=''){
                foreach ($subdepartments as $dep) {
                    if($dep->asstmanager){
                        array_push($manager_ids, $dep->asstmanager->manager_id);   
                    }
                    if($dep->supervisors !=''){
                        foreach ($dep->supervisors as $key => $value) {
                            array_push($supervisors_ids, $value->supervisor_id);
                        }
                    }
                }
            }
            $supervisors = User::find($supervisors_ids,['id','name']);
            $managers = User::find($manager_ids,['id','name']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->with('supervisors','department','department.society')->get();
            $supervisors = User::where('society_id',$user_detail->society_id)->where('user_level_id', 5)->get();
            $managers = User::where('society_id',$user_detail->society_id)->where('user_level_id',4)->get();
        }
        $counts = $subdepartments->count();
        if($counts > 0){
            $message = 'yes';
        }
        if($this->webLogUser() !=''){
           return view('societymanagement.departments.subdepartments.index', compact('subdepartments','supervisors','managers','societies','departments'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'subdepartments' => $subdepartments
            ], 201);
        }
    }
    public function create(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $departments = Department::with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->get();
            
        }else if($user_detail->user_level_id == 2){
            $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->whereIn('society_id',$this->adminSocieties())->get();
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereIn('id', $this->hodDepartments())->with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->where('society_id', $user_detail->society_id)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $departments = Department::where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $managers = User::where('user_level_id',4)->where('society_id',$user_detail->society_id)->get();
        }else{
            $departments = collect();
            $managers = collect();
        }
        $subdepartment = new SubDepartment();
        return view('societymanagement.departments.subdepartments.create', compact('subdepartment','departments','managers'));
    }

    public function store(Request $request){        
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'name' => 'required|string|min:3',
            'department_id' => 'required',
            'manager_id' => 'integer|nullable',
        ]);
        
        if($request->ta_time){
            $created_at = Carbon::now();
            $ta_time_minutes = $this->taTimeExpire($request->ta_time, $created_at);
            $ta_time = $request->ta_time;
        }else{
            $ta_time_minutes = null;
            $ta_time = null;
        }
        $slug = $this->getSlug($request->name);
        $ifExists = SubDepartment::where('slug', $slug)->get(['id','slug']);
        if($ifExists->count() > 0) {
            $type = 'danger';
            $message = 'The Sub Department has already been taken. Add New!';
        
        } else {
            $department = Department::find($request->department_id);
            $subdepartment = SubDepartment::create([
                'name' => $request->name,
                'slug' => $slug,
                'society_id' => $department->society_id,
                'department_id' => $request->department_id,
                'ta_time' => $ta_time,
                'ta_time_minutes' => $ta_time_minutes,
                'addedby' => $userId,
            ]);

            if($request->manager_id > 0){
                $subdepartment->asstmanager()->create([
                    'manager_id' => $request->manager_id,
                ]);
            } 

            if($request->from_level || $request->from_user){
                return redirect()->back();
            }else{
                $type = 'success';
                $message = 'New Sub Department created successfully!';
            } 
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('subdepartments.index'); 
    }
    
    public function show($id) {
        $subdepartment = '';
        $is_int = is_numeric($id);
        if($is_int){
            $subdepartment = SubDepartment::find($id);
            $message = "no";
            if($subdepartment != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        return response()->json([
            'message' => $message,
            'subdepartment' => $subdepartment
        ], 201);
    }

    public function edit($id) {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            
            $departments = Department::with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->get();
            
        }else if($user_detail->user_level_id == 2){
            $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->whereIn('society_id',$this->adminSocieties())->get();
        }else if($user_detail->user_level_id == 3){
            $departments = Department::whereIn('id', $this->hodDepartments())->with('subdepartments','society')->get();
            $managers = User::where('user_level_id',4)->where('society_id', $user_detail->society_id)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $departments = Department::where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments:id,name,slug,department_id')->get(['id','name','society_id']);
            $managers = User::where('user_level_id',4)->where('society_id',$user_detail->society_id)->get();
        }else{
            $departments = collect();
            $managers = collect();
        }
        
        
        $subdepartment = SubDepartment::find($id);
        
        // dd($subdepartment->toArray());
        return view('societymanagement.departments.subdepartments.create', compact('subdepartment','departments','managers'));
    }

    public function update(Request $request, $id) {
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'name' => 'required|string|min:3',
            'department_id' => 'required',
            'manager_id' => 'integer|nullable',
        ]);
        
        if($request->ta_time != ''){
            $created_at = Carbon::now();
            $ta_time_minutes = $this->taTimeExpire($request->ta_time, $created_at);
        }else{
            $ta_time_minutes = '';
        }
        
        // dd($ta_time_minutes);
        
        $slug = $this->getSlug($request->name);
        $subdepartment = SubDepartment::with('asstmanager')->find($id);
        $department = Department::find($request->department_id);
        $subdepartment->update([
            'name' => $request->name,
            'society_id' => $department->society_id,
            'department_id' => $request->department_id,
            'ta_time' => $request->ta_time,
            'ta_time_minutes' => $ta_time_minutes,
            'updatedby' => $userId,
        ]);
        if($request->manager_id > 0){
            $subdepartment->asstmanager()->update([
                'manager_id' => $request->manager_id,
            ]);
        }  
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('subdepartments.index');
    }
    
    // public function taTimeMinutes($input){
    //     if($input == '30 minutes'){
    //         $time = 30;
    //     }else if($input == '45 minutes'){
    //         $time = 45;
    //     }else if($input == '1 hour'){
    //         $time = 60;
    //     }else if($input == '3 hours'){
    //         $time = 60*3;
    //     }else if($input == '6 hours'){
    //         $time = 60*6;
    //     }else if($input == '9 hours'){
    //         $time = 60*9;
    //     }else if($input == '1 day'){
    //         $time = 1440;
    //     }else if($input == '2 days'){
    //         $time = 1440*2;
    //     }else{
    //         $time == 1440*3;
    //     }
    //     return $time;
    // }
    
    public function taTimeExpire($input, $created_at){
        $exp_time = '';
        $time = '';
        if($created_at !=''){
            if($input == '30 Minutes'){
                $time = 30;
                $exp_time = $created_at->addMinutes($time);
            }else if($input == '45 Minutes'){
                $time = 45;
                $exp_time = $created_at->addMinutes($time);
            }else if($input == '1 Hour'){
                $time = 1;
                $exp_time = $created_at->addHours($time);
            }else if($input == '3 Hours'){
                $time = 3;
                $exp_time = $created_at->addHours($time);
            }else if($input == '6 Hours'){
                $time = 6;
                $exp_time = $created_at->addHours($time);
            }else if($input == '9 Hours'){
                $time = 9;
                $exp_time = $created_at->addHours($time);
            }else if($input == '1 Day'){
                $time = 1;
                $exp_time = $created_at->addDays($time);
            }else if($input == '2 Days'){
                $time = 2;
                $exp_time = $created_at->addDays($time);
            }else if($input == '3 Days'){
                $time == 3;
                $exp_time = $created_at->addDays($time);
            }else{
                $time == 6;
                $exp_time = $created_at->addDays($time);
            }
        }
        return $exp_time;
    }

    public function destroy($id) {
        abort_if(Gate::denies('delete-subdepartments'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');
        
        $subdepartment = SubDepartment::find($id);
        if($subdepartment !=''){
            $subs = SubDepartmentManager::where('sub_department_id', $id)->get();
            if($subs != ''){
                foreach($subs as $manger){  
                    $manger->delete();
                }
            }
            $supervisors = SubDepartmentSupervisor::where('sub_department_id', $id)->get();
            if($supervisors != ''){
                foreach($supervisors as $sup){  
                    $sup->delete();
                }
            }
            $subdepartment->delete();
        }
        
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }

    public function setSupervisor_sub(Request $request) {
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'subdepartment_id' => 'required',
            'supervisor_id' => 'required',
        ]);
        foreach ($request->supervisor_id as $key => $value) {
            $sub_dep_supervisors = DB::table('sub_department_supervisors')->updateOrInsert(
                ['supervisor_id' => $value], 
                ['sub_department_id' => $request->subdepartment_id,
                'created_at' => $this->currentDateTime(),
                'updated_at' => $this->currentDateTime(),
            ]);
            
        }
        Session::flash('notify', ['type'=>'success','message' => 'Sub Department Supervisor Added successfully']);

        return redirect()->back();
    }

    // get departments supervisorss
    public function subdepartmentSupervisors($id) {
        $userId = \Auth::user()->id;
        $supervisors = SubDepartmentSupervisor::where('sub_department_id',$id)->with('subdepartment','supervisor:id,name,user_level_id','supervisor.userlevel:id,title')->get();
        return response()->json(['supervisors'=>$supervisors->toArray()]);
    }

    // add subdepartment assistant maanger
    public function addAssManager(Request $request) {
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'subdepartment_id' => 'required',
            'manager_id' => 'required',
        ]);
        $exmanager = SubDepartmentManager::where('sub_department_id',$request->subdepartment_id)->where('manager_id',$request->manager_id)->get();

        if($exmanager->count() > 0){
            $type = 'warning';
            $message = 'This Manager Already Attatched to That Subdepartment';
        }else{
            $manager = SubDepartmentManager::create([
                'sub_department_id' => $request->subdepartment_id,
                'manager_id' => $request->manager_id,
            ]); 
            $type = 'success';
            $message = 'Assistent Manager Added successfully';
        }
        Session::flash('notify', ['type'=>$type,'message' => $message ]);
        return redirect()->back();
    }
    // for api
    public function subdepartmentWithManagers() {
        $departments = Department::whereHas('hod')->get();
        $subdepartments = SubDepartment::whereHas('asstmanager')->get();
        // $subdepartmentsupervisors = SubDepartment::whereHas('supervisors')->get();
        $supervisors = SubDepartmentSupervisor::with('user')->get();
        $department_hods_count = $departments->count();
        $sub_manager_count = $subdepartments->count();
        $supervisor_count = $supervisors->count();
        $web_user_id = Auth::guard('web')->user();
        $message = 'no';
        if($sub_manager_count){
            $message = 'yes';
        }
        if($web_user_id ==''){
            return response()->json([
                'message' => $message,
                'department_hods_count' => $department_hods_count,
                'sub_manager_count' => $sub_manager_count,
                'supervisor_count' => $supervisor_count,
                'departments' => $departments,
                'subdepartments' => $subdepartments,
                'supervisors' => $supervisors,
            ], 201); 
        }
    }
}