<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentHod;
use App\Models\Society;
use App\Models\SubDepartment;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;

class DepartmentController extends Controller
{
    use HelperTrait;
    public function index(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $societies = Society::get();
            $departments = Department::with('subdepartments','society')->get();
            $subdepartments = SubDepartment::get();
            $hods = User::where('user_level_id',3)->get();
        }
        else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get();
            $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::whereIn('society_id',$this->adminSocieties())->get();
            $hods = User::where('user_level_id',3)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id == 3){
            $societies = Society::where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereIn('id', $this->hodDepartments())->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->whereIn('department_id', $this->hodDepartments())->get();
            $hods = User::where([['user_level_id',3],['society_id', $user_detail->society_id]])->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $societies = Society::where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->whereIn('id', $sub_ids)->get();
            $hods = User::where([['user_level_id',3],['society_id', $user_detail->society_id]])->get();
        }
        else if($user_detail->user_level_id > 4){
            $societies = Society::where('id', $user_detail->society_id)->get();
            $departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->get();
            $hods = User::where([['user_level_id',3],['society_id', $user_detail->society_id]])->get();
        }else{
            $societies = collect();
            $departments = collect();
            $hods = collect();
        }

        $message = 'no';
        $counts = $departments->count();
        if($counts > 0){
            $message = 'yes';
        }
        
        if($this->webLogUser() !=''){
            return view('societymanagement.departments.index', compact('departments','hods','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'departments' => $departments,
                'subdepartments' => $subdepartments
            ], 201);
        }
    }
    
    public function forApi(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $departments = Department::whereHas('subdepartments')->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::get();
        }
        else if($user_detail->user_level_id == 2){
            $departments = Department::whereHas('subdepartments')->whereIn('society_id', $this->adminSocieties())->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::whereIn('society_id',$this->adminSocieties())->get();
            
        }elseif($user_detail->user_level_id == 3){
            
            $departments = Department::whereHas('subdepartments')->where('society_id', $user_detail->society_id)->whereIn('id', $this->hodDepartments())->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->whereIn('department_id', $this->hodDepartments())->get();
            
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $departments = Department::whereHas('subdepartments')->where('society_id', $user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->whereIn('id', $sub_ids)->get();
        }
        else if($user_detail->user_level_id > 4){
            $departments = Department::whereHas('subdepartments')->where('society_id', $user_detail->society_id)->with('subdepartments','society')->get();
            $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->get();
        }else{
            $departments = collect();
            $subdepartments = collect();
        }

        $message = 'no';
        $counts = $departments->count();
        if($counts > 0){
            $message = 'yes';
        }
        
        if($this->webLogUser() !=''){
            // return view('societymanagement.departments.index', compact('departments','hods','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'departments' => $departments,
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

        if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $societies = Society::get();
        }
        $department = new Department();
        return view('societymanagement.departments.create', compact('department','societies'));
    }

    public function store(Request $request){        
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'name' => 'required|string|min:3|unique:departments',
            'society_id' => 'required|integer',
        ]);
        // dd($request->toArray());
        $slug = $this->getSlug($request->name);
        $servicesubtype_id = DB::table('departments')->updateOrInsert(
            ['society_id' => $request->society_id, 'slug' => $slug], 
            [
                'name' => $request->name,
                'is_complaint' => $request->is_complaint,
                'is_service' => $request->is_service,
                'addedby' => $userId,
                'created_at' => $this->currentDateTime(),
                'updated_at' => $this->currentDateTime(),
            ]);
        
            if($request->from_level || $request->from_user){
                return redirect()->back();
            }else{

                $type = 'success';
                $message = 'New Department created successfully!';
            }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('departments.index');
    }

     public function show($id) {
        $sop = '';
        $is_int = is_numeric($id);

        if($is_int){
            $department = Department::with('subdepartments','society')->find($id);
            $message = "no";

            if($sop != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        return response()->json([
            'message' => $message,
            'department' => $department
        ], 201);
    }

    public function edit($id) {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $societies = Society::get();
        }

        $department = Department::find($id);
        return view('societymanagement.departments.create', compact('department','societies'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'name' => 'required|string|min:3|unique:departments,name,'.$id,
            'society_id' => 'required|integer',
        ]);
        $slug = $this->getSlug($request->name);

        // check slug if exists or not
        $ifExists = Department::where('id','!=', $id)->where('slug', $slug)->get(['id','slug']);
        if($ifExists->count() > 0) {
            $type = 'danger';
            $message = 'The Department has already been taken. Add New!';
        }else{
            $department = Department::find($id)->update([
                'name' => $request->name,
                'society_id' => $request->society_id,
                'is_complaint' => $request->is_complaint,
                'is_service' => $request->is_service,
                'slug' => $slug,
                'updatedby' => $userId,
            ]);

            $type = 'success';
            $message = 'The Department has already been taken. Add New!';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('departments.index');
    }

    public function destroy($id) {
        $department = Department::find($id);
        $department->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return back();
    }

    // add department hod
    public function setdepartment_hod(Request $request) {
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'department_id' => 'required',
            'hod_id' => 'required',
        ]);
        $exhod = DepartmentHod::where('department_id',$request->department_id)->where('hod_id',$request->hod_id)->get();
        if($exhod->count() > 0){
            $type = 'warning';
            $message = 'This HOD Already Attatched to That Department!';
        }else{
            $manager = DepartmentHod::create([
                'department_id' => $request->department_id,
                'hod_id' => $request->hod_id,
                'addedby' => $userId,
            ]); 
            $type = 'success';
            $message = 'Department HOD Added successfully';
        }
        Session::flash('notify', ['type'=>$type,'message' => $message ]);
        return redirect()->back();
    }

    // get departments hods
    public function getdepartment_hods($id) {
        $userId = \Auth::user()->id;
        $dep_hods = DepartmentHod::where('department_id',$id)->with('department:id,name','hod:id,name,user_level_id','hod.userlevel:id,title')->get();
        $counts = $dep_hods->count();
        if($counts > 0){
            $message = 'yes';
            $hods = $dep_hods->toArray();
        }else{
            $message = 'no';
            $hods = $dep_hods;
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'hods' => $hods,
        ], 200);
    }

    public function departmentWithHods() {
        // $subdep_sups = SubDepartmentSupervisor::with('supervisor')->get();
        $departments = Department::whereHas('hod')->get();
        $counts = $departments->count();

        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            $user_id = $web_user_id->id;
        }else{
            $api_user_id = Auth::guard('api')->user();
            $user_id = $api_user_id->id;
        }

        
        $message = 'No hods and Supervisor found';
        if($counts > 0){
            $message = 'yes';
        }
        if($api_user_id !=''){

           return response()->json([
                'message' => $message,
                'counts' => $counts,
                'departments' => $departments,
            ], 201); 
       }
    }
}