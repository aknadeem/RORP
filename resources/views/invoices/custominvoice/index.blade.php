@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('Invoice')}}</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>

				<a href="{{ route('invoice.index') }}"><span class="kt-subheader__desc">{{ __('Invoices')}}</span></a>
			</div>
		</div>
	</div>

	@php
	// filter department from departments array
	$search_society_id = request()->search_society_id;
	$search_user_id = request()->search_user_id;
	if($search_society_id !='all' AND $search_society_id !=''){

	$invoices = $invoices->where('soc_id', $search_society_id);
	}else{
	$invoices = $invoices;
	}

	if($search_user_id !='all' AND $search_user_id !=''){

	$invoices = $invoices->where('user_id', $search_user_id);
	}else{
	$invoices = $invoices;
	}
	@endphp
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		<form action="" method="get" class="loader">
			<div class="alert alert-light alert-elevate row" role="alert">
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>

				<div class="form-group validated col-sm-5">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true"
						required>
						<option selected disabled> {{ __('Select Society')}}</option>
						<option {{ ($search_society_id=='all' ) ? 'selected' : '' }} value="all"> {{ __('All
							Societies')}}</option>
						@forelse($societies as $soc)
						<option {{ ($search_society_id==$soc->id) ? 'selected' : '' }} value="{{$soc->id}}">{{
							$soc->name }}</option>
						@empty
						<option disabled> No Society Found </option>
						@endforelse
					</select>
				</div>

				<div class="form-group validated col-sm-5">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="search_user_id" data-live-search="true" required>
						<option selected disabled> {{ __('Select User')}}</option>
						<option {{ ($search_user_id=='all' ) ? 'selected' : '' }} value="all"> {{ __('All Users')}}
						</option>
						@forelse($users as $user)
						<option {{ ($search_user_id==$user->id) ? 'selected' : '' }} value="{{$user->id}}">{{
							$user->name }}</option>
						@empty
						<option disabled> No user Found </option>
						@endforelse
					</select>
				</div>
				<div class="kt-section__content kt-section__content--solid mt-4">
					<button type="submit" class="btn btn-primary btn-sm">Search</button>
				</div>
			</div>
		</form>

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-tags"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Invoices')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-invoices')
							<a href="{{ route('custominvoice.create') }}"
								class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Invoice"><i
									class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
							@endcan
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('#')}}</th>
							<th>{{ __('User')}}</th>
							<th>{{ __('Due Date')}} </th>
							<th>{{ __('Price')}} </th>
							<th>{{ __('Discount')}} </th>
							<th>{{ __('Final Price')}} </th>
							<th>{{ __('Payment')}} </th>
							<th>{{ __('Action')}} </th>
						</tr>
					</thead>
					<tbody>
						@forelse ($invoices as $key=>$invoice)
						<tr>
							<td>{{ ++$key }}</td>
							<td>{{ $invoice->user->name}}</td>
							<td> <b class="text-warning"> {{ $invoice->due_date->format('M d, Y')}} </b></td>
							<td>{{ number_format($invoice->price,0)}}</td>
							<td> <span class="text-success"> <b> {{ number_format($invoice->discount_amount,0)}} </b>
								</span> </td>
							<td> <span class="text-danger"> <b> {{ number_format($invoice->final_price,0)}} </b></td>
							<td>
								@if ($invoice->is_payed < 1) @can('add-payment-invoices') <span
									invoice-id="{{$invoice->id}}" user_id="{{ $invoice->user_id}}"
									invoice-price="{{$invoice->price - $invoice->discount_amount - $invoice->payed_amount}}"
									class="btn btn-primary btn-sm openPaymentModel" title="Add Payment">
									<i class="fa fa-plus"></i>Payment
									</span>
									@else
									<span style="width: 100px;"><span class="btn btn-bold btn-sm"> {{
											ucfirst($invoice->status ?? 'pending')}}
										</span></span>
									@endcan
									@else
									<span class="btn btn-success btn-sm ">
										<i class="fa fa-check"></i> Paid
									</span>
									@endif
							</td>

							<td>
								<a href="{{route('custominvoice.show', $invoice->id)}}" class="text-brand"> <i
										class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;

								@can('update-invoices')
								<a href="{{route('custominvoice.edit', $invoice->id)}}" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="View Detail"></i> </a> &nbsp;
								@endcan
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="10" class="text-danger text-center"> No Data Available </td>
						</tr>
						@endforelse
					</tbody>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
	<!-- begin:: End Content  -->
</div>
@endsection

@section('modal-popup')
<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Add Payment </h5>
				<button type="button" class="close paymentModel" aria-label="Close"></button>
			</div>

			<form class="loader" action="{{ route('custominvoice.payment') }}" method="POST" id="PckgServiceModelForm">
				@csrf
				<input name="user_id" type="hidden" id="user_id" />
				<input name="custom_invoice_id" type="hidden" id="invoice_id" />
				<input name="invoice_price" type="hidden" id="invoice_price" />
				<div class="modal-body">

					<label class="col-form-label" style="float: right !important;"> <b>Amount: Rs. <span
								id="pckgPrice"></span> </b> <br>

						<span id="FinalAmt" class="text-danger"></span> <br>
					</label>

					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label"> <b> Invoice No: </b></label>
						<div class="col-8 mt-2">
							<span id="invoiceNumber"></span>
						</div>
					</div>
					<span id="AmtError" class="text-danger"></span><br>
					<div class="row">
						<div class="col-lg-6">
							<label class="form-control-label"><b>{{ __('Payment Date:*') }}</b></label>
							<div class="input-group date">

								@php
								$now_date = Carbon\Carbon::now()->format('Y-m-d');
								@endphp
								<input type="text" name="paid_date" required
									class=" kt_datepicker_validate form-control @error('payed_date') is-invalid @enderror"
									value="{{$now_date}}" placeholder="Select date" style="border-radius: 3px;">

								@error('payed_date')
								<div class="invalid-feedback">{{ $message }}</div>
								@enderror

							</div>
						</div>

						<div class="col-lg-6">
							<label class="form-control-label"><b>{{ __('Amount:*') }}</b></label>
							<div class="input-group date">
								<input type="number" step="any" min="0" max="" required id="payed_amount"
									name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror"
									placeholder="Enter Amount">

								@error('paid_amount')
								<div class="invalid-feedback">{{ $message }}</div>
								@enderror

							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col-md-12 mt-2">
							<label for="message-text" class="form-control-label"> <b> Remarks:* </b></label>
							<textarea class="form-control" name="remarks" required></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm paymentModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('top-styles')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet"
	type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript">
</script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

<script>
	$(document).ready(function () {
   		//close Add Payment Model
   		$('.paymentModel').click(function () {
			$('#PckgServiceModelForm').trigger('reset');
			$('#AddPaymentModel').modal('toggle');
			$('#AmtError').html('');
			$('#FinalAmt').html('');
		});
   		// open Add Payment Modal
   		$('.openPaymentModel').click(function () {
			$('#AddPaymentModel').modal('show');
			var invoiceId = $(this).attr('invoice-id');
			var user_id = $(this).attr('user_id');
	        var invoice_price = parseFloat($(this).attr('invoice-price'));
	        $('#user_id').val(user_id);
	        $('#invoice_id').val(invoiceId);
	        $('#invoice_price').val(invoice_price);
	        $('#invoiceNumber').html('<b class="text-danger"> '+invoiceId+'</b>');
	        $('#pckgPrice').html(invoice_price);
	        $('#payed_amount').val(invoice_price);
	        $('#payed_amount').attr({"max" : invoice_price,"min" : invoice_price,});
	        $('#payed_amount').on('input', function() {
	    		var final_amount = invoice_price; // remaing amount
	    		var payAmount = parseFloat($(this).val()); // get payment Amount
	    		if(payAmount > final_amount || payAmount < final_amount){
	    			$('#AmtError').html('<b> Amount Cannot be greater Than: '+invoice_price+' OR Less Then '+invoice_price+' 0 </b>');
	    			$('#FinalAmt').html('');
	    		}else{
	    			var new_amount = final_amount - payAmount;
	    			$('#FinalAmt').html('Remaining Amount: <b> '+new_amount+'</b>');
	    			$('#AmtError').html('');
	    		}
	    	});
		});
	});
</script>
@endsection