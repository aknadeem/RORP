<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentHandyMenRequest;
use App\Models\HandyServiceType;
use App\Models\ResidentData;
use App\Models\ResidentHandyMan;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class ResidentHandyMenController extends Controller
{
    use HelperTrait;

    public function handyServiceTypes()
    {
        $handyServiceTypes = HandyServiceType::get();
        $message = "No Data Found";
        $counts = $handyServiceTypes->count();

        if($counts > 0){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'handyServiceTypes' => $handyServiceTypes
        ], 201);
    }

    public function myhandymen($id)
    {
        $is_int = is_numeric($id);
        if($is_int){
            $myhandymen = ResidentHandyMan::where('resident_data_id',$id)->get();
            $message = "No Data Found";
            
            if($myhandymen ==''){
                $message = "No Data Found";
            }
    
            $counts = $myhandymen->count();
    
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
                'myhandymen' => $myhandymen
            ], 201);
        }
    }
    
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id < 2){
            $societies = Society::get(['id','code','name']);
            $residenthandymen = ResidentHandyMan::with('residentdata:id,name,society_id','handy_type','society:id,code,name')->get();
        }elseif($user_detail->user_level_id == 2){
            $admin_soc = $this->adminSocieties();
            $societies = Society::whereIn('id',$admin_soc)->get(['id','code','name']);
            $residenthandymen = ResidentHandyMan::whereIn('society_id',$admin_soc)->with('handy_type','society:id,code,name','residentdata:id,name,society_id')->get();

        }elseif($user_detail->user_level_id >= 6){
            $societies = Society::where('id',$user_detail->resident_id)->get(['id','code','name']);
            $residenthandymen = ResidentHandyMan::where('resident_data_id', $user_detail->resident_id)->with('handy_type','society:id,code,name','residentdata:id,name,society_id')->get();
        }else{
            $societies = [];
            $residenthandymen = [];
        }

        $message = "No Data Found";
        $counts = count($residenthandymen);
        if($counts > 0){
            $message = "Success";
        }
        if($this->webLogUser()){
            return view('residentmanagement.residenthandymen.index', compact('residenthandymen','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residenthandymen' => $residenthandymen
            ], 201);
        }  
    }

    public function create()
    {
        $user = new ResidentHandyMan();
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
        }else{
            $residents = [];
        }

        $handy_service_types = HandyServiceType::get();
        return view('residentmanagement.residenthandymen.create', compact('user','residents','handy_service_types'));
    }

    // public function store(Request $request)
    public function store(ResidentHandyMenRequest $request)
    {   
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        // dd($request->toArray());

        $residenthandyman = ResidentHandyMan::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'cnic' => $request->cnic,
            'type_id' => $request->type_id,
            'gender' => $request->gender,
            'mobile_number' => $request->mobile_number,
            'resident_data_id' => $request->resident_data_id,
            'addedby' => $user_detail->id,
        ]);

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Created successfully']); 
            return redirect()->route('residenthandymen.index');
        }else{
            return response()->json([
                'message' => 'Resident Handy Man successfully registered',
                'residenthandyman' => $residenthandyman
            ], 200);
        }  
    }

    public function storeHandyImage(Request $req, $id)
    {
        $loc = 'uploads/user';
        // dd("hello");
        $residenthandymen = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residenthandymen = ResidentHandyMan::find($id);
            if($residenthandymen == '') {
                $message = 'No data Found';

            }else{
                $message = 'Resident Handy Man Image successfully Updated';
                $new_name = time().$req->image->getClientOriginalName();
                $file = $req->image->move($loc,$new_name);
                // return $new_name;
               $residenthandymen->image = $new_name;
               $residenthandymen->save();
            }
        }else{
            $message = 'Id Must be Integer';
        }
        return response()->json([
            'message' => $message ,
            'residenthandymen' => $residenthandymen
        ], 201);
    }

    public function show($id)
    {
        $residenthandyman = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residenthandyman = ResidentHandyMan::with('handy_type')->find($id);
            $message = "No Data Found";

            if($residenthandyman){
                $message = "Success";
            }
        }else{
            $message = "Id Must be Integer";
        }
        return response()->json([
            'message' => $message,
            'residenthandyman' => $residenthandyman
        ], 201);
    }

    public function edit($id)
    {
        $user = ResidentHandyMan::with('handy_type')->find($id);
        $handy_service_types = HandyServiceType::get();
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
        }else{
            $residents = [];
        }
        return view('residentmanagement.residenthandymen.create', compact('user','residents','handy_service_types'));
    }

    public function update(ResidentHandyMenRequest $request, $id)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $residenthandyman = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residenthandyman = ResidentHandyMan::find($id);

            if($residenthandyman == '') {
                $message = 'No data Found';
            }else{
                $message = 'Resident Handy Man successfully Updated';
                $residenthandyman = $residenthandyman->update([
                    'name' => $request->name,
                    'father_name' => $request->father_name,
                    'cnic' => $request->cnic,
                    'type' => $request->type,
                    'gender' => $request->gender,
                    'mobile_number' => $request->mobile_number,
                    'email' => $request->email,
                    'resident_data_id' => $request->resident_data_id,
                    'updatedby' => $user_detail->id,
                ]);
            }
        }else{
            $message = 'Id Must be Integer';
        }
        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
            return redirect()->route('residenthandymen.index');
        }else{
           return response()->json([
                'message' => $message,
                'residenthandyman' => $residenthandyman
            ], 200);
        } 
    }


    public function destroy($id)
    {
        // dd($id);
        $residenthandyman = '';
        $is_int = is_numeric($id);
        if($is_int){
            $residenthandyman = ResidentHandyMan::find($id);
            $message = "No Data Found";
            if($residenthandyman !=''){
                $residenthandyman->delete();
                $message = "Resident Handy Man Deleted Successfully";
            }
        }else{
            $message = "Id Must be Integer";
        }
        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
            return redirect()->route('residenthandymen.index');
        }else{
           return response()->json([
                'message' => $message,
                'residenthandyman' => $residenthandyman
            ], 200);
        }   
    }
}

