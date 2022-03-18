<?php

namespace App\Http\Controllers\UserManagement;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SubModule;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Session;


class SubModuleController extends Controller
{
	use HelperTrait;

    public function index(){
        $submodules = SubModule::with('module')->get();

        // dd($submodules->toArray());
        return view('societymanagement.module.submodules.index', compact('submodules'));
    }

    public function create(){
        $submodule = new SubModule();
        $modules = Module::get();
        return view('societymanagement.module.submodules.create', compact('submodule', 'modules'));
    }

    public function store(Request $request){
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        // dd($request);
        $this->validate($request,[
            'module_id' => 'required|integer',
            'title' => 'required|string|min:3|unique:sub_modules',
        ]);

		$slug = $this->getSlug($request->title);

		// dd($slug);

        $submodule = SubModule::create([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'slug' => $slug,
            'addedby' => $userId,

        ]);

        if($request->from_group || $request->from_user){
            // echo $SubModule->id;
            return redirect()->back();
        }else{

            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            return redirect()->route('submodules.index');
        }
        
    }

    public function show($id) {
        //
    }

    public function edit($id) {
    	$modules = Module::get();
        $submodule = SubModule::find($id);
        return view('societymanagement.module.submodules.create', compact('submodule', 'modules'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'module_id' => 'required|integer',
            'title' => 'required|string|min:3|unique:sub_modules,title,'.$id,
        ]);
    
        $submodule = SubModule::find($id)->update([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'title' => $request->title,
            'updatedby' => $userId,
        ]);

        // User::find($id)->update($data);

        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);

        // return redirect()->back();
        return redirect()->route('submodules.index');
    }


    public function destroy($id) {

        $submodule = SubModule::find($id);
        $submodule->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }
}
