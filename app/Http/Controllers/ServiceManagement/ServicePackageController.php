<?php

namespace App\Http\Controllers\ServiceManagement;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Tax;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
// use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ServicePackageController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 1){
            $servicepackages = ServicePackage::with('service','tax_details')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $servicepackages = ServicePackage::whereIn('society_id', $admin_soctities)->with('service','tax_details')->orderBy('id','DESC')->get();
            
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
           $servicepackages = ServicePackage::with('service')->whereHas('service', function($q) use ($hod_deps){
                $q->whereIn('type_id', $hod_deps);
            })->with('tax_details')->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $servicepackages = ServicePackage::with('service')->whereHas('service', function($q) use ($sub_ids){
                $q->whereIn('sub_type_id', $sub_ids);
            })->with('tax_details')->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $servicepackages = ServicePackage::with('service')->whereHas('service', function($q) use ($supervisors_subdeps){
                $q->whereIn('sub_type_id', $supervisors_subdeps);
            })->with('tax_details')->get();
        }else{
           $servicepackages = ServicePackage::where('society_id', $user_detail->society_id)->with('service','tax_details')->orderBy('id','DESC')->get();
        }
        
        $message = "No Data Found";
        $counts = $servicepackages->count();
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.service_packages.index', compact('servicepackages'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'servicepackages' => $servicepackages
        ], 201);
    }

    public function create()
    {
        abort_if(Gate::denies('create-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $services = Service::where('billing_type', '!=', 'no_billing')->get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('society_id', $admin_soctities)->get();
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('type_id', $hod_deps)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('sub_type_id', $sub_ids)->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('sub_type_id', $supervisors_subdeps)->get();
        }else{
            $services = Service::where('billing_type', '!=', 'no_billing')->where('society_id', $user_detail->society_id)->get();
        }
        
        $servicepackage = new ServicePackage();
        $taxes = Tax::where('tax_type','packages')->get();
        
        return view('servicemanagement.service_packages.create', compact('servicepackage','services','taxes'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $now = $this->currentDate(); 
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
            'service_id' => 'required|integer',
            'title' => 'required|string',
            'price' => 'required|numeric',
            'detail' => 'nullable',
        ]);

        $package = ServicePackage::create([
            'service_id' => $request->service_id,
            'title' => $request->title, 
            'price' => $request->price, 
            'detail' => $request->detail, 
            'addedby' => $this->webLogUser()->id,
        ]);

        if($request->tax_id!=''){
            foreach ($request->tax_id as $key => $value) {
                $package->tax_details()->attach($value, ['type' => 'package']);
            }
        }
        if($package){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
            $tyoe = 'danger';
        }

        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('servicepackages.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('vupdateiew-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $servicepackage = ServicePackage::with('tax_details')->findOrFail($id);
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $services = Service::where('billing_type', '!=', 'no_billing')->get();
        }else if($user_detail->user_level_id == 2){
            $admin_soctities = $this->adminSocieties();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('society_id', $admin_soctities)->get();
        }else if($user_detail->user_level_id == 3){
            $hod_deps = $this->hodDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('type_id', $hod_deps)->get();
        }else if($user_detail->user_level_id == 4){
            $sub_ids = $this->managerSubDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('sub_type_id', $sub_ids)->get();
        }else if($user_detail->user_level_id == 5){
            $supervisors_subdeps = $this->supervisorDepartments();
            $services = Service::where('billing_type', '!=', 'no_billing')->whereIn('sub_type_id', $supervisors_subdeps)->get();
        }else{
            $services = Service::where('billing_type', '!=', 'no_billing')->where('society_id', $user_detail->society_id)->get();
        }
        $taxes = Tax::where('tax_type','packages')->get();
        return view('servicemanagement.service_packages.create', compact('servicepackage','services','taxes'));
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $servicepackage = '';
        $is_int = is_numeric($id); // checks id is integer or not

        if($is_int){
            $servicepackage = ServicePackage::with('service')->find($id);
            $message = "No Data Found";

            if($servicepackage != ''){
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }

        if($web_user !=''){
            // return back to detail page
        }else{
            return response()->json([
                'message' => $message,
                'servicepackage' => $servicepackage
            ], 201);
        }
        
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $this->validate($request,[
            'service_id' => 'required|integer',
            'title' => 'required|string',
            'price' => 'required|numeric',
            'detail' => 'nullable   ',
        ]);
        $package_update = ServicePackage::with('tax_details')->findOrFail($id);
        $package_update->update([
            'service_id' => $request->service_id,
            'title' => $request->title, 
            'price' => $request->price, 
            'detail' => $request->detail, 
            'updatedby' => $this->webLogUser()->id,
        ]);
        if($request->tax_id !=''){
            $package_update->tax_details()->detach($service_device_update->tax_details);
            foreach ($request->tax_id as $key => $value) {
                $package_update->tax_details()->attach($value, ['type' => 'package']);
            }
        }
        if($package_update){
           $message = 'Data Updated Successfully';
            $type = 'successs'; 
        }else{
            $message = 'Data Not Updated';
            $type = 'danger';
        }

        if($this->webLogUser() !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('servicepackages.index');
        }else{
            return response()->json([
                'message' => $message,
                'service_update' => $service_update
            ], 201);
        }
        
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-service-packages'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $deal = '';
        $is_int = is_numeric($id);
        if($is_int){
            $servicepackage = ServicePackage::find($id);
            $message = "No Data Found";
            if($deal != ''){
                $deal->delete();
                $message = "Data Deleted Successfully";
            }
        }else{
          $message = "Id Must be Integet";  
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
            return back();
        }else{
            return response()->json([
                'message' => $message,
                'dealsdiscount' => $deal
            ], 201);
        }
    }
}