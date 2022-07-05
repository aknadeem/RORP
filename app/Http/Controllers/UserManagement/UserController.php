<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Society;
use App\Models\ResidentData;
use App\Models\User;
use App\Models\UserLevel;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HelperTrait;
    public function index()
    {
        // $user = DB::table('users')->update(['password' => '$2y$10$bO/HjopZqcZ3V.wp1bzpGu0eU.1Ozu1W1P90JH6flUiI4l9mGf1fm']);

        $permissionsArray = [];
        // $ulevels = UserLevel::with('permissions')->find(5);
        // if($ulevels->permissions != ''){
        //     foreach ($ulevels->permissions as $permission) {
        //         $permissionsArray[] = $permission->id;
        //     }
        // }
        // $users = User::with('userlevel.permissions','permissions')->where('user_level_id',5)->get();
        // foreach($users as $user){
        //     $user->permissions()->sync($permissionsArray);
        // }
        // dd('hello');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();

            abort_if(Gate::denies('view-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::cursor();
            $users = User::with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','<',6)->cursor();
            
            // dd($users->toArray());
        }elseif($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->cursor();
            $users = User::where('user_level_id','<',6)->whereIn('society_id', $this->adminSocieties())->where('user_level_id','>=',$user_detail->user_level_id)->with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','!=',$user_detail->user_level_id)->cursor();
        }else{
            $societies = Society::where('id', $user_detail->society_id)->cursor();
            $users = User::where('user_level_id','<',6)->where('user_level_id','>=',$user_detail->user_level_id)->where('society_id', $user_detail->society_id)->with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','!=',$user_detail->user_level_id)->cursor();
        }
        return view('usermanagement.user.index', compact('users','societies'));
    }

    public function create()
    {
        abort_if(Gate::denies('create-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get();
        }elseif($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get();
        }else{
            $societies = Society::with('sectors:id,sector_name,society_id')->get();
        }

        $user = new User();
        $user_levels = UserLevel::where('id', '>', $user_detail->user_level_id)->get();

        $permissions = Permission::get();
        return view('usermanagement.user.create', compact('user','user_levels','permissions','societies'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $userId = \Auth::user()->id;
        $this->validate($request,[
            'society_id' => 'bail|required|integer',
            'name' => 'bail|required|string|min:3',
            'email' => 'bail|required|string|unique:users',
            'password' => 'bail|required|string|min:6',
            'contact_no' => 'bail|required|string',
            'user_level_id' => 'bail|required|integer',
            'work_number' => 'bail|nullable',
            'gender' => 'bail|string|nullable',
            'address' => 'bail|nullable',
        ]);

        $user = User::create([
            'name' => $request->name,
            'society_id' => $request->society_id,
            'society_sector_id' => $request->society_sector_id,
            'email' => $request->email,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'password' => Hash::make($request->password),
            'user_level_id' => $request->user_level_id,
            'user_type' => $request->user_type,
            'work_number' => $request->work_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'addedby' => $userId
        ]);

        if($user->user_level_id == 2){
            $user->societies()->attach($request->society_id);
        }
        $permissionsArray = [];
        $ulevels = UserLevel::with('permissions')->find($request->user_level_id);
        if($ulevels->permissions != ''){
            foreach ($ulevels->permissions as $permission) {
                $permissionsArray[] = $permission->id;
            }
            $user->permissions()->sync($permissionsArray);
        }
        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('users.index');
    }

    public function show($id)
    { 
        abort_if(Gate::denies('view-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::with('societies','society:id,code,name','sector:id,sector_name,society_id','profile','departments:id,department_id,hod_id')->find($id,['id','unique_id','resident_id','user_level_id','name','cnic','email','contact_no','is_active','society_id','society_sector_id','gender','address','fcm_token']);
        $resident = '';
        if($user->resident_id > 0){
            $resident = ResidentData::with('tenants:id,name,landlord_id,father_name,email,cnic,martial_status,e_pin,m_pin,pin_verified','familes','servents','handymen.handy_type','vehicles.vehicleType')->find($user->resident_id, ['id','name','landlord_id']);
        }
        // dd($user->toArray());
        return view('usermanagement.user.userprofile', compact('user','resident'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::find($id);
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get();
        }elseif($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get();
        }else{
            $societies = Society::with('sectors:id,sector_name,society_id')->get();
        }
        $user_levels = UserLevel::where('id', '>', $user_detail->user_level_id)->get();
        $permissions = Permission::get();
        return view('usermanagement.user.create', compact('user','user_levels','permissions','societies'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $userId = \Auth::user()->id;
        $this->validate($request,[
            'society_id' => 'bail||required|integer',
            // 'society_sector_id' => 'bail|required|integer',
            'name' => 'bail|required|string|min:3',
            'email' => 'bail|required|string|unique:users,email,'.$id,
            'user_level_id' => 'required',
        ]);

        $user = User::with('societies')->find($id);
        $user->name = $request->name;
        $user->society_id = $request->society_id;
        // $user->society_sector_id = $request->society_sector_id;
        $user->email = $request->email;
        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        if($request->cnic != ''){
            $user->cnic = $request->cnic;
        }
        $user->user_level_id = $request->user_level_id;
        $user->gender = $request->gender;
        $user->contact_no = $request->contact_no;
        $user->address = $request->address;
        $user->updatedby = $userId;
        $user->updated_at = $this->currentDateTime();
        $user->save();

        if($user->user_level_id == 2){
            $user->societies()->attach($request->society_id);
        }

        $permissionsArray = [];
        $ulevels = UserLevel::with('permissions')->find($request->user_level_id);
        if($ulevels->permissions != ''){
            foreach ($ulevels->permissions as $permission) {
                $permissionsArray[] = $permission->id;
            }
            $user->permissions()->sync($permissionsArray, []);
        }
        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']);
        // return redirect()->back();
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::find($id);
        $user->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return redirect()->back();
    }

    public function editPermissions($userid)
    {
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::with('permissions.module')->findOrFail($userid);
        $perm_groupBy = $user->groupBy('code_name');
        // $permissions = Permission::get();
        $permissions = Permission::with('module')->get();
        $permissions = $permissions->groupBy('code_name');
        $allmodules = Module::get();
        // dd($perm_groupBy);
        return view('usermanagement.user.setuserpermission', compact('user','permissions','allmodules'));
    }

    public function setPermissions(Request $request, $userid)
    {

        abort_if(Gate::denies('assign-permission-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'permissions' => 'required',

        ]);
        $user = User::findOrFail($userid);
        $user->permissions()->sync($request->input('permissions', []));
        Session::flash('notify', ['type'=>'success','message' => 'User Privileges Updated Successfully']);
        return redirect()->route('users.index');
    }

    public function toggleStatus($id)
    {
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::find($id);
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        if ($user->is_active == 0) {
            $user->is_active = 1;
        }else{
            $user->is_active = 0;
        }
        $user->updatedby = $userId;
        $user->updated_at = $this->currentDateTime();
        $user->save();
        Session::flash('notify', ['type'=>'success','message' => 'User Status Updated Successfully']);
        return redirect()->back();
    }
    public function changePasswordView($id){
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');
        $user = User::find($id);
        return view('usermanagement.user.changepassword', compact('user'));
    }

     public function changePasswordUpdate(Request $request)
    {
        abort_if(Gate::denies('update-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $auth_user_id = Auth::id();
        $user_id = $request->change_user_id ?? '';
        
        $data = request()->validate([
            'password' => 'bail|required|min:3|confirmed',
        ]);
        $user = User::where('id', $user_id)->first();

        $user->fill([
                'password' => Hash::make($request->password),
            ])
            ->save();
        if($auth_user_id == $user_id){
            Auth::logout();
            Session::flush();
        }
        Session::flash('notify', ['type'=>'success','message' => 'Password Updated Successfully']);
        return back();
    }

    public function UserPermissions($id)
    {
        abort_if(Gate::denies('view-user-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized for this page');

        $user = User::with('permissions')->find($id);
        $permissions = $user->permissions->groupBy('code_name');
        return view('usermanagement.user.user-permissions', compact('user','permissions'));
    }
}
