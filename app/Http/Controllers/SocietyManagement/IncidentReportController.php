<?php

namespace App\Http\Controllers\SocietyManagement;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Society;
use App\Models\SocietySector;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;
use Validator;

class IncidentReportController extends Controller
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
        	$incidents = IncidentReport::orderBy('id', 'DESC')->with('society:id,code,name','sector:id,sector_name,society_id')->get();
        	$societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $incidents = IncidentReport::whereIn('society_id', $this->adminSocieties())->orderBy('id', 'DESC')->with('society:id,code,name','sector')->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }elseif($user_detail->user_level_id > 5){
            $incidents = IncidentReport::where('user_id', $user_detail->id)->orderBy('id', 'DESC')->with('society','sector')->get();
            
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        else{
        	$incidents = IncidentReport::where('society_id', $user_detail->society_id)->orderBy('id', 'DESC')->with('society','sector')->get();
        	
        	$societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }

        // dd($news->toArray());
        $message = 'No Data Found';
        $counts = $incidents->count();
        if($counts > 0){
            $message = 'yes';
        }

        if($this->webLogUser() !=''){
            return view('societymanagement.incidents.index', compact('incidents','societies'));
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'incidents' => $incidents
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
            $sectors = SocietySector::get(['id','sector_name','society_id']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            $sectors = SocietySector::whereIn('society_id', $this->adminSocieties())->get(['id','sector_name','society_id']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
            $sectors = SocietySector::where('society_id', $user_detail->society_id)->get(['id','sector_name','society_id']);
        }
        
        $incident = new IncidentReport();
        return view('societymanagement.incidents.create', compact('incident','societies','sectors'));
    }

    public function store(Request $request){     
        $user_detail = $this->apiLogUser();
        $message = 'no';
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required|string|min:2',
            'image' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $image_location = 'uploads/incidents';
        // dd($request->toArray());
        if($request->image){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }else{
           $image_name=''; 
        }
        $incident = '';
        if($user_detail !=''){
            $incident = IncidentReport::create([
                'title' => $request->title,
                'society_id' => $user_detail->society_id,
                'society_sector_id' => $user_detail->society_sector_id,
                'status' => $request->status,
                'image' => $image_name,
                'description' => $request->description,
                'addedby' => $user_detail->id,
                'user_id' => $user_detail->id,
            ]);
            if($incident){
                $message = 'yes';
            }
        
        }else{
            $message = 'Invalid user detail';
        }

        return response()->json([
            'message' => $message,
            'incident' => $incident
        ], 200);
    }

    public function show($id) {
        $incident = IncidentReport::with('society','sector')->find($id);
        $message = 'No Data Found';
        if($incident){
            $message = 'yes';
        }
        return response()->json([
            'message' => $message,
            'incident' => $incident
        ], 201);
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
            $sectors = SocietySector::get(['id','sector_name','society_id']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            $sectors = SocietySector::whereIn('society_id', $this->adminSocieties())->get(['id','sector_name','society_id']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
            $sectors = SocietySector::where('society_id', $user_detail->society_id)->get(['id','sector_name','society_id']);
        }
        
        $incident = IncidentReport::find($id);
        return view('societymanagement.incidents.create', compact('incident','societies','sectors'));
    }

    public function update(Request $request, $id) {
        $user_detail = $this->apiLogUser();
        $image_location = 'uploads/incidents';
        
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required|string|min:2',
            'image' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $incident = IncidentReport::find($id);
       	if($request->image){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }else{
           $image_name=$incident->image; 
        }
        if($incident !=''){
            $incident->title = $request->title;
            $incident->description = $request->description;
            if($incident->image != ''){
                $incident->image =$image_name;
            }
            if($incident->status != ''){
                $incident->status =$request->status;
            }
            $incident->user_id = $user_detail->id;
            $incident->updatedby = $user_detail->id;
            $incident->updated_at = $this->currentDateTime();
            $incident->save();
            $message = 'yes';
        }else{
            $message = 'no incident found against this id';
        }
        return response()->json([
            'message' => $message,
            'incident' => $incident
        ], 200);
    }

    public function destroy($id) {
    	$incident = IncidentReport::find($id);
        $incident->delete();
        if($incident){
            $message = 'yes';
        }else{
            $message = 'no';
        }
        
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
            return back();
        }else{
            return response()->json([
                'message' => $message,
                'incident' => $incident
            ], 200);
        } 
    }
}
