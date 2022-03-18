<?php

namespace App\Http\Controllers\SocietyManagement;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\SocietySos;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;
use Validator;

class SocietySosController extends Controller
{
    use HelperTrait;
    public function index(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
        	$society_soses = SocietySos::orderBy('id', 'DESC')->with('society:id,code,name,slug')->get();
        	
        	$societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $society_soses = SocietySos::whereIn('society_id', $this->adminSocieties())->orderBy('id', 'DESC')->with('society:id,code,name,slug')->get();
            
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            
        }elseif($user_detail->user_level_id > 5){
            $society_soses = SocietySos::where('user_id', $user_detail->id)->orderBy('id', 'DESC')->with('society:id,code,name,slug')->get();
            
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        else{
        	$society_soses = SocietySos::where('society_id', $user_detail->society_id)->orderBy('id', 'DESC')->with('society:id,code,name,slug')->get();
        	$societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }

        // dd($news->toArray());
        $message = 'No Data Found';
        $counts = $society_soses->count();
        if($counts > 0){
            $message = 'yes';
        }

        if($this->webLogUser() !=''){
            return view('societymanagement.sos.index', compact('society_soses','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'society_soses' => $society_soses
            ], 200);
        }
    }
    public function create(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        
        $sos = new SocietySos();
        return view('societymanagement.sos.create', compact('sos','societies'));
    }

    public function store(Request $request){  
        $user_detail = $this->apiLogUser();
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'user_id' => 'nullable',
            'description' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $society_sos = DB::table('society_sos')->insertGetId([
            'society_id' => $user_detail->society_id,
            'lat' => $request->lat,
            'long' => $request->lat,
            'user_id' => $user_detail->id,
            'description' => $request->description,
            'addedby' => $user_detail->id,
        ]);
        
        if($society_sos > 0){
            $message='yes';
        }else{
            $message='no';
        }
        return response()->json([
            'message' => 'data created',
            'society_sos_id' => $society_sos
        ], 200);
    }

    public function show($id) {
        $society_sos = SocietySos::with('society')->find($id);
        $message = 'No Data Found';
        if($society_sos){
            $message = 'yes';
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){

           // return view('societymanagement.incident.index', compact('news'));
       }else{
            return response()->json([
                'message' => $message,
                'society_sos' => $society_sos
            ], 201);
       }
    }

    public function edit($id) {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        
        $sos = SocietySos::with('society:id,code,name')->find($id);
        return view('societymanagement.sos.create', compact('sos','societies'));
    }

    public function update(Request $request, $id) {
        $user_detail = $this->apiLogUser();
        
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'description' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if($user_detail !=''){
            $society_sos = DB::table('society_sos')->where('id', $id)->update([
                'society_id' => $user_detail->society_id,
                'lat' => $request->lat,
                'long' => $request->lat,
                'user_id' => $user_detail->id,
                'description' => $request->description,
                'updatedby' => $user_detail->id,
            ]);
            
            if($society_sos > 0){
                $message='yes';
            }else{
                $message='no';
            }
        }else{
            $message='yes';
            $society_sos='';
        }
        
        return response()->json([
            'message' => $message,
            'society_sos' => $society_sos
        ], 200);
    }

    public function destroy($id) {
    	$society_sos = SocietySos::find($id);
        $society_sos->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }
}