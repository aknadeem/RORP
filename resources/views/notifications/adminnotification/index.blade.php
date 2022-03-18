@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('Notification')}}</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="#"><span class="kt-subheader__desc">{{ __('Custom Notifications')}}</span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		@php
		// filter department from departments array
		$search_society_id = request()->search_society_id;
		if($search_society_id !='all' AND $search_society_id !=''){
		$notifications = $notifications->where('society_id', $search_society_id);
		}else{
		$notifications = $notifications;
		}
		@endphp
		<form action="" method="get" class="loader">
			<div class="alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 ">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
						<option selected disabled> {{ __('Select Society')}}</option>
						<option {{ ($search_society_id == 'all') ? 'selected' : '' }} value="all">
							{{ __('All Societies')}}
						</option>
						@forelse($societies as $soc)
						<option {{ ($search_society_id == $soc->id) ? 'selected' : '' }} value="{{$soc->id}}">
							{{ $soc->name }}
						</option>
						@empty
						<option disabled>No Society Found</option>
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
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Custom Notification')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-notifications')
							<a href="{{ route('create.notification') }}"
								class="btn btn-brand btn-sm btn-elevate btn-icon-sm"
								title="Create Custom Notification"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Description')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($notifications as $key=>$notify)
						<tr>
							<td>{{++$key}}</td>
							<td>{{ $notify->title}}</td>
							<td> <b> {{ $notify->society->name ?? '' }} </b> </td>
							<td>{!! Str::limit($notify->description,20) !!}</td>
							<td>
								@can('delete-notifications')
								<a href="{{ route('notification.detail', $notify->id) }}"
									class="text-info"> <i class="fa fa-eye fa-lg" title="View Detail"></i> </a>
								@endcan
								@can('update-notifications')
								<!--&nbsp;<a href="{{ route('edit.notification', $notify->id) }}"-->
								<!--	class="text-success"> <i class="fa fa-edit fa-lg" title="Click to edit"></i> </a>-->
								@endcan
								@can('delete-notifications')
								&nbsp;<a href="{{ route('delete.notification', $notify->id) }}" class="text-danger delete-confirm" del_title="Custom Notification"> <i class="fa fa-trash fa-lg" title="Click to Delete"></i> </a>
								@endcan
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="text-danger text-center"> No Data Available </td>
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
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection