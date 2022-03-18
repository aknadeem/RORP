@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Deal&Discount
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
				<div class="kt-widget kt-widget--user-profile-3">
					<div class="kt-widget__top">
						@if ($dealsdiscount->vendor->logo)
							<img alt="Logo" width="200px" height="100px" src="{{ asset('uploads/vendor/'.$dealsdiscount->vendor->logo.'?v=1') }}" />
						@else
							<div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light">
								MP
							</div>
						@endif
						
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									{{$dealsdiscount->title ?? ''}}
								</a>
								<div class="kt-widget__action">
									<span type="button" class="btn btn-label-success btn-sm btn-upper btn-document">{{$dealsdiscount->start_date->format('d M, Y')}}</span>&nbsp;
									<span type="button" class="btn btn-label-danger btn-sm btn-upper btn-document">{{$dealsdiscount->end_date->format('d M, Y')}}</span>&nbsp;
								</div>
							</div>
							<div class="kt-widget__subhead">
								<a href="#"><i class="flaticon-location"></i>{{$dealsdiscount->society->name ?? ''}}</a>
							</div>
							<span class="kt-widget24__desc">
								vendor: <b> &nbsp;&nbsp; {{$dealsdiscount->vendor->title ?? ''}}</b>
							</span>
							<!--<div class="kt-widget__info mt-2">-->
							<!--	<div class="kt-widget__desc">-->
							<!--		{!! $dealsdiscount->description ?? '' !!}-->
							<!--	</div>-->
							<!--	<div class="kt-widget__progress">-->
									
							<!--	</div>      -->
							<!--</div>-->
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<!-- end:: Begin Deal Sector -->
		@if ($dealsdiscount->sectors->count() > 0)
			<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							{{ __('Sectors')}}
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<!--begin: Datatable -->
					<table class="table table-striped table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
								<th>{{ __('#')}}</th>
								<th>{{ __('Society Code')}} </th>
								<th>{{ __('Society Name')}} </th>
								<th>{{ __('Sector Name')}} </th>
							</tr>
						</thead>
						<tbody>
							@forelse ($dealsdiscount->sectors as $key=>$sector)
								<tr>
								 	<td>{{ ++$key }}</td>
		                            <td>{{ $sector->society->code }}</td>
		                            <td>{{ $sector->society->name }}</td>
		                            <td>{{ $sector->sector_name }}</td>              
								</tr>
	                        @empty
							<tr>
								<td colspan="4" class="text-danger text-center"> No Data Available </td>
							</tr>						
							@endforelse
						</tbody>
					</table>
					<!--end: Datatable -->
				</div>
			</div>
		@endif
		<!-- end:: End Deal Sector -->
		
		<div class="kt-portlet">
			<div class="kt-portlet__body">
			    <h6>Description: </h6>
				{!! $dealsdiscount->description ?? '' !!}
			</div>
		</div>
		
	</div>
	<!-- end:: Content -->
</div>
@endsection
@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
@endsection