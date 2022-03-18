<?php

namespace App\Http\Controllers\SocietyManagement;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Department;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Province;
use App\Models\Society;
use App\Models\SocietySector;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Session;
use Validator;

class SocietyController extends Controller
{
    use HelperTrait;

    public function index(){

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
            
            if($user_detail->user_level_id < 2){
                $societies = Society::with('sectors:id,sector_name,society_id')->get();
            }elseif($user_detail->user_level_id == 2){
                $societies = Society::whereIn('id', $this->adminSocieties())->with('sectors:id,sector_name,society_id')->get();
            }else{
                $societies = Society::where('id', $user_detail->society_id)->with('sectors:id,sector_name,society_id')->get();
            }
        
        }else{
            $societies = Society::with('sectors:id,sector_name,society_id')->get();
        }

        $message = "No Data Found";
        $counts = $societies->count();
        if($counts > 0){
            $message = "yes";
        }

        if($this->webLogUser() !=''){
           return view('societymanagement.index', compact('societies')); 
        }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'societies' => $societies
            ], 201);   
        }  
    }

    public function create(){
        $society = new Society();
        $provinces = Province::with('cities')->get();
        return view('societymanagement.create', compact('society','provinces'));
    }

    public function store(Request $request){        
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'code' => 'bail|required|string|min:4|max:4|unique:societies',
            'name' => 'bail|required|string|min:3',
            'country_id' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()->toArray()]);
        // }
        
        $society_slug = $this->getSlug($request->name);
        $society = Society::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $society_slug,
            'city_id' => $request->city_id,
            'country_id' => $request->country_id,
            'province_id' => $request->province_id,
            'address' => $request->address,
            'addedby' => $userId,
        ]);

        // $name = '';
        if ($request->sector_name != '') {
            foreach ($request->sector_name as $key => $sector_name) {
                // $name .= $value;
                $societySector = SocietySector::create([
                    'society_id' => $society->id,
                    'sector_name' => $sector_name,
                    'addedby' => $userId,
                ]);
            }
        }

        if ($request->department_name != '') {
            foreach ($request->department_name as $key => $department_name) {
                $slug = $this->getSlug($department_name);
                $department = Department::create([
                    'society_id' => $society->id,
                    'name' => $department_name,
                    'slug' => $slug,
                    'addedby' => $userId,
                ]);
            }
        }
        // return response()->json([
        //     'message' => "yes",
        //     'Society' => $society
        // ], 201);

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('societies.index');
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $society = Society::find($id);
        $provinces = Province::with('cities')->get();
        return view('societymanagement.create', compact('society','provinces'));
    }

    public function update(Request $request, $id) {

        $userId = \Auth::user()->id;

        $this->validate($request,[
            'code' => 'required|string|min:4|max:4|unique:societies,code,'.$id,
            'name' => 'required|string|min:3',
            'country_id' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
        ]);

        // dd($request->toArray());

        $slug = $this->getSlug($request->name);
    
        $permission = Society::find($id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $slug,
            'country_id' => $request->country_id,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'updatedby' => $userId,
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        
        return redirect()->route('societies.index');
    }

    public function destroy($id) {

        $society = Society::find($id);
        $society->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }


    public function addSocietySector(Request $request)
    {
        $userId = \Auth::user()->id;
        
        $this->validate($request,[
            'society_id' => 'required',
            'soc_name' => 'nullable',
            'sector_name' => 'required|string',
        ]);


        $societySector = SocietySector::create([
            'society_id' => $request->society_id,
            'sector_name' => $request->sector_name,
            'addedby' => $userId,
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'New Sector Added successfully']);
        return redirect()->back();
    }

    // get departments hods
    public function getSocietySectors($id) {
        $sectors = SocietySector::where('society_id', $id)->get();
        $message = "No Data Found";
        $counts = $sectors->count();
        if($counts > 0){
            $message = "yes";
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'sectors' => $sectors
        ], 200);
    }

}

