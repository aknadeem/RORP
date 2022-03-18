<?php

namespace App\Http\Controllers\SocietyManagement;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Province;
use App\Models\Society;
use App\Models\SocietyBlock;
use App\Models\SocietySector;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Session;

class SocietyBlockController extends Controller
{
    use HelperTrait;

    public function index(){

        $societyblocks = SocietyBlock::get();
        // dd($societyblocks->toArray());
        return view('societymanagement.block.index', compact('societyblocks'));
    }

    public function create(){
        $societyblock = new SocietyBlock();
        $sectors = SocietySector::get();
        $socites = Society::get();
        return view('societymanagement.block.create', compact('societyblock','sectors','socites'));
    }

    public function store(Request $request){        
        $userId = \Auth::user()->id;

        // dd($request->toArray()); 

        $this->validate($request,[
            'block_name' => 'required|string|min:3',
            'society_id' => 'required',
            'society_sector_id' => 'required',
        ]);

        $slug = $this->getSlug($request->block_name);

        $society = SocietyBlock::create([
            'block_name' => $request->block_name,
            'slug' => $slug,
            'society_id' => $request->society_id,
            'society_sector_id' => $request->society_sector_id,
            'addedby' => $userId,
        ]);

        if($request->from_level || $request->from_user){
            return redirect()->back();

        }else{

            Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);

            return redirect()->route('societyblocks.index');
        }  
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $societyblock = SocietyBlock::with('sector')->find($id);

        // dd($societyblock->toArray());

        $sectors = SocietySector::get();
        $socites = Society::get();
        return view('societymanagement.block.create', compact('societyblock','sectors','socites'));
    }

    public function update(Request $request, $id) {

        $userId = \Auth::user()->id;

        $this->validate($request,[
            'block_name' => 'required|string|min:3',
            'society_id' => 'required',
            'society_sector_id' => 'required',
        ]);



        $slug = $this->getSlug($request->block_name);
    
        $societyblock = SocietyBlock::find($id)->update([
            'block_name' => $request->block_name,
            'slug' => $slug,
            'society_id' => $request->society_id,
            'society_sector_id' => $request->society_sector_id,
            'updatedby' => $userId,
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        
        return redirect()->route('societyblocks.index');
    }

    public function destroy($id) {

        $societyblock = SocietyBlock::find($id);
        $societyblock->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        return redirect()->back();
    }


    public function addSocietySector(Request $request)
    {
        $userId = \Auth::user()->id;
        // dd($request->toArray());
        $this->validate($request,[
            'society_id' => 'required',
            'sector_name' => 'required',
        ]);

        $slug = $this->getSlug($request->name);
        $societySector = SocietySector::create([
            'society_id' => $request->society_id,
            'slug' => $slug,
            'sector_name' => $request->sector_name,
            'addedby' => $userId,
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'New Sector Added successfully']);
        return redirect()->back();
    }

}

