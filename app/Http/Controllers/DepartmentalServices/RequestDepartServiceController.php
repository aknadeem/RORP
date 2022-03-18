<?php

namespace App\Http\Controllers\DepartmentalServices;

use App\Models\User;
use App\Helpers\Constant;
use App\Models\Department;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\DepartmentHod;
use App\Models\SubDepartment;
use Illuminate\Validation\Rule;
use App\Models\DepartmentalService;
use App\Http\Controllers\Controller;
use App\Models\SubDepartmentManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\DepartmentalServiceRequest;
use App\Models\DepartmentalServiceRequestLog;
use App\Http\Resources\DepartmentalServiceResource;

class RequestDepartServiceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_level_id = $user_detail->user_level_id;
        $services = DepartmentalServiceRequest::
        when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('department_id', $this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('sub_department_id',$this->managerSubDepartments());
        })->when($user_level_id == 5, function ($qry) use ($user_detail){
            $qry->where('refer_to', $user_detail->id);
        })->when($user_level_id > 5, function ($qry) use ($user_detail){
            $qry->where('added_by', $user_detail->id);
        })
        ->with('department:id,name','subdepartment:id,name','service:id,service_title,service_charges,charges_type','RequestBy:id,name,user_level_id')->orderBy('id', 'DESC')->get();

        // $services = DepartmentalServiceRequest::with('department:id,name','subdepartment:id,name','service:id,service_title,service_charges,charges_type','RequestBy:id,name,user_level_id')->orderBy('id', 'DESC')->get();

        // dd($services->toArray());

        if($this->webLogUser()){
            return view('departmentalservices.servicerequests.index', compact('services'));
        }else{
            return response()->json([
                'success' => $success,
                'total' => $total,
            ], 201);
        } 
    }
    
    public function getSentRequests()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_id = $user_detail->id;

        $services = DepartmentalServiceRequest::where('addedby', $user_id)->with('department:id,name','subdepartment:id,name','service:id,service_title,service_charges,charges_type','RequestBy:id,name,user_level_id')->orderBy('id', 'DESC')->get();
        return view('departmentalservices.servicerequests.sent_requests', compact('services'));
    }

    public function getReceivedRequests()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user_id = $user_detail->id;
        $services = DepartmentalServiceRequest::where('refer_to', $user_id)
        /* ->when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('department_id', $this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('sub_department_id',$this->managerSubDepartments());
        })->when($user_level_id == 5, function ($qry) use ($user_detail){
            $qry->where('refer_to', $user_detail->id);
        })->when($user_level_id > 5, function ($qry) use ($user_detail){
            $qry->where('added_by', $user_detail->id);
        }) */
        ->with('department:id,name','subdepartment:id,name','service:id,service_title,service_charges,charges_type','RequestBy:id,name,user_level_id')->orderBy('id', 'DESC')->get();

        // $services = DepartmentalServiceRequest::where('refer_to', $id)->with('department:id,name','subdepartment:id,name','service:id,service_title,service_charges,charges_type','RequestBy:id,name,user_level_id')->orderBy('id', 'DESC')->get();

        return view('departmentalservices.servicerequests.received_requests', compact('services'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'department_id' => 'required|integer',
            'sub_department_id' => 'required|integer',
            'departmental_service_id' => 'bail|required|integer',
            'description' => 'bail|nullable',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->toArray(),
                'success' => 'no',
            ], 201);
        }

        $userId = \Auth::user()->id;
        $userName = \Auth::user()->name;

        $dep_manager = SubDepartmentManager::where('sub_department_id',$request->sub_department_id)->first();

        $store_request = DepartmentalServiceRequest::create([
            'department_id' => $request->department_id,
            'sub_department_id' => $request->sub_department_id,
            'departmental_service_id' => $request->departmental_service_id,
            'request_date' => today()->format('Y-m-d'),
            'request_status' => Constant::REQUEST_STATUS['Open'],
            'description' => $request->description,
            'refer_to' => $dep_manager->manager_id,
            'refer_by' => $userId,
            'addedby' => $userId,
        ]);

        if($store_request){
            $log = DepartmentalServiceRequestLog::create([
                'service_id' => $request->departmental_service_id,
                'request_id' => $store_request->id,
                'request_date' => today()->format('Y-m-d'),
                'log_type' => 'External',
                'request_status' => Constant::REQUEST_STATUS['Open'],
                'remarks' => 'New Departmental Service Request',
                'refer_to' => $dep_manager->manager_id,
                'refer_by' => $userId,
                'addedby' => $userId,
            ]);
            
            if($dep_manager !=''){
                $details = [
                    'title' => 'New Departmental Service Request',
                    'by' => $userName,
                    'service_id' => $request->departmental_service_id,
                    'request_id' => $store_request->id,
                ];
                $dep_manager->user->notify(new \App\Notifications\DepartmentalServiceNotification($details));
            }

            $success = 'yes';
            $message = 'New Service Request Added successfully!';
        }else{
            $success = 'no';
            $message = 'Data not saved something went wrong!';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'department_id' => 'bail|required|integer',
            'sub_department_id' => 'bail|required|integer',
            'service_id' => 'bail|required|integer',
            'description' => 'bail|nullable',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->toArray(),
                'success' => 'no',
            ], 201);
        }

        $success = 'no';
        $message = 'Data not saved something went wrong!';

        $service = DepartmentalServiceRequest::find($id);
        $userId = \Auth::user()->id;
        $update = $service->update([
            'department_id' => $request->department_id,
            'sub_department_id' => $request->sub_department_id,
            'service_id' => $request->service_title,
            'description' => $request->description,
            'updatedby' => $userId,
        ]);

        if($update){

            $log = DepartmentalServiceRequestLog::create([
                'service_id' => $request->service_id,
                'request_date' => today()->format('Y-m-d'),
                'service_id' => $request->service_id,
                'request_status' => $update->request_status,
                'remarks' => 'Departmental Service Request Updated',
                'addedby' => $userId,
            ]);

            $success = 'yes';
            $message = 'A Service Request Updated successfully!';
        }else{
            $success = 'no';
            $message = 'Data not saved something went wrong!';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ], 200);
    }

    public function storeinternalLogs(Request $request, $id)
    {

        // dd($request->toArray());

        $validator = Validator::make($request->all(),[
            'departmental_service_id' => 'bail|required|integer',
            'internal_comment' => 'bail|string|required',
        ]);

        $service_request = DepartmentalServiceRequest::findOrfail($id);
        $userId = \Auth::user()->id;

        $log = DepartmentalServiceRequestLog::create([
            'service_id' => $request->departmental_service_id,
            'request_id' => $id,
            'request_date' => today()->format('Y-m-d'),
            'log_type' => 'Internal',
            'request_status' => $service_request->request_status,
            'remarks' => $request->internal_comment,
            'refer_to' => $service_request->refer_to,
            'refer_by' => $service_request->refer_by,
            'addedby' => $userId,
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Comment Added successfully!']);
        return back();
    }

    public function show($id)
    { 
        $detail = DepartmentalServiceRequest::with('service','logs','logs.user:id,name,user_level_id','referto:id,name,user_level_id')->findOrFail($id);
        return view('departmentalservices.servicerequests.detail', compact('detail'));
    }

    public function UpdateRequestStatus(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'request_id' => 'bail|required|integer',
            'request_status' => ['bail','required', Rule::in(Constant::REQUEST_STATUS_RULE)],
            'comments' => 'bail|nullable',
        ]);

        if($validator->fails()){
            // return response()->json([
            //     'error' => $validator->errors()->toArray(),
            //     'success' => 'no',
            // ], 201);

            Session::flash('notify', ['type'=> 'danger', 'message' => $validator->errors()->toArray()]);
            return redirect()->route('request_depart_service.index');   
        }

        $userId = \Auth::user()->id;
        $userName = \Auth::user()->name;

        $service_request = DepartmentalServiceRequest::find($request->request_id);

        
        // $service_notifyTo = DepartmentHod::where('department_id', $service_request->department_id)->with('hod:id,name,email,user_level_id')->first();
        // dd($service_notifyTo->hod->destroytoArray());
        $service_request->update([
            'request_status' => $request->request_status,
            'updatedby' => $userId,
        ]);

        if($service_request){
            $log = DepartmentalServiceRequestLog::create([
                'request_id' => $request->request_id,
                'service_id' => $request->service_id,
                'request_status' => $request->request_status,
                'remarks' => $request->comments,
                'addedby' => $userId,
            ]);
            $service_notifyTo = null;
            if($request->request_status == Constant::REQUEST_STATUS['Approved']){
                $service_notifyTo = User::where('id',$service_request->addedby)->first(['id','name','email']);
            }else if($request->request_status == Constant::REQUEST_STATUS['Completed']){

                $service_notifyTo = DepartmentHod::where('department_id', $service_request->department_id)->with('hod:id,name,email,user_level_id')->first();

                $service_notifyTo = $service_notifyTo->hod;

            } else if ($request->request_status == Constant::REQUEST_STATUS['InCorrect']){
                $service_notifyTo = SubDepartmentManager::where('sub_department_id',$service_request->sub_department_id)->with('user')->first();

                $service_notifyTo = $service_notifyTo->user;
            }

            if($service_notifyTo != null){
                $details = [
                    'title' => $request->comments,
                    'by' => $userName,
                    'service_id' => $request->service_id,
                    'request_id' => $request->request_id,
                ];
                $service_notifyTo->notify(new \App\Notifications\DepartmentalServiceNotification($details));
            }

            $type = 'success';
            $message = 'The Status updated successfully!';
        }else{
            $type = 'danger';
            $message = 'Data not Updated something went wrong!';
        }

        Session::flash('notify', ['type'=> $type, 'message' => $message]);
        return redirect()->route('request_depart_service.index');

        // return response()->json([
        //     'success' => $success,
        //     'message' => $message,
        // ], 200);
    }

    public function destroy($id)
    {
        $serviceRequest = DepartmentalServiceRequest::findOrFail($id);
        $serviceRequest->delete();
        Session::flash('notify', ['type'=>'success', 'message' => 'A service request Deleted successfully']);
        return redirect()->route('request_depart_service.index');
    }
}

