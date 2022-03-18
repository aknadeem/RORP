@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					ServiceManagement
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						User Services Detail </span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<a href="{{URL::previous()}}" class="btn btn-default btn-bold">
					Back </a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!--Begin:: Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<div class="kt-widget kt-widget--user-profile-3">
					<div class="kt-widget__top">
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<div class="kt-widget__user">
									<a href="#" class="kt-widget__username">
										{{ $user_services->name ?? '-' }}
									</a>
									<span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success p-2">{{  $user_services->level_slug }}</span>
									
								</div>
							</div>
							<div class="kt-widget__subhead mb-2">
								<span><i class="fa fa-hands-helping"></i> Total Services: &nbsp; <b>{{ $user_services->totalServices}}</b> </span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--End:: Portlet-->
		<div class="row">
			<div class="col-xl-8">
				<!--Begin:: Portlet-->
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head kt-portlet__head--lg">
						<div class="kt-portlet__head-label">
							<span class="kt-portlet__head-icon">
							<i class="fa fa-hands-helping"></i>
							</span>
							<h3 class="kt-portlet__head-title">
							{{ __('User Services')}}
							</h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								<div class="kt-portlet__head-actions">
									@can('create-invoices')
										<a href="{{ route('minvoice.create', $user_services->id) }}" class="btn btn-brand btn-sm  btn-elevate btn-icon-sm" title="Create Invoice"><i class="fa fa-plus"></i>{{ __('Create Invoice')}}</a>
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
									<th>{{ __('Service')}}</th>
									<th>{{ __('Package')}}</th>
									<th>{{ __('Price')}} </th>
									<th>{{ __('Discount')}} </th>
									<th>{{ __('Final Price')}} </th>
									<th>{{ __('Start Date')}}</th>
									<th>{{ __('Status')}}</th>
									<th>{{ __('Action')}}</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($user_services->services as $key=>$user_service)
									<tr>
										<td>{{ ++$key}}</td>
										<td>{{$user_service->service->title}}</td>
										<td>{{$user_service->package->title}}</td>
										<td> <b> {{ number_format($user_service->price,0)}} </b></td>
										<td class="text-success"> <b> {{ number_format($user_service->discount_amount,0)}}</b> </td>
										<td class="text-danger"><b> {{number_format($user_service->final_price,0)}}</b> </td>
										<td>{{$user_service->start_date}}</td>
										<td>
											{{-- @can('update-service-management') --}}
				                                <a href="{{ route('userservices.edit',$user_service->id) }}" class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success  confirm-status">
				                                <label class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success" >
				                                    <input   {{ $user_service->status == 1 ? 'checked' : '' }} type="checkbox" >
				                                    <span  {{ $user_service->status == 1 ? 'title=Deactive' : 'title=Active' }} class="slider {{ $user_service->status == 1 ? '' : '' }}"></span>
				                                </label>
				                                </a>
			                                {{-- @endcan          --}}
			                             </td>
										<td>
											<span service_title="{{$user_service->service->title}}" service_package="{{$user_service->package->title}}" ex-price="{{$user_service->price}}" sr_id="{{$user_service->id}}" ex-discount-percent="{{$user_service->discount_percentage}}" ex-discount="{{$user_service->discount_amount}}" class="btn btn-primary btn-sm openPaymentModel" title="Add Discount">
										    	<i class="fa fa-plus"></i>Discount
											</span>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-danger text-center"> No Data Available </td>
									</tr>						
								@endforelse
							</tbody>
						</table>
						<!--end: Datatable -->
					</div>
				</div>
				<!--End:: Portlet-->
			</div>
		</div>
	</div>
	<!-- end:: Content -->
</div>
{{-- Active Deactive User Service form --}}
<form method="post" id="status-form"> 
    @method('GET')
    @csrf
</form>
@endsection
@section('modal-popup')
	<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title"> <i class="fa fa-tags"></i> Add Discount </h5>
	                <button type="button" class="close paymentModel" aria-label="Close"></button>
	            </div>
	            <form action="{{ route('userservices.update') }}" method="POST" id="ServiceDiscountModelForm">
	                @csrf
	                @method('PUT')
	                <input name="user_service_id" type="hidden" id="user_service_id" />
	                <input name="package_price" type="hidden" id="package_price" />
	                <div class="modal-body">
	                    <div class="row">
	                    	<div class="form-group col-lg-12">
	                            <label class="form-control-label"><b>{{ __('Service Title:') }}</b></label>
	                            <div class="input-group">
	                                <input type="text" id="ExServiceTitle" class="form-control" readonly disabled placeholder="Service Title" style="border-radius: 3px;">
	                            </div>
	                        </div>
	                        <div class="form-group col-lg-12">
	                            <label class="form-control-label"><b>{{ __('Package Title:') }}</b></label>
	                            <div class="input-group">
	                                <input type="text" id="ExPackageTitle" class="form-control" readonly disabled placeholder="Package Title" style="border-radius: 3px;">
	                            </div>
	                        </div>
	                        
							<div class="form-group col-lg-6">
	                            <label class="form-control-label"><b>{{ __('Discount Amount:*') }}</b></label>
	                            <div class="input-group date">
	                                <input type="number" step="any" min="0" max="" required id="discount_amount" name="discount_amount" class="form-control" placeholder="Enter Discount">
	                                @error('payed_amount')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group col-lg-6">
	                            <label class="form-control-label"><b>{{ __('Amount Percentage:*') }}</b></label>
	                            <div class="input-group">
	                                <input type="number" step="any" min="0" max="100" required id="discount_percent" name="discount_percent" class="form-control" placeholder="Discount Amount %">
	                            </div>
	                        </div>
	                       
	                        <div class="form-group col-lg-6">
		                        <label class="form-control-label"><b>{{ __('Package Price:') }}</b></label>
	                            <div class="input-group">
	                                <input type="number" readonly disabled step="any" min="0" id="pckgPrice" class="form-control" placeholder="Package Price">
	                            </div>
		                    </div>

		                    <div class="form-group col-lg-6">
		                        <label class="form-control-label"><b>{{ __('Final Price:') }}</b></label>
	                            <div class="input-group">
	                                <input type="number" step="any" min="0" max="" name="final_amount" id="finalAmount" readonly class="form-control" placeholder="Final Amount">
	                            </div>

	                            <span class="text-danger mt-2"> <h6 id="PriceError"></h6></span>
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
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js') }}" type="text/javascript"></script>


    <script>
   	$(document).ready(function () {
   		//close Add Discount in user service Model
   		$('.paymentModel').click(function () {
			$('#ServiceDiscountModelForm').trigger('reset');
			$('#AddPaymentModel').modal('toggle');
			$('#PriceError').html('');
			$('#FinalAmt').html('');
		});
   		// open Add Discount in User Service Modal
   		$('.openPaymentModel').click(function () {
			$('#AddPaymentModel').modal('show');
			var user_service_Id = parseInt($(this).attr('sr_id'));
			var service_title = $(this).attr('service_title');
			var service_package = $(this).attr('service_package');
			var discount_amount = parseFloat($(this).attr('ex-discount'));
			var discount_percent = parseFloat($(this).attr('ex-discount-percent'));
			var pckgPrice = parseFloat($(this).attr('ex-price'));

	        $('#user_service_id').val(user_service_Id);

	        $('#package_price').val(pckgPrice);
	        $('#ExServiceTitle').val(service_title);
	        $('#ExPackageTitle').val(service_package);
	        $('#discount_amount').val(discount_amount);
	        $('#discount_percent').val(discount_percent);
	        $('#pckgPrice').val(pckgPrice);
	        var finalAmount = pckgPrice - discount_amount;
	        $('#finalAmount').val(finalAmount);
	        $('#finalAmount').attr({"max" : pckgPrice});
	        $('#discount_amount').on('input', function() {
	    		var final_amount = pckgPrice;
	    		var discountAmount = parseFloat($(this).val());
	    		if(discountAmount > final_amount){
	    			$('#PriceError').html('<b>Discount Amount Cannot be greater Than '+final_amount+'</b>');
	    		}else{
	    			var discountPercent = discountAmount/parseFloat(final_amount)*100;
	    			var new_amount = parseFloat(final_amount) - discountAmount;
	    			$('#finalAmount').val(new_amount);
	    			$('#discount_percent').val(discountPercent);
	    			$('#PriceError').html('');
	    		}
	    	});
	    	$("#discount_percent").on('input', function() {
	    		var final_amount = pckgPrice;
	    		var Percentage = parseInt($(this).val());
	    		if(Percentage > 100){
	    			$('#PriceError').html('<b>Discount Percentage Cannot be greater Than 100</b>');
	    		}else{
	    			var PercentAmt = Percentage/100*parseFloat(final_amount);
	    			var new_amount_percent = parseFloat(final_amount) - PercentAmt;

	    			$('#finalAmount').val(new_amount_percent);
	    			$('#discount_amount').val(PercentAmt);
	    			$('#PriceError').html('');
	    		}
	    	});
		});
	});
   </script>
@endsection