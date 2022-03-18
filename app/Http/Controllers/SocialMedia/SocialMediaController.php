<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;
use Session;
use Gate;
use Symfony\Component\HttpFoundation\Response;  

class SocialMediaController extends Controller
{
    use HelperTrait;
    public function index(){
        abort_if(Gate::denies('view-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        //filter data against login user socities 
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
            $socialmedias = SocialMedia::with('societies')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            $societies = $this->adminSocieties();
            $socialmedias = SocialMedia::whereHas('societies', function ($q) 
                use ($societies) {
                $q->whereIn('society_id', $societies);
            })->with('societies')->orderBy('id','DESC')->get();
            
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
            
        }else{
            $society_id = $user_detail->society_id;
            $socialmedias = SocialMedia::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies')->orderBy('id','DESC')->get();
            $societies = Society::where('id', $society_id)->get(['id','code','name']);
        }
        $message = "No Data Found";
        $counts = $socialmedias->count();
        if($counts > 0){
            $message = "yes";
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           return view('socialmedia.index', compact('socialmedias','societies'));
       }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'socialmedias' => $socialmedias
            ], 201);
       }
    }

    public function create(){
        abort_if(Gate::denies('create-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->get();
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $societies = Society::get();
        }
        $socialmedia = new SocialMedia();
        return view('socialmedia.create', compact('socialmedia','societies'));
    }

    public function store(Request $request){

        abort_if(Gate::denies('create-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $userId = \Auth::user()->id;
        // dd($request->toArray());
        $this->validate($request,[
            'social_type' => 'required|string|min:3',
            'title' => 'required|string|min:3',
            'link_url' => 'required|string|min:3',
        ]);
        $slug = $this->getSlug($request->title);
        $socialmedia = SocialMedia::create([
            'social_type' => $request->social_type,
            'title' => $request->title,
            'slug' => $slug,
            'link_url' => $request->link_url,
            'addedby' => $userId,
        ]);
        if(count($request->societies) > 0){
            $socialmedia->societies()->attach($request->societies);
        }

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('socialmedia.index');
    }

    public function show($id) {

        abort_if(Gate::denies('view-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $socialmedia = SocialMedia::find($id);
        $message = "No Data Found";
        if($socialmedia){
            $message = "yes";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           // return view('socialmedia.index', compact('socialmedias'));
       }else{
            return response()->json([
                'message' => $message,
                'socialmedia' => $socialmedia
            ], 201);
       }
    }

    public function edit($id) {
        abort_if(Gate::denies('update-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->get();
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $societies = Society::get();
        }
        $socialmedia = SocialMedia::with('societies')->find($id);
        return view('socialmedia.create', compact('socialmedia','societies'));
    }

    public function update(Request $request, $id) {
        abort_if(Gate::denies('update-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $userId = \Auth::user()->id;
        $this->validate($request,[
            'social_type' => 'required|string|min:3',
            'title' => 'required|string|min:3',
            'link_url' => 'required|string|min:3',
        ]);
        $slug = $this->getSlug($request->title);
        $social = SocialMedia::find($id);
        $social->update([
            'social_type' => $request->social_type,
            'title' => $request->title,
            'slug' => $slug,
            'link_url' => $request->link_url,
            'updatedby' => $userId,
        ]);
        if(count($request->societies) > 0){
            $social->societies()->sync($request->societies);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('socialmedia.index');
    }


    public function destroy($id) {
        abort_if(Gate::denies('delete-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $socialmedia = SocialMedia::findOrFail($id);
        $socialmedia->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }
}