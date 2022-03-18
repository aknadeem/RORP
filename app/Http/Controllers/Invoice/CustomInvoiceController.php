<?php

namespace App\Http\Controllers\Invoice;

use Auth;
use Gate;
use Session;
use Validator;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Society;
use App\Models\ImposedFine;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\CustomInvoice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomInvoicePayment;
use Symfony\Component\HttpFoundation\Response;

class CustomInvoiceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $invoices = CustomInvoice::whereIn('society_id', $admin_soctities)->with('user.services')->get();
            $users = User::whereIn('society_id', $admin_soctities)->where('user_level_id','>',5)->get();
            $societies = Society::whereIn('id', $admin_soctities)->get();
        }else if($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6){
            $invoices = CustomInvoice::where('society_id', $user_detail->society_id)->with('user.services')->get();
            $users = User::where('society_id', $user_detail->society_id)->where('user_level_id','>',5)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
            
        }else if($user_detail->user_level_id >= 6){
            $invoices = CustomInvoice::where('user_id', $user_detail->id)->with('user.services')->get();
            $users = User::where('id', $user_detail->id)->where('user_level_id','>',5)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $invoices = CustomInvoice::with('user.services')->get();
            $users = User::where('user_level_id','>',5)->get();
            $societies = Society::get();
        }
        
        $message = "No Data Found";
        if(count($invoices) > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('invoices.custominvoice.index', compact('invoices','users','societies'));
        }
        return response()->json([
            'message' => $message,
            'invoices' => $invoices
        ], 201);
    }

    public function create()
    {
        abort_if(Gate::denies('create-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');
        $invoice = new CustomInvoice();
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $users = User::whereIn('society_id', $admin_soctities)->where('user_level_id','>',5)->get();
        }else if($user_detail->user_level_id > 2){
            $users = User::where('society_id', $user_detail->society_id)->where('user_level_id','>',5)->get();
        }else{
            $users = User::where('user_level_id','>',5)->get();
        }
        return view('invoices.custominvoice.create', compact('invoice','users'));
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('create-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $this->validate($request, [
            'title' => 'required|string',
            'price' => 'required|string',
            'due_date' => 'required|date|after_or_equal:'.today(),
            'user_id' => 'required|integer',
            'description' => 'string|nullable',
        ]);

        // dd($request->toArray()); 
        $fine = CustomInvoice::create([
            'title' => $request->title,
            'price' => $request->price,
            'final_price' => $request->price,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id,
            'description' => $request->description, 
        ]);

        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']); 
        return redirect()->route('custominvoice.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');
        $invoice = '';
        $is_int = is_numeric($id);
        if($is_int){
            $invoice = CustomInvoice::with('user')->find($id);
            $message = 'No Data Found';

            if($invoice != ''){
                $message = 'success';
            }
        }else{
            $message = 'Id must be integer';
        }

        if($this->webLogUser() !=''){
            return view('invoices.custominvoice.custom-invoice-detail', compact('invoice'));

        }else{
            return response()->json([
                'message' => $message,
                'invoice' => $invoice
            ], 201);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('update-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');
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

        $invoice = CustomInvoice::find($id);
        // return view('fines.create', compact('fine','users'));
        return view('invoices.custominvoice.create', compact('invoice','users'));
    }

    public function addPayment(Request $request)
    {

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        // dd($request->toArray());


        $validator = Validator::make($request->all(), [
            'custom_invoice_id' => 'bail|required|integer',
            'paid_amount' => 'required',
            'paid_date' => 'bail|required|date|after_or_equal:'.today(),
            'remarks' => 'bail|required|string',
        ]);

        // dd($validator->errors());

        if ($validator->fails()) {
            Session::flash('notify', ['type'=>'danger','message' => 'Error! Some thing went wrong']); 
            return back(); 
        }

        $invoice_payment = CustomInvoicePayment::create([
            'custom_invoice_id' => $request->custom_invoice_id,
            'paid_amount' => $request->paid_amount,
            'paid_date' => $request->paid_date,
            'remarks' => $request->remarks,
            'addedby' => $user_detail->id,
        ]);
        if($invoice_payment){
            $fine = CustomInvoice::find($request->custom_invoice_id)->update([
                'is_payed' => 1,
                'paid_amount' => $request->paid_amount,
                'updatedby' => $user_detail->id,
            ]);
        }
        Session::flash('notify', ['type'=>'success','message' => 'Payment Added Successfully!']); 
        return back(); 

    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $this->validate($request, [
            'title' => 'required|string',
            'price' => 'required|string',
            'due_date' => 'required|date|after_or_equal:'.today(),
            'user_id' => 'required|integer',
            'description' => 'string|nullable',
        ]);
        
        $fine = CustomInvoice::find($id)->update([
            'title' => $request->title,
            'price' => $request->price,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id,
            'description' => $request->description
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'Data Updated successfully']); 
        return redirect()->route('custominvoice.index');        
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('delete-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized ');
        $delete_fine = DB::table('custom_invoices')->delete($id);
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

    public function getUserInvoices($id)
    {
        $invoices = CustomInvoice::where('user_id', $id)->with('user.services')->get();
        $count = $invoices->count();
        $message = 'No Data Found';
        if($count > 0){
            $message = 'success';
        }
        return response()->json([
            'message' => $message,
            'count' => $count,
            'invoices' => $invoices
        ], 201);
    }

    public function getUserAllInvoices(){
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }

        $message = 'no';
        $service_invoices = Invoice::where('user_id', $user_detail->id)->with('invoicepayment','user:id,name,email,user_level_id', 'user.userlevel:id,title,slug')->get();

        $custom_invoices = CustomInvoice::where('user_id', $user_detail->id)->with('custominvoicepayment', 'user:id,name,email,user_level_id', 'user.userlevel:id,title,slug')->get();

        $fine_invoices = ImposedFine::where('user_id', $user_detail->id)->with('fine','finepayment','user:id,name,email,user_level_id', 'user.userlevel:id,title,slug')->get();

        if($service_invoices !='' || $custom_invoices !=''){
            $message = 'yes';
        }
        if($fine_invoices !=''){
            $message = 'yes';
        }

        dd($service_invoices->toArray());

        return response()->json([
            'message' => $message,
            'service_invoices' => $service_invoices,
            'custom_invoices' => $custom_invoices,
            'fine_invoices' => $fine_invoices
        ], 200);
    }
}