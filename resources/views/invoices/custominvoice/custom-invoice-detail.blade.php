@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title" >
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
					<div class="kt-invoice-1 p-2" style="border:2px solid;">
						<div class="kt-invoice__head pb-4 pt-4" style="background: #1c5b90;">
							<div class="kt-invoice__container pl-4" style="width: 100% !important;">
								<div class="kt-invoice__brand ">
									<h4 class="kt-invoice__title">INVOICE</h4>
									<div href="#" class="kt-invoice__logo" style="margin-left: -3rem;">
										<a href="#"><img src="{{ asset('assets/media/logos/pr/vlogo-white.png') }}"></a>
										{{-- <span class="kt-invoice__desc">
											<span> {{$invoice->user->society->name ?? 'Society Name' }}</span>
											<span> {{$invoice->user->society->address ?? 'Address' }} </span>
										</span> --}}
									</div>
								</div>
								<div class="kt-invoice__items mt-2">
									<div class="kt-invoice__item ">
										<span class="kt-invoice__subtitle">INVOICE TO.</span>
										<span class="kt-invoice__text">{{$invoice->user->name}}<br> <b> {{$invoice->user->unique_id ?? '-'}} </b></span>
									</div>

									<div class="kt-invoice__item text-right  mr-5">
										<span class="kt-invoice__text">{{$invoice->created_at->format('M d, Y')}}</span>
										<span class="kt-invoice__text mt-2"> <b class="text-white">INVOICE No: </b> {{$invoice->id}} </span>
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
												<th>User</th>
												<th>AMOUNT</th>
												<th>Payment</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{$invoice->description ?? ''}}</td>
												<td> Maintenance Charges </td>
												<td> {{$invoice->user->name}}</td>
												<td>{{number_format($invoice->final_price,0)}}</td>
												<td>
													@if ($invoice->is_payed < 1)
											 			<span class="btn btn-warning btn-sm ">
														    <i class="fa fa-check"></i>Not Paid
														</span>
											 		@else
														<span class="btn btn-success btn-sm ">
														    <i class="fa fa-check"></i>Paid
														</span>
											 		@endif
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="kt-invoice__footer pt-4 pb-2">
							<div class="kt-invoice__container" style="width: 100% !important;">
								<div class="kt-invoice__bank"></div>

								<div class="kt-invoice__total">
									<span class="kt-invoice__title">TOTAL AMOUNT</span>
									<span class="kt-invoice__price" style="color:#e54f68 !important;"> RS. {{number_format($invoice->price,0)}} </span>
									<span class="kt-invoice__notice"><span id="newAmount"> Final Amount: {{number_format($invoice->final_price,0)}} </span> </span>
									<span class="text-danger" id="errorAmt"></span>
									<span class="kt-invoice__notice mt-2" style="float: right !important;">
										<input type="number" readonly value="{{$invoice->discount_amount ?? 0}}" class="form-control form-control-sm"  placeholder="Enter Discount Amount">
									</span>
									<span class="input_span_label"> Discount Amount</span>
									<span class="kt-invoice__notice mt-2">
										<input type="number" value="{{$invoice->discount_percentage ?? 0}}" readonly class="form-control form-control-sm"placeholder="Enter Discount %">
									</span>
									<span class="input_span_label"> Discount Percentage</span>
									<span class="kt-invoice__notice mt-2"> <input type="text" readonly class=" kt_datepicker_validate form-control form-control-sm" placeholder="Select Due Date" value="{{$invoice->due_date->format('Y-m-d')}}" required>
									</span>
									<span class="input_span_label"> Invoice Due Date </span>
								</div>
							</div>
						</div>
						<div class="kt-invoice__actions pt-2 pb-2">
							<div class="kt-invoice__container" style="width: 100% !important;">
								<button type="button" class="btn btn-label-brand btn-sm" onclick="window.print();">Download Invoice</button>
								<span></span>
								<a href="{{URL::previous()}}" class="btn btn-brand btn-sm"> Back </a>
							</div>
						</div>
					</div>
				
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
@endsection