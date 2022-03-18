<?php

namespace App\Http\Controllers\ServiceManagement;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceDevice;
use App\Models\Tax;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
// use Illuminate\Support\Facades\Response;

use Symfony\Component\HttpFoundation\Response;
use Gate;

class ServiceDeviceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $service_devices = ServiceDevice::with('tax_details','service')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $service_devices = ServiceDevice::whereIn('society_id', $admin_soctities)->with('tax_details','service')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
            $service_devices = ServiceDevice::with('service')->whereHas('service', function($q) use ($hod_deps){
                $q->whereIn('type_id', $hod_deps);
            })->with('tax_details')->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $service_devices = ServiceDevice::with('service')->whereHas('service', function($q) use ($sub_ids){
                $q->whereIn('sub_type_id', $sub_ids);
            })->with('tax_details')->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $service_devices = ServiceDevice::with('service')->whereHas('service', function($q) use ($supervisors_subdeps){
                $q->whereIn('sub_type_id', $supervisors_subdeps);
            })->with('tax_details')->get();
        }else{
            $service_devices = ServiceDevice::where('society_id', $user_detail->society_id)->with('tax_details','service')->orderBy('id','DESC')->get();
        }
        $message = "No Data Found";
        $counts = count($service_devices);
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.service_devices.index', compact('service_devices'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_devices' => $service_devices
        ], 201);
    }

    public function create()
    {
        abort_if(Gate::denies('create-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $services = Service::get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $services = Service::whereIn('society_id', $admin_soctities)->get();
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
            $services = Service::whereIn('type_id', $hod_deps)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = Service::whereIn('sub_type_id', $sub_ids)->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $services = Service::whereIn('sub_type_id', $supervisors_subdeps)->get();
        }else{
            $services = Service::where('society_id', $user_detail->society_id)->get();
        }
        
        $service_device = new ServiceDevice();
        $taxes = Tax::where('tax_type','devices')->get();
        return view('servicemanagement.service_devices.create', compact('service_device','services','taxes'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $this->validate($request,[
            'device_title' => 'required|string',
            'device_price' => 'required',
            'service_id' => 'required|integer',
            'device_status' => 'required|string',
        ]);
        // dd($request->toArray());
        $service_device = ServiceDevice::create([
            'device_title' => $request->device_title, 
            'device_price' => $request->device_price,
            'service_id' => $request->service_id, 
            'device_status' => $request->device_status, 
        ]);

        if($request->tax_id !=''){
            foreach ($request->tax_id as $key => $value) {
                $service_device->tax_details()->attach($value, ['type' => 'device']);
            }
        }

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']); 
        return redirect()->route('servicedevices.index');
    }

    public function show($id)
    {
    	dd($id);
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $services = Service::get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $services = Service::whereIn('society_id', $admin_soctities)->get();
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
            $services = Service::whereIn('type_id', $hod_deps)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = Service::whereIn('sub_type_id', $sub_ids)->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $services = Service::whereIn('sub_type_id', $supervisors_subdeps)->get();
        }else{
            $services = Service::where('society_id', $user_detail->society_id)->get();
        }
        
        $service_device = ServiceDevice::with('tax_details')->find($id);
        $taxes = Tax::where('tax_type','devices')->get();
        return view('servicemanagement.service_devices.create', compact('service_device','services','taxes'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $this->validate($request,[
            'device_title' => 'required|string',
            'device_price' => 'required',
            'service_id' => 'required|integer',
            'device_status' => 'required|string',
        ]);

        $service_device_update = ServiceDevice::with('tax_details')->findOrFail($id);
        $service_device_update->update([
            'device_title' => $request->device_title, 
            'device_price' => $request->device_price,
            'service_id' => $request->service_id,
            'device_status' => $request->device_status,
        ]);

        if($request->tax_id !=''){
            $service_device_update->tax_details()->detach($service_device_update->tax_details);
            foreach ($request->tax_id as $key => $value) {
                $service_device_update->tax_details()->attach($value, ['type' => 'device']);
            }
        }

        if($service_device_update){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
            $type = 'danger';
        }

        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('servicedevices.index');
        }else{
            return response()->json([
                'message' => $message,
                'service_device_update' => $service_device_update
            ], 201);
        }
        
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-service-devices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $delete_service_device = DB::table('service_devices')->delete($id);
        if($delete_service_device){
            $message = 'Data Deleted successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found Against this id';
            $type = 'danger';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return back();
    }
}