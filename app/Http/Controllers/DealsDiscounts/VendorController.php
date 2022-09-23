<?php

namespace App\Http\Controllers\DealsDiscounts;

use App\Http\Controllers\Controller;
use App\Models\DealsDiscounts;
use App\Models\Society;
use App\Models\Vendor;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;
use Auth;

class VendorController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $vendors = Vendor::whereIn('society_id', $admin_soctities)->with('society:id,code,name')->orderBy('id','DESC')->get();
            $societies = Society::whereIn('id', $admin_soctities)->get(['id','code','name']);
        }else if($user_detail->user_level_id > 2){
            $vendors = Vendor::where('society_id', $user_detail->society_id)->with('society:id,code,name')->orderBy('id','DESC')->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }else{
           $vendors = Vendor::with('society:id,code,name')->orderBy('id','DESC')->get();
           $societies = Society::get(['id','code','name']);
        }

        $message = "No Data Found";
        $counts = $vendors->count();

        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('dealsdiscounts.vendor.index', compact('vendors','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
        ], 201);

    }

    public function create()
    {
        $vendor = new Vendor();
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors')->get();

        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors')->get();

        }else{
            $societies = Society::with('sectors')->get();
        }
        return view('dealsdiscounts.vendor.create', compact('vendor','societies'));
    }

    public function store(Request $request)
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
            'society_id' => 'required|integer',
            'title' => 'required|string',
            'logo' => 'nullable',
            'address' => 'nullable',
        ]);

        // dd($request->toArray());

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $extension = $request->file('logo')->extension();
            $logoname = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/vendor';
            $file = $logo->move($loc,$logoname);

        }else{
            $logoname = '';
        }

        $vendor = Vendor::create([
            'society_id' => $request->society_id,
            'title' => $request->title,
            'logo' => $logoname,
            'address' => $request->address,
            'addedby' => $userId,
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);

        return redirect()->route('vendors.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = '';
        $is_int = is_numeric($id);

        if($is_int){
            $vendor = Vendor::find($id);
            $message = "No Data Found";

            if($vendor != ''){
                $message = "Success";
            }
        }else{
            $message = "Id must be integer";
        }

        return response()->json([
            'message' => $message,
            'vendor' => $vendor
        ], 201);
    }

    public function edit($id)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors')->get();

        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors')->get();

        }else{
            $societies = Society::with('sectors')->get();
        }

        $vendor = Vendor::find($id);
        return view('dealsdiscounts.vendor.create', compact('vendor','societies'));
    }

    /**
     * Update the specified resource in database.
     */
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
            'society_id' => 'required|integer',
            'title' => 'required|string',
            'logo' => 'nullable',
            'address' => 'nullable',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $extension = $request->file('logo')->extension();
            $logoname = time().mt_rand(10,99).'.'.$extension;
            $loc = 'uploads/vendor';
            $file = $logo->move($loc,$logoname);

        }else{
            $logoname = '';
        }

        $message = 'No Data Found';
        $vendor = Vendor::find($id);
        if($vendor !=''){

            $vendor = $vendor->update([
                'society_id' => $request->society_id,
                'title' => $request->title,
                'logo' => $logoname,
                'address' => $request->address,
                'updatedby' => $userId,
            ]);
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=> 'success','message' => 'Data Updated Successfully']);
            return redirect()->route('vendors.index');
        }else{
            return response()->json([
                'message' => $message,
                'vendor' => $vendor
            ], 200);
        }
    }

    /**
     * Remove the specified resource from database.
     */
    public function destroy($id)
    {
        $web_user = Auth::guard('web')->user();
        $vendor = '';
        $is_int = is_numeric($id);
        if($is_int){
            $vendor = Vendor::find($id);
            $message = "No Data Found";
            if($vendor != ''){
                $vendor->delete();
                $message = "Data Deleted Successfully";
            }
        }else{
          $message = "Id Must be Integet";
        }

        if($web_user !=''){
            Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
            return redirect()->back();
        }else{
            return response()->json([
                'message' => $message,
                'vendor' => $vendor
            ], 201);
        }

    }
}
