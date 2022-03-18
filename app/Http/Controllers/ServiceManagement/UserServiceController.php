<?php

namespace App\Http\Controllers\ServiceManagement;

use Auth;
use Session;
use App\Models\User;
use App\Models\Society;
use App\Models\UserService;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Response;

use Symfony\Component\HttpFoundation\Response;
use Gate;

class UserServiceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-smart-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        if($user_detail->user_level_id >= 6){
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','slug','name']);
            $users = User::where('is_active', 1)->where('id', $user_detail->id)->has('services')->with('society','services')->withCount('services as totalServices')->get();
        } else if($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6){
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','slug','name']);
            $users = User::where('is_active', 1)->where('society_id', $user_detail->society_id)->has('services')->with('society','services')->withCount('services as totalServices')->get();
        }else if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies services
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->get(['id','code','slug','name']);
            $users = User::where('is_active', 1)->whereIn('society_id', $admin_soctities)->has('services')->with('society','services')->withCount('services as totalServices')->get();
        }else{
            $societies = Society::get(['id','code','slug','name']);
            $users = User::where('is_active', 1)->has('services')->with('society','services')->withCount('services as totalServices')->get();
        }
        $message = "No Data Found";
        $counts = $users->count();
        if($counts > 0){
            $message = "success";
        }
        // dd($users->toArray());
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('servicemanagement.user_services.index', compact('users','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'users' => $users
        ], 201);

    }

    public function show($id) {
        abort_if(Gate::denies('view-smart-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $user_services = '';
        $is_int = is_numeric($id);
        if($is_int){
            $user_services = User::where('is_active', 1)->has('services')->with('society','services.service','services.servicetype','services.package')->withCount('services as totalServices')->orderBy('id','DESC')->find($id);
            // $services = UserService::where('user_id',$id)->get();
            // dd($user_services->toArray());
            $message = "No Data Found";

            if($user_services !=''){
                $message = "success";
            }
        }else{
            $message = "Id must be integer";
        }

        // dd($user_services->toArray());
        if($web_user !=''){
            return view('servicemanagement.user_services.user-services-detail', compact('user_services'));
        }else{
            return response()->json([
                'message' => $message,
                'user_services' => $user_services
            ], 201);
        }
    }

    // function is used for changing User Service status i.e [active,inactive]
    public function edit($id) {

        abort_if(Gate::denies('update-smart-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $user_service = UserService::findOrFail($id);
        // dd($user_service->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        if ($user_service->status == 0) {
            $user_service->status = 1;
        }else{
            $user_service->status = 0;
        }
        $user_service->updatedby = $userId;
        $user_service->updated_at = $this->currentDateTime();
        $user_service->save();
        Session::flash('notify', ['type'=>'success','message' => 'Service Status Updated Successfully']);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('update-smart-services'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        // dd($request->toArray());
        $price = $request->package_price;
        // dd($request_service->toArray());
        if($request->discount_amount > 0 ){
            $final_price = $price - $request->discount_amount;
        }else{
            $final_price = $price;
        }
        if($final_price > $price){
            $message = 'Discount Amount Cannot Be Greater Than '.$price;
            $type = 'danger';
        }else{
            $service_request_update = DB::table('user_services')->where('id', $request->user_service_id)
                ->update([
                'price' => $request->package_price,
                'discount_amount' => $request->discount_amount,
                'discount_percentage' => $request->discount_percent,
                'final_price' => $final_price,
                'updated_at' => $this->currentDateTime(),
                'updatedby' => $userId,
            ]);
            $message = 'Data Updated Successfully';
            $type = 'success';
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->back();
        }else{
            return response()->json([
                'message' => $message,
            ], 201);
        }
        
    }
}