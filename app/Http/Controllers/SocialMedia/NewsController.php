<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;
use Session;
use Gate;
use Symfony\Component\HttpFoundation\Response;  

class NewsController extends Controller
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
            $news = News::with('societies:id,code,name')->orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            $societies = $this->adminSocieties();
            $news = News::whereHas('societies', function ($q) use ($societies) {
                $q->whereIn('society_id', $societies);
            })->with('societies:id,code,name')->orderBy('id','DESC')->get();
            $societies = Society::whereIn('id',$this->adminSocieties())->get(['id','code','name']);
        }else{
            $society_id = $user_detail->society_id;
            $news = News::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies:id,code,name')->orderBy('id','DESC')->get();
            $societies = Society::where('id',$society_id)->get(['id','code','name']);
        }
        // dd($news->toArray());
        $message = 'No Data Found';
        $counts = $news->count();
        if($counts > 0){
            $message = 'yes';
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           return view('socialmedia.news.index', compact('news','societies'));
       }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'news' => $news
            ], 200);
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
        $news = new News();
        return view('socialmedia.news.create', compact('news','societies'));
    }

    public function store(Request $request){ 
        abort_if(Gate::denies('create-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $userId = \Auth::user()->id;
        // dd($request->toArray());
        $image_location = 'uploads/news';
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string|nullable',
            'image' => 'nullable',
            'pdf_file' => 'nullable',
            'is_flash' => 'nullable',
        ]);
        // dd($request->toArray());
        if($request->image){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }else{
           $image_name=''; 
        }

        if($request->pdf_file){
            $pdf_file_image = $request->file('pdf_file');
            $extension = $request->file('pdf_file')->extension();
            $pdf_name = time().mt_rand(10,99).'.'.$extension;
            $file = $pdf_file_image->move($image_location,$pdf_name);
        }else{
           $pdf_name=''; 
        }
        $news = News::create([
            'title' => $request->title,
            'is_flash' => $request->is_flash,
            'description' => $request->description,
            'image' => $image_name,
            'pdf_file' => $pdf_name,
            'addedby' => $userId,
        ]);
        if(count($request->societies) > 0){
            $news->societies()->attach($request->societies);
        }
        
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('news.index');
        
    }

    public function show($id) {
        abort_if(Gate::denies('view-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $news = News::with('societies')->find($id);
        $message = 'No Data Found';
        if($news){
            $message = 'yes';
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           return view('socialmedia.news.index', compact('news'));
        }else{
            return response()->json([
                'message' => $message,
                'news' => $news
            ], 201);
        }
    }

    public function edit($id) {
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
        $news = News::with('societies')->find($id);
        return view('socialmedia.news.create', compact('news','societies'));
    }

    public function update(Request $request, $id) {
        abort_if(Gate::denies('update-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $userId = \Auth::user()->id;
        $image_location = 'uploads/news';
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string',
            'image' => 'nullable',
            'pdf_file' => 'nullable',
            'is_flash' => 'nullable',
        ]);

        if($request->image){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }else{
           $image_name=''; 
        }

        if($request->pdf_file){
            $pdf_file_image = $request->file('pdf_file');
            $extension = $request->file('pdf_file')->extension();
            $pdf_name = time().mt_rand(10,99).'.'.$extension;
            $file = $pdf_file_image->move($image_location,$pdf_name);
        }else{
           $pdf_name=''; 
        }

        $news = News::find($id);
        $news->title = $request->title;
        $news->description = $request->description;
        
        if($request->image != ''){
            $news->image =$image_name;
        }
        if($request->pdf_file != ''){
            $news->pdf_file =$pdf_name;
        }
        $news->updatedby = $userId;
        $news->is_flash = $request->is_flash;
        $news->updated_at = $this->currentDateTime();
        $news->save();

        if(count($request->societies) > 0){
            $news->societies()->sync($request->societies);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('news.index');
    }

    public function destroy($id) {

        abort_if(Gate::denies('delete-social-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

    	$news = News::findOrFail($id);
        $news->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }

    // flash news
    public function getFlashNews(){
        $flash_news = News::orderBy('id','DESC')->where('is_flash', 1)->first();
        $message = 'no';
        if($flash_news !=''){
            $message = 'yes';
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           // return view('socialmedia.news.index', compact('news'));
       }else{
            return response()->json([
                'message' => $message,
                'flash_news' => $flash_news
            ], 201);
       }
    }
}
