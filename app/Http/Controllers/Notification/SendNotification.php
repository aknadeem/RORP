<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\Society;
use App\Models\User;
use App\Notifications\AdminNotification;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Session;

class SendNotification extends Controller
{

  use HelperTrait;


  // mark all as read login user notification
  // public function getNotifications(){
  //   $notifications = DatabaseNotification::with('notifiable')->where('type','App\Notifications\AdminNotification')->get();

  //   // dd($notifications->toArray());

  //   return view('notifications.adminnotification.index', compact('notifications'));

 	// }

  // mark all as read login user notification
  public function MarkAsRead(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
  }

 	// Read Single notification as well multiple notification 
 	public function markRead(Request $request)
 	{
 		$web_user = Auth::guard('web')->user();

        if($web_user != ''){
          $user = $web_user;
        }else{
          $api_user = Auth::guard('api')->user();
          $user = $api_user;
  	    }
       $message = 'No Data Found';
	   $type = 'success';
        // Read Single notification as well multiple notification
      	if($user !=''){
      		$user->unreadNotifications->when($request->input('id'), function($q) use ($request){
    			return $q->where('id', $request->input('id'));
    		})->markAsRead();
    
    		  $message = 'success';
    		  $type = 'success';
      	}
    
      	if($web_user !=''){
      		Session::flash('notify', ['type'=> $type,'message' => $message]);
      		return back();
      		// dd('hello');
      	}else{
      		return response()->json([
            'message' => $message,
          ], 201);
      	}

 	}

 	public function createadminNotification()
 	{
    abort_if(Gate::denies('create-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

 		$societies = Society::with('sectors','vendors')->get();
    // dd($societies->toArray());
    return view('notifications.adminnotification.create', compact('societies'));
 	}

 	public function storeadminNotification(Request $request)
 	{
    abort_if(Gate::denies('create-notifications'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

    $this->validate($request,[
      'title' => 'required|string',
      'society_id' => 'required|integer',
      'sector_id' => 'required',
      // 'package_id' => 'nullbale',
    ]);

    $society_id = $request->society_id;
    $sector_id = $request->sector_id;
    //  take all residents or teneants having account belongs to this society and sectors
    $users = User::where('user_level_id','>',5)->where('society_id',$society_id)->whereIn('society_sector_id',$sector_id)->get();
    // dd($users->toArray());
    $message = 'No Residents Found';
    $type = 'danger';
    if(count($users) > 0){
      // check login from api or web
      $web_user = Auth::guard('web')->user();
      if($web_user != ''){
        $userId = $web_user->id;
        $user_name = $web_user->name;
      }else{
        $api_user = Auth::guard('api')->user();
        $userId = $api_user->id;
        $user_name = $api_user->name;
      }
      
      $attachment_name = '';
      $image_location = 'uploads/custom_notification';
      if($request->hasFile('attachment')){
        $attachment = $request->file('attachment');
        $extension = $request->file('attachment')->extension();
        $attachment_name = time().mt_rand(10,99).'.'.$extension;
        $file = $attachment->move($image_location,$attachment_name);
      }else{
        $attachment_name=''; 
      }

      $custom_notification_id = DB::table('custom_notifications')->insertGetId([
        'title' => $request->title, 
        'description' => $request->description,
        'attachment' => $attachment_name,
        'is_highlight' => $request->is_highlight,
        'society_id' => $society_id,
        'addedby' => $userId,
        'created_at' => $this->currentDateTime(),
      ]);

      $details=[
        'custom_notification_id' => $custom_notification_id,
        'sender_id' => $userId,
        'is_highlight' => $request->is_highlight,
        'sender_name' => $user_name,
      ];

      Notification::send($users, new AdminNotification($details));
      
      // Send Push Notificatio For Mobile Users
      if($users !=''){
        $tokens = array();
        foreach($users as $user){
          if($user->fcm_token !=''){
            $tokens[] = $user->fcm_token;
          }
        }
        $title_message = $request->title ?? 'Custom Notification';
        $this->sendFCM($title_message, $tokens); 
      }
      // End Send Push Notificatio For Mobile Users
      
      $message = 'Notification sends successfully';
      $type = 'success';
    }
    Session::flash('notify', ['type'=> $type,'message' => $message]);
    return redirect()->route('customNotifications');
 	}


 	public function notificationList($id)
 	{
    $notifications = '';
    $message = 'No data Found';
    //   $notifications = $user->unreadNotifications
    $is_int = is_numeric($id);
    // get all unread notification against the user id 
    // custom notification has many notifications
    if($is_int){
        $notifications = CustomNotification::join('notifications','notifications.data->custom_notification_id','=','custom_notifications.id')
        ->select('custom_notifications.id','custom_notifications.title','custom_notifications.description','custom_notifications.created_at','custom_notifications.updated_at','notifications.id AS nid')->where('type','App\Notifications\AdminNotification')
        ->where('read_at', null)->where('notifiable_id', $id)->get();
    }else{
      $message = "Id must be integer";
    }

    if($notifications->count() > 0){
      $message = 'success';
    }
    $web_user = Auth::guard('web')->user();

    return response()->json([
            'message' => $message,
            'notifications' => $notifications,
        ], 201);
 	}

}
