<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\User;
use App\Models\ComplaintLog;
use App\Models\RequestService;
use App\Models\Complaint;
use App\Models\DepartmentHod;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ComplaintNotification;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('auth')->except(['smsApi','dashboardApi']);
    }

    public function index() {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $societies = Society::with('province','city','complaints','request_services','smart_services','sectors','residents')->get();
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id',  $this->adminSocieties())->with('complaints','request_services','smart_services','sectors','residents')->get();
        }else if($user_detail->user_level_id == 3){
            $hod_id = $user_detail->id;
            $dephod  = DepartmentHod::has('accountdepartment')->with(['accountdepartment' => function($qry) use ($hod_id){
                   $qry->where('slug', 'accounts-finance');
                }])->where('hod_id', $hod_id)->first();
            if($dephod != ''){
                $societies = Society::where('id',$user_detail->society_id)->with('complaints','request_services','smart_services','sectors','residents')->get();
            }else{
                $hod_deps = $this->hodDepartments();
                $societies = Society::where('id',$user_detail->society_id)->with('complaints','request_services','smart_services','sectors','residents')->with('request_services', function($q) use ($hod_deps){
                    $q->whereIn('type_id', $hod_deps);
                })->with('complaints', function($q) use ($hod_deps){
                    $q->whereIn('department_id', $hod_deps);
                })->get();  
            }
            
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $societies = Society::where('id',$user_detail->society_id)->with('complaints','request_services','smart_services','sectors','residents')->with('request_services', function($q) use ($sub_ids){
                $q->whereIn('sub_type_id', $sub_ids);
            })->with('complaints', function($q) use ($sub_ids){
                $q->whereIn('sub_department_id', $sub_ids);
            })->get();
            
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            
            $societies = Society::where('id',$user_detail->society_id)->with('complaints','request_services','smart_services','sectors','residents')->with('request_services', function($q) use ($supervisors_subdeps){
                $q->whereIn('sub_type_id', $supervisors_subdeps);
            })->with('complaints', function($q) use ($supervisors_subdeps){
                $q->whereIn('sub_department_id', $supervisors_subdeps);
            })->get();
            
        }else{
            $societies = Society::where('id',$user_detail->society_id)->with('complaints','request_services','smart_services','sectors','residents')->get();
        }
        return view('home', compact('societies'));
    }
    
    
    public function dashboardApi()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $societies = Society::with('complaints:id,complaint_status,society_id','request_services:id,status,service_type,society_id,type_id')->get()
        ->Append(['total_services','pending_services','inprocess_services','resolved_services','total_complaints','pending_complaints','inprocess_complaints','resolved_complaints']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id',  $this->adminSocieties())->with('complaints:id,complaint_status,society_id','request_services:id,status,service_type,society_id,type_id')->get()
        ->Append(['total_services','pending_services','inprocess_services','resolved_services','total_complaints','pending_complaints','inprocess_complaints','resolved_complaints']);
        }else{
            $societies = Society::where('id',$user_detail->society_id)->with('complaints:id,complaint_status,society_id','request_services:id,status,service_type,society_id,type_id')->get()
        ->Append(['total_services','pending_services','inprocess_services','resolved_services','total_complaints','pending_complaints','inprocess_complaints','resolved_complaints']);
        }
        
        // $societies = Society::with('complaints:id,complaint_status,society_id','request_services:id,status,service_type,society_id,type_id')->get()
        // ->Append(['total_services','pending_services','inprocess_services','resolved_services','total_complaints','pending_complaints','inprocess_complaints','resolved_complaints']);
    
        $counts = $societies->count();
        $message = 'no';
        if($counts >0){
            $message = 'yes';
        }
        
        
        return response()->json([
            'message' => $message,
            'societies' => $societies,
        ], 200);
    }
    
    public function smsApi($to, $m_pin){
        $msg = 'Your One time pin (OTP) for Royal Orchard App is: '.$m_pin;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wp2rqd.api.infobip.com/sms/2/text/advanced',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"messages":[{"from":"IB Test","destinations":[{"to":"'.$to.'"}],"text":"'.$msg.'"}]}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: App 6a7c9e8d0dcf500bd82771334a1e53f4-b9c8354b-f6ec-4b06-a81d-772b7f30cb60',
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return $response;
        // });
    }
    
    public function checkEmailExists($email)
    {
        $user = '';
        if($email !=''){
            $user = User::where('email', $email)->exists();
        }
        if($user){
            $message = 'yes';
        }else{
            $message = 'no';
        }
        return response()->json([
            'message' => $message,
        ], 200);
    }
}
