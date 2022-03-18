<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Society;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;

use Gate;
use Symfony\Component\HttpFoundation\Response;  
use Session;

class EventController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->get();
            $events = Events::whereIn('society_id', $admin_soctities)->with('society:id,name,code', 'sector:id,sector_name,society_id')->orderBy('id','DESC')->get();
        }else if($user_detail->user_level_id > 2){
            $events = Events::where('society_id', $user_detail->society_id)->with('society:id,name,code', 'sector:id,sector_name,society_id')->orderBy('id','DESC')->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $events = Events::with('society:id,name,code','sector:id,sector_name,society_id')->orderBy('id','DESC')->get();
            $societies = Society::get();
        }
        $message = "no";
        $counts = $events->count();
        if($counts > 0){
            $message = "yes";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
           return view('eventmanagement.index', compact('events','societies')); 
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'events' => $events
            ], 201);
        }  
    }

    public function create()
    {
        abort_if(Gate::denies('create-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

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
        $event = new Events();
        return view('eventmanagement.create', compact('event','societies'));
    }

    public function store(Request $request){

        abort_if(Gate::denies('create-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $userId = \Auth::user()->id;
        $now = $this->currentDate(); 
        $image_location = 'uploads/events';
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'event_date' => 'required',
            'society_id' => 'integer|nullable',
            'image' => 'nullable',
            'description' => 'string|nullable',
            'event_venue' => 'string|nullable',
            'event_date' => 'required|date|after_or_equal:'.$now,
        ]);

        if($request->image){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }else{
           $image_name=''; 
        }

        $event = Events::create([
            'title' => $request->title,
            'event_date' => $request->event_date,
            'society_id' => $request->society_id,
            'event_venue' => $request->event_venue,
            'description' => $request->description,
            'image' => $image_name,
            'addedby' => $userId,
        ]);
        if(count($request->sectors) > 0){
            $event->sectors()->attach($request->sectors);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('event.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $event = '';
        $is_int = is_numeric($id);
        if($is_int){
            $event = Events::with('society','sectors.society:id,code,name')->find($id);
            $message = "no";
            if($event != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            // dd($event->toArray());
           return view('eventmanagement.event_detail', compact('event')); 
        }else{
            return response()->json([
                'message' => $message,
                'event' => $event,
            ], 200);
        } 
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $event = Events::with('society','sectors')->find($id);
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
        return view('eventmanagement.create', compact('event','societies'));
    }

    public function update(Request $request, $id){
        abort_if(Gate::denies('update-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        
        $userId = \Auth::user()->id;
         $image_location = 'uploads/events';
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'event_date' => 'required',
            'description' => 'string',
            'image' => 'nullable',
        ]);
        // $cevent_date = Carbon::createFromFormat('m/d/Y', $request->event_date)->toDateString();
        if($request->image != ''){
            $image = $request->file('image');
            $extension = $request->file('image')->extension();
            $image_name = time().mt_rand(10,99).'.'.$extension;
            $file = $image->move($image_location,$image_name);
        }

        $event = Events::find($id);
        $event->title = $request->title;
        $event->event_date = $request->event_date;
        $event->description = $request->description;
        $event->society_id = $request->society_id;
        $event->event_venue = $request->event_venue;
        
        if($request->image != ''){
            $event->image =$image_name;
        }
        $event->updatedby = $userId;
        $event->updated_at = $this->currentDateTime();
        $event->save();

        if(count($request->sectors) > 0){
            $event->sectors()->sync($request->sectors);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('event.index');
    }

    public function destroy($id){
        abort_if(Gate::denies('delete-events-management'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $event = Events::findOrFail($id);
        $event->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return back();
    }
}
