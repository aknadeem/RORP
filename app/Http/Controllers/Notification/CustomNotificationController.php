<?php
namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\Society;
use App\Models\User;
use App\Notifications\AdminNotification;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
// use Illuminate\Support\Facades\Response;
use Gate;
use Symfony\Component\HttpFoundation\Response;  
use App\Traits\HelperTrait;
use Session;

class CustomNotificationController extends Controller
{
    use HelperTrait;
    public function getNotifications(){
        
        abort_if(Gate::denies('view-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->get(['id','code','name']);
            $notifications = CustomNotification::whereIn('society_id', $admin_soctities)->with('society:id,code,name')->latest('id')->get();
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
            $notifications = CustomNotification::where('society_id', $user_detail->society_id)->with('society:id,code,name')->latest('id')->get();
        }else{
            $societies = Society::get(['id','code','name']);
            $notifications = CustomNotification::with('society:id,code,name')->latest('id')->get();
        }
        return view('notifications.adminnotification.index', compact('notifications','societies'));
 	}

 	public function createadminNotification()
 	{
        abort_if(Gate::denies('create-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

 		$societies = Society::with('sectors','vendors')->get();
 		
 		if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors','vendors')->get();
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors','vendors')->get();
        }else{
            $societies = Society::with('sectors','vendors')->get();
        }
        $custom = new CustomNotification();
        return view('notifications.adminnotification.create', compact('societies','custom'));
 	}
 	
 	public function edit($id)
 	{
        abort_if(Gate::denies('update-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

 		$societies = Society::with('sectors','vendors')->get();
 		if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $societies = Society::whereIn('id', $admin_soctities)->with('sectors','vendors')->get();
        }else if($user_detail->user_level_id > 2){
            $societies = Society::where('id', $user_detail->society_id)->with('sectors','vendors')->get();
        }else{
            $societies = Society::with('sectors','vendors')->get();
        }
        // dd($societies->toArray());
        $custom = CustomNotification::find($id);
        return view('notifications.adminnotification.create', compact('societies','custom'));
 	}

    public function show($id){
        abort_if(Gate::denies('view-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $custom = CustomNotification::find($id);
        if($custom !=''){
          $notifications = DatabaseNotification::where('type','App\Notifications\AdminNotification')->where('data->custom_notification_id', $id)->with('notifiable')->orderBy('created_at','DESC')->get();
          // dd($notifications->toArray());
          return view('notifications.adminnotification.notification-detail', compact('custom','notifications'));
        }else{
          Session::flash('notify', ['type'=> 'danger','message' => 'No Data Found']);
          return back();
        }
    }
    
    public function destroy($id)
    {
        abort_if(Gate::denies('delete-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $custom = CustomNotification::find($id);
        if($custom !=''){
            $message = 'No Notification Found Against this id';
            $notification = DB::table('notifications')->where('type', 'App\Notifications\AdminNotification')->where('data->custom_notification_id', $id)->delete();
            // $complaint_referers = ComplaintRefer::where('complaint_id', $id)->get();
            // if($complaint_referers !=''){
            //     foreach($complaint_referers as $refer){
            //         $refer->delete();
            //     }
            // }
            $custom->delete();
        }
        Session::flash('notify', ['type'=>'danger','message' => 'Custom Notification Deleted Successfully']);
        return back();
    }
}

