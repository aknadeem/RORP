<?php

namespace App\Http\Controllers\TwoFourSeven;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentDataRequest;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use App\Models\TwoFourSeven;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class TwoFourSevenController extends Controller
{
    use HelperTrait;

    public function index()
    {
        $two4sevens = TwoFourSeven::get();
        $message = "No Data Found";

        // dd($two4sevens->toArray());

        $counts = $two4sevens->count();

        if($counts > 0){
            $message = "Success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){

            return view('twofourseven.index', compact('two4sevens'));
        }

        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'two4sevens' => $two4sevens
        ], 201);

    }

    public function create()
    {
        $twoFourSeven = new TwoFourSeven();

        return view('twofourseven.create', compact('twoFourSeven'));
    }

    public function store(Request $request)
    {
        $TwoFourSeven = TwoFourSeven::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return redirect()->route('twofour.index');
        }else{
            return response()->json([
                'message' => 'yes',
                'error' => 'no',
                'TwoFourSeven' => $TwoFourSeven
            ], 201);
        }
    }

    public function storeUpdateImage(Request $request, $id)
    {
        $loc = 'uploads/user';
        // dd("hello");

        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        $residentdata = '';

        $is_int = is_numeric($id);

        if($is_int){
            $residentdata = TwoFourSeven::find($id);
            if($residentdata == '') {
                $message = 'No data Found';
            }else{

                $message = 'Resident successfully updated';
                $new_name = time().$request->image->getClientOriginalName();
                $file = $request->image->move($loc,$new_name);
                // return $new_name;

               $residentdata->image = $new_name;
               $residentdata->save();
            }
        }else{
            $message = 'Id Must be Integer';
        }
       return response()->json([
            'message' => $$message,
            'residentdata' => $residentdata
        ], 201);

    }

    public function show($id) {

        $two4seven = '';
        $is_int = is_numeric($id);

        if($is_int){
            $two4seven = TwoFourSeven::find($id);
            $message = "no";

            if($two4seven != ''){
                $message = "yes";
            }
        }else{
            $message = "Id must be integer";
        }
        
        return response()->json([
            'message' => $message,
            'two4seven' => $two4seven
        ], 201);
    }

    public function edit($id)
    {

        $twoFourSeven = TwoFourSeven::find($id);

        return view('twofourseven.create', compact('twoFourSeven'));
    }

    public function update(Request $req, $id)
    {
        // dd($request->toArray());
        // $userId = \Auth::user()->id;

        // return response()->json([
        //     'residentdata' => $id
        // ], 201);
        $TwoFourSeven = TwoFourSeven::find($id);

        

        if($TwoFourSeven == '') {
            $message = 'No data Found';
        }else{

            $message = 'Resident successfully updated';
            $TwoFourSeven = $TwoFourSeven->update([
                'title' => $req->title,
                'description' => $req->description,

            ]);

        }

        return response()->json([
            'message' => $message,
            'TwoFourSeven' => $TwoFourSeven
        ], 201);
        
    }

    public function destroy($id)
    {
        $resident = ResidentData::find($id);
        $message = 'Resident Deleted Successfully';

        if($resident == '') {
            $message = 'No Data Found';
        }else{
            $resident->delete(); 
        }

        return response()->json([
                'message' => $message,
                'resident' => $resident
            ], 201); 
    }
}