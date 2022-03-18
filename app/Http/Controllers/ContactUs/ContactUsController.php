<?php

namespace App\Http\Controllers\ContactUs;

use App\Http\Controllers\Controller;
use App\Models\ResidentData;
use App\Models\Society;
use App\Models\SocietySector;
use Auth;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use App\Http\Requests\ResidentDataRequest;

class ContactUsController extends Controller
{
    use HelperTrait;

    

    public function index()
    {
        $residents = ResidentData::get();
        $message = "No Data Found";

        $counts = $residents->count();

        // dd($residents->toArray());


        if($counts > 0){
            $message = "Success";
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'residents' => $residents
        ], 201);

        // return view('residentmanagement.residentdata.index', compact('residents'));


    }

    public function create()
    {
        $resident = new ResidentData();
        $societies = Society::with('sectors:id,sector_name,society_id',)->get(['id','name']);
        $sectors = SocietySector::get(['id','sector_name','society_id']);

        // dd($sectors->toArray());

        return view('residentmanagement.residentdata.create', compact('resident','societies','sectors'));
    }

    public function store(ResidentDataRequest $req)
    {
        $residentdata = ResidentData::create([
            'type' => $req->type,
            'name' => $req->name,
            'father_name' => $req->name,
            'cnic' => $req->cnic,
            'landlord_name' => $req->landlord_name,
            'mobile_number' => $req->mobile_number,
            'emergency_contact' => $req->emergency_contact,
            'email' => $req->email,
            'occuptaion' => $req->occuptaion,
            'gender' => $req->gender,
            'martial_status' => $req->martial_status,
            'business_number' => $req->business_number,
            'society_id' => $req->society_id,
            'society_sector_id' => $req->society_sector_id,
            'address' => $req->address,
            'previous_address' => $req->previous_address,
            'business_address' => $req->business_address,
            'mail_address' => $req->mail_address,
        ]);

        return response()->json([
            'message' => 'Resident successfully registered',
            'residentdata' => $residentdata
        ], 201);
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
            $residentdata = ResidentData::find($id);
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

        $residentdata = '';

        $is_int = is_numeric($id);

        if($is_int){
            $residentdata = ResidentData::find($id);
            $message = "No Data Found";

            if($residentdata != ''){
                $message = "Success";
            }
        }else{
            $message = "Id must bE integer";
        }
        
        return response()->json([
            'message' => $message,
            'residentdata' => $residentdata
        ], 201);
    }

    public function edit($id)
    {

        $resident = ResidentData::find($id);
        $societies = Society::with('sectors:id,sector_name,society_id',)->get(['id','name']);
        $sectors = SocietySector::get(['id','sector_name','society_id']);

        // dd($sectors->toArray());

        return view('residentmanagement.residentdata.create', compact('resident','societies','sectors'));
    }

    public function update(ResidentDataRequest $req, $id)
    {
        // dd($request->toArray());
        // $userId = \Auth::user()->id;

        // return response()->json([
        //     'residentdata' => $id
        // ], 201);
        $residentdata = ResidentData::find($id);

        

        if($residentdata == '') {
            $message = 'No data Found';
        }else{

            $message = 'Resident successfully updated';
            $residentdata = $residentdata->update([
                'type' => $req->type,
                'name' => $req->name,
                'father_name' => $req->name,
                'cnic' => $req->cnic,
                'landlord_name' => $req->landlord_name,
                'mobile_number' => $req->mobile_number,
                'emergency_contact' => $req->emergency_contact,
                'email' => $req->email,
                'occuptaion' => $req->occuptaion,
                'gender' => $req->gender,
                'martial_status' => $req->martial_status,
                'business_number' => $req->business_number,
                'society_id' => $req->society_id,
                'society_sector_id' => $req->society_sector_id,
                'address' => $req->address,
                'previous_address' => $req->previous_address,
                'business_address' => $req->business_address,
                'mail_address' => $req->mail_address,

            ]);
        }

        return response()->json([
            'message' => $message,
            'residentdata' => $residentdata
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