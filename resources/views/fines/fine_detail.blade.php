@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Fine&Plenties
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						Detail </span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<a href="{{URL::previous()}}" class="btn btn-default btn-bold">
					Back </a>
			</div>
		</div>
	</div>
	<!-- end:: Subheader -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<div class="kt-widget__top">
					<div class="kt-widget__content">
						<div class="kt-widget__head">
							<h4> {{$fine->title ?? ''}}  &nbsp; &nbsp;<span class="btn btn-sm btn-font-lg  btn-label-brand">{{$fine->society->name}} </span></span></h4> 
						</div>
						<div class="kt-widget__subhead">
							<span class="btn btn-bold btn-sm btn-font-lg  btn-label-danger">Amount: {{ number_format($fine->fine_amount, 0) }} </span></span>
						</div>
					</div>
				</div>
				<p> {!! $fine->description ?? '' !!} </p>
			</div>
		</div>
		<!-- end:: Begin Fine Imposed Residents -->
		@if ($fine->imposed->count() > 0)
			<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Fine Imposed To:
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<!--begin: Datatable -->
					<table class="table table-striped table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
								<th>{{ __('#')}}</th>
								<th>{{ __('Resident')}} </th>
								<th>{{ __('Fine Date')}} </th>
								<th>{{ __('Due Date')}} </th>
								<th>{{ __('Fine By')}} </th>
								<th>{{ __('Status')}} </th>
								<th>{{ __('Action')}} </th>
							</tr>
						</thead>
						<tbody>
							@php
								$label_color = 'danger';
							@endphp
							@forelse ($fine->imposed as $key=>$impose)

							@php
								if($impose->fine_status == 'pending'){
									$label_color = 'warning';
								}else if($impose->fine_status == 'paid'){
									$label_color = 'success';
								}
							@endphp
								<tr>
								 	<td>{{ ++$key }}</td>
		                            <td>{{ $impose->user->name }} <br>[ <b> {{ $impose->user->unique_id }} </b> ] </td>
		                            <td>
		                            	<span style="width: 100px;"><span class="btn btn-bold btn-sm btn-font-md  btn-label-success"> {{ $impose->fine_date->format('d M, Y') }} </span></span>
		                            </td>
		                            <td>
		                            	<span style="width: 100px;"><span class="btn btn-bold btn-sm btn-font-md  btn-label-danger"> {{ $impose->due_date->format('d M, Y') }} </span></span>

		                            </td>
		                            <td>{{ $impose->fineby->name }} <br> [ <b> {{ $impose->fineby->level_slug }} </b> ]</td>  

		                            <td>
		                            	@if($impose->fine_status == 'pending')
								 			{{-- @can('add-payment-invoice') --}}
								 				<button imposed-id="{{$impose->id ?? 0}}" fine-id="{{$impose->fine_id ?? 0}}" user_id="{{ $impose->user_id ?? 0}}" invoice-price="{{$fine->fine_amount ?? 0}}" UserName="{{$impose->user->name ?? ''}}" class="btn btn-bold btn-sm btn-label-brand openPaymentModel"  data-container="body" data-toggle="kt-popover" data-placement="top" data-content="Click to add Payment">
											    	<i class="fa fa-plus"></i>Payment
												</button>
											{{-- @endcan --}}
								 		@else
											<span style="width: 100px;"><span class="btn btn-bold btn-sm btn-label-{{$label_color}}"> <i class="fa fa-credit-card"></i> {{ ucfirst($impose->fine_status ?? '')}} </span></span>
								 		@endif
		                            </td> 
		                            <td>
		                            	<a href="{{ route('imposedfine.show', $impose->id) }}" data-container="body" data-toggle="kt-popover" data-placement="top" data-content="Click to view detail"  class="btn btn-bold btn-sm btn-label-brand" > <i class="fa fa-eye fa-lg"></i>View</a> &nbsp;
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
		@endif
		<!-- end:: End Fine Imposed Residents  -->
	</div>
	<!-- end:: Content -->
</div>
@endsection



@section('modal-popup')
	@include('_partial.fine_pay_view_modal', ['fine'=> $fine])
@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js') }}" type="text/javascript"></script>
@endsection