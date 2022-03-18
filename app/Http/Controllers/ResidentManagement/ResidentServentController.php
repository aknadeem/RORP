<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentServentRequest;
use App\Models\ResidentData;
use App\Models\ResidentServent;
use App\Models\ServentType;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class ResidentServentController extends Controller
{
   use HelperTrait;
   public function serventTypes()
   {
       $serventtypes = ServentType::get();
        $message = "No Data Found";
        $counts = $serventtypes->count();

        if($counts > 0){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'serventtypes' => $serventtypes
        ], 201);
   }
    
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $residentservents = [];
        if($user_detail->user_level_id < 2){
            $societies = Society::get(['id','code','name']);
            $residentservents = ResidentServent::with('residentdata:id,name,society_id','society')->get();
        }elseif($user_detail->user_level_id == 2){
            $admin_soc = $this->adminSocieties();
            $societies = Society::whereIn('id',$admin_soc)->get(['id','code','name']);
            
            $residentservents = ResidentServent::whereIn('society_id',$admin_soc)->with('society','residentdata:id,name,society_id')->get();

        }elseif($user_detail->user_level_id >= 6){
            $societies = Society::where('id',$user_detail->resident_id)->get(['id','code','name']);
            $residentservents = ResidentServent::where('resident_data_id', $user_detail->resident_id)->with('society')->get();
        }else{
            $societies = [];
            $residentservents = [];
        }
        
        $message = "No Data Found";
        $counts = count($residentservents);
        if($counts > 0){
            $message = "Success";
        }

        if($this->webLogUser()){
            return view('residentmanagement.residentservent.index', compact('residentservents','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residentservents' => $residentservents
            ], 201);
        }
    }

    public function myservent($id)
    {
        $is_int = is_numeric($id);
        if($is_int){
            $residentservent = ResidentServent::where('resident_data_id',$id)->get();
            $message = "No Data Found";
            
            if($residentservent ==''){
                $message = "No Data Found";
            }
            $counts = $residentservent->count();
    
            if($counts > 0){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            // return view('residentmanagement.residentdata.index', compact('residents'));
            dd('true');
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residentservent' => $residentservent
            ], 201);
        }
    }

    public function create()
    {
        $user = new ResidentServent();
        $serventtypes = ServentType::get();
        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id >= 6 ){
            $residents = ResidentData::where('id',$user_detail->resident_id)->get();
        }
        return view('residentmanagement.residentservent.create', compact('user','residents','serventtypes'));
    }

    public function store(ResidentServentRequest $request)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $residentservent = ResidentServent::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'cnic' => $request->cnic,
            'servent_type_id' => $request->servent_type_id,
            'gender' => $request->gender,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'occupation' => $request->occupation,
            'resident_data_id' => $request->resident_data_id,
            'addedby' => $user_detail->id,
        ]);

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Created successfully']); 
            return redirect()->route('residentservent.index');
        }else{
           return response()->json([
                'message' => 'Resident Servent successfully registered',
                'residentservent' => $residentservent
            ], 200);
        }  
    }

    public function storeServentImage(Request $req, $id)
    {
        $loc = 'uploads/user';
        // dd("hello");
        $residentservent = ResidentServent::find($id);

        if($residentservent == '') {
            $message = 'No data Found';

        }else{
            $message = 'Resident Servent Image successfully Updated';
            $new_name = time().$req->image->getClientOriginalName();
            $file = $req->image->move($loc,$new_name);
            // return $new_name;

           $residentservent->image = $new_name;
           $residentservent->save();
       }

       return response()->json([
            'message' => $$message,
            'residentservent' => $residentservent
        ], 201);

    }

    public function show($id)
    {
        $residentservent = ResidentServent::with('servent_type')->find($id);
        $message = "No Data Found";

        if($residentservent){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'residentservent' => $residentservent
        ], 201);
    }

    public function edit($id)
    {
        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id >= 6){
            $residents = ResidentData::where('id',$user_detail->resident_id)->get();
        }
        
        $user = ResidentServent::with('servent_type')->find($id);
        $serventtypes = ServentType::get();
        
        if($this->webLogUser()){
             return view('residentmanagement.residentservent.create', compact('user','residents','serventtypes'));
        }else{
           return response()->json([
                'message' => $message,
                'residentservent' => $user
            ], 200);
        }  
    }

    public function update(ResidentServentRequest $request, $id)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $residentservent = Residentservent::find($id);
        if($residentservent == '') {
            $message = 'No data Found';

        }else{
            $message = 'Resident Servent Data successfully Updated';
            $residentservent = $residentservent->update([
                'name' => $request->name,
                'father_name' => $request->father_name,
                'cnic' => $request->cnic,
                'servent_type_id' => $request->servent_type_id,
                'gender' => $request->gender,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'resident_data_id' => $request->resident_data_id,
                'updatedby' => $user_detail->id,

            ]);
        }

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
            return redirect()->route('residentservent.index');
        }else{
           return response()->json([
                'message' => $message,
                'residentservent' => $residentservent
            ], 200);
        }      
    }

    public function destroy($id)
    {
        $residentservent = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residentservent = ResidentServent::find($id);
            $message = "No Data Found";
            if($residentservent != ''){
                $residentservent->delete();
                $message = "Servent Deleted Successfully";
            }
        }else{
          $message = "Id Must be Integet";  
        }

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
            return redirect()->route('residentservent.index');
        }else{
           return response()->json([
                'message' => $message,
                'residentservent' => $residentservent
            ], 201);
        }    
    }
}

