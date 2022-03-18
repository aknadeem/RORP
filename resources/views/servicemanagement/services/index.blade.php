@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="{{ route('services.index') }}"><span class="kt-subheader__desc">{{ __('Services')}}</span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		@php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		$search_department_id = request()->search_department_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$services = $services->where('society_id',$search_society_id);
		}else{
		$services = $services;
		}
		if($search_department_id !='all' AND $search_department_id !=''){
		$services = $services->where('type_id',$search_department_id);
		}else{
		$services = $services;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-4 col-xs-4">
					<label class="form-control-label">Search by Society</label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true">
						<option selected disabled value=""> Select Society </option>
						<option @if ($search_society_id=='all' ) selected @endif value="all"> All </option>
						@forelse ($societies as $society)
						<option @if ($search_society_id==$society->id)
							selected
							@endif value="{{$society->id}}"> {{$society->name}} [{{$society->code}}]</option>
						@empty
						@endforelse
					</select>
				</div>
				<div class="form-group validated col-sm-4 col-xs-4">
					<label class="form-control-label">Search by Department</label>
					<select class="form-control kt-selectpicker" name="search_department_id" data-live-search="true">
						<option selected disabled value=""> Select department </option>
						<option @if ($search_department_id=='all' ) selected @endif value="all"> All </option>
						@forelse ($societies as $society)
    						@forelse ($society->departments as $department)
    						<option @if ($search_society_id==$society->id)
    							selected
    							@endif value="{{$department->id}}"> {{$department->name}}</option>
    						@empty
    						@endforelse
						@empty
						@endforelse
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
						<i class="fa fa-hands-helping"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Services')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-service-management')
							<a href="{{ route('services.create') }}"
								class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Service"><i
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
							<th>{{ __('Service Title')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Billing Type')}} </th>
							<th>{{ __('Department')}} </th>
							<th>{{ __('Subdepartment')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>

						@forelse ($services as $key=>$service)
						<tr>
							<td>{{ ++$key }}</td>
							<td>{{ __($service->title) }}</td>
							<td><b> {{ __($service->society->name) }}</b> </td>
							<td>{{ __($service->billing_type) }}</td>
							<td>{{ __($service->servicetype->name ?? '') }}</td>
							<td>{{ __($service->subtype->name ?? '') }}</td>

							<td>
								@can('update-service-management')
								<a href="{{route('services.edit', $service->id)}}" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit Service"></i> </a> &nbsp;
								@endcan

								@can('delete-service-management')
								<a href="{{route('services.destroy', $service->id)}}" class="text-danger delete-confirm"
									del_title="Service {{ substr($service->title, 0, 20)}}"><i
										class="fa fa-trash-alt fa-lg" title="{{ __('Delete Service') }}"></i></a>
								@endcan
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
@endsection