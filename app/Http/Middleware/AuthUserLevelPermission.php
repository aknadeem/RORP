<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class AuthUserLevelPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();

        if (!app()->runningInConsole() && $user) {
            $users = User::with('permissions')->get();
            $permissionsArray = [];

            foreach ($users as $auser) {
                foreach ($user->permissions as $permissions) {
                    $permissionsArray[$permissions->slug][] = $auser->id;
                }
            }
            // dd($user->pluck('id')->toArray());

                if($permissionsArray){
                    foreach ($permissionsArray as $slug => $users) {
                    Gate::define($slug, function (\App\Models\User $user) use ($users) {
                        return count(array_intersect($user->pluck('id')->toArray(), $users)) > 0;
                    });
                }
            }
        }
        return $next($request);

        // if (!app()->runningInConsole() && $user) {
        //     $userlevels = UserLevel::with('permissions')->get();
        //     $permissionsArray = [];

        //     foreach ($userlevels as $level) {
        //         foreach ($level->permissions as $permissions) {
        //             $permissionsArray[$permissions->title][] = $level->id;
        //         }
        //     }

        //     foreach ($permissionsArray as $title => $userlevels) {
        //         Gate::define($title, function (\App\Models\User $user) use ($userlevels) {
        //             return count(array_intersect($user->userlevel->pluck('id')->toArray(), $userlevels)) > 0;
        //         });
        //     }
        // }
        // return $next($request);
    }
}
