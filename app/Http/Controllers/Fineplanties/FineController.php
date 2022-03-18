<?php

namespace App\Http\Controllers\Fineplanties;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Fine;
use App\Models\FinePayment;
use App\Models\ImposedFine;
use App\Models\Service;
use App\Models\Society;
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


class FineController extends Controller
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
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id < 2){
            $residents = User::where('user_level_id','>',5)->get();
            $fines = Fine::with('society:id,code,name','imposed')->get();
            $societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $residents = User::whereIn('society_id', $this->adminSocieties())->where('user_level_id','>',5)->get();
            $fines = Fine::whereIn('society_id', $this->adminSocieties())->with('society:id,code,name','imposed')->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $residents = User::where('society_id', $user_detail->society_id)->where('user_level_id','>',5)->get();
            $fines = Fine::where('society_id', $user_detail->society_id)->with('society:id,code,name','imposed')->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        $message = "No Data Found";
        $counts = $fines->count();
        if($counts > 0){
            $message = "success";
        }
        if($this->webLogUser() !=''){
            return view('fines.index', compact('fines','residents','societies'));
        }
        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'fines' => $fines
        ], 200);
    }

    public function create()
    {
        // HTTP_NOT_FOUND
        abort_if(Gate::denies('create-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        
        if($user_detail->user_level_id == 1){
            $societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $admin_soc = $this->adminSocieties();
            $societies = Society::whereIn('id',$admin_soc)->get(['id','code','name',]);
        }elseif($user_detail->user_level_id == 6 OR $user_detail->user_level_id == 7){
            $societies = Society::where('id',$user_detail->society_id)->get(['id','code','name',]);
        }else{
            $societies = Society::where('id',$user_detail->society_id)->get(['id','code','name']);
        }
        
        $fine = new Fine();
        return view('fines.create', compact('fine','societies'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $this->validate($request, [
            'society_id' => 'required|integer',
            'title' => 'required|string',
            'fine_amount' => 'required|string',
            'description' => 'nullable',
        ]);
        $fine = Fine::create([
            'society_id' => $request->society_id,
            'title' => $request->title,
            'fine_amount' => $request->fine_amount,
            'description' => $request->description, 
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('fines.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $fine = '';
        $is_int = is_numeric($id);
        if($is_int){
            $fine = Fine::with('society:id,code,name','imposed.fine','imposed.fineby','imposed.user')->find($id);
            $message = 'No Data Found';

            if($fine != ''){
                $message = 'success';
            }
        }else{
            $message = 'Id must be integer';
        }
        if($this->webLogUser() !=''){
            return view('fines.fine_detail', compact('fine'));
        }else{
            return response()->json([
                'message' => $message,
                'fine' => $fine
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
            $societies = Society::get(['id','code','name']);
        }elseif($user_detail->user_level_id == 2){
            $users = User::whereIn('society_id', $this->adminSocieties())->with('userlevel.permissions','permissions','society')->where('user_level_id','>',5)->get();
            $societies = Society::whereIn('id', $this->adminSocieties())->get(['id','code','name']);
        }else{
            $users = User::where('society_id', $user_detail->society_id)->with('userlevel.permissions','permissions','society')->where('user_level_id','>',5)->get();
            $societies = Society::where('id', $user_detail->society_id)->get(['id','code','name']);
        }
        $fine = Fine::with('society:id,code,name')->find($id);
        return view('fines.create', compact('fine','users','societies'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-fine-penalties'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $this->validate($request, [
            'title' => 'required|string',
            'fine_amount' => 'required',
            'society_id' => 'required|integer',
        ]);
        
        $fine = Fine::find($id)->update([
            'title' => $request->title,
            'society_id' => $request->society_id,
            'fine_amount' => $request->fine_amount,
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
        return back();
    }

    public function getUserFineApi($id)
    {
        $imposed_fines = ImposedFine::where('user_id', $id)->with('user','fineby','fine')->get();
        $count = $imposed_fines->count();
        $message = 'no';
        if($count > 0){
            $message = 'yes';
        }
        return response()->json([
            'message' => $message,
            'count' => $count,
            'imposed_fines' => $imposed_fines
        ], 200);
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
        $message = '';
        $type='success';
        if ($validator->fails()) {

            $message = 'Error! Payment Not Saved! Some thing went wrong ';
            $type= 'warning';
            // Session::flash('notify', ['type'=>'danger','message' => 'Error! Some thing went wrong']); 
            // return back(); 
        }else if($request->paid_amount != $request->invoice_price){
            $message = 'Paid Amount should be equal to Fine Price:'.$request->invoice_price;
            $type= 'warning';
        }else{
            $fine_payment = FinePayment::create([
                'fine_id' => $request->fine_id,
                'imposed_fine_id' => $request->imposed_id,
                'user_id' => $request->user_id,
                'paid_amount' => $request->paid_amount,
                'paid_date' => $request->paid_date,
                'remarks' => $request->remarks,
                'addedby' => $user_detail->id,
            ]);
            if($fine_payment){
                $impose_fine =ImposedFine::find($request->imposed_id)->update([
                    'fine_status' => 'paid',
                    'updatedby' => $user_detail->id,
                ]);
            }
            $message = 'Payment Added Successfully!';
            $type= 'success';
        }

        Session::flash('notify', ['type'=> $type,'message' => $message]); 
        return back(); 
    }
}


