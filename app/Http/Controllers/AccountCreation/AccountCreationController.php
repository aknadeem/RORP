<?php

namespace App\Http\Controllers\AccountCreation;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentHod;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\User;
use App\Models\UserLevel;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Session;

class AccountCreationController extends Controller
{
    use HelperTrait;
    public function viewResidentsAccount(){
    if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::cursor();
            $users = User::with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','>',5)->cursor();
        }elseif($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->cursor();
            $users = User::where('user_level_id','>',5)->whereIn('society_id', $this->adminSocieties())->with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','!=',$user_detail->user_level_id)->cursor();
        }else{
            $societies = Society::where('id', $user_detail->society_id)->cursor();
            $users = User::where('user_level_id','>',5)->where('society_id', $user_detail->society_id)->with('userlevel.permissions','permissions','society:id,code,name')->where('user_level_id','!=',$user_detail->user_level_id)->cursor();
        }
        
        // dd($users->toArray());
        return view('usermanagement.accountcreation.residents_accounts',  compact('users','societies'));
    }
        
    // public function index(){
        
    //     if($this->webLogUser() !=''){
    //         $user_detail = $this->webLogUser();

    //     }else{
    //         $user_detail = $this->apiLogUser();
    //     }
    //     if($user_detail->user_level_id < 2){
    //         $departments = Department::with('subdepartments')->get();
    //     }elseif($user_detail->user_level_id == 2){
    //         $departments = Department::whereIn('society_id', $this->adminSocieties())->with('subdepartments')->get();
    //     }else{
    //         $departments = Department::where('society_id', $user_detail->society_id)->with('subdepartments')->get();
    //     }
    //     $hods = User::where('user_level_id',3)->get();
    //     return view('departments.index', compact('departments','hods'));
    // }

    // public function create(){
    //     if($this->webLogUser() !=''){
    //         $user_detail = $this->webLogUser();

    //     }else{
    //         $user_detail = $this->apiLogUser();
    //     }
    //     if($user_detail->user_level_id < 2){
    //         $societies = Society::get();
    //         $sectors = SocietySector::get();
    //     }elseif($user_detail->user_level_id == 2){
    //         $societies = Society::whereIn('id', $this->adminSocieties())->get();
    //         $sectors = SocietySector::whereIn('society_id', $this->adminSocieties())->get();
    //     }else{
    //         $societies = Society::where('id', $user_detail->society_id)->get();
    //         $sectors = SocietySector::where('society_id', $user_detail->society_id)->get();
    //     }
        
    //     $department = new Department();
    //     return view('departments.create', compact('department','societies','sectors'));
    // }


    public function createAccount($id){
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();

        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::get();
            $sectors = SocietySector::get();
        }elseif($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get();
            $sectors = SocietySector::whereIn('society_id', $this->adminSocieties())->get();
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get();
            $sectors = SocietySector::where('society_id', $user_detail->society_id)->get();
        }
    	$user = ResidentData::find($id); 
    	$user_levels = UserLevel::get(); 
        return view('usermanagement.accountcreation.create_residentaccount', compact('user','user_levels','societies','sectors'));
    }

    public function verifyAccount(Request $request){
        $this->validate($request,[
            'society_id' => 'bail|required|integer',
            'name' => 'bail|required|string|min:3',
            'email' => 'bail|required|string|unique:users',
            'user_level_id' => 'bail|required|integer',
        ]);
        
        $userId = \Auth::user()->id;
        $user = ResidentData::find($request->resident_id); 
        $society_code = $user->society->code;
        $cnic = $user->cnic;
        $unique_id = $this->getUniqueID($society_code, $cnic);
        $decrypt_passwerd = Crypt::decryptString($user->password);
        $new_user = User::create([
            'unique_id' => $unique_id,
            'name' => $request->name,
            'society_id' => $request->society_id,
            'society_sector_id' => $request->society_sector_id,
            'email' => $request->email,
            'cnic' => $cnic,
            'contact_no' => $request->contact_no,
            'password' => Hash::make($decrypt_passwerd),
            'user_level_id' => $request->user_level_id,
            'addedby' => $userId,
            'resident_id' => $user->id,
        ]);
        
        if($new_user){
           $user->is_account = 1;
           $user->save();
        }
        Session::flash('notify', ['type'=>'success','message' => 'Resident Account created successfully']);
        return redirect()->route('users.index');
    }
    
    public function verifyAccountFromAdmin($id){
        $userId = \Auth::user()->id;
        $resident = ResidentData::findOrFail($id); 
        $society_code = $resident->society->code;
        $cnic = $resident->cnic;
        $unique_id = $this->getUniqueID($society_code, $cnic);
        $decrypt_passwerd = Crypt::decryptString($resident->password);
        $new_resident_user = User::create([
            'unique_id' => $unique_id,
            'name' => $resident->name,
            'society_id' => $resident->society_id,
            'society_sector_id' => $resident->society_sector_id,
            'email' => $resident->email,
            'cnic' => $cnic,
            'contact_no' => $resident->contact_no,
            'password' => Hash::make($decrypt_passwerd),
            'user_level_id' => $resident->user_level_id,
            'addedby' => $userId,
            'resident_id' => $resident->id,
        ]);
        
        if($new_resident_user){
           $resident->is_account = 1;
           $resident->save();
        }
        Session::flash('notify', ['type'=>'success','message' => 'Resident Account created successfully']);
        return back();
    }

    public function getUniqueID($society_code, $cnic)
    {
        $cnic = str_replace("-",'',$cnic);
        $cnic_last = $cnic[-1];
        $cnic_2last = $cnic[-2];
        $cnic_last2digit = $cnic_2last.$cnic_last;
        $c_day = Carbon::now()->format('d');
        $c_month = Carbon::now()->format('m');
        $c_year = Carbon::now()->format('y');
        $random_digit = mt_rand(1000,9999);  
        $unique_id = $society_code.'-'.$cnic_last2digit.$c_day.'-'.$c_month.$c_year.'-'.$random_digit;
        return $unique_id;
    }


    public function pendingAccounts(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies services
            $admin_soctities = $this->adminSocieties();
            $residents = ResidentData::where('is_account', 0)->where('pin_verified', 1)->whereIn('society_id', $admin_soctities)->with('user_data')->orderBy('id', 'DESC')->get(['id','email','name','society_id','m_pin','e_pin','pin_verified','created_at']);
        }else if($user_detail->user_level_id > 2){
            $residents = ResidentData::where('is_account', 0)->where('pin_verified', 1)->where('society_id', $user_detail->society_id)->orderBy('id', 'DESC')->with('user_data')->get(['id','email','name','society_id','m_pin','e_pin','pin_verified','created_at']);
        }else{
            $residents = ResidentData::where('is_account', 0)->where('pin_verified', 1)->orderBy('id', 'DESC')->with('user_data')->get(['id','email','name','society_id','m_pin','e_pin','pin_verified','created_at']);
        }
        
        $message = "No Data Found";
        $counts = $residents->count();
        if($counts > 0){
            $message = "yes";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('usermanagement.accountcreation.pending-accounts', compact('residents'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residents' => $residents
            ], 201);
        }

    }
}
