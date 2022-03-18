<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentDataRequest;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\TwoFourSeven;
use App\Models\TwoFourSevenAttachment;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

use Symfony\Component\HttpFoundation\Response;
use Gate;

class TwoFourSevenController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $two4sevens = TwoFourSeven::whereHas('societies', function ($q) 
                use ($admin_soctities) {
                $q->whereIn('society_id', $admin_soctities);
            })->with('societies')->orderBy('id','DESC')->get();
            $attachments = TwoFourSevenAttachment::whereIn('society_id', $admin_soctities)->orderBy('id','DESC')->get();
            $societies = Society::whereIn('id', $admin_soctities)->get();
        }else if($user_detail->user_level_id > 2){
            
            $society_id = $user_detail->society_id;
            $two4sevens = TwoFourSeven::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies')->orderBy('id','DESC')->get();
            $attachments = TwoFourSevenAttachment::where('society_id', $society_id)->orderBy('id','DESC')->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $two4sevens = TwoFourSeven::with('societies')->orderBy('id','DESC')->get();
            $attachments = TwoFourSevenAttachment::orderBy('id','DESC')->get();
            $societies = Society::get();
        }
        
        $message = "No Data Found";
        $counts = $two4sevens->count();
        if($counts > 0){
            $message = "Success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('pages.twofourseven.index', compact('two4sevens','societies','attachments'));
        }

        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'two4sevens' => $two4sevens,
            'attachments' => $attachments
        ], 200);

    }

    public function create()
    {
        abort_if(Gate::denies('create-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
        
        $twoFourSeven = new TwoFourSeven();
        return view('pages.twofourseven.create', compact('twoFourSeven', 'societies'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $TwoFourSeven = TwoFourSeven::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        if(count($request->societies) > 0){
         $TwoFourSeven->societies()->attach($request->societies);
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return redirect()->route('twofour.index');
        }else{
            return response()->json([
                'message' => 'yes',
                'error' => 'no',
                'TwoFourSeven' => $TwoFourSeven
            ], 201);
        }
    }

    public function show($id) {
        abort_if(Gate::denies('view-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $two4seven = '';
        $is_int = is_numeric($id);
        if($is_int){
            $two4seven = TwoFourSeven::find($id);
            $message = "no";

            if($two4seven != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('pages.detail_page')->with('page_title', '24/7')->with('detail', $two4seven);
        }
        
        return response()->json([
            'message' => $message,
            'two4seven' => $two4seven
        ], 201);
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
        
        $twoFourSeven = TwoFourSeven::with('societies')->find($id);
        return view('pages.twofourseven.create', compact('twoFourSeven', 'societies'));
    }

    public function update(Request $req, $id)
    {
        abort_if(Gate::denies('update-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $TwoFourSeven = TwoFourSeven::find($id);
        if($TwoFourSeven == '') {
            $message = 'No data Found';
        }else{
            $message = 'Resident successfully updated';
            $TwoFourSeven->update([
                'title' => $req->title,
                'description' => $req->description,
            ]);
            if(count($req->societies) > 0){
                $TwoFourSeven->societies()->sync($req->societies);
            }
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return redirect()->route('twofour.index');
        }else{
            return response()->json([
                'message' => $message,
                'TwoFourSeven' => $TwoFourSeven
            ], 200);
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $TwoFourSeven = TwoFourSeven::find($id);
        $message = 'Data Deleted Successfully';

        if($TwoFourSeven == '') {
            $message = 'No Data Found';
        }else{
            $TwoFourSeven->delete(); 
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            Session::flash('notify', ['type'=>'success','message' => 'Data Deleted successfully']);
            return back();
        }else{
            return response()->json([
                'message' => $message,
                'two_four_seven' => $TwoFourSeven
            ], 201);    
        }
    }

    public function uploadAttachment(Request $request)
    {
        abort_if(Gate::denies('update-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $validator = Validator::make($request->all(), [
            'attachment' => 'bail|required|mimes:pdf|max:10000',
        ]);
        if ($validator->fails()) {
            Session::flash('notify', ['type'=>'warning', 'delay' => 2500,'message' => $validator->errors()->first()]);
            return back();
        }else{
            $attachment_name = '';
            $image_location = 'uploads/twofour';
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
                    $twofour_attachment = DB::table('two_four_seven_attachments')->insert([
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
        abort_if(Gate::denies('update-24-7-Page'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $twofourAttachment = DB::table('two_four_seven_attachments')->where('attachment',$attachment)->delete();

        // if($twofourAttachment->attachment != null && \File::exists(public_path('uploads/residents/'.$twofourAttachment->attachment))){
        //     \File::delete(public_path('uploads/residents/'.$resident->image));
        // }

        // $twofourAttachment->delete();

        if($twofourAttachment){
            Session::flash('notify', ['type'=>'danger','message' => 'An Attachment Deleted successfully!']);
        }
        return back();
    }
    
    public function filterWithSociety(Request $request)
    {
        $societies = Society::get();
        $society_id = $request->society_id;
        if($request->society_id !='all' AND $request->society_id !=''){
            $two4sevens = TwoFourSeven::whereHas('societies', function ($q) use ($society_id) {
                $q->where('society_id', $society_id);
            })->with('societies')->orderBy('id','DESC')->get();
            $attachments = TwoFourSevenAttachment::where('society_id', $society_id)->orderBy('id','DESC')->get();
            return view('pages.twofourseven.index', compact('two4sevens','societies', 'attachments','society_id'));
        }else{
            $two4sevens = TwoFourSeven::with('societies')->orderBy('id','DESC')->get();
            $attachments = TwoFourSevenAttachment::orderBy('id','DESC')->get();
            return view('pages.twofourseven.index', compact('two4sevens','societies', 'attachments','society_id'));
        }
    }
}