<?php

namespace App\Http\Controllers\Fineplanties;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Fine;
use App\Models\FinePayment;
use App\Models\ImposedFine;
use App\Models\Service;
use App\Models\SubDepartment;
use App\Models\Tax;
use App\Models\TaxDetail;
use App\Models\User;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use Gate;
use Symfony\Component\HttpFoundation\Response;  


class ImposedFineController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $impose_fines = ImposedFine::whereIn('society_id', $admin_soctities)->with('fine','user:id,name,unique_id,user_level_id','fineby:id,name,unique_id,user_level_id')->get();
        }else if($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6){
            $impose_fines = ImposedFine::where('society_id', $user_detail->society_id)->with('fine','user:id,name,unique_id,user_level_id','fineby:id,name,unique_id,user_level_id')->get();
        }else if($user_detail->user_level_id >= 6){
            $impose_fines = ImposedFine::where('user_id', $user_detail->id)->with('fine','user:id,name,unique_id,user_level_id','fineby:id,name,unique_id,user_level_id')->get();
        }else{
           $impose_fines = ImposedFine::with('fine','user:id,name,unique_id,user_level_id','fineby:id,name,unique_id,user_level_id')->get();
        }
        if($this->webLogUser() !=''){
            return view('invoices.imposedfine.index', compact('impose_fines'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $impose_fines->count(),
            'impose_fines' => $impose_fines
        ], 200);
    }

    public function create()
    {
        abort_if(Gate::denies('create-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $fine = new Fine();
        return view('fines.create', compact('fine'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $validator = Validator::make($request->all(), [
            'fine_id' => 'bail|required|integer',
            'fine_date' => 'bail|required|date',
            'due_date' => 'bail|required|date|after_or_equal:fine_date',
        ]);

        if ($validator->fails()) {
            Session::flash('notify', ['type'=>'warning', 'delay'=>'2000','message' => $validator->errors()]); 
            return back(); 
        }

        foreach ($request->residents as $key=>$value) {
        	$imposedfine = ImposedFine::create([
	            'fine_id' => $request->fine_id,
	            'user_id' => $value,
	            'fine_by' => $user_detail->id,
	            'fine_date' => $request->fine_date,
	            'due_date' => $request->due_date,
	        ]);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Fine Imposed successfully']); 
        return back();
    }


    public function addPayment(Request $request)
    {
        abort_if(Gate::denies('add-payment-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $validator = Validator::make($request->all(), [
            'fine_id' => 'bail|required|integer',
            'paid_amount' => 'required',
            'paid_date' => 'bail|required|date|after_or_equal:'.today(),
            'remarks' => 'bail|required|string',
        ]);
        // dd($validator->errors());
        if ($validator->fails()) {
            Session::flash('notify', ['type'=>'danger','message' => 'Error! Some thing went wrong']); 
            return back(); 
        }
        $invoice_payment = FinePayment::create([
            'fine_id' => $request->fine_id,
            'paid_amount' => $request->paid_amount,
            'paid_date' => $request->paid_date,
            'remarks' => $request->remarks,
            'addedby' => $user_detail->id,
        ]);
        if($invoice_payment){
            $fine = Fine::find($request->fine_id)->update([
                'is_paid' => 1,
                'fine_status' => 'paid',
                'paid_amount' => $request->paid_amount,
                'updatedby' => $user_detail->id,
            ]);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Payment Added Successfully!']); 
        return back(); 

    }

    public function show($id)
    {
        abort_if(Gate::denies('view-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $imposed_fine = '';
        $is_int = is_numeric($id);
        if($is_int){
            $imposed_fine = ImposedFine::with('user','fineby','fine')->find($id);
            $message = 'No Data Found';

            if($imposed_fine != ''){
                $message = 'success';
            }
        }else{
            $message = 'Id must be integer';
        }

        if($this->webLogUser() !=''){
            return view('fines.fine-invoice', compact('imposed_fine'));
        }else{
            return response()->json([
                'message' => $message,
                'imposed_fine' => $imposed_fine
            ], 200);
        }
    }


    public function edit($id)
    {
        abort_if(Gate::denies('update-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id < 2){
            $users = User::with('userlevel.permissions','permissions','society')->where('user_level_id','>',5)->get();
        }elseif($user_detail->user_level_id == 2){
            $users = User::whereIn('society_id', $this->adminSocieties())->with('userlevel.permissions','permissions','society')->where('user_level_id','>',5)->get();
        }else{
            $users = User::where('society_id', $user_detail->society_id)->with('userlevel.permissions','permissions','society')->where('user_level_id','>',5)->get();
        }

        $fine = Fine::find($id);
        return view('fines.create', compact('fine','users'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $this->validate($request, [
            'title' => 'required|string',
            'fine_amount' => 'required|string',
            'fine_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:fine_date',
            'user_id' => 'required|integer',
        ]);

        $fine = Fine::find($id)->update([
            'title' => $request->title,
            'fine_amount' => $request->fine_amount,
            'fine_date' => $request->fine_date,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id,
            'fine_by' => $user_detail->id,
            'description' => $request->description, 
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
        return redirect()->route('fines.index');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $delete_fine = DB::table('fines')->delete($id);
        if($delete_fine){
            $message = 'Data Deleted successfully';
            $type = 'success';
        }else{
            $message = 'No Data Found Against this id';
            $type = 'danger';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->back();
    }

    public function getUserFineApi($id)
    {
        $fines = Fine::where('user_id', $id)->with('user','fineby')->get();
        $count = $fines->count();
        $message = 'No Data Found';
        if($count > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'count' => $count,
            'fines' => $fines
        ], 200);
    }
}


