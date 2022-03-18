<?php
namespace App\Http\Controllers\ServiceManagement;

use App\Http\Controllers\Controller;
use App\Models\ServiceSubType;
use App\Models\ServiceType;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;

class ServiceSubTypeController extends Controller
{
    use HelperTrait;
    public function index()
    {
       $service_subtypes = ServiceSubType::with('servicetype')->get();
        // dd($deals->toArray());
        $message = "No Data Found";
        $counts = $service_subtypes->count();
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.service_subtype.index', compact('service_subtypes'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_subtypes' => $service_subtypes
        ], 201);

    }

    public function create()
    {
        $service_subtype = new ServiceSubType();
        $servicetypes =  ServiceType::get();
        // dd($societies->toArray());
        return view('servicemanagement.service_subtype.create', compact('service_subtype','servicetypes'));
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
            'title' => 'required',
            'type_id' => 'required',
        ]);

        // dd($request->toArray());

        foreach ($request->title as $key => $title) {

            $servicesubtype_id = DB::table('service_sub_types')->updateOrInsert(
                ['title' => $title, 'type_id' => $request->type_id ], 
                ['addedby' => $userId,
                'created_at' => $this->currentDateTime(),
                'updated_at' => $this->currentDateTime(),
            ]);
            
        }
        
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            
        return redirect()->route('subtypes.index');
    }

    public function show($id)
    {

        $web_user = Auth::guard('web')->user();
        $service_type = '';
        $is_int = is_numeric($id);

        if($is_int){
            $service_type = ServiceType::find($id);
            $message = "No Data Found";

            if($service_type != ''){
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }

        if($web_user !=''){

        }else{
            return response()->json([
                'message' => $message,
                'service_type' => $service_type
            ], 201);
        }
        
    }


    public function edit($id)
    {
        $servicetypes =  ServiceType::get();
        $service_subtype = ServiceSubType::find($id);
        // dd($societies->toArray());
        return view('servicemanagement.service_subtype.create', compact('service_subtype','servicetypes'));
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
            'title' => 'required',
            'type_id' => 'required|integer',
        ]);

        foreach ($request->title as $key => $title) {
            $service_subtype = DB::table('service_sub_types')
              ->where('id', $id)
              ->update([
                'title' => $title,
                'type_id' => $request->type_id,
                'updatedby' => $userId,
                'updated_at' => $this->currentDateTime(),
            ]);
        }

        if($service_subtype){
            $message = 'Data updated successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found';
            $type = 'danger';
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('subtypes.index');
        }else{
            return response()->json([
                'message' => $message,
                'dealsdiscount' => $deal
            ], 201);
        }
        
    }

    public function destroy($id)
    {
        $delete_subtype = DB::table('service_sub_types')->delete($id);

        if($delete_subtype){
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


