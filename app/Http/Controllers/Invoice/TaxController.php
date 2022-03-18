<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Service;
use App\Models\ServiceSubType;
use App\Models\ServiceType;
use App\Models\SubDepartment;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;

class TaxController extends Controller
{
    use HelperTrait;
    public function index()
    {
        $services = Service::with('servicetype:id,name,is_service','subtype')->orderBy('id','DESC')->get();
        // dd($services->toArray());
        $message = "No Data Found";
        $counts = count($services);
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.services.index', compact('services'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'services' => $services
        ], 201);
    }

    public function create()
    {
        $service = new Service();
        // $service_types = ServiceType::with('subtypes')->orderBy('id','DESC')->get();
        // $subtypes = ServiceSubType::get();
        
        $departments = Department::has('hod')->with('hod','subdepartments')->where('is_service', 1)->orderBy('id','DESC')->get();
        return view('servicemanagement.services.create', compact('service','departments'));
    }

    public function store(Request $request)
    {
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
            'tax_title' => 'required|string',
            'tax_percentage' => 'required|integer',
            'tax_type' => 'string|nullable',
        ]);

        if($request->tax_percentage < 1 && $request->tax_percentage > 100){
        	$message = "Tax Can't Greater Then 100 Or Less Then 1";
        	$type = "warning";
        }else{
        	$service_id = DB::table('taxes')->insert([
	            'tax_title' => $request->tax_title, 
	            'tax_percentage' => $request->tax_percentage,
	            'tax_type' => $request->tax_type, 
	            'addedby' => $userId,
	            'created_at' => $this->currentDateTime(),
	        ]);
	        $message = "Data Created Successfully!";
        	$type = "success";
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]); 
        return redirect()->back();
        // return redirect()->route('services.index');
    }

    public function show($id)
    {
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
        $service = Service::find($id);
        $service_types = ServiceType::get();
        $subtypes = ServiceSubType::get();
        // dd($societies->toArray());
        $departments = Department::with('subdepartments')->where('is_service', 1)->get();
        return view('servicemanagement.services.create', compact('service','service_types','subtypes','departments'));
    }

    public function update(Request $request, $id)
    {
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

        $service_update = DB::table('services')->where('id', $id)
          ->update([
            'title' => $request->title, 
            'description' => $request->description, 
            'billing_type' => $request->billing_type, 
            'type_id' => $request->type_id, 
            'sub_type_id' => $request->sub_type_id,
            'updatedby' => $userId,
            'created_at' => $this->currentDateTime(),
            'updated_at' => $this->currentDateTime(),
        ]);

        if($service_update){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
            $type = 'danger';
        }

        if($web_user !=''){
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



