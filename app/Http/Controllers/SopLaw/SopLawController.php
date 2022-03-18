<?php

namespace App\Http\Controllers\SopLaw;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use App\Models\SopLaw;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;
use Auth;


class SopLawController extends Controller
{
    use HelperTrait;

    public function index(){

        // dd($hods->toArray());
        $sops = SopLaw::get();

        $message = "No Data Found";
        $counts = $sops->count();
        if($counts > 0){
            $message = "yes";
        }

        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){

           return view('soplaw.index', compact('sops'));
       }else{
            return response()->json([
                'message' => $message,
                'counts' => $counts,
                'sops' => $sops
            ], 201);
       } 
    }

    public function create(){
        $sop = new SopLaw();
        return view('soplaw.create', compact('sop'));
    }

    public function store(Request $request){    
        $userId = \Auth::user()->id;
        // dd($request->toArray());
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string',
        ]);

        $slug = $this->getSlug($request->title);

        $sop = SopLaw::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('sops.index');
        
    }

    public function show($id) {
        $sop = '';
        $is_int = is_numeric($id);

        if($is_int){
            $sop = SopLaw::find($id);
            $message = "no";

            if($sop != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        
        return response()->json([
            'message' => $message,
            'sop' => $sop
        ], 201);

    }

    public function edit($id) {
        $sop = SopLaw::find($id);
        return view('soplaw.create', compact('sop'));
    }

    public function update(Request $request, $id) {
        // dd($request->toArray());
        
        $userId = \Auth::user()->id;
        //dd($request);
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'description' => 'string',
        ]);

        $sop = SopLaw::find($id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Data updated successfully']);
        return redirect()->route('sops.index');
    }


    public function destroy($id) {

        // dd($id);
        $sop = SopLaw::findOrFail($id);
        $sop->delete();
        Session::flash('notify', ['type'=>'danger','message' => 'Data Deleted successfully']);
        // return redirect()->route('users.index');
        return redirect()->back();
    }
}