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
						Create Invoice</span>
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
				<form class="loader" action="{{ route('minvoice.store') }}" method="post">
					@csrf

					@php
					$now_date = Carbon\Carbon::now()->format('M d, Y');
					@endphp

					<div class="kt-invoice-1 p-2" style="border:2px solid; ">
						<div class="kt-invoice__head pb-4 pt-4" style="background: #1c5b90;">
							<div class="kt-invoice__container pl-4" style="width: 100% !important;">
								<div class="kt-invoice__brand ">
									<h4 class="kt-invoice__title  mt-4">INVOICE</h4>
									<div href="#" class="kt-invoice__logo" style="margin-left: -3rem;">
										<a href="#"><img src="{{ asset('assets/media/logos/pr/vlogo-white.png') }}"
												width="80"></a>
										<span class="kt-invoice__desc">
											<span>Royal Orchard</span>
											<span>Islamabad</span>
										</span>
									</div>
								</div>
								<div class="kt-invoice__items mt-2">
									<div class="kt-invoice__item">
										<span class="kt-invoice__subtitle">DATE</span>
										<span class="kt-invoice__text">{{$now_date}}</span>
									</div>
									<div class="kt-invoice__item text-center">
										{{-- <span class="kt-invoice__subtitle">INVOICE NO.</span>
										<span class="kt-invoice__text">GS 000014</span> --}}
									</div>
									<div class="kt-invoice__item text-right">
										<span class="kt-invoice__subtitle">INVOICE TO.</span>
										<span class="kt-invoice__text">{{$user->name}}<br>{{$user_services->user->cnic
											?? '123817319831293'}}</span>
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
												<th> Description </th>
												<th>Package</th>
												<th>Price</th>
												<th>Discount</th>
												<th>Final Amount</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											@foreach ($user->userservices as $userservice)
											<tr>
												<td>{{$userservice->service->title}}</td>
												<td>{{$userservice->package->title}}</td>
												<td>{{number_format($userservice->package->price,0)}}</td>
												<td>{{number_format($userservice->discount_amount,0)}}</td>
												<td>{{number_format($userservice->final_price,0)}}</td>
												<td></td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="kt-invoice__footer pt-4 pb-2">
							<div class="kt-invoice__container" style="width: 100% !important;">
								<div class="kt-invoice__bank"></div>

								<input type="hidden" name="invoice_type" value="monthly">
								<input type="hidden" name="total_amount" value="{{$total_price}}">
								<input type="hidden" name="user_id" value="{{$user->id}}">
								<input type="hidden" name="final_amount" id="new_final_amount">

								<div class="kt-invoice__total">
									<span class="kt-invoice__title">TOTAL AMOUNT</span>
									<span class="kt-invoice__price" style="color:#e54f68 !important;"> RS:
										{{number_format($total_price,0)}} </span>
									<span class="kt-invoice__notice">New Amount: <span id="newAmount"> </span> </span>
									<span class="text-danger" id="errorAmt"></span>
									<span class="kt-invoice__notice mt-2" style="float: right !important;">
										<input type="number" min="0" id="DiscountAmount" step="any"
											name="discount_amount" class="form-control form-control-sm"
											placeholder="Enter Discount Amount">
									</span>
									<span class="input_span_label"> Discount Amount</span>
									<span class="kt-invoice__notice mt-2">
										<input type="number" id="DiscountPercent" min="0" max="100" step="any"
											name="discount_amount_percent" class="form-control form-control-sm"
											placeholder="Enter Discount %">
									</span>
									<span class="input_span_label"> Discount Percentage</span>
									<span class="kt-invoice__notice mt-2"> <input type="text" name="due_date"
											class=" kt_datepicker_validate form-control form-control-sm"
											placeholder="Select Due Date" required style="border-radius: 3px;">
									</span>
									<span class="input_span_label"> Invoice Due Date </span>
								</div>
							</div>
						</div>
						<div class="kt-invoice__actions pt-2 pb-2">
							<div class="kt-invoice__container" style="width: 100% !important;">
								<button type="button" class="btn btn-label-brand btn-bold btn-sm"
									onclick="window.print();">Download Invoice</button>
								<span></span>
								<button type="submit" class="btn btn-brand btn-bold btn-sm"> Save </button>
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
    		var final_amount = <?php echo json_encode($total_price); ?>;
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
    		var final_amount = <?php  echo json_encode($total_price); ?>;
    		var Percentage = parseInt($(this).val());
    		if(Percentage > 100){
    			$('#errorAmt').html('<b>Discount Percentage Cannot be greater Than 100</b>');
    		}else{
    			var PercentAmt = Percentage/100*parseFloat(final_amount);
    			var new_amount_percent = parseFloat(final_amount) - PercentAmt;
    			$('#newAmount').html(new_amount_percent);
    			$('#new_final_amount').val(new_amount_percent);
    			$('#DiscountAmount').val(PercentAmt);
    			$('#errorAmt').html('');
    		}
    	});
</script>
@endsection