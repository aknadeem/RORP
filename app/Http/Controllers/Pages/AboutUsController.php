<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\Society;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Response;
use Session;
use Auth;

class AboutUsController extends Controller
{
    use HelperTrait;
    public function index()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $aboutus = AboutUs::with('society:id,code,name')->get();
            $societies = Society::get(['id','code','name']);
        }else if($user_detail->user_level_id == 2){
            $aboutus = AboutUs::whereIn('society_id', $this->adminSocieties())->with('society:id,code,name')->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $aboutus = AboutUs::where('society_id', $user_detail->society_id)->with('society:id,code,name')->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        $message = "No Data Found";
        $counts = $aboutus->count();
        if($counts > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('aboutus.index', compact('aboutus','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'aboutus' => $aboutus
        ], 201);
    }
    public function create()
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        
        $about = new AboutUs();
        return view('aboutus.create', compact('about','societies'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'society_id' => 'bail|required|integer',
            'title' => 'bail|required|string|min:2',
            'description' => 'string|nullable',
        ]);

        $web_user_id = Auth::guard('web')->user();

        if($web_user_id){
            $userId = $web_user_id->id;
        }else{
            $api_user_id = Auth::guard('api')->user();
            $userId = $api_user_id->id;
        }

        $aboutus = AboutUs::create([
            'title' => $request->title,
            'society_id' => $request->society_id,
            'description' => $request->description,
            'addedby' => $userId,
        ]);

        if($web_user_id){
            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            return redirect()->route('aboutus.index');

        }else{
            return response()->json([
                'error' => 'no',
                'success' => 'yes',
                'message' => 'Data Created Successfully!',
                'aboutus' => $aboutus,
            ], 201);
        }  
    }

    public function show($id)
    {
        
    }
    public function edit($id)
    {
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
        }else if($user_detail->user_level_id == 2){
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        
        $about = AboutUs::find($id);
        return view('aboutus.create', compact('about','societies'));
    }
    public function update(Request $request, $id)
    {
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'title' => 'bail|required|string|min:2',
            'society_id' => 'bail|required|integer',
            'description' => 'string',
        ]);
     
        $about = AboutUs::find($id);
        $about->title = $request->title;
        $about->society_id = $request->society_id;
        $about->description = $request->description;
        $about->updatedby = $userId;
        $about->updated_at = $this->currentDateTime();
        $about->save();
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('aboutus.index');
    }

    public function destroy($id)
    {
        
        $about = AboutUs::findOrFail($id);
        $about->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }
}