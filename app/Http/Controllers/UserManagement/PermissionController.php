<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Traits\HelperTrait;
use Session;

class PermissionController extends Controller
{
    use HelperTrait;

    public function index(){
        $permissions = Permission::with('userlevels')->get();

        // dd($permissions->toArray());
        return view('usermanagement.permission.index', compact('permissions'));
    }

    public function create(){
        $permission = new Permission();
        $modules = Module::get();

        // dd($modules->toArray());
        return view('usermanagement.permission.create', compact('permission','modules'));
    }

    public function store(Request $request){        
        $userId = \Auth::user()->id;
        
        $this->validate($request,[
            'title' => 'required',
            'module_id' => 'required',
        ]);

        // $permission_array = array($request->title);

        // dd($request->toArray());

        $module = Module::find($request->module_id);

        if($module !=''){
            if ($request->title != '') {
                foreach ($request->title as $key => $title) {
                    $slug = $this->getSlug($title);
                    $permission_slug = $slug.'-'.$module->slug;

                    $permission = Permission::updateOrCreate(['slug' => $permission_slug ], [ 
                        'title' => $title,
                        'module_id' => $request->module_id,
                        'code_name' => $module->slug,
                        'addedby' => $userId,
                    ]);
                }
                $type = 'success';
                $message = 'Data Created Successfully';
            }else{
                $type = 'danger';
                $message = 'Permission Title is required';
            }
        }else{
            $type = 'danger';
            $message = 'No Module Found';
        }


        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('permissions.index');

        

        // $array = array_unique($array);

        
        
        // dd($module->toArray());

        // if($module != ''){
        //     $ifExists = Permission::where('slug', $permission_slug)->get(['id','slug']);
        //     if($ifExists->count() > 0) {
        //         $type = 'danger';
        //         $message = 'The Permission has already been taken. Add New!';
        //     } else {
        //         $permission = Permission::create([
        //             'title' => $request->title,
        //             'slug' => $permission_slug,
        //             'module_id' => $request->module_id,
        //             'code_name' => $module->slug,
        //             'addedby' => $userId,
        //         ]);
        //         if($request->from_level || $request->from_user){
        //             return redirect()->back();
        //         } else {
        //             $type = 'success';
        //             $message = 'Permission Created Successfully';
        //         }
        //     }
        // }else{
        //     $type = 'danger';
        //     $message = 'Before creation of permission Select module first!';
        // }
        // Session::flash('notify', ['type'=> $type,'message' => $message]);
        // return redirect()->route('permissions.index');
        
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $permission = Permission::find($id);
        $modules = Module::get();
        return view('usermanagement.permission.create', compact('permission','modules'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'bail|required|string|min:3|',
            'module_id' => 'required',
        ]);

        $module = Module::find($request->module_id);
        $slug = $this->getSlug($request->title);
        $permission_slug = $slug.'-'.$module->slug;

        $ifExists = Permission::where('id','!=', $id)->where('slug', $permission_slug)->get(['id','slug']);
        if($ifExists->count() > 0) {
            $type = 'danger';
            $message = 'The Permission has already been taken. Add New!';
        }else{

            $permission = Permission::find($id)->update([
                    'title' => $request->title,
                    'slug' => $permission_slug,
                    'module_id' => $request->module_id,
                    'code_name' => $module->slug,
                    'updatedby' => $userId,
            ]);
            $type = 'success';
            $message = 'The Permission updated successfully!';

        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('permissions.index');
    }


    public function destroy($id) {

        // dd($request->toArray());

        $permission = Permission::find($id);
        $permission->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }
}
