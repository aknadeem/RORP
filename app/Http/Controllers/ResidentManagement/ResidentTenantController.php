<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResidentDataRequest;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Session;

class ResidentTenantController extends Controller
{
	use HelperTrait;
    public function storeTenant(Request $req)
    {
        $web_user_id = 0;
        $api_user_id = 0;
        $userId = '';
        $mobile_number = '';
        $web_user_id = Auth::guard('web')->user();
        $api_user_id = Auth::guard('api')->user();
        
        if($web_user_id){
            $web_user_id = $web_user_id->id;
            $userId = $web_user_id;
            $remove_dash = str_replace( '-', '', $mobile_number);
            $remove_zero = ltrim($remove_dash, '0');
            $mobile_number = '92'.$remove_zero;
            $tenant_cnic =  $req->cnic;
        }
        if ($api_user_id) {
            $api_user_id = $api_user_id->id;
            $userId = $api_user_id;
            $mobile_number = $req->mobile_number1.$req->mobile_number2.$req->mobile_number3;
            $tenant_cnic =  $req->cnic1.'-'.$req->cnic2.'-'.$req->cnic3;
        }
        
        // return response()->json([
        //     'message' => 'yes',
        //     'tenant' => $req->toArray(),
        //     'tenant_cnic' => $tenant_cnic,
        //     'mobile_number' => $mobile_number
        // ], 201);
        
        // if ($request->hasFile('police_form')) {
        //     $image = $request->file('police_form');
        //     $extension = $request->file('police_form')->extension();
        //     $fileName = time().mt_rand(10,99).'.'.$extension;
        //     $loc = 'uploads/complaint';
        //     $file = $image->move($loc,$fileName);

        // }else{
        //     $fileName = '';
        // }

        $image_location = 'uploads/residenttenant';
        if($req->hasFile('police_form')){
            $police_form_image = $req->file('police_form');
            $extension = $req->file('police_form')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $police_form_image->move($image_location,$image_name);
        }else{
          $image_name =''; 
        }
        if($req->hasFile('tenant_agreement')){
            $image = $req->file('tenant_agreement');
            $extension = $req->file('tenant_agreement')->extension();
            $agreement_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$agreement_name);
        }else{
          $agreement_name =''; 
        }
        $e_pin = 0;
        $m_pin = 0;
        $password = null;
        if($req->is_account == 'yes' AND $req->email !=''){
            $e_pin = rand(1000,9999); // code to send  email
            $m_pin = rand(1000,9999); // code to send sms
            $password = Crypt::encryptString($req->password);
        }
        $tenant = '';
        $resident = User::find($req->landlord_id, ['id','name','resident_id','society_id','society_sector_id']);

        // dd($resident->toArray());
        if($resident !=''){
	        $tenant = ResidentData::create([
	            'e_pin' => $e_pin,
	            'm_pin' => $m_pin,
	            'type' => $req->type,
	            'name' => $req->name,
	            'father_name' => $req->father_name,
	            'password' => $password,
	            'cnic' => $tenant_cnic,
	            'landlord_id' => $resident->resident_id,
	            'landlord_name' => $resident->name,
	            'mobile_number' => $mobile_number,
	            'emergency_contact' => $req->emergency_contact,
	            'email' => $req->email,
	            'occuptaion' => $req->occuptaion,
	            'gender' => $req->gender,
	            'martial_status' => $req->martial_status,
	            'business_number' => $req->business_number,
	            'society_id' => $resident->society_id,
	            'society_sector_id' =>$resident->society_sector_id,
	            'address' => $req->address,
	            'previous_address' => $req->previous_address,
	            'business_address' => $req->business_address,
	            'mail_address' => $req->mail_address,
	            'police_form' => $image_name,
	            'tenant_agreement' => $agreement_name,
	            'addedby' => $userId,
	        ]);

	        if($req->is_account == 'yes' AND $req->email !=''){
	            $email_data = array(
	                'sentto' => $req->email,
	                'name' => $req->name,
	                'sms_code' => $m_pin,
	                'pin_code' => $e_pin,
	                'subject' => 'Email Verification Code',
	            );
	            Mail::send('accountemail', $email_data, function ($message) use ($email_data) {
	                $message
	                    ->to($email_data['sentto'], $email_data['name'], $email_data['sms_code'], $email_data['pin_code'], $email_data['subject'])
	                    ->subject($email_data['subject']);
	            });
	            $send_sms = $this->sendSms($mobile_number, $m_pin);
	        }
	        if($tenant != ''){
	            $type = 'success';
                $message = 'Tenant Added successfully';
	        }else{
	            $type = 'success';
                $message = 'Tenant Not Added';   
	        }
        }else{
            $type = 'danger';
            $message = 'No resident found';
        }
        if (!$this->webLogUser()) {
            return response()->json([
                'message' => $message,
                'tenant' => $tenant,
                'request_sended' => $req->toArray()
            ], 201);
        }else{
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('residenttenant.index');
        }
    }
    
    public function show($id) {
        $tenantdata = '';
        $is_int = is_numeric($id);
        if($is_int){
            $tenantdata = ResidentData::find($id);
            $message = "no";
            if($tenantdata != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        return response()->json([
            'message' => $message,
            'tenantdata' => $tenantdata
        ], 200);
    }

    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $tenants = ResidentData::where('type', 'tenant')->with('landlord:id,name')->get();
            $societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            $tenants = ResidentData::where('type', 'tenant')->whereIn('society_id', $this->adminSocieties())->with('landlord:id,name')->get();
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $tenants = ResidentData::where('landlord_id', $user_detail->resident_id)->with('landlord:id,name')->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }else{
            $societies = [];
            $tenants = [];
        }
        $message = "No Data Found";
        $counts = count($tenants);
        if($counts > 0){
            $message = "Success";
        }

        if($this->webLogUser()){
            return view('residentmanagement.residenttenants.index', compact('tenants','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'tenants' => $tenants
            ], 201);
        }   
    }
    
    public function create()
    {
        $resident = new ResidentData();
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();

        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::with('sectors:id,sector_name,society_id')->get(['id','name']);
            $landlords = User::where('user_level_id','=',6)->get(['id','name','unique_id','email','resident_id']);
        }elseif($user_detail->user_level_id == 2){
            $landlords = User::whereIn('society_id', $this->adminSocieties())->where('user_level_id','=',6)->get(['id','name','unique_id','email','resident_id']);
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $landlords = User::where('id','=',$user_detail->id)->get(['id','name','unique_id','email','resident_id']);
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }else{
            $landlords = [];
            $societies = [];
        }
        // dd($landlords->toArray());
        return view('residentmanagement.residenttenants.create', compact('resident','societies','landlords'));
    }
    
    public function edit($id)
    {
        $resident = ResidentData::findOrFail($id);
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();

        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::with('sectors:id,sector_name,society_id')->get(['id','name']);
            $landlords = User::where('user_level_id','=',6)->get(['id','name','unique_id','email','resident_id']);
        }elseif($user_detail->user_level_id == 2){
            $landlords = User::whereIn('society_id', $this->adminSocieties())->where('user_level_id','=',6)->get(['id','name','unique_id','email','resident_id']);
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $landlords = User::where('id','=',$user_detail->id)->get(['id','name','unique_id','email','resident_id']);
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }else{
            $landlords = [];
            $societies = [];
        }
        // dd($landlords->toArray());
        return view('residentmanagement.residenttenants.create', compact('resident','societies','landlords'));
    }

    public function sendSms($to, $m_pin){
        // Route::get('/send-signup-otp/{to}/sms/{pin}', function ($to, $m_pin) {
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
    public function storeUpdateImageWeb(Request $request)
    {
        $residentdata = '';
        $message = '';
        $image = $request->file('file');
        // $new_name = time().$image->getClientOriginalName();
        $extension = $request->file('file')->extension();
        $fileName = time().mt_rand(10,99).'.'.$extension;
        if($image){
            $residentdata = ResidentData::find($request->user_id);
            if($residentdata) {
                $loc = 'uploads/user';
                $message = 'yes';
                $file = $image->move($loc,$fileName);
                $residentdata->image = $fileName;
                $residentdata->save();
            }
        }else{
            $message = 'Image Field is requireds';
        }
        return response()->json([
            'message' => $message,
            'residentdata' => $residentdata
        ], 201);
    }

    public function storeUpdateImage(Request $request, $id)
    {
        $loc = 'uploads/user';
        $is_int = is_numeric($id);
        if($is_int){
            $residentdata = ResidentData::find($id);
            if($residentdata == '') {
                $message = 'No data Found';
            }else{
                $message = 'yes';
                $new_name = time().$request->image->getClientOriginalName();
                $file = $request->image->move($loc,$new_name);
                // return $new_name;
               $residentdata->image = $new_name;
               $residentdata->save();
            }
        }else{
            $message = 'Id Must be Integer';
        }
       return response()->json([
            'message' => $$message,
            'residentdata' => $residentdata
        ], 201);
    }

    public function update(ResidentDataRequest $req, $id)
    {
        $residentdata = ResidentData::find($id);
        if($residentdata == '') {
            $message = 'No data Found';
        }else{

            $web_user_id = 0;
            $api_user_id = 0;
            $userId = 0;
            $mobile_number = $req->mobile_number;
            $web_user_id = Auth::guard('web')->user();
            $api_user_id = Auth::guard('api')->user();
            
            if($web_user_id){
                $web_user_id = $web_user_id->id;
                $userId = $web_user_id;
                $remove_dash = str_replace( '-', '', $mobile_number);
                $remove_zero = ltrim($remove_dash, '0');
                $mobile_number = '92'.$remove_zero;
            }
            if ($api_user_id) {
                $api_user_id = $api_user_id->id;
                $userId = $api_user_id;
            }

            $e_pin = 0;
            $m_pin = 0;
            $password = '';
            if($req->is_account == 'yes' AND $req->email !=''){
                $e_pin = rand(1000,9999); // code to send  email
                $m_pin = rand(1000,9999); // code to send sms
                $password = Crypt::encryptString($req->password);
            }
            $image_location = 'uploads/residenttenant';
            if($req->police_form){
                $police_form_image = $req->file('police_form');
                $extension = $req->file('police_form')->extension();
                $image_name = time().mt_rand(10,99).'.'.$extension;
                $file = $police_form_image->move($image_location,$image_name);

                $residentdata->police_form = $image_name;
                $image_name =''; 
            }
            if($req->tenant_agreement){
                $image = $req->file('tenant_agreement');
                $extension = $req->file('tenant_agreement')->extension();
                $agreement_name = time().mt_rand(10,99).'.'.$extension;
                $file = $image->move($image_location,$agreement_name);
                $residentdata->tenant_agreement = $agreement_name;
            }else{
                $agreement_name =''; 
            }

            if($req->password != ''){
                $password = Crypt::encryptString($req->password);
            }else{
                $password = $residentdata->password;
            }
            $message = 'yes';

            $residentdata->name = $req->name;
	        $residentdata->father_name = $req->father_name;

	        if($req->emergency_contact != ''){
	            $residentdata->emergency_contact = $req->emergency_contact;
	        }
	        if($req->cnic != ''){
	            $residentdata->cnic = $req->cnic;
	        }
	        if($req->password != ''){
	            $residentdata->password = Crypt::encryptString($req->password);
	        }
	        if($req->address != ''){
	        	$residentdata->address = $req->address;
	        }
	        if($req->mobile_number != ''){
	        	$residentdata->mobile_number = $mobile_number;
	        }
            if($req->business_number != ''){
	        	$residentdata->business_number = $req->business_number;
	        }
	        if($req->martial_status != ''){
	        	$residentdata->martial_status = $req->martial_status;
	        }
	        if($req->gender != ''){
	        	$residentdata->gender = $req->gender;
	        }
	        if($req->business_address != ''){
	            $residentdata->business_address = $req->business_address;
	        }
	        if($req->mail_address != ''){
	            $residentdata->mail_address = $req->mail_address;
	        }
            
            if($req->society_id != ''){
	            $residentdata->society_id = $req->society_id;
	        }
	        if($req->society_sector_id != ''){
	            $residentdata->society_sector_id = $req->society_sector_id;
	        }
	        $residentdata->updatedby = $userId;
	        $residentdata->updated_at = $this->currentDateTime();
	        $residentdata->save();

            if($req->is_account == 'yes' AND $req->email !=''){
	            $email_data = array(
	                'sentto' => $req->email,
	                'name' => $req->name,
	                'pin_code' => $e_pin,
	                'subject' => 'Email Verification Code',
	            );
	            Mail::send('accountemail', $email_data, function ($message) use ($email_data) {
	                $message
	                    ->to($email_data['sentto'], $email_data['name'], $email_data['pin_code'], $email_data['subject'])
	                    ->subject($email_data['subject']);
	            });
	            $send_sms = $this->sendSms($mobile_number, $m_pin);
	        }
            
        }
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
            return redirect()->route('residenttenant.index');
        }else{
            return response()->json([
                'message' => $message,
                'tenant_update' => $residentdata
            ], 201);
        }
    }
}
