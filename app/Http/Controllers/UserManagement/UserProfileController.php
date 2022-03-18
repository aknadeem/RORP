<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Society;
use App\Models\SubDepartment;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Session;
use Validator;

class UserProfileController extends Controller
{
    use HelperTrait;
    // Admin Has Multiple societies
    public function getAdminSocities($id){

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user = User::with('societies','departments.department.society','subdepartments.subdepartment','supervisor_subdepartments')->find($id);

        // dd($user->toArray());
        if($user->user_level_id == 2){
            $societies = Society::get(['id','name','code']);
    	   return view('usermanagement.user.profile.admin-societies', compact('user','societies'));
        }elseif ($user->user_level_id == 3) {
            // Head Of Department
            if($user_detail->user_level_id == 2){
                $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments','society')->get();
            }else{
                $departments = Department::get();
            }
            return view('usermanagement.user.profile.hod-departments', compact('user','departments'));
        }elseif ($user->user_level_id == 4) {
            if($user_detail->user_level_id == 2){
                $subdepartments = SubDepartment::with(['department' =>function($qry){
                    return $qry->whereIn('society_id',$this->adminSocieties());
                }])->get();
            }elseif($user_detail->user_level_id == 3){
                $subdepartments = SubDepartment::with(['department' =>function($qry){
                    return $qry->where('society_id',$user_detail->society_id);
                }])->get();
            }else{
                $subdepartments = SubDepartment::with('department')->get();
            }
            return view('usermanagement.user.profile.manager-subdepartments', compact('user','subdepartments'));
        }elseif ($user->user_level_id == 5){
            return back();

        }else{
            return back();
        }
    }

    // Admin Has Multiple societies
    public function addAdminSocities(Request $request){
        $message = 'Data Not Created Successfully!';
        $type = 'danger';
        $user = User::find($request->user_id, ['id', 'name','user_level_id']);

        if ($user !='' ) {
            if ($user->user_level_id == 2) {
                $user->societies()->sync($request->society_id);
                $message = 'Data Created Successfully!';
                $type = 'success';
            }else{
                $message = "You Cant add Societies Against This user! Only Admin Has Multiple Societies!";
                $type = 'warning';  
            }
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return back();
    }

    public function updateUserProfile(Request $request, $id) {   
        $fileName = '';
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $extension = $request->file('user_image')->extension();
            $fileName = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/userprofile';
            $file = $image->move($loc,$fileName);   
        }
        $user = User::find($id);
        $userupdate = $user->update([
            'name'=>$request->name,
            'contact_no'=>$request->contact_no,
            'address'=>$request->address,
            'user_image'=>$fileName,
        ]);
        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=>'success', 'message' => 'Data Updated successfully']);
        return back();
    }

    // DeAttach Society From Admin
    public function deAttachAdminSociety(Request $request, $id)
    {
        if($this->webLogUser()->user_level_id < 2){
            $is_int = is_numeric($id);
            if($is_int){
                $user = User::has('societies')->with('societies:id,name')->find($request->user_id,['id','user_level_id','society_id']);
                $user_soc = $user->societies()->detach($id);
                if($user_soc){
                    $type = 'success';
                    $message = 'Society Successfully DeAttached!';
                }
            }else{
                $type = 'warning';
                $message = 'Society Id Must Be Integer';
            } 
        }else{
            $type = 'warning';
            $message = 'Only Super Admin Can Add Or Remove Admin Society';
        }
        
        Session::flash('notify', ['type'=> $type, 'message' => $message]);
        return back();
    }

    // DeAttach Department From HOD
    public function deAttachDepartment($department_id,$user_id)
    { 
        if($this->webLogUser()->user_level_id < 3){
            $is_int = is_numeric($department_id);
            if($is_int){
                $user = User::has('departments')->with('departments:id,hod_id,department_id')->find($user_id,['id','user_level_id']);
                // dd($user->toArray());
                $user_dep = $user->departments()->where('department_id', $department_id)->delete();
                // $user_soc = $user->departments()->detach($id);
                if($user_dep){
                    $type = 'success';
                    $message = 'Department Successfully DeAttached!';
                }
            }else{
                $type = 'warning';
                $message = 'Department Id Must Be Integer';
            }
        }else{
            $type = 'warning';
            $message = 'Only Super Admin OR Society Admin Can Add Or Remove HOD Department';
        }
        Session::flash('notify', ['type'=> $type, 'message' => $message]);
        return back();
    }

    // DeAttach SubDepartment From Assistent Manager
    public function deAttachSubDepartment(Request $request, $id)
    { 
        if($this->webLogUser()->user_level_id < 3){
            $is_int = is_numeric($id);
            if($is_int){
                $user = User::has('subdepartments')->with('subdepartments:id,manager_id,sub_department_id')->find($request->user_id,['id','user_level_id']);
                // dd($user->toArray());
                $user_dep = $user->subdepartments()->where('sub_department_id', $id)->delete();
                // $user_soc = $user->departments()->detach($id);
                if($user_dep){
                    $type = 'success';
                    $message = 'SubDepartment Successfully DeAttached!';
                }
            }else{
                $type = 'warning';
                $message = 'SubDepartment Id Must Be Integer';
            }
        }else{
            $type = 'warning';
            $message = 'Only Super Admin OR Society Admin OR Department HOD Can Add Or Remove Managers SubDepartment';
        }
        Session::flash('notify', ['type'=> $type, 'message' => $message]);
        return back();
    }

    // DeAttach SubDepartment From Assistent Manager
    public function deAttachSubDepartmentSupervisor($subdep_id,$userid)
    { 
        if($this->webLogUser()->user_level_id < 5){
            $is_int = is_numeric($subdep_id);
            if($is_int){
                $user = User::has('supervisor_subdepartments')->find($userid,['id','user_level_id']);
                // dd($user->toArray());
                $user_dep = $user->supervisor_subdepartments()->where('sub_department_id', $subdep_id)->delete();
                // $user_soc = $user->departments()->detach($id);
                if($user_dep){
                    $type = 'success';
                    $message = 'Supervisor Successfully DeAttached!';
                }
            }else{
                $type = 'warning';
                $message = 'SubDepartment Id Must Be Integer';
            }
        }else{
            $type = 'warning';
            $message = 'Only Super Admin OR Society Admin OR Department HOD OR SubDepartment Manager Can Add Or Remove Supervisor From SubDepartment';
        }
        Session::flash('notify', ['type'=> $type, 'message' => $message]);
        return back();
    }
    
    public function updateUserFcmToken(Request $request, $id){
        
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'device' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $user = User::find($id);
        $message = 'no';
        if($user){
            $message = 'yes';
            $user->fcm_token = $request->fcm_token;
            $user->device = $request->device;
            $user->updated_at = $this->currentDateTime();
            $user->save();
        }
        
        return response()->json([
            'message' => $message,
            'user' => $user
        ], 201);
    }
}