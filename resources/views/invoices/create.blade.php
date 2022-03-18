@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Invoice
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						Create Invoice </span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<div class="kt-subheader__wrapper">
					<a href="{{URL::previous()}}" class="btn btn-default btn-bold">
						Back </a>
				</div>
			</div>
		</div>
	</div>
	<!-- end:: Subheader -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
			<div class="kt-portlet__body kt-portlet__body--fit">
				<form class="loader"
					action="{{ ($request_service->invoice !='') ? route('invoice.update', $request_service->invoice->id ) : route('invoice.store') }}"
					method="post">
					@csrf
					@if($request_service->invoice !='')
					@method('PUT')
					@php
					$now_date = $request_service->invoice->created_at->format('M d, Y');
					@endphp
					@else
					@php
					$now_date = Carbon\Carbon::now()->format('M d, Y');
					@endphp
					@endif
					<div class="kt-invoice-1 p-2" style="border:2px solid; ">
						<div class="kt-invoice__head pb-4 pt-4" style="background: #1c5b90;">
							<div class="kt-invoice__container pl-4" style="width: 100% !important;">
								<div class="kt-invoice__brand">
									<h4 class="kt-invoice__title">INVOICE</h4>
									<div href="#" class="kt-invoice__logo" style="margin-left: -3rem;">
										<a href="#"><img src="{{ asset('assets/media/logos/pr/vlogo-white.png') }}"></a>
										{{-- <span class="kt-invoice__desc">
											<span>Royal Orchard</span>
											<span>Islamabad</span>
										</span> --}}
									</div>
								</div>
								<div class="kt-invoice__items mt-2">
									<div class="kt-invoice__item ">
										<span class="kt-invoice__subtitle">INVOICE TO.</span>
										<span
											class="kt-invoice__text">{{$request_service->user->name}}<br>{{$user_services->user->cnic
											?? '123817319831293'}}</span>
									</div>
									<div class="kt-invoice__item text-right  mr-5">
										{{-- <span class="kt-invoice__subtitle">DATE</span> --}}
										<span class="kt-invoice__text">{{$now_date}}</span>
										{{-- <span class="kt-invoice__subtitle mt-2">INVOICE</span> --}}
										<span class="kt-invoice__text mt-2"> <b class="text-white">INVOICE No: </b>
											000014</span>
									</div>
								</div>
							</div>
						</div>
						<div class="pt-2 pb-0">
							<div class="">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>DESCRIPTION</th>
												<th>Type</th>
												<th>TAX</th>
												<th>AMOUNT</th>
												<th>AMOUNT INCL TAX</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{$request_service->service->title}}</td>
												<td> Service Installation <Charges></Charges>
												</td>
												<td class="text-center"> <b> {{$request_service->service->total_tax}} %
													</b> </td>
												<td class="text-center">{{
													number_format($request_service->service->installation_fee ?? 0,0)}}
												</td>
												<td class="text-center"> <b> {{
														$request_service->service->price_include_tax ?? 0}} </b> </td>
											</tr>

											@if ($request_service->package !='')
											<tr>
												<td>{{$request_service->package->title}}</td>
												<td>Package Price</td>
												<td class="text-center"> <b> {{$request_service->package->total_tax}} %
													</b> </td>
												<td class="text-center">
													{{number_format($request_service->package->price,0)}}</td>
												<td class="text-center"> <b>
														{{$request_service->package->price_include_tax,0}} </b> </td>
											</tr>

											@endif

											@forelse ($request_service->devices as $device)
											<tr>
												<td>{{$device->device_title}}</td>
												<td>Device Charges [ {{$device->device_status}} ]</td>
												<td class="text-center"> <b> {{$device->total_tax}} % </b> </td>
												<td class="text-center">{{number_format($device->device_price,0)}}</td>
												<td class="text-center"> <b> {{$device->price_include_tax,0}} </b> </td>
											</tr>
											@empty
											<tr>

											</tr>
											@endforelse
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="kt-invoice__footer pt-4 pb-2">
							<div class="kt-invoice__container" style="width: 100% !important;">
								<div class="kt-invoice__bank"></div>

								@if ($request_service->invoice !='')
								@php
								$old_discount_amt = $request_service->invoice->discount_amount;
								$old_discount_percnt = $request_service->invoice->discount_percentage;
								$old_finalprice = $request_service->invoice->final_price;
								$old_due_date = $request_service->invoice->due_date->format('Y-m-d');
								@endphp
								<input type="hidden" name="invoiced_id" value="{{$request_service->invoice->id}}">
								@else
								@php
								$old_discount_amt = '';
								$old_discount_percnt = '';
								$old_finalprice = '';
								$old_due_date = '';
								@endphp
								@endif
								<input type="hidden" name="rs_id" value="{{$request_service->id}}">
								<input type="hidden" name="invoice_type" value="first_time">
								<input type="hidden" name="final_amount" value="{{$total_price_incl_tax}}">
								<input type="hidden" name="new_final_amount" id="new_final_amount">

								<div class="kt-invoice__total">
									<span class="kt-invoice__title">TOTAL AMOUNT</span>
									<span class="kt-invoice__price" style="color:#e54f68 !important;"> RS.
										{{number_format($total_price_incl_tax,0)}} </span>
									<span class="kt-invoice__notice"><span id="newAmount"> {{$old_finalprice}} </span>
									</span>

									{{-- <span class="kt-invoice__title">TOTAL AMOUNT</span>
									<span class="kt-invoice__price" style="color:#e54f68 !important;"> RS.
										{{number_format($total_price,0)}} </span>
									<span class="kt-invoice__notice"><span id="newAmount"> {{$old_finalprice}} </span>
									</span> --}}
									<span class="text-danger" id="errorAmt"></span>
									<span class="kt-invoice__notice mt-2" style="float: right !important;">
										<input type="number" min="0" max="{{$total_price_incl_tax}}" id="DiscountAmount"
											step="any" value="{{$old_discount_amt}}" name="discount_amount"
											class="form-control form-control-sm" style="width: 90%; float: right;"
											placeholder="Enter Discount Amount">
									</span>
									<span style="font-size: 10px;"> Discount Amount</span>
									<span class="kt-invoice__notice mt-2">
										<input type="number" value="{{$old_discount_percnt}}" id="DiscountPercent"
											min="0" max="100" step="any" name="discount_amount_percent"
											class="form-control form-control-sm" style="width: 90%; float: right;"
											placeholder="Enter Discount %">
									</span>
									<span style="font-size: 10px;"> Discount Percentage</span>
									<span class="kt-invoice__notice mt-2"> <input type="text" name="due_date"
											class=" kt_datepicker_validate form-control form-control-sm"
											placeholder="Select Due Date" value="{{$old_due_date}}" required
											style="border-radius: 3px;width: 90%; float: right;">
									</span>
									<span style="font-size: 10px;"> Invoice Due Date </span>

									<span class="kt-invoice__notice mt-2"> <input type="number" name="surcharges"
											step="any" class="form-control form-control-sm"
											placeholder="Add Surcharges If Any" value=""
											style="border-radius: 3px;width: 90%; float: right;">
									</span>
									<span style="font-size: 11px;"> Surcharges </span>
								</div>
							</div>
						</div>
						<div class="kt-invoice__actions pt-2 pb-2">
							<div class="kt-invoice__container" style="width:100% !important;">

								<button type="button" class="btn btn-label-brand btn-sm"
									onclick="window.print();">Download Invoice</button>
								<span></span>
								@canany(['update-invoices', 'create-invoices'])
								<button type="submit" @if ($request_service->invoice !='' AND
									$request_service->invoice->is_payed == 1)
									disabled title="Paid Invoiced Can't Be Update"
									@endif class="btn btn-brand btn-bold btn-sm"> {{ ($request_service->invoice !='') ?
									'Update' : 'Save' }}
								</button>
								@endcanany
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- end:: Content -->
</div>
@endsection
@section('styles')
<link href="{{ asset('assets/css/pages/invoices/invoice-1.css?v=1.0') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript">
</script>
<script>
	$('#DiscountAmount').on('input', function() {
    		var final_amount = <?php echo json_encode($total_price_incl_tax); ?>;
    		var discountAmount = parseFloat($(this).val());
    		if(discountAmount > final_amount){
    			$('#errorAmt').html('<b>Discount Amount Cannot be greater Than '+final_amount+'</b>');
    		}else{
    			var discountPercent = discountAmount/parseFloat(final_amount)*100;
    			var new_amount = parseFloat(final_amount) - discountAmount;
    			$('#newAmount').html(new_amount);
    			$('#new_final_amount').val(new_amount);
    			$('#DiscountPercent').val(discountPercent);
    			$('#errorAmt').html('');
    		}
    	});
    	$("#DiscountPercent").on('input', function() {
    		var final_amount = <?php  echo json_encode($total_price_incl_tax); ?>;
    		var Percentage = parseInt($(this).val());
    		if(Percentage > 100){
    			$('#errorAmt').html('<b>Discount Percentage Cannot be greater Than 100</b>');
    		}else{
    			var PercentAmt = Percentage/100*parseFloat(final_amount);
    			var new_amount_percent = parseFloat(final_amount) - PercentAmt;

    			$('#newAmount').html('Final Amount: '+new_amount_percent);
    			$('#new_final_amount').val(new_amount_percent);
    			$('#DiscountAmount').val(PercentAmt);
    			$('#errorAmt').html('');
    		}
    	});
</script>
@endsection