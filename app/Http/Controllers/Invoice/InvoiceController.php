<?php
namespace App\Http\Controllers\Invoice;

use Auth;
use Session;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Society;
use App\Models\ServiceType;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\DepartmentHod;
use App\Models\RequestService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Gate;
use Symfony\Component\HttpFoundation\Response;  

class InvoiceController extends Controller
{
    use HelperTrait;
    public function index()
    {
        // dd('hello');
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $invoices = Invoice::whereIn('society_id', $admin_soctities)->with('user.services','society')->orderBy('id','DESC')->get();
            $users = User::whereIn('society_id', $admin_soctities)->where('user_level_id','>',0)->get();
            $societies = Society::whereIn('id', $admin_soctities)->get();
        }else if($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6 ){
            $invoices = Invoice::where('society_id', $user_detail->society_id)->with('user.services','society')->orderBy('id','DESC')->get();
            $users = User::where('society_id', $user_detail->society_id)->where('user_level_id','>',0)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }elseif($user_detail->user_level_id >= 6){
            $invoices = Invoice::where('user_id', $user_detail->id)->with('user.services','society')->orderBy('id','DESC')->get();
            $users = User::where('id', $user_detail->id)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $invoices = Invoice::with('user.services','society')->orderBy('id','DESC')->get();
            $users = User::where('user_level_id','>',0)->get();
            $societies = Society::get();
        }
        $message = "No Data Found";
        if(count($invoices) > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('invoices.index', compact('invoices','users','societies'));
        }
        return response()->json([
            'message' => $message,
            'invoices' => $invoices
        ], 201);
    }
    
    public function getWithFilters($filter)
    {
        if($filter == 'paid'){
            $invoices = Invoice::where('is_payed', 1)->with('user.services','society')->orderBy('id','DESC')->get();
        }else if($filter == 'unpaid'){
            $invoices = Invoice::where('is_payed','!=',1)->with('user.services','society')->orderBy('id','DESC')->get();
        }else if($filter == 'overdue'){
            $invoices = Invoice::where([['is_payed','!=',1],['due_date', '<', today()]])->with('user.services','society')->orderBy('id','DESC')->get();
        }else{
            $invoices = Invoice::with('user.services','society')->orderBy('id','DESC')->get();
        }
        
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        if($user_detail->user_level_id == 2){
            // Admin Only Saw All His Societies data
            $admin_soctities = $this->adminSocieties();
            $invoices =  $invoices->whereIn('society_id', $admin_soctities);
            
            $users = User::whereIn('society_id', $admin_soctities)->where('user_level_id','>',5)->get();
            $societies = Society::whereIn('id', $admin_soctities)->get();
            
        }else if($user_detail->user_level_id > 2 && $user_detail->user_level_id < 6){
            $invoices =  $invoices->where('society_id', $user_detail->society_id);
            $users = User::where('society_id', $user_detail->society_id)->where('user_level_id','>',5)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else if($user_detail->user_level_id >= 6){
            $invoices =  $invoices->where('user_id', $user_detail->id);
            $users = User::where('id', $user_detail->id)->where('user_level_id','>',5)->get();
            $societies = Society::where('id', $user_detail->society_id)->get();
        }else{
            $invoices = $invoices;
            $users = User::where('user_level_id','>',5)->get();
            $societies = Society::get();
        }
        
        $message = "No Data Found";
        if(count($invoices) > 0){
            $message = "success";
        }
        $web_user_id = Auth::guard('web')->user();
        if($web_user_id){
            return view('invoices.filter-invoice', compact('invoices','users','societies','filter'));
        }
        return response()->json([
            'message' => $message,
            'invoices' => $invoices
        ], 201);
    }

    public function MonthlyInvoice($id) {
        // Get user Active Services
        if($this->webLogUser() !=''){
            $user_detail = $this->webLogUser();
        }else{
            $user_detail = $this->apiLogUser();
        }
        $user = User::where([['is_active', 1]])->has('services')->with(['society','userservices' => function($qry){
            return $qry->with('service','servicetype','package')->where('status', 1); 
        }])->find($id);
        // dd($user->toArray());
        if($user !=''){
            $total_price = $user->userservices->sum('final_price');
            return view('invoices.m_invoice', compact('user','total_price'));
        }else{
            Session::flash('notify', ['type'=> 'danger','message' => 'No Invoice Found']);
            return back();
        }
    }

    public function store(Request $request){
        abort_if(Gate::denies('create-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        // dd($request->toArray());

        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }

        // dd($request->toArray());

        $r_service = RequestService::with('service','devices','package','user')->find($request->rs_id);
        if($r_service !=''){
            $final_amount = $request->final_amount;
            if($request->discount_amount > 0 ){
                $new_final_price = $final_amount - $request->discount_amount;
            }else{
                $new_final_price = $final_amount;
            }
            if($new_final_price > $final_amount){
                $message = 'Discount Amount Cannot be Greater Than: '.$final_amount;
                $type = 'danger';
            }else{
                $invoice = Invoice::create([
                    'user_id' => $r_service->user_id,
                    'invoice_type' => $request->invoice_type,
                    'request_service_id' => $request->rs_id,
                    'due_date' => $request->due_date,
                    'price' => $final_amount,
                    'discount_amount' => $request->discount_amount,
                    'discount_percentage' => $request->discount_amount_percent,
                    'final_price' => $new_final_price,
                    'remaining_amount' => $new_final_price,
                    'surcharges' => $request->surcharges,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);
                // update request_services table, status and is_invoiced
                $service_update = DB::table('request_services')->where('id', $request->rs_id)
                  ->update([
                    'status' => 'in_process', 
                    'is_invoiced' => 1, 
                    'updatedby' => $userId,
                    'updated_at' => $this->currentDateTime(),
                ]);
                // invoice detail table, service installation fee, device price, package price
                $invoice_detail_service = DB::table('invoice_details')->insert([
                    'invoice_id' => $invoice->id,
                    'service_id' => $r_service->service_id,
                    'price' => $r_service->service->installation_fee,
                    'discount_amount' => 0,
                    'discount_percentage' => 0,
                    'final_price' => $r_service->service->installation_fee,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);

                // add Package in invoice Detail
                if($r_service->package !=''){
                    $invoice_detail_pckg = DB::table('invoice_details')->insert([
                        'invoice_id' => $invoice->id,
                        'package_id' => $r_service->package_id,
                        'price' => $r_service->package->price,
                        'discount_amount' => 0,
                        'discount_percentage' => 0,
                        'final_price' => $r_service->package->price,
                        'addedby' => $userId,
                        'created_at' => $this->currentDateTime(),
                    ]);
                }
                // Add Devices in  invoice detail 
                if($r_service->devices->count() > 0){
                    foreach ($r_service->devices as $key => $device) {
                        $invoice_detail_pckg = DB::table('invoice_details')->insert([
                            'invoice_id' => $invoice->id,
                            'device_id' => $device->id,
                            'price' => $device->device_price,
                            'discount_amount' => 0,
                            'discount_percentage' => 0,
                            'final_price' => $device->device_price,
                            'addedby' => $userId,
                            'created_at' => $this->currentDateTime(),
                        ]);
                    }
                }
                $type="success";
                $message="Invoice Created Successfully!";
            }
        }else{
            $type="danger";
            $message="No Service Request Found!";
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('invoice.index');
    }

    public function storeMonthlyInvoice(Request $request)
    {
    	// get user loged in apis or web 

        // dd($request->toArray());
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $user_services = User::where([['is_active', 1],['society_id',Auth::guard('web')->user()->society_id]])->has('services')->with(['society','userservices' => function($qry){
            return $qry->with('service','servicetype','package')->where('status', 1); 
        }])->find($request->user_id);

       $total_amount = $request->total_amount;
        if($user_services !=''){
            if($request->discount_amount > 0 ){
                $final_amount = $total_amount - $request->discount_amount;
            }else{
                $final_amount = $total_amount;
            }
            if($final_amount > $total_amount){
                $message = 'Discount Amount Cannot be Greater Than: '.$total_amount;
                $type = 'danger';
            }else{
                $invoice = Invoice::create([
                    'user_id' => $request->user_id,
                    'invoice_type' => $request->invoice_type,
                    'due_date' => $request->due_date,
                    'price' => $total_amount,
                    'discount_amount' => $request->discount_amount,
                    'discount_percentage' => $request->discount_amount_percent,
                    'final_price' => $final_amount,
                    'remaining_amount' => $final_amount,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);
                foreach ($user_services->services as $service) {
                    $invoice_detail = DB::table('invoice_details')->insert([
                        'invoice_id' => $invoice->id,
                        'service_id' => $service->service_id,
                        'user_service_id' => $service->id,
                        'price' => $service->price,
                        'discount_amount' => $service->discount_amount,
                        'discount_percentage' => $service->discount_percentage,
                        'final_price' => $service->final_price,
                        'addedby' => $userId,
                        'created_at' => $this->currentDateTime(),
                    ]);
                }
                $message = 'Data created successfully';
                $type = 'success';
            }
        }else{
            $message = 'Data Connot updated';
            $type = 'danger'; 
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->route('invoice.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');

        $request_service = RequestService::with('service','devices','invoice','package','user')->find($id);
    	// dd($request_service->toArray());
        if($request_service !=''){
            $package_price = 0;
            $pckg_price_incl_tax = 0;
            $devices_sum = 0;
            $device_price_incl_tax = 0;
            $service_price_incl_tax = 0;
            if($request_service->package !=''){
                $package_price =  $request_service->package->price;
                $pckg_price_incl_tax = str_replace(',','',$request_service->package->price_include_tax);
            }
            // dd($pckg_price_incl_tax);
            if($request_service->devices !=''){
                $devices_sum = $request_service->devices->sum('device_price');
                $device_price_incl_tax = $request_service->devices->sum('price_without_tax');
            }
            
            if($request_service->service !=''){
                $service_price_incl_tax = str_replace(',','',$request_service->service->price_include_tax);
            }

            $total_price_incl_tax = $service_price_incl_tax+$pckg_price_incl_tax+$device_price_incl_tax;

            // dd($total_price_incl_tax);
            $total_price = $request_service->service->installation_fee + $package_price + $devices_sum;
           return view('invoices.create', compact('request_service','total_price','total_price_incl_tax'));
       }else{
        Session::flash('notify', ['type'=> 'danger','message' => 'No Invoice Found']);
        return redirect()->route('invoice.index');
       } 
    }

    public function edit($id)
    {
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $service_type = ServiceType::find($id);
        // dd($societies->toArray());
        return view('servicemanagement.service_type.create', compact('service_type'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('update-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $r_service = RequestService::with('invoice:id,request_service_id')->find($request->rs_id);
        if($r_service->invoice->is_payed != 1){
            $final_amount = $request->final_amount;
            if($request->discount_amount > 0 ){
                $new_final_price = $final_amount - $request->discount_amount;
            }else{
                $new_final_price = $final_amount;
            }
            if($new_final_price > $final_amount){
                $message = 'Discount Amount Cannot be Greater Than: '.$final_amount;
                $type = 'danger';
            }else{
                $invoice_update = DB::table('invoices')->where('id', $request->invoiced_id)
                  ->update([
                    'due_date' => $request->due_date,
                    'price' => $final_amount,
                    'discount_amount' => $request->discount_amount,
                    'discount_percentage' => $request->discount_amount_percent,
                    'final_price' => $new_final_price,
                    'remaining_amount' => $new_final_price,
                    'addedby' => $userId,
                    'created_at' => $this->currentDateTime(),
                ]);
                $message = 'Data Updated Successfully';
                $type = 'success';
            }
        }else{
            $message = "Paid Invoiced Can't be Updated";
            $type = 'danger';
        }
        if($web_user !=''){
            Session::flash('notify', ['type'=> $type,'message' => $message]);
            return redirect()->route('invoice.index');
        }else{
            return response()->json([
                'message' => $message,
                'service_type' => $service_type
            ], 201);
        }
    }

    public function invoicePayment(Request $request){
        $web_user = Auth::guard('web')->user();
        if($web_user != ''){
            $userId = $web_user->id;
            $user_name = $web_user->name;
        }else{
            $api_user = Auth::guard('api')->user();
            $userId = $api_user->id;
            $user_name = $api_user->name;
        }
        $message = '';
        $type = '';
        // dd($request->toArray());
        if($request->payed_amount > $request->invoice_price || $request->payed_amount < $request->invoice_price){
            $message = 'Paid Amount Should be Equal To Invoice Payment';
            $type= 'danger';
        }{
            $invoice_payment = DB::table('invoice_payments')->insert([
                'invoice_id' =>  $request->invoice_id,
                'user_id' => $request->user_id,
                'payed_amount' => $request->payed_amount,
                'payed_date' => $request->payed_date,
                'remarks' => $request->remarks,
                'addedby' => $userId,
                'created_at' => $this->currentDateTime(),
            ]);
            $payed_amount = DB::table('invoice_payments')->where('invoice_id',$request->invoice_id)->get()->sum('payed_amount');
            $remaining_amount = $request->invoice_price - $payed_amount;
            // update invoice table
            $invoice_update = Invoice::find($request->invoice_id);
            $invoice_update->pay_date = $request->payed_date;
            $invoice_update->is_payed = 1;
            $invoice_update->status = 'paid';
            $invoice_update->paid_amount = $payed_amount;
            $invoice_update->remaining_amount = $remaining_amount;
            $invoice_update->updatedby = $userId;
            $invoice_update->updated_at = $this->currentDateTime();
            $invoice_update->save();
            // in first time invoice it will send to department hod for approvel after payment
            if($invoice_update->invoice_type == 'first_time'){
                $s_request = RequestService::find($request->sr_id);
                // Update Service Request Table, is_paid
                $s_request->is_paid = 1;
                $s_request->status = 'paid';
                $s_request->updatedby = $userId;
                $s_request->updated_at = $this->currentDateTime();
                $s_request->save();
                // Get Department Head From Service Request, to Send Notification
                $dephod  = DepartmentHod::where('department_id',$s_request->type_id)->first();
            
                // dd($dephod->toArray());
                if($dephod !=''){
                    $refer_to_id = $dephod->hod_id;
                }else{
                    $message .= '<br/> No Head Of Department Found';
                    $type = 'danger';
                }

                // get user to refer request{ hod }
                $user = User::where('id',$refer_to_id)->first();
                if($user !='' AND $message == ''){
                    // Send Notification to Service Request's HOD
                    $details=[
                        'title' => 'Service Request Payment Received',
                        'sender_name' => $user_name,
                        'sender_id' => $userId,
                        'service_id' => $s_request->service_id,
                        'service_request_id' => $request->sr_id,
                    ];
                    $user->notify(new \App\Notifications\ServiceNotification($details));
                    // generate service log
                    $service_log = DB::table('service_logs')->insert([
                        'service_id' => $s_request->service_id,
                        'service_request_id' => $request->sr_id,
                        'status' => 'paid', 
                        'comments' => $request->remarks,
                        'addedby' => $userId,
                        'created_at' => $this->currentDateTime(),
                    ]);
                    // $message .= 'Data Created Successfully';
                    // $type = 'success';
                }else{
                    $message .= '<br/> No Head Of Department Found To assign This service';
                    $type = 'danger';
                }
            }
            $message = 'Paymant Added Successfully';
            $type= 'success';
        }
        Session::flash('notify', ['type'=> $type,'message' => $message]);
        return redirect()->back();
    }
    public function InvoiceDetail($id){
        abort_if(Gate::denies('view-invoices'), Response::HTTP_FORBIDDEN, '403 Permission Denied, You are unauthorized');
        $invoice = Invoice::with('items.service','user:id,unique_id,name,society_id','user.society:id,name,address')->find($id);

        // dd($invoice->toArray());    
        return view('invoices.invoice-detail', compact('invoice'));
    }
}