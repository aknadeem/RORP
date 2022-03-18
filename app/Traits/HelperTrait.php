<?php 
namespace App\Traits;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

trait HelperTrait {

    public function getSlug($title)
    {
        $slug = Str::of($title)->slug('-');
        return $slug;
    }

    public function currentDateTime()
    {
        $today = Carbon::now();
        return $today->toDateTimeString();
    }
    
    public function today()
    {
        $today = Carbon::now();
        return $today;
    }

    public function currentDate()
    {
        return Carbon::now()->format('Y-m-d');
    }

    public function webLogUser()
    {   
      $log_user = Auth::guard('web')->user();
      if($log_user){
          return $log_user;
      }
    }

    public function apiLogUser()
    {   
      $log_user = Auth::guard('api')->user();
      if($log_user){
          return $log_user;
      }
    }


    public function adminSocieties()
    { 
      if($this->webLogUser() !=''){
        $user_detail = $this->webLogUser();
      }else{
        $user_detail = $this->apiLogUser();
      }
      $admin_societies = array();

      if($user_detail->user_level_id == 2){
        foreach ($user_detail->societies as $key => $value) {
           array_push($admin_societies, $value->id);
        }
        return $admin_societies;
      }
    }
    
    public function hodDepartments()
    { 
        if($this->webLogUser() !=''){
          $user_detail = $this->webLogUser();
        }else{
          $user_detail = $this->apiLogUser();
        }
        $hod_departments = array();
        if($user_detail->user_level_id == 3){
          foreach ($user_detail->departments as $key => $value) {
            array_push($hod_departments, $value->department_id);
          }
          return $hod_departments;
        }
    }
    public function managerSubDepartments()
    { 
        if($this->webLogUser() !=''){
          $user_detail = $this->webLogUser();
        }else{
          $user_detail = $this->apiLogUser();
        }
        $manager_departments = array();
        if($user_detail->user_level_id == 4){
          foreach ($user_detail->subdepartments as $key => $value) {
            array_push($manager_departments, $value->sub_department_id);
          }
          return $manager_departments;
        }
    }
    
    public function supervisorDepartments()
    { 
        if($this->webLogUser() !=''){
          $user_detail = $this->webLogUser();
        }else{
          $user_detail = $this->apiLogUser();
        }
        $sp_visors_subdepartments = array();
        if($user_detail->user_level_id == 5){
           $sp_visors_subdepartments = array();
            if($user_detail->supervisor_subdepartments->count() > 0){
              foreach ($user_detail->supervisor_subdepartments as $key => $value) {
                array_push($sp_visors_subdepartments, $value->sub_department_id);
              }
            }
          return $sp_visors_subdepartments;
        }
    }


    public function sendFCM($title, $mess, $id) {
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array (
					'registration_ids' => $id,
					'notification' => array (
							"body" => $mess,
							"title" => $title,
					'vibrate' => 1,
					'sound'	=> 'default',		                
					"icon" => "myicon"
					)
			);
			$fields = json_encode ( $fields );
			$headers = array (
					'Authorization:key=AAAAUvPtpPM:APA91bH-3kOky34BBAMt03GR0v14wSYV9CXP2HBbcFS3qgS4BrOqQOQyUdfJRXiex0ZSr2vzVsUa20rRdaAExXZ61BqYTeynwxQDxzgGOAJ7n3UGwpujBddkR_DRBsVk0GeMKH_00JgQ',
					'Content-Type: application/json'
			);
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, true );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
			
			$result = curl_exec ( $ch );
			curl_close ($ch);		
			return $result;
		}
}
?>