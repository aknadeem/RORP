<?php
namespace App\Http\Controllers\ServiceManagement;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Traits\HelperTrait;
use Session;
use Auth;

class ServiceTypeController extends Controller
{

    use HelperTrait;

    public function index()
    {
        $service_types = ServiceType::get();
        // dd($deals->toArray());
        $message = "No Data Found";
        $counts = $service_types->count();
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.service_type.index', compact('service_types'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'service_types' => $service_types
        ], 201);
    }

    public function create()
    {
        $service_type = new ServiceType();
        // dd($societies->toArray());
        return view('servicemanagement.service_type.create', compact('service_type'));
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
        ]);

        foreach ($request->title as $key => $title) {

            $servicetype_id = DB::table('service_types')->updateOrInsert(
                ['title' => $title ], 
                ['addedby' => $userId,
                'created_at' => $this->currentDateTime(),
                'updated_at' => $this->currentDateTime(),
            ]);
        }
        
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            
        return redirect()->route('servicetypes.index');
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
        $service_type = ServiceType::find($id);
        // dd($societies->toArray());
        return view('servicemanagement.service_type.create', compact('service_type'));
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
        ]);

        foreach ($request->title as $key => $title) {
            $service_type = DB::table('service_types')
              ->where('id', $id)
              ->update([
                'title' => $title,
                'updatedby' => $userId,
                'updated_at' => $this->currentDateTime(),
            ]);
            
        }

        if($service_type){
        	$message = 'Data updated successfully';
        	$type = 'success';
        }else{
        	$message = 'No Data Found';
            $type = 'danger';
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('servicetypes.index');
        }else{
            return response()->json([
                'message' => $message,
                'service_type' => $service_type
            ], 201);
        }
        
    }

    public function destroy($id)
    {
    	$delete_service = DB::table('service_types')->delete($id);

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


