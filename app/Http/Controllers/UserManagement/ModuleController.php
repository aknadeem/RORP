<?php
namespace App\Http\Controllers\UserManagement;
use App\Http\Controllers\Controller;
use App\Models\Module;
use Session;
use Illuminate\Http\Request;
use App\Traits\HelperTrait;


class ModuleController extends Controller
{
	use HelperTrait;

    public function index(){

        // $web_user = Auth::guard('web')->user();

        // $admin_societies = array();
        // if($web_user->user_level_id == 2){
        //     foreach ($web_user->societies as $key => $value) {
        //        array_push($admin_societies, $value->id);
        //     }
        //     $societies = Society::whereIn('id', $admin_societies)->with('sectors:id,sector_name,society_id')->get();
        // }elseif($web_user->user_level_id > 2){
        //     $societies = Society::where('id', $web_user->society_id)->with('sectors:id,sector_name,society_id')->get();
        // }else{
        //     $societies = Society::with('sectors:id,sector_name,society_id')->get();
        // }

        $modules = Module::orderby('id','DESC')->get();

        // dd($modules->toArray());
        return view('societymanagement.module.index', compact('modules'));
    }

    public function create(){
        $module = new Module();
        return view('societymanagement.module.create', compact('module'));
    }

    public function store(Request $request){
        
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        // dd($request);
        $this->validate($request,[
            'title' => 'bail|required|string|min:3|unique:modules',
        ]);

		$slug = $this->getSlug($request->title);

		// dd($slug);
        $module = Module::create([
            'title' => $request->title,
            'slug' => $slug,
            'addedby' => $userId,

        ]);
        if($request->from_group || $request->from_user){
            // echo $Module->id;
            return redirect()->back();
        }else{

            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
            return redirect()->route('modules.index');
        }
        
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $module = Module::find($id);
        return view('societymanagement.module.create', compact('module'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'bail|required|string|min:3|unique:modules,title,'.$id,
        ]);
    
        $module = Module::find($id)->update([
            'title' => $request->title,
            'title' => $request->title,
            'updatedby' => $userId,
        ]);

        // User::find($id)->update($data);

        //Session::flash('success','user group created successfully');
        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);

        // return redirect()->back();
        return redirect()->route('modules.index');
    }


    public function destroy($id) {

        // dd($request->toArray());

        $module = Module::find($id);
        $module->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }
}
