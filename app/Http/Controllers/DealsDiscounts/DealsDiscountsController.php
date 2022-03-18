<?php

namespace App\Http\Controllers\DealsDiscounts;

use App\Http\Controllers\Controller;
use App\Models\DealsDiscounts;
use App\Models\Notification;
use App\Models\Society;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;
use Session;
use Gate;
use Symfony\Component\HttpFoundation\Response;  

class DealsDiscountsController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $deals = DealsDiscounts::whereIn('society_id', $admin_soctities)->with('society:id,code,name','vendor','sectors:id,sector_name,society_id')->orderBy('id', 'DESC')->get();
            $societies = Society::whereIn('id', $admin_soctities)->get(['id','code','name']);
        }else if($user_detail->user_level_id > 2){
            $deals = DealsDiscounts::where('society_id', $user_detail->society_id)->with('society:id,code,name','vendor','sectors:id,sector_name,society_id')->orderBy('id', 'DESC')->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }else{
           $deals = DealsDiscounts::with('society:id,code,name','vendor','sectors:id,sector_name,society_id')->orderBy('id', 'DESC')->get();
           $societies = Society::get(['id','code','name']);
        }
        $message = "No Data Found";
        $counts = $deals->count();
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('dealsdiscounts.index', compact('deals','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'deals' => $deals
        ], 201);
    }

    public function create()
    {
        abort_if(Gate::denies('create-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors','vendors')->get();
            $vendors = Vendor::whereIn('society_id', $admin_soctities)->get();
        
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors','vendors')->get();
            $vendors = Vendor::where('society_id', $user_detail->society_id)->get();
        
        }else{
            $societies = Society::with('sectors','vendors')->get();
            $vendors = Vendor::get();
        }
        
        $deal = new DealsDiscounts();
        return view('dealsdiscounts.create', compact('deal','societies','vendors'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
            'society_id' => 'required|integer',
            'sectors' => 'required',
            'vendor_id' => 'required|integer',
            'title' => 'required|string|min:1',
            'start_date' => 'required|date|after_or_equal:'.$now,
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable',
        ]);

        $deal = DealsDiscounts::create([
            'society_id' => $request->society_id,
            'vendor_id' => $request->vendor_id,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'addedby' => $userId,
        ]);

        if(count($request->sectors) > 0){
            $deal->sectors()->attach($request->sectors);
        }

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('deals.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $web_user = Auth::guard('web')->user();
        $dealsdiscount = '';
        $is_int = is_numeric($id);

        if($is_int){
            $dealsdiscount = DealsDiscounts::with('society','vendor','sectors')->find($id);
            $message = "No Data Found";
            if($dealsdiscount != ''){
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }
        
        // $req->query->add(['key'=>'variable']);

        if($web_user !=''){
            return view('dealsdiscounts.deal_detail', compact('dealsdiscount'));
        }else{
            return response()->json([
                'message' => $message,
                'dealsdiscount' => $dealsdiscount,
            ], 201);
        }
        
    }

    public function getsocietydeal($id)
    {
        $web_user = Auth::guard('web')->user();
        $dealsdiscount = '';
        $is_int = is_numeric($id);

        if($is_int){
            $dealsdiscount = DealsDiscounts::with('society','vendor','sector','sectors')->where('society_id',$id)->get();
            $message = "No Data Found";
            $counts = $dealsdiscount->count();

            if($counts > 0){
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }

        if($web_user !=''){

        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'dealsdiscount' => $dealsdiscount
            ], 201);
        }
        
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors','vendors')->get();
            $vendors = Vendor::whereIn('society_id', $admin_soctities)->get();
        
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors','vendors')->get();
            $vendors = Vendor::where('society_id', $user_detail->society_id)->get();
        
        }else{
            $societies = Society::with('sectors','vendors')->get();
            $vendors = Vendor::get();
        }
        
        $deal = DealsDiscounts::with('society','vendor','sectors')->find($id);
        return view('dealsdiscounts.create', compact('deal','societies','vendors'));
    }

    public function update(Request $request, $id)
    {   
        abort_if(Gate::denies('update-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
            'society_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'title' => 'required|string|min:1',
            'start_date' => 'required|date|after_or_equal:'.$now,
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable',
        ]);
        
        // dd($request->toArray());
        $message = 'No Data Found';
        $deal = DealsDiscounts::find($id);
        if($deal !=''){
            $deal->update([
                'society_id' => $request->society_id,
                'vendor_id' => $request->vendor_id,
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'addedby' => $userId,
            ]);
            $message = 'Data updated successfully';
            $type = 'success';

            if(count($request->sectors) > 0){
                $deal->sectors()->sync($request->sectors);
            }
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('deals.index');
        }else{
            return response()->json([
                'message' => $message,
                'dealsdiscount' => $deal
            ], 201);
        }
        
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-deals-and-discounts'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $web_user = Auth::guard('web')->user();
        $deal = '';
        $is_int = is_numeric($id);
        if($is_int){
            $deal = DealsDiscounts::find($id);
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
            ], 200);
        }
    }
}
