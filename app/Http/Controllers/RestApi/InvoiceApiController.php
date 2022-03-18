<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceApiController extends Controller
{
    public function index($id)
    {
    	// $invoices = Invoice::where('user_id', $id)->with('user.services')->get();
    	$invoices = Invoice::where('user_id', $id)->get();
        $message = "No Data Found";
        if(count($invoices) > 0){
            $message = "success";
        }
        return response()->json([
            'message' => $message,
            'invoices' => $invoices
        ], 201);
    }


    public function show($id)
    {
        // $invoice = Invoice::with('items.service','user:id,unique_id,name,society_id','user.society:id,name,address')->find($id);
        
    	$invoice_detail = Invoice::with('items.service','user:id,unique_id,name,society_id','user.society:id,name,address')->find($id);
        $message = "No Data Found";
        if($invoice_detail !=''){
            $message = "success";
        }
        return response()->json([
            'message' => $message,
            'invoice_detail' => $invoice_detail
        ], 201);
    }
}
