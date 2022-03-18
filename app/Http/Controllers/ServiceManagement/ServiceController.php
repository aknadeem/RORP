<?php

namespace App\Http\Controllers\ServiceManagement;

use Auth;
use Session;
use App\Models\Tax;
use App\Models\Service;
use App\Models\Society;
use App\Models\TaxDetail;
use App\Models\Department;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\SubDepartment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Response;

use Symfony\Component\HttpFoundation\Response;
use Gate;

class ServiceController extends Controller
{
    use HelperTrait;
    public function index()
    {   
        abort_if(Gate::denies('view-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail !='' && $user_detail->user_level_id == 1){
            $societies = Society::with('departments')->get(['id','code','name']);
            $services = Service::with('servicetype:id,name,is_service,society_id','subtype','society')->orderBy('id','DESC')->get();
        }elseif($user_detail !='' && $user_detail->user_level_id == 2){
            $admin_socs = $this->adminSocieties();
            $services = Service::whereIn('society_id', $admin_socs)->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
            $societies = Society::whereIn('id', $admin_socs)->with('departments')->get(['id','code','name']);
            // $departments = Department::whereHas('services')->whereIn('society_id', $admin_socs)->with('subdepartments')->get();
        }else if($user_detail->user_level_id == 3){
             $soc_id = $user_detail->society_id;
            $societies = Society::where('id', $soc_id)->with('departments')->get(['id','code','name']);
            $services = Service::where('society_id', $soc_id)->whereIn('type_id', $this->hodDepartments())->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 4){
            $soc_id = $user_detail->society_id;
            $societies = Society::where('id', $soc_id)->with('departments')->get(['id','code','name']);
            $services = Service::where('society_id', $soc_id)->whereIn('sub_type_id', $this->managerSubDepartments())->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 5){
            
            $soc_id = $user_detail->society_id;
            $societies = Society::where('id', $soc_id)->with('departments')->get(['id','code','name']);
           $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $services = Service::where('society_id', $soc_id)->whereIn('sub_type_id', $sp_visors_subdepartments)->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
            }else{
                $services = collect();    
            }
            
        }else{
            $soc_id = $user_detail->society_id;
            $societies = Society::where('id', $soc_id)->with('departments')->get(['id','code','name']);
            $services = Service::where('society_id', $soc_id)->with('servicetype','subtype','society')->orderBy('id','DESC')->get();
        }
        $message = "No Data Found";
        $counts = count($services);
        if($counts > 0){
            $message = "success";
        }
        if($this->webLogUser() !=''){
            return view('servicemanagement.services.index', compact('services','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'services' => $services
        ], 201);
    }

    public function serviceTypesAPi(){
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $soc_id = $user_detail->society_id;
        if($user_detail->user_level_id < 2){
            $service_types = Department::has('services')->whereHas('hod')->where('is_service', 1)->orderBy('id','DESC')->get();
            $service_subtypes = SubDepartment::wherehas('asstmanager')->wherehas('supervisors')->with('asstmanager','supervisors')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            $service_types = Department::whereIn('society_id', $this->adminSocieties())->has('services')->whereHas('hod')->where('is_service', 1)->orderBy('id','DESC')->get();
            $service_subtypes = SubDepartment::whereIn('society_id', $this->adminSocieties())->wherehas('asstmanager')->wherehas('supervisors')->with('asstmanager','supervisors')->orderBy('id','DESC')->get();
        }else{
            $service_types = Department::where('society_id', $soc_id)->has('services')->whereHas('hod')->where('is_service', 1)->orderBy('id','DESC')->get();
            $service_subtypes = SubDepartment::where('society_id', $soc_id)->wherehas('asstmanager')->wherehas('supervisors')->with('asstmanager','supervisors')->orderBy('id','DESC')->get();
        } 
        $message = "No Data Found";
        $types = count($service_types);
        $subtypes = count($service_subtypes);
        if($types > 0 OR $subtypes > 0){
            $message = "success";
        }
        return response()->json([
            'message' => $message,
            'types' => $types,
            'subtypes' => $subtypes,
            'service_types' => $service_types,
            'service_subtypes' => $service_subtypes
        ], 201);
    }

    public function create()
    {
        abort_if(Gate::denies('create-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();

        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
           $departments = Department::with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 2){
           $departments = Department::whereIn('society_id',$this->adminSocieties())->with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 3){
           $departments = Department::whereIn('id',$this->hodDepartments())->with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 4){
        //   $departments = Department::with('subdepartments')->where('is_service', 1)->get(); 
           $sub_ids = $this->managerSubDepartments();
           $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments')->get();
            
        }else if($user_detail->user_level_id == 5){
           $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments')->get();
            }else{
                $departments = collect();    
            }
        }
        $taxes = Tax::where('tax_type','services')->get();
        $service = new Service();
        return view('servicemanagement.services.create', compact('service','departments','taxes'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        // get user loged in apis or web 
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }

        $this->validate($request,[
            'title' => 'required|string',
            'billing_type' => 'required|string',
            'type_id' => 'required|integer',
            'sub_type_id' => 'required|integer',
        ]);
        $installation_fee = 0;
        if ($request->has(['installation_fee'])) {
            if ($request->installation_fee > 0) {
                $installation_fee = $request->installation_fee;
            }  
        }

        $service = Service::create([
            'type_id' => $request->type_id, 
            'sub_type_id' => $request->sub_type_id,
            'title' => $request->title, 
            'billing_type' => $request->billing_type,
            'installation_fee' => $installation_fee,
            'description' => $request->description, 
            'addedby' => $userId,
            'created_at' => $this->currentDateTime(),
        ]);
        if($request->tax_id !=''){
            if(count($request->tax_id) > 0){
                foreach ($request->tax_id as $key => $value) {
                    $service->tax_details()->attach($value, ['type' => 'service']);
                }
            }
        }
        
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']); 
        return redirect()->route('services.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $service = '';
        $is_int = is_numeric($id);
        if($is_int){
            $service = Service::find($id);
            $message = "No Data Found";

            if($service != ''){
                $message = "success";
            }
        }else{
            $message = "Id must be integer";
        }

        if($web_user !=''){
        }else{
            return response()->json([
                'message' => $message,
                'service' => $service
            ], 201);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
           $departments = Department::with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 2){
           $departments = Department::whereIn('society_id',$this->adminSocieties())->with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 3){
           $departments = Department::whereIn('id',$this->hodDepartments())->with('subdepartments')->where('is_service', 1)->get(); 
        }else if($user_detail->user_level_id == 4){
        //   $departments = Department::with('subdepartments')->where('is_service', 1)->get(); 
           $sub_ids = $this->managerSubDepartments();
           $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sub_ids){
                $q->whereIn('id', $sub_ids);
            })->with('subdepartments')->get();
            
        }else if($user_detail->user_level_id == 5){
            $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
            if(count($sp_visors_subdepartments) > 0){
                $departments = Department::where('is_service', 1)->where('society_id',$user_detail->society_id)->whereHas('subdepartments', function($q) use ($sp_visors_subdepartments){
                $q->whereIn('id', $sp_visors_subdepartments);
            })->with('subdepartments')->get();
            }else{
                $departments = collect();    
            }
        }
        $service = Service::with('tax_details')->find($id);
        $taxes = Tax::where('tax_type','services')->get();  
        // dd($societies->toArray());
        return view('servicemanagement.services.create', compact('service','departments','taxes'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $this->validate($request,[
            'title' => 'required|string',
            'billing_type' => 'required|string',
            'type_id' => 'required|integer',
            'sub_type_id' => 'required|integer',
        ]);
        $service_update = Service::with('tax_details')->findOrFail($id);
        $service_update->update([
            'title' => $request->title, 
            'description' => $request->description, 
            'billing_type' => $request->billing_type, 
            'type_id' => $request->type_id, 
            'sub_type_id' => $request->sub_type_id,
            'updatedby' => $this->webLogUser()->id,
        ]);
        if($request->tax_id !=''){
            if(count($request->tax_id) > 0){
                $service_update->tax_details()->detach($service_update->tax_details);
                foreach ($request->tax_id as $key => $value) {
                    $service_update->tax_details()->attach($value, ['type' => 'service']);
                }
            }
        }
        if($service_update){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
            $type = 'danger';
        }
        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('services.index');
        }else{
            return response()->json([
                'message' => $message,
                'dealsdiscount' => $deal
            ], 201);
        }
        
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $delete_service = DB::table('services')->delete($id);
        if($delete_service){
            $message = 'Data Deleted successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found Against this id';
            $type = 'danger';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->back();
    }
}

