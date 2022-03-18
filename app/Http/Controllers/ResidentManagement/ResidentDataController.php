<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentDataRequest;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Session;

class ResidentDataController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        // $users = ResidentData::where('type', 'resident')
        //         ->when($role, function ($query, $role) {
        //             return $query->where('role_id', $role);
        //         })
        //         ->get();

        // \Storage::disk('public')->exists('residents/'.$residentdata->image)
        // \File::exists(public_path('ReportesTodos-5.zip'));
        // echo \File::delete(public_path('uploads/complaint/162747705311.png'));

        if($user_detail->user_level_id < 2){
            $societies = Society::get(['id','code','name']);
            $residents = ResidentData::where('type', 'resident')->with('society:id,name')->orderBy('id','DESC')->get(['id','name','image','e_pin','m_pin','society_id','pin_verified','landlord_id','type']);
        }elseif($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            $residents = ResidentData::where('type', 'resident')->whereIn('society_id', $this->adminSocieties())->with('society:id,name')->orderBy('id','DESC')->get(['id','name','image','e_pin','m_pin','society_id','pin_verified','landlord_id','type']);
        }elseif($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6){
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
            $residents = ResidentData::where('type', 'resident')->where('society_id', $user_detail->society_id)->with('society:id,name')->orderBy('id','DESC')->get(['id','name','image', 'e_pin','m_pin','society_id','pin_verified','landlord_id','type']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
            $residents = ResidentData::where('id', $user_detail->resident_id)->with('society:id,name')->orderBy('id','DESC')->get(['id','name','image', 'e_pin','m_pin','society_id','pin_verified','landlord_id','type']);
        }

        // dd($residents->toArray());
        $message = "No Data Found";
        $counts = count($residents);
        if($counts > 0){
            $message = "yes";
        }
        if($this->webLogUser()){
            return view('residentmanagement.residentdata.index', compact('residents','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residents' => $residents
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
            $landlords = User::where('user_level_id','=',6)->get();
        }elseif($user_detail->user_level_id == 2){
            $landlords = User::whereIn('society_id', $this->adminSocieties())->where('user_level_id','=', 6)->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $landlords = User::where('resident_id', $user_detail->resident_id)->where('user_level_id','=', 6)->get();
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }else{
            $societies = [];
            $landlords = [];
        }
        return view('residentmanagement.residentdata.create', compact('resident','societies','landlords'));
    }

    public function store(ResidentDataRequest $req)
    {
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
        $e_pin = rand(1000,9999); // code to send  email
        $m_pin = rand(1000,9999); // code to send sms
        $password = Crypt::encryptString($req->password);
        
        $email_data = array(
        'sentto' => $req->email,
        'name' => $req->name,
        'sms_code' => $m_pin,
        'pin_code' => $e_pin,
        'subject' => 'Email Verification Code',
        );
        
        $mail_error = '';
        $message = '';
        
        try {
            Mail::send('accountemail', $email_data, function ($message) use ($email_data) {
                $message
                    ->to($email_data['sentto'], $email_data['name'], $email_data['sms_code'], $email_data['pin_code'], $email_data['subject'])
                    ->subject($email_data['subject']);
            });
        } catch (Exception $e) {
           $mail_error = 'The mail server could not deliver mail to'.$req->email;
        }
        
        if($mail_error == ''){
            $residentdata = ResidentData::create([
                'e_pin' => $e_pin,
                'm_pin' => $m_pin,
                'type' => $req->type,
                'password' => $password,
                'name' => $req->name,
                'father_name' => $req->father_name,
                'password' => $password,
                'cnic' => $req->cnic,
                'landlord_name' => $req->landlord_name,
                'mobile_number' => $mobile_number,
                'emergency_contact' => $req->emergency_contact,
                'email' => $req->email,
                'occuptaion' => $req->occuptaion,
                'gender' => $req->gender,
                'martial_status' => $req->martial_status,
                'business_number' => $req->business_number,
                'society_id' => $req->society_id,
                'society_sector_id' => $req->society_sector_id,
                'address' => $req->address,
                'previous_address' => $req->previous_address,
                'business_address' => $req->business_address,
                'mail_address' => $req->mail_address,
                'addedby' => $userId,
            ]);
            $send_sms = $this->sendSms($mobile_number, $m_pin);
            $type = 'success';
            $message = 'Data Created Successfully';
        }else{
            $type = 'danger';
            $message = 'Data Not Created Because'.$mail_error;
        }
        
        if (!$this->webLogUser()) {
            return response()->json([
                'message' => 'yes',
                'mail_error' => $mail_error,
                'residentdata' => $residentdata
            ], 200);
        }else{
            Session::flash('notify', ['type'=> $type, 'message' => $message]);
            return redirect()->route('residentdata.index');
        }
    }

    public function show($id) {
        $residentdata = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residentdata = ResidentData::find($id);
            $message = "no";
            if($residentdata != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must bE integer";
        }
        return response()->json([
            'message' => $message,
            'residentdata' => $residentdata
        ], 200);
    }

    public function edit($id)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $societies = Society::with('sectors:id,sector_name,society_id')->get(['id','name']);
            $landlords = User::where('user_level_id','=',6)->get();
        }elseif($user_detail->user_level_id == 2){
            $landlords = User::where('user_level_id','=',6)->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $landlords = User::where('resident_id', $user_detail->resident_id)->where('user_level_id','=', 6)->get();
            $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get(['id','name']);
        }else{
            $landlords = [];
            $societies = [];
        }
        $resident = ResidentData::find($id);
        return view('residentmanagement.residentdata.create', compact('resident','societies','landlords'));
    }

    public function update(Request $req, $id)
    {
        $user_update = '';
        $residentdata = ResidentData::find($id);
        if($residentdata == '') {
            $message = 'No data Found';
        }else{
            $message = 'yes';
            $residentdata->name = $req->name;
            $residentdata->father_name = $req->father_name;
            $residentdata->address = $req->address;
            $residentdata->martial_status = $req->martial_status;
            $residentdata->save();
            
            if($residentdata){
                $user_update = User::with('profile:id,address,society_id,society_sector_id','profile.society','profile.sector')->where('resident_id', $id)->first();
                if($user_update){
                    $user_update->name = $req->name;
                    $user_update->address = $req->address;
                    $user_update->save();
                }
            }
        }
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
            return redirect()->route('residentdata.index');
        }else{
            return response()->json([
                'message' => $message,
                'user' => $user_update
            ], 200);
        }
    }


    public function destroy($id)
    {
        $resident = ResidentData::find($id);
        $message = 'yes';
        if($resident == '') {
            $message = 'No Data Found';
        }else{
            $user = User::where('resident_id', $id)->first();
            if($user !=''){ 
                $user->delete(); 
            }
            
            if($resident->image != null && \File::exists(public_path('uploads/residents/'.$resident->image))){
                \File::delete(public_path('uploads/residents/'.$resident->image));
            }
                    
            $resident->delete(); 
        }
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
            return back();
        }else{
            return response()->json([
                'message' => $message,
                'resident' => $resident
            ], 200);
        } 
    }

    // public function destroy($id)
    // {
    //     $resident = ResidentData::find($id);
    //     $message = 'yes';
    //     if($resident == '') {
    //         $message = 'No Data Found';
    //     }else{
    //         $user = User::where('resident_id', $id)->first();
    //         if($user !=''){
    //             $user->delete(); 
    //         }
    //         $resident->delete(); 
    //     }
    //     if($this->webLogUser() !=''){
    //         Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
    //         return back();
    //     }else{
    //         return response()->json([
    //             'message' => $message,
    //             'resident' => $resident
    //         ], 200);
    //     } 
    // }

     
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


    public function storeUpdateImage(Request $request, $id)
    {
        $validator = \Validator::make($request->all(),[
            'image_file'=> 'required|image|mimes:jpg,jpeg,png |max:5000'
        ],[
            'image_file.max'=> 'Maximum Image size to upload is 5MB (5000KB). If you are uploading a photo, try to reduce its resolution to make it under 5MB',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->toArray(),
                'success' => 'no',
            ], 201);
        }
        $is_int = is_numeric($id);
        if($is_int){
            $residentdata = ResidentData::find($id);
            if($residentdata == '') {
                $message = 'No data Found';
                $success = 'no';
            }else{
                if ($request->hasFile('image_file')) {
                    if($residentdata->image != null && \Storage::disk('public')->exists('residents/'.$residentdata->image)){
                        \Storage::disk('public')->delete('residents/'.$residentdata->image);
                    }
                    $path = 'residents/';
                    $image_file = $request->file('image_file');
                    $extension = $request->file('image_file')->extension();
                    $imageName = time().mt_rand(10,99).'.'.$extension;
                    $upload = $image_file->storeAs($path, $imageName, 'public');
                }else{
                    $imageName = null;
                }
                $message = 'Resident Image Updated Successfully!';
                $success = 'yes';
                $residentdata->image = $imageName;
                $residentdata->updated_at = $this->currentDateTime();
                $residentdata->save();
            }
        }else{
            $message = 'Id Must be Integer';
            $success = 'no';
        }
       return response()->json([
            'message' => $message,
            'success' => $success,
            'residentdata' => $residentdata
        ], 200);
    }


    // verify sms and e mail pin with database
    public function verifyEpinMpin(Request $request){
        $residentdata = ResidentData::where('email',$request->email)->where('e_pin', $request->e_pin)->where('m_pin', $request->m_pin)->where('pin_verified','!=', 1)->first();
        // dd($residentdata->toArray());
        if($residentdata !=''){
            $residentdata->pin_verified = 1;
            $residentdata->pin_verified_at = $this->currentDateTime();
            $residentdata->save();
            $message = 'yes';
        }else{
            $message = 'no';
        }
        return response()->json([
            'message' => $message,
        ], 200); 
    }
}