<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\ByLaw;
use App\Models\BylawAttachment;
use App\Models\BylawHasSociety;
use App\Models\SocialMedia;
use App\Models\Society;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;
use Validator;

class ByLawController extends Controller
{
    use HelperTrait;
    public function index(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $attachments = '';
        //filter services against login user socities 
        if($user_detail->user_level_id == 1){
            $bylaws = ByLaw::with('societies')->orderBy('id','DESC')->get();
            $societies = Society::get();
            $attachments = BylawAttachment::orderBy('id','DESC')->get();
        }elseif($user_detail->user_level_id == 2){
            $societies = $this->adminSocieties();
            $bylaws = ByLaw::whereHas('societies', function ($q) use ($societies) {
                $q->whereIn('society_id', $societies);
            })->with('societies')->orderBy('id','DESC')->get();
            $societies = Society::whereIn('id', $societies)->get();
            $attachments = BylawAttachment::whereIn('society_id', $societies)->orderBy('id','DESC')->get();
        }else{
            $society_id = $user_detail->society_id;
            $bylaws = ByLaw::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies')->orderBy('id','DESC')->get();
            $societies = Society::where('id', $society_id)->get();
            $attachments = BylawAttachment::where('society_id', $society_id)->orderBy('id','DESC')->get();
        }
        $message = "No Data Found";
        $counts = $bylaws->count();
        if($counts > 0){
            $message = "yes";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           return view('pages.bylaw.index', compact('bylaws', 'societies','attachments'));
       }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'bylaws' => $bylaws,
                'attachments' => $attachments,
            ], 201);
       } 
    }
    public function create(){
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
        $sop = new ByLaw();
        return view('pages.bylaw.create', compact('sop','societies'));
    }

    public function store(Request $request){
        $userId = \Auth::user()->id;
        // dd($request->toArray());
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string',
        ]);
        $slug = $this->getSlug($request->title);
        $bylaw = ByLaw::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        if(count($request->societies) >0){
         $bylaw->societies()->attach($request->societies);   
        }
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('bylaws.index');
        
    }

    public function show($id) {
        $bylaw = '';
        $is_int = is_numeric($id);
        if($is_int){
            $bylaw = ByLaw::find($id);
            $message = "no";

            if($bylaw != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('pages.detail_page')->with('page_title', 'ByLaws')->with('detail', $bylaw);
        }
        return response()->json([
            'message' => $message,
            'bylaw' => $bylaw
        ], 201);

    }

    public function edit($id) {
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
        $sop = ByLaw::find($id);
        return view('pages.bylaw.create', compact('sop','societies'));
    }

    public function update(Request $request, $id) {
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string',
        ]);
        $bylaw = ByLaw::find($id);
        $bylaw->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        if(count($request->societies) >0){
         $bylaw->societies()->sync($request->societies);   
        }

        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('bylaws.index');
    }


    public function destroy($id) {
        $bylaw = ByLaw::findOrFail($id);
        $bylaw->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }


    public function uploadAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => 'bail|required|mimes:pdf|max:10000',
        ]);
        if ($validator->fails()) {
            Session::flash('notify', ['type'=>'warning', 'delay' => 2500,'message' => $validator->errors()->first()]);
            return back();
        }else{
            $attachment_name = '';
            $image_location = 'uploads/bylaw';
            if($request->hasFile('attachment')){
                $attachment = $request->file('attachment');
                $extension = $request->file('attachment')->extension();
                $attachment_name = time().mt_rand(10,99).'.'.$extension;
                $file = $attachment->move($image_location,$attachment_name);
            }else{
                $attachment_name=''; 
            }
            if(count($request->societies) > 0){
                foreach ($request->societies as $society) {
                    $bylaw_attachment = DB::table('bylaw_attachments')->insert([
                        'title' => $request->title,
                        'attachment' => $attachment_name,
                        'society_id' => $society,
                        'created_at' => $this->currentDateTime(),
                    ]);
                }
            }
            Session::flash('notify', ['type'=>'success', 'message' => 'Sop Attachment upload successfully!']);
            return back();
        }
    }
    
    public function deleteAttachment($attachment)
    {
        $bylawAttachment = DB::table('bylaw_attachments')->where('attachment',$attachment)->delete();
        if($bylawAttachment){
            Session::flash('notify', ['type'=>'danger','message' => 'An Attachment Deleted successfully!']);
        }
        return back();
    }
    
    public function filterWithSociety(Request $request)
    {
        $societies = Society::get();
        $society_id = $request->society_id;
        if($request->society_id !='all' AND $request->society_id !=''){
            $society_id = $request->society_id;
            $bylaws = ByLaw::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies')->orderBy('id','DESC')->get();
            $attachments = BylawAttachment::where('society_id', $society_id)->orderBy('id','DESC')->get();
            return view('pages.bylaw.index', compact('bylaws', 'societies', 'attachments','society_id'));
        }else{
            $bylaws = ByLaw::with('societies')->orderBy('id','DESC')->get();
            $attachments = BylawAttachment::orderBy('id','DESC')->get();
            return view('pages.bylaw.index', compact('bylaws', 'societies', 'attachments','society_id'));
        }
    }
}