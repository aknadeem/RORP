<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Traits\HelperTrait;
use Session;

class UserLevelController extends Controller
{
    use HelperTrait;

    public function index()
    {
        $user = \Auth::user();
        $userlevels = UserLevel::with('permissions')->get();
        $permissions = Permission::get();
        return view('usermanagement.userlevel.index', compact('userlevels','permissions'));
    }

    public function create()
    {
        $userlevel = new UserLevel();
        // $permissions = Permission::get();
        $permissions = Permission::get()->pluck('code_name', 'id');

        $new_permissions = Permission::with('module')->get();

        $new_permissions = $new_permissions->groupBy('code_name');

        // dd($new_permissions->toArray());

        $allmodules = Module::get();

        // dd($permissions->toArray());

        return view('usermanagement.userlevel.create', compact('userlevel','permissions','new_permissions','allmodules'));
    }

    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3|unique:user_levels',
        ]);
        $slug = $this->getSlug($request->title);
        //Check existence because userlevel is unique
        $ifExists = UserLevel::where('slug', $slug)->get(['id','slug']);
        if($ifExists->count() > 0) {
            $type = 'danger';
            $message = 'The User Level has already been taken. Add New!';
        }else{
           $userlevel = UserLevel::create([
                'title' => $request->title,
                'slug' => $slug,
                'addedby' => $userId

            ]);
            if($request->from_user){
                return redirect()->back();
            }
            if($request->permissions != ''){
                $userlevel->permissions()->sync($request->input('permissions', []));
            }

            $type = 'success';
            $message = 'User Level Created Successfully!';
        }
        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('userlevels.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id) {
        $userlevel = UserLevel::find($id);
        $new_permissions = Permission::with('module')->get();

        $new_permissions = $new_permissions->groupBy('code_name');

        $permissions = Permission::get()->pluck('code_name', 'id');

        // dd($new_permissions->toArray());

        $allmodules = Module::get();

        return view('usermanagement.userlevel.create', compact('userlevel','permissions','new_permissions','allmodules'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3|unique:user_levels,title,'.$id,
        ]);

        $slug = $this->getSlug($request->title);
        //Check existence because userlevel is unique
        $ifExists = UserLevel::where('id','!=', $id)->where('slug', $slug)->get(['id','slug']);
        if($ifExists->count() > 0) {
            $type = 'danger';
            $message = 'The User Level has already been taken. Add New!';
        }else{
            $userlevel = UserLevel::with('users')->find($id);
            $userlevel->update([
                'title' => $request->title,
                'slug' => $slug,
                'updatedby' => $userId,
            ]);
            $userlevel->permissions()->sync($request->input('permissions', []));

            $type = 'success';
            $message = 'The User Level updated successfully!';
        }

        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('userlevels.index');
    }

    public function destroy($id)
    {
        // dd($request->toArray());

        $userlevel = UserLevel::find($id);
        $userlevel->delete();

        // Detach a single role from the user...
        // $user->roles()->detach($roleId);

        // // Detach all roles from the user...
        // $user->roles()->detach();

        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }
}
