<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Console\Command;

class SendMonthlyInvoices extends Command
{
    protected $signature = 'monthly:invoices';
    protected $description = 'This command send monthly smart services invoices';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $today = Carbon::now();
        $user_services = User::where('is_active', 1)->has('services')->with(['society','userservices' => function($qry){
            return $qry->with('service','servicetype','package')->where('status', 1); 
        }])->get();
        $due_date = Carbon::now()->addDays(10);
        if($user_services->count() > 0){
            foreach($user_services as $singleuser){
                if($singleuser->services->count() > 0){
                    $price = $singleuser->services->sum('price');
                    $discount_amount = $singleuser->services->sum('discount_amount');
                    $final_price = $price - $discount_amount;
                    $invoice = Invoice::create([
                        'user_id' => $singleuser->id,
                        'invoice_type' => 'monthly',
                        'due_date' => $due_date->format('Y-m-d'),
                        'price' => $price,
                        'discount_amount' => $discount_amount,
                        'discount_percentage' => $singleuser->services->sum('discount_percentage'),
                        'final_price' => $final_price,
                        'remaining_amount' => $final_price,
                    ]);
                    foreach ($singleuser->services as $service) {
                        $invoice_detail = DB::table('invoice_details')->insert([
                            'invoice_id' => $invoice->id,
                            'service_id' => $service->service_id,
                            'user_service_id' => $service->id,
                            'price' => $service->price,
                            'discount_amount' => $service->discount_amount,
                            'discount_percentage' => $service->discount_percentage,
                            'final_price' => $service->final_price,
                            'created_at' => $today->toDateTimeString(),
                        ]);
                    }
                }
            }
        }
        // return 0;
        // monthly
    }
}
