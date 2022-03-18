<?php

namespace App\Http\Controllers\DepartmentalServices;

use App\Helpers\Constant;
use App\Models\Department;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\SubDepartment;
use Illuminate\Validation\Rule;
use App\Models\DepartmentalService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DepartmentalServiceResource;
use App\Models\Validators\DepartmentalServiceValidator;

class DepartmentalServicesController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        // $sub_department_ids = $this->managerSubDepartments();
        $user_level_id = $user_detail->user_level_id;

        $departments = Department::when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('id', $this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereHas('subdepartments', function($q){
                $q->whereIn('id', $this->managerSubDepartments());
            });
        })->with('society:id,code,name,slug','subdepartments:id,name,department_id','subdepartments.department:id,name')->orderBy('id','DESC')->get(['id','name','slug','society_id']);

        // $services = DepartmentalService::with('department:id,name','subdepartment:id,name')->orderBy('id', 'DESC')->get();

        $services = DepartmentalService::with('department:id,name','subdepartment:id,name')->orderBy('id', 'DESC')->get();

        // dd($services->toArray());
        
        if($this->webLogUser()){
            return view('departmentalservices.services.index', compact('services','departments'));
        }else{
            return response()->json([
                'success' => $success,
                'total' => $total,
            ], 201);
        } 
    }

    public function store(Request $request)
    {
        // $depService = new DepartmentalService();
        // $data = (new DepartmentalServiceValidator())->validate($depService, $request->toArray());
        // $data['addedby'] = \Auth::user()->id; 
        // $depService = DepartmentalService::create($data);

        // return DepartmentalServiceResource::collection(DepartmentalService::all());

        // return new UserResource($depService);

    //    return DepartmentalServiceResource::make($depService);

        $validator = Validator::make($request->all(),[
            'department_id' => 'required|integer',
            'sub_department_id' => 'required|integer',
            'service_title' => 'bail|required|string|min:3',
            'service_charges' => 'bail|required|numeric',
            'charges_type' => ['bail','required', Rule::in(Constant::CHARGES_TYPE_RULE)],
            'description' => 'bail|nullable',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->toArray(),
                'success' => 'no',
            ], 201);
        }

        $userId = \Auth::user()->id;

        $store_service = DepartmentalService::create([
            'department_id' => $request->department_id,
            'sub_department_id' => $request->sub_department_id,
            'service_title' => $request->service_title,
            'service_charges' => $request->service_charges,
            'charges_type' => $request->charges_type,
            'description' => $request->description,
            'addedby' => $userId,
        ]);

        if($store_service){
            $success = 'yes';
            $message = 'New Service Created successfully!';
        }else{
            $success = 'no';
            $message = 'Data not saved something went wrong!';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ], 200);
    }

    // http://127.0.0.1:8000/departmental-services/depart_services/3

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'department_id' => 'bail|required|integer',
            'sub_department_id' => 'bail|required|integer',
            'service_title' => 'bail|required|string|min:3',
            'service_charges' => 'bail|required|numeric',
            'charges_type' => ['bail','required', Rule::in(Constant::CHARGES_TYPE_RULE)],
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

    //     $data = (new DepartmentalServiceValidator())->validate($depService, $request->toArray());
    //     $data['addedby'] = \Auth::user()->id; 
    //     $depService->update($data);

    //    return DepartmentalServiceResource::make($depService);
        $service = DepartmentalService::find($id);
        $userId = \Auth::user()->id;
        $update = $service->update([
            'department_id' => $request->department_id,
            'sub_department_id' => $request->sub_department_id,
            'service_title' => $request->service_title,
            'service_charges' => $request->service_charges,
            'charges_type' => $request->charges_type,
            'description' => $request->description,
            'updatedby' => $userId,
        ]);

        if($update){
            $success = 'yes';
            $message = 'A Service Updated successfully!';
        }else{
            $success = 'no';
            $message = 'Data not saved something went wrong!';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ], 200);
    }

    public function getDeparments()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $sub_department_ids = $this->managerSubDepartments();
        $user_level_id = $user_detail->user_level_id;

        $departments = Department::whereHas('hod')->when($user_level_id == 2, function ($qry){
            $qry->whereIn('society_id', $this->adminSocieties());
        })->when($user_level_id == 3, function ($qry) use ($user_detail){
            $qry->where('society_id', $user_detail->society_id)
            ->whereIn('id', $this->hodDepartments());
        })->when($user_level_id == 4, function ($qry) use ($user_detail,$sub_department_ids){
            $qry->where('society_id', $user_detail->society_id)
            ->whereHas('subdepartments', function($q) use ($sub_department_ids){
                $q->whereIn('id', $sub_department_ids);
            });
        })->with('society:id,code,name,slug','subdepartments:id,name,department_id','subdepartments.department:id,name')->orderBy('id','DESC')->get(['id','name','slug','society_id']);

        if($departments->count() > 0){
            $success = 'yes';
            $total = $departments->count();
            $departments = $departments->toArray();
        }else{
            $success = 'no';
            $total = 0;
            $departments = collect();
        }

        return response()->json([
            'success' => $success,
            'total' => $total,
            'departments' => $departments,
        ], 200);
    }

    public function getDepartmentSubdepartments($id)
    {
        $subdepartments = SubDepartment::where('department_id', $id)->whereHas('asstmanager')->get(['id','name','department_id']);

        if($subdepartments->count() > 0){
            $success = 'yes';
            $subdepartments = $subdepartments->toArray();
        }else{
            $success = 'no';
            $subdepartments = collect();
        }

        return response()->json([
            'success' => $success,
            'subdepartments' => $subdepartments,
        ], 200);
    }

    public function destroy($id)
    {
        $DepartmentalService = DepartmentalService::findOrFail($id);
        $DepartmentalService->delete();
        Session::flash('notify', ['type'=>'success', 'message' => 'A service Deleted successfully']);
        return redirect()->route('depart_services.index');
    }

    public function getServiceWithFilter($depart_id, $subdepart_id)
    {
        $services = DepartmentalService::where([['department_id', $depart_id], ['sub_department_id', $subdepart_id]])->with('department:id,name','subdepartment:id,name')->orderBy('id', 'DESC')->get();

        if($services->count() > 0){
            $success = 'yes';
            $services = $services->toArray();
        }else{
            $success = 'no';
            $services = collect();
        }

        return response()->json([
            'success' => $success,
            'services' => $services,
        ], 200);
    }
}
