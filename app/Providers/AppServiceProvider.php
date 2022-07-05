<?php

namespace App\Providers;

use DB;
use URL;
use App\Models\User;
use App\Models\Complaint;
use App\Traits\HelperTrait;
use App\Models\ComplaintLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Notifications\ComplaintNotification;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    use HelperTrait;
    public function register()
    {
        //
    }
    public function boot()
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        $current_day = today();
        if (Schema::hasTable('complaints')) {
            $complaints = Complaint::where('expire_time','<', $current_day)->where('is_expired', 0)->orderBy('id','DESC')->get();
            if($complaints !=''){
                foreach($complaints as $cp){
                    $cp->is_expired = 1;
                    $cp->save();
                    $cp_log = ComplaintLog::create([
                        'complaint_id' => $cp->id,
                        'status'=> $cp->complaint_status,
                        'comments' => 'Turnaround time(TAT) Expired',
                        'addedby' => 1,
                    ]);
                    
                    $hod_id = $cp->dep_hod_id ?? '';
                    $manager_id = $cp->sub_manager_id ?? '';
                    
                    $details = [
                        'title' => 'Turnaround time(TAT) Expired',
                        'by' => 'Automated system',
                        'complaint_id' => $cp->id,
                    ];
                    $users = User::find([$hod_id, $manager_id]);
                    $admin_socs = DB::table('socities_admins')->where('society_id', $cp->society_id)->get();
                    
                    if($admin_socs !=''){
                        foreach($admin_socs as $soc){
                            $admin_users = User::where('id', $soc->user_id);
                            Notification::send($admin_users, new ComplaintNotification($details));
                        }
                    }
                    Notification::send($users, new ComplaintNotification($details));
                }
            }
        }
    }
}
