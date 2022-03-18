<?php

namespace App\Http\Controllers\ResidentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentFamilyRequest;
use App\Models\ResidentData;
use App\Models\ResidentFamily;
use App\Models\User;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class ResidentFamilyController extends Controller
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
            $societies = Society::get(['id','code','name']);
            $resfamilies = ResidentFamily::with('residentdata:id,name,society_id','society:id,code,name')->get();
        }elseif($user_detail->user_level_id == 2){
            $admin_soc = $this->adminSocieties();
            $societies = Society::whereIn('id',$admin_soc)->get(['id','code','name']);
            $resfamilies = ResidentFamily::whereIn('society_id', $admin_soc)->with('society:id,code,name','residentdata:id,name,society_id')->get();
            
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $societies = Society::where('id',$user_detail->society_id)->get(['id','code','name']);
            $resfamilies = ResidentFamily::with('residentdata:id,name,society_id','society:id,code,name')->where('resident_data_id', $user_detail->resident_id)->get();
        }else{
            $soc_id = $user_detail->society_id;
            $societies = [];
            $resfamilies = [];
        }
        $message = "No Data Found";
        $counts = count($resfamilies);
        if($counts > 0){
            $message = "Success";
        }

        if($this->webLogUser()){
            return view('residentmanagement.residentfamily.index', compact('resfamilies','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'residentfamilies' => $resfamilies
            ], 201);
        }   
    }

    public function myfamily($id)
    {
        $is_int = is_numeric($id);
        if($is_int){
            $myfamily = ResidentFamily::where('resident_data_id',$id)->get();
            $message = "No Data Found";
            
            if($myfamily ==''){
                $message = "No Data Found";
            }
    
            $counts = $myfamily->count();
    
            if($counts > 0){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        
        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            return view('residentmanagement.residentdata.index', compact('residents'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'myfamily' => $myfamily
            ], 201);
        }
    }

    public function create()
    {
        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();

        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->where('id', $user_detail->resident_id)->get();
        }else{
            $residents = [];
        }
        $user = new ResidentFamily();
        return view('residentmanagement.residentfamily.create', compact('user','residents'));
    }

    public function store(ResidentFamilyRequest $request)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $residentfamliy = ResidentFamily::create([
            'name' => $request->name,
            'guardian_name' => $request->guardian_name,
            'cnic' => $request->cnic,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'relation' => $request->relation,
            'resident_data_id' => $request->resident_data_id,
            'gender' => $request->gender,
            'addedby' => $user_detail->id,
        ]);

        if ($this->webLogUser()) {
            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            return redirect()->route('residentfamily.index');
        }else{
            return response()->json([
                'message' => 'Resident Famliy successfully registered',
                'residentfamliy' => $residentfamliy
            ], 201);   
        }  
    }

    public function storeFamilyImage(Request $request, $id)
    {
        $loc = 'uploads/user';
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);
        $residentfamily = ResidentFamily::find($id);

        if($residentfamily == ''){
            $message = "No Data Found";
        }else{
            $message = "Resident Image successfully Updated";
            $new_name = time().$request->image->getClientOriginalName();
            $file = $request->image->move($loc,$new_name);
           $residentfamily->image = $new_name;
           $residentfamily->save();
        }

       return response()->json([
            'message' => $message,
            'residentfamily' => $residentfamily
        ], 201);

    }

    public function show($id)
    {
        $residentfamliy = ResidentFamily::find($id);
        $message = "No Data Found";
        if($residentfamliy){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'residentfamliy' => $residentfamliy
        ], 201);
    }

    public function edit($id)
    {
        $user = ResidentFamily::find($id);
        $residents = '';
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->get();
        }elseif($user_detail->user_level_id == 2){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->whereIn('society_id', $this->adminSocieties())->get();
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $residents = ResidentData::where('pin_verified',1)->where('is_active',1)->where('id', $user_detail->resident_id)->get();
        }else{
            $residents = [];
        }

        return view('residentmanagement.residentfamily.create', compact('user','residents'));
    }

    public function update(ResidentFamilyRequest $request, $id)
    {

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $residentfamliy = ResidentFamily::find($id);

        if($residentfamliy == '') {
            $message = 'No Resident Found';

        }else{
            $message = 'Resident Famliy successfully Updated';
            $updateresidentfamliy = $residentfamliy->update([
                'name' => $request->name,
                'guardian_name' => $request->guardian_name,
                'cnic' => $request->cnic,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'relation' => $request->relation,
                'gender' => $request->gender,
                'resident_data_id' => $request->resident_data_id,
                'updatedby' => $user_detail->id,
            ]);
        }

        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Created successfully']); 
            return redirect()->route('residentfamily.index');
        }else{
            return response()->json([
                'message' => $message,
                'residentfamliy' => $residentfamliy
            ], 200);
        } 
    }


    public function destroy($id)
    {
        $residentfamliy = ResidentFamily::find($id);
        $message = "No Data Found";
        if($residentfamliy !=''){
            $residentfamliy->delete();
            $message = "Resident Family Deleted Successfully";
        }
        if($this->webLogUser()){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']); 
            return redirect()->route('residentfamily.index');
        }else{
            return response()->json([
                'message' => $message,
                'residentfamliy' => $residentfamliy
            ], 200); 
        }               
    }
}   

