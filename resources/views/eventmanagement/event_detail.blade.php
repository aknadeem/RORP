@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Event Management
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
						
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									{{$event->title ?? ''}}
								</a>
								<div class="kt-widget__action">
									<span type="button" class="btn btn-label-success btn-sm btn-upper btn-document">{{$event->event_date->format('d M, Y')}}</span>&nbsp;
								</div>
							</div>
							<div class="kt-widget__subhead">
								<a href="#"><i class="flaticon-location"></i>{{$event->event_venue ?? ''}}</a>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>

		<!-- end:: Begin Deal Sector -->
		@if ($event->sectors->count() > 0)
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
							@forelse ($event->sectors as $key=>$sector)
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

		{{-- @if ($dealsdiscount->descriptionition !='') --}}
			<div class="kt-portlet kt-portlet--mobile">
				
				<div class="kt-portlet__body">
					{!! $event->description ?? '' !!}

				</div>
			</div>
		{{-- @endif --}}
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