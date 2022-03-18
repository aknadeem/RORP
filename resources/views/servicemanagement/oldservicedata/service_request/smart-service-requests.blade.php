@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __('smart-service-request')}}</span>
               
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-hands-helping"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Smart Service Request')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							{{-- <a href="{{ route('requestservice.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Request Service"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('#')}} </th>
							<th>{{ __('User')}} </th>
							<th>{{ __('Service')}} </th>
							<th>{{ __('Service Type')}} </th>
							<th>{{ __('Package')}} </th>
							<th>{{ __('Remarks')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@php
                            $user_id = Auth::user()->id;
                            $user_level_id = Auth::user()->user_level_id;
                        @endphp
					
						@forelse ($service_requests as $key=>$service_req)
							<tr>
							 	<td>{{ ++$key }}</td>
							 	<td> {{$service_req->user->name}} </td>
							 	<td>{{ $service_req->service->title }}</td>
							 	<td>{{ $service_req->service->billing_type }}</td>
	                            <td>{{ __($service_req->package->title ?? '--') }}</td>

	                            <td>
	                            	<span class="btn btn-primary btn-sm">{{ $service_req->status }}</span>

	                            	@if ($user_level_id < 5 )
	                            		@if ($service_req->service->billing_type !='no_billing' AND $service_req->package_id > 0)

	                            			@if ($service_req->status != 'approved')
		                            			@can('update-service-management')
		                            				<span request-id="{{$service_req->id}}" data-pckg-price="{{$service_req->package->price}}" request_ispaid="{{$service_req->is_paid}}" class="btn btn-sm btn-clean btn-icon btn-icon-md openPckgModel" title="Update">
				                                        <i class="flaticon-edit"></i>
				                                    </span>
				                                @endcan
	                            			@endif
		                                @else
			                                @can('update-service-management')
			                                	<span data-toggle="modal" data-target="#close-re-assign" data-target-id="{{$service_req->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
			                                        <i class="flaticon-edit"></i>
			                                    </span>
			                                @endcan
	                            		@endif
	                                @else
		                                @can('update-service-management')
		                                	<span data-toggle="modal" data-target="#todoWorking" data-target-id="{{$service_req->id}}" service_status="{{$service_req->status}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
	                                        	<i class="flaticon-edit"></i>
	                                    	</span>
	                                   	@endcan
	                                @endif

	                            </td>

	                            <td>
	                            	@can('view-service-management')
										<a href="{{route('requestservice.show', $service_req->id)}}" class="text-info"> <i class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;
									@endcan
									@can('update-service-management')
										<a href="{{route('requestservice.edit', $service_req->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Service Request"></i> </a> &nbsp;
									@endcan
									{{-- @can('delete-service-management')
										<a href="{{route('requestservice.destroy', $service_req->id)}}" class="text-danger delete-confirm" del_title="Service Request {{ substr($service_req->title, 0, 20)}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Service Request') }}"></i></a>
									@endcan --}}
								</td>                       
							</tr>
                        @empty
						<tr>
							<td colspan="7" class="text-danger text-center"> No Data Available </td>
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

	<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel"> Add Remarks </h5>
	                <button type="button" class="close closetodoWorkingModel" aria-label="Close"></button>
	            </div>
	            <form action="{{ route('request.addremarks') }}" method="POST" id="todoWorkingForm">
	                @csrf
	                <input class="form-control" name="service_request_id" type="hidden" id="service_request_id" />
	                <div class="modal-body">
	                    <div class="form-group row">
	                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
	                        <div class="col-9">
	                            <div class="kt-radio-inline">
	                            	<label class="kt-radio kt-radio--solid kt-radio--warning">
	                                    <input type="radio" value="in_process" id="pending" name="service_status" required /> 
	                                    In Process
	                                    <span></span>
	                                </label>

	                                <label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
	                                    <input type="radio" id="completed" value="completed" name="service_status" required />Completed
	                                    <span></span>
	                                </label>

	                                <label class="kt-radio kt-radio--solid kt-radio--dark">
	                                    <input type="radio" id="incorrect" value="incorrect" name="service_status" required />InCorrect
	                                    <span></span>
	                                </label>
	                                
	                            </div>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group row">
	                        <div class="col-md-12">
	                        <label for="message-text" class="form-control-label">Comments:*</label>

	                        <textarea class="form-control" name="comments" required></textarea>
	                        </div>
	                        
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-secondary closetodoWorkingModel">Close</button>
	                    <button type="submit" class="btn btn-primary">Save</button>
	                </div>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Service Status</h5>
	                <button type="button" class="close closeModel" aria-label="Close"></button>
	            </div>
	            <form action="{{ route('request.updatestatus') }}" method="POST" id="close-re-assignForm">
	                @csrf
	                <input class="form-control" name="service_request_id" type="hidden" id="cid" />
	                <div class="modal-body">
	                    <div class="form-group row">
	                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
	                        <div class="col-9">
	                            <div class="kt-radio-inline">
	                                <label class="kt-radio kt-radio--solid kt-radio--dark">
	                                    <input type="radio" value="closed"  name="service_status" required /> 
	                                    Close
	                                    <span></span>
	                                </label>
	                                <label class="kt-radio kt-radio--solid kt-radio--info">
	                                    <input type="radio" value="re_assign" id="re-assigne" name="service_status" required />Re-Assign
	                                    <span></span>
	                                    </label>
	                                @php
	                                    $user_level_id = Auth::user()->user_level_id;
	                                @endphp

	                                {{-- @if ($user_level_id == 4) --}}
	                                    <label class="kt-radio kt-radio--solid kt-radio--warning">
	                                        <input type="radio" value="modified" id="change_deparment" name="service_status" required /> Forward
	                                        <span></span>
	                                    </label>
	                                {{-- @endif --}}
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row re_assign_row" style="display: none;">
	                        <div class="form-group validated col-sm-12">
	                            <label for="message-text" class="form-control-label">Assign To:*</label>
	                            <select class="form-control refer_dep_row_input kt-selectpicker @error('user_id') is-invalid @enderror"  name="user_id" data-live-search="true">

	                                <option selected disabled>Select Supervisor</option>
	                                @foreach ($subdep_sups as $subdep_sup)
	                                    <option value="{{ $subdep_sup->supervisor_id }}">{{ $subdep_sup->supervisor->name}}</option>
	                                @endforeach
	                            </select>
	                        </div>
	                    </div>

	                    <div class="row refer_dep_row" style="display: none;">
	                        <div class="form-group validated col-sm-12">
	                            <label for="message-text" class="form-control-label">Forward To:*</label>
	                            <select class="form-control re_assign_row_input kt-selectpicker @error('department_id') is-invalid @enderror"  name="department_id" data-live-search="true">

	                                <option selected disabled>Select Department</option>
	                                @forelse ($departments as $dep)
	                                    <option value="{{ $dep->id }}">{{ $dep->name}}</option>
	                                @empty
	                                    <option disabled> No Department Found </option>
	                                @endforelse
	                            </select>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group row">
	                        <div class="col-md-12">
	                        <label for="message-text" class="form-control-label">Comments:*</label>

	                        <textarea class="form-control" name="comments" required></textarea>
	                        </div>
	                        
	                    </div>

	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-secondary closeModel">Close</button>
	                    <button type="submit" class="btn btn-primary">Save</button>
	                </div>
	            </form>
	        </div>
	    </div>
	</div>
	{{-- // model for service request package --}}
	<div class="modal fade" id="PckgServiceModel" tabindex="-1" role="dialog" aria-labelledby="PckgServiceModel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title"> Service Package Request </h5>
	                <button type="button" class="close closePckgModel" aria-label="Close"></button>
	            </div>

	            <form action="{{ route('request.pckgservice') }}" method="POST" id="PckgServiceModelForm">
	                @csrf
	                <input class="form-control" name="service_request_id" type="hidden" id="pckgRequestId" />
	                <div class="modal-body">

	                	<label class="col-form-label" style="float: right !important;"> <b>Package Price: Rs.<span id="pckgPrice"></span> </b> <br>

	                		<span id="FinalAmt" class="text-danger"></span> <br>
	                	</label>
	                    <div class="form-group row">
	                        <label for="example-text-input" class="col-3 col-form-label"> <b> Status:* </b></label>
	                        <div class="col-9">
	                            <div class="kt-radio-inline">
	                                <label class="kt-radio kt-radio--solid kt-radio--info approveOption" style="display: none;">
	                                    <input type="radio" value="approved" id="approve_pckg" name="service_status" required />Approve
	                                    <span></span>
	                                </label>

                                    <label class="kt-radio kt-radio--solid kt-radio--warning">
                                        <input type="radio" value="cancel" name="service_status" required /> Cancel
                                        <span></span>
                                    </label>
	                            </div>
	                        </div>
	                    </div>
	                  
	                    <span id="AmtError" class="text-danger"></span> <br>

	                    <div class="row" id="pckg_dates" style="display: none;">

	                    	
		                    <div class="col-lg-6">
	                            <label class="form-control-label"><b>{{ __('Start Date*') }}</b></label>
	                            <div class="input-group date">
	                                <input type="text" name="pckg_start_date" class=" kt_datepicker_validate form-control @error('pckg_start_date') is-invalid @enderror" placeholder="Select date" style="border-radius: 3px;">
	                                
	                                @error('pckg_start_date')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror

	                            </div>
	                        </div>
	                        
							<div class="col-lg-6">
	                            <label class="form-control-label"><b>{{ __('Add Discount') }}</b></label>
	                            <div class="input-group date">
	                                <input type="number" min="0" max="" id="discount_amount" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" placeholder="Add Discount">
	                                
	                                @error('discount_amount')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror

	                            </div>
	                        </div>
                        </div>
	                    <div class="row">
	                        <div class="col-md-12 mt-2">
	                        <label for="message-text" class="form-control-label"> <b> Comments:* </b></label>
	                        <textarea class="form-control" name="comments" required></textarea>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-secondary closePckgModel">Close</button>
	                    <button type="submit" class="btn btn-primary">Save</button>
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
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>

    <script>
     $("input[name='service_status']").click(function () {
     	// alert('hello');
        if ($('#re-assigne').is(':checked')) {

        	// alert('hello');
            $('.re_assign_row').show();
            $('.re_assign_row_input').prop('required', true);

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', false);

        }else if($('#change_deparment').is(':checked')){
            $('.refer_dep_row').show();
            $('.refer_dep_row_input').prop('required', true);

            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
        }else {
            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
            $('.re_assign_row_input').val('');

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', true);
            $('.refer_dep_row_input').val();
        }

    });

    </script>
    <script>
		$(document).ready(function () {
	        $('#todoWorking').on('show.bs.modal', function (e) {
	            var service_request_id = $(e.relatedTarget).data('target-id');
	            var service_status = $(e.relatedTarget).attr('service_status');
	            $('#service_request_id').val(service_request_id);
	        });
	    });

	    $(document).ready(function () {
	        $('#close-re-assign').on('show.bs.modal', function (e) {
	            var cid = $(e.relatedTarget).data('target-id');
	            $('#cid').val(cid);
	        });
	    });

	    $(document).ready(function () {
	        $('#feedback').on('show.bs.modal', function (e) {
	            var feed_cid = $(e.relatedTarget).data('target-id');
	            $('#feed_cid').val(feed_cid);
	        });
	    });

	    // clear form on modal close
	   	$('.closetodoWorkingModel').click(function () {
			$('#todoWorkingForm').trigger( 'reset' );
			$('#todoWorking').modal('toggle');
		});

		$('.closeModel').click(function () {
			$('#close-re-assignForm').trigger( 'reset' );
			$('#close-re-assign').modal('toggle');
		});

		$('.closePckgModel').click(function () {
			$('#PckgServiceModelForm').trigger('reset');
			$('#PckgServiceModel').modal('toggle');
		});

		$('.openPckgModel').click(function () {
			$('#PckgServiceModel').modal('show');

			var rid = $(this).attr('request-id');
			var is_paid = $(this).attr('request_ispaid');


	        var pckg_price = $(this).data('pckg-price');
	        $('#pckgRequestId').val(rid);
	        $('#pckgPrice').html(pckg_price);

	        
	        if(is_paid == 1){
	        	$('.approveOption').show();
	        }

	        $('#approve_pckg').prop('required', true);
	        $('#discount_amount').attr({"max" : pckg_price}); // add max in discount 
	        
	        $('#discount_amount').on('input', function() {
	    		var final_amount = parseFloat(pckg_price);
	    		var discountAmount = parseFloat($(this).val());

	    		// alert(discountAmount);
	    		if(discountAmount > final_amount || discountAmount < 0){
	    			$('#AmtError').html('<b>Discount Amount Cannot be greater Than: '+pckg_price+' OR Less Then 0 </b>');
	    			$('#FinalAmt').html('');
	    		}else{

	    			// var discountAmt = discountAmount/final_amount*100;
	    			var new_amount = final_amount - discountAmount;
	    			$('#FinalAmt').html('<b> Final Amount: '+new_amount+'</b>');
	    			// $('#new_final_amount').val(new_amount);
	    			// $('#DiscountPercent').val(discountPercent);
	    			$('#AmtError').html('');
	    		}
	    		
	    	});

		});
		$("input[name='service_status']").click(function () {
        // for Service Package Request
        if ($('#approve_pckg').is(':checked')) {
        	$('#pckg_dates').show();
        	$('#approve_pckg').prop('required', true);
        }else{
        	$('#pckg_dates').hide();
        	$('#approve_pckg').prop('required', false);
        }
    });
	</script>

@endsection