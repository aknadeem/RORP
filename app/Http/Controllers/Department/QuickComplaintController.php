<?php

namespace App\Http\Controllers\Department;

use Session;
use Validator;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\SubDepartment;
use App\Models\QuickComplaint;
use App\Http\Controllers\Controller;

class QuickComplaintController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $subdepartments = SubDepartment::get();
            $quick_complaints = QuickComplaint::with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
        }else if($user_detail->user_level_id == 2){
            $subdepartments = SubDepartment::whereIn('society_id',$this->adminSocieties())->get();
            $quick_complaints = QuickComplaint::whereIn('society_id',$this->adminSocieties())->with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
        }elseif($user_detail->user_level_id ==3){
            $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->whereIn('department_id',$this->hodDepartments())->with('supervisors','department','department.society')->get();
            $sub_dep_ids = array();
            if($subdepartments != ''){
              foreach ($subdepartments as $key => $value) {
                array_push($sub_dep_ids, $value->id);
              }
            }
            if(count($sub_dep_ids) > 0){
                $quick_complaints = QuickComplaint::whereHas('subdepartment', function($q) use ($sub_dep_ids){
                    $q->whereIn('id', $sub_dep_ids);
                })->with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
            }else{
                $quick_complaints = collect();
            }
        }else if($user_detail->user_level_id == 4){
            $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->whereIn('id', $this->managerSubDepartments())->with('supervisors','department','department.society')->get();
            
            $quick_complaints = QuickComplaint::whereIn('sub_department_id', $this->managerSubDepartments())->with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
        }else if($user_detail->user_level_id == 5){
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $subdepartments = SubDepartment::where('society_id',$user_detail->society_id)->whereIn('id', $sp_visors_subdepartments)->with('supervisors','department','department.society')->get();
                $quick_complaints = QuickComplaint::whereIn('sub_department_id', $sp_visors_subdepartments)->with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
            }else{
                $subdepartments = collect();
                $quick_complaints = collect();
            }
            
            // dd($subdepartments);
        }
        else{
           $subdepartments = SubDepartment::where('society_id', $user_detail->society_id)->get();
            $quick_complaints = QuickComplaint::where('society_id', $user_detail->society_id)->with('subdepartment:id,name','addedby:id,name,user_level_id')->get();
        }
        $total = $quick_complaints->count();
        $success = 'no';
        if($total > 0){
            $success = 'yes';
        }
        if($this->webLogUser()){
            return view('societymanagement.departments.quickcomplaints.index', compact('subdepartments','quick_complaints'));
        }else{
            return response()->json([
            'success' => $success,
            'total' => $total,
            'subdepartments' => $subdepartments,
            'quick_complaints' => $quick_complaints,
        ], 201);
        }
        
    }
    
    public function show($id){
        $quick_complaint = QuickComplaint::find($id, ['id', 'title','detail']);
        $success = 'no';
        if($quick_complaint !=''){
            $success = 'yes';
        }else{
            $success = 'no';
        }
        
        return response()->json([
            'success' => $success,
            'quick_complaint' => $quick_complaint,
        ], 201);
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_department_id' => 'bail|required|integer',
            'quick_title' => 'bail|required|string|min:2',
            'quick_title' => 'nullable',
        ]);
        if ($validator->fails()) {
                return response()->json([ 'success' => 'no', 'error' => $request->toArray()
            ], 200);
        }else{
            $userId = \Auth::user()->id;
            // return response()->json([ 'success' => 'yes', 'message' => $request->toArray()
            // ], 200);
            $quickComplaint = QuickComplaint::create([
                'sub_department_id' => $request->sub_department_id,
                'title' => $request->quick_title,
                'detail' => $request->quick_detail,
                'addedby' => $userId
            ]);
            return response()->json([ 'success' => 'yes', 'message' =>'Quick complaint Added Successfully!'
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sub_department_id' => 'bail|required|integer',
            'quick_title' => 'bail|required|string|min:2',
            'quick_title' => 'nullable',
        ]);
        if ($validator->fails()) {
                return response()->json([ 'success' => 'no', 'error' => $validator->errors()->toArray()
            ], 200);
        }else{
            $userId = \Auth::user()->id;
            // return response()->json([ 'success' => 'yes', 'message' => $request->toArray()
            // ], 200);
            $quickComplaint = QuickComplaint::findOrFail($id);
            $quickComplaint->update([
                'sub_department_id' => $request->sub_department_id,
                'title' => $request->quick_title,
                'detail' => $request->quick_detail,
                'addedby' => $userId
            ]);
            return response()->json([ 'success' => 'yes', 'message' =>'Quick complaint Updated Successfully!'
            ], 200);
        }
    }
    public function destroy($id) {
        $quickComplaint = QuickComplaint::find($id);
        $quickComplaint->delete();
        Session::flash('notify', ['type' => 'danger', 'message' => 'Quick Complaint Deleted successfully']);
        return back();
    }

    public function getWithSubdepartment($id)
    {
        $quick_complaints = QuickComplaint::with('subdepartment:id,name','addedby:id,name,user_level_id')->where('sub_department_id', $id)->get();
        $count = $quick_complaints->count();
        if($count > 0){
            $success = 'yes';
            $total = $count;
        }else{
            $success = 'no';
            $total = 0;
        }
        return response()->json([
            'success' => $success,
            'total' => $total,
            'quick_complaints' => $quick_complaints,
        ], 201);
    }

}
