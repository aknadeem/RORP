@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('Fine&Plenties')}}</h3>
				{{-- <span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="{{ route('fines.index') }}"><span class="kt-subheader__desc">{{
						__('Fine&Plenties')}}</span></a> --}}
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		@php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$fines = $fines->where('society_id',$search_society_id);
		}else{
		$fines = $fines;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 col-xs-6">
					<label class="form-control-label">Select Society</label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true">
						<option selected disabled value=""> Select Society </option>
						<option @if ($search_society_id=='all' ) selected @endif value="all"> All </option>
						@foreach ($societies as $society)
						<option @if ($search_society_id==$society->id)
							selected
							@endif value="{{$society->id}}"> {{$society->name}} [{{$society->code}}]</option>
						@endforeach
					</select>
				</div>
				<div class="kt-section__content kt-section__content--solid mt-3 pt-3">
					<button type="submit" class="btn btn-primary btn-sm mt-1">Search</button>
				</div>

			</div>
		</form>

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-hammer"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Fine&Plenties')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-fine-penalties')
							<a href="{{ route('fines.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm"
								title="Add Fine"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Title')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Amount')}} </th>
							@can('create-fine-penalties')
							<th>{{ __('Imposed Fine')}} </th>
							@endcan

							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($fines as $key=>$fine)
						<tr>
							<td>{{ ++$key }}</td>
							<td>{{ $fine->title }}</td>
							<td> <b> {{ $fine->society->name ?? '' }} </b> </td>
							<td> <b class="text-danger"> {{ number_format($fine->fine_amount,0) }} </b> </td>
							@can('create-fine-penalties')
							<td>
								<a href="#" title="Imposed Fine" class="btn btn-brand btn-sm OpenImposedFine"
									Fine-ID="{{$fine->id}}" Fine-Title="{{$fine->title}}">
									<i class="fa fa-plus fa-lg"></i>
									{{ __('imposed Fine') }}
								</a>
							</td>
							@endcan

							<td>
								<a href="{{route('fines.show', $fine->id)}}" class="text-brand"> <i
										class="fa fa-eye fa-lg" title="View Detail"></i></a> &nbsp;
								@can('update-fine-penalties')
								<a href="{{route('fines.edit', $fine->id)}}" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit"></i> </a> &nbsp;
								@endcan
								@can('delete-fine-penalties')
								<a href="{{route('fines.destroy', $fine->id)}}" class="text-danger delete-confirm"
									del_title="Fine {{ substr($fine->title, 0, 20)}}"><i class="fa fa-trash-alt fa-lg"
										title="{{ __('Delete') }}"></i></a>
								@endcan
							</td>
						</tr>
						@empty
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

<div class="modal fade" id="imposed_fine_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Imposed Fine </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
				</button>
			</div>
			<form action="{{ route('imposedfine.store') }}" method="POST">
				@csrf
				<div class="modal-body pb-0 mb-0">
					<div class="row">
						<div class="form-group validated col-sm-12">
							<label class="form-control-label" for="title"> <b> {{ __('Fine Title:') }} </b></label>
							<input type="text" id="Fine-Title" class="form-control" readonly disabled>
						</div>

						<input type="hidden" name="fine_id" id="Fine-Id">

						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b>{{ __('Select Residents*:') }}</b></label>
							<select class="form-control kt-selectpicker" name="residents[]" data-live-search="true"
								autofocus="true" required multiple>
								<option disabled> {{ __('Select Residents')}}</option>
								@foreach ($residents as $resident)
								<option value="{{$resident->id}}"> {{$resident->name}} </option>
								@endforeach
							</select>
						</div>

						<div class="form-group validated col-md-6">
							<label class="form-control-label"><b>{{ __('Fine Date*') }}</b></label>
							<div class="input-group date">
								<input type="text" name="fine_date" class="kt_datepicker_validate form-control"
									placeholder="Select date" style="border-radius: 3px;"
									value="{{today()->format('Y-m-d')}}" required>
							</div>
						</div>

						<div class="form-group validated col-md-6">
							<label class="form-control-label"><b>{{ __('Due Date*') }}</b></label>
							<div class="input-group date">
								<input type="text" name="due_date" class="kt_datepicker_validate form-control"
									placeholder="Select date" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary btn-sm CloseImposedModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Fine Detail </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
				</button>
			</div>
			<div class="kt-portlet mb-0">
				<div class="kt-portlet__body mb-2 pb-0">
					<div class="kt-widget kt-widget--user-profile-3">
						<div class="kt-widget__top">
							<div class="kt-widget__media">
								<img src="{{url('assets/media/users/default.jpg')}}" alt="image"
									style="border-radius: 20%;">
							</div>

							<div class="kt-widget__content">
								<div class="kt-widget__head">
									<a href="#" id="invoiceTo" class="kt-widget__username">
										username
									</a>
									<div class="kt-widget__action">
										<div class="kt-widget__details">
											<b>Total Fine:</b>
											<span class="kt-widget__value"> <b> <span>Rs </span> <span
														id="FineAmountVal"></span> </b>
										</div>
									</div>
								</div>

								<div class="kt-widget__subhead">
									<span><b id="UserUniqueID"></b></span>
									<span class="text-right" style="float: right;">
										status:
										<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success"
											id="FineStatus"></span>
									</span>
								</div>
								<div class="kt-widget__head">
									<a class="kt-widget__username">
										<span style="font-size: 13px;"> Fine By: </span> <span id="FineBy"></span>
									</a>
								</div>

								<div class="kt-widget__subhead">
									<span><i class="fa fa-tags"></i> <span id="AdminLevel"></span></span>
								</div>
								<div class="kt-widget__info text-right">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body mt-0 pt-0">
				<div class="row">
					<div class="form-group validated col-sm-6">
						<label class="form-control-label" for="title"> <b> {{ __('Title:') }} </b></label>
						<input type="text" id="FineTitle" class="form-control" readonly disabled>
					</div>
					<div class="form-group validated col-sm-3">
						<label class="form-control-label"> <b> {{ __('Fine Date:') }} </b> </label>
						<input type="text" class="form-control" id="FineDate" readonly>
					</div>
					<div class="form-group validated col-sm-3">
						<label class="form-control-label"> <b> {{ __('Due Date:') }} </b> </label>
						<input type="text" class="form-control" id="DueDate" readonly>
					</div>

					<div class="form-group validated col-sm-12">
						<label class="form-control-label" for="title"> <b> {{ __('Description:') }} </b> </label>
						<textarea class="form-control" id="FineDescription" cols="30" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Add Payment </h5>
				<button type="button" class="close paymentModel" aria-label="Close"></button>
			</div>

			<form action="{{ route('fine.payment') }}" method="POST" id="PckgServiceModelForm">
				@csrf
				<input name="user_id" type="hidden" id="user_id" />
				<input name="fine_id" type="hidden" id="invoice_id" />
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
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript">
</script>
<script>
	$(".OpenImposedFine").click(function(){
    	var fine_id = parseInt($(this).attr('Fine-ID'));
    	var fine_title = $(this).attr('Fine-Title');
        $('#imposed_fine_modal').modal('show');

        $('#Fine-Title').val(fine_title);
        $('#Fine-Id').val(fine_id);
    });

    $(".CloseImposedModel").click(function(){
    	$('#imposed_fine_modal').trigger('reset');
		$('#imposed_fine_modal').modal('toggle');
    });

    $(".ViewFineDetail").click(function(){
    	var fine_id = parseInt($(this).attr('FineID'));
        var fine_list = <?php  echo json_encode($fines); ?>;
        var fine_detail = fine_list.find(x => x.id === fine_id);
        $('#kt_modal_1').modal('show');
        $('#FineAmountVal').html(fine_detail['fine_amount']);
        $('#FineTitle').val(fine_detail['title']);
        $('#FineDate').val(fine_detail.fine_date);
        $('#DueDate').val(fine_detail.due_date);
        $('#invoiceTo').html(fine_detail.user.name);
        $('#UserUniqueID').html(fine_detail.user.unique_id);
        $('#FineBy').html(fine_detail.fineby.name);
        $('#AdminLevel').html(fine_detail.fineby.level_slug);
        $('#FineDescription').val(fine_detail.description);
        $('#FineStatus').html(fine_detail.fine_status);
    });

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