@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<span class="kt-subheader__desc">{{ __('User Services')}}</span>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
		@php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		$search_user_id = request()->search_user_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$users = $users->where('society_id',$search_society_id);
		}else{
		$users = $users;
		}
		if($search_user_id !='all' AND $search_user_id !=''){
		$users = $users->where('id',$search_user_id);
		}else{
		$users = $users;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-1"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-4 col-xs-6">
					<label class="form-control-label">Search by user </label>
					<select class="form-control kt-selectpicker" name="search_user_id" data-live-search="true">
						<option selected disabled value=""> Select user </option>
						<option @if ($search_user_id=='all' ) selected @endif value="all"> All </option>
						@foreach ($users as $user)
						<option @if ($search_user_id==$user->id)
							selected
							@endif value="{{$user->id}}"> {{$user->name}} [{{$user->unique_id}}]</option>
						@endforeach
					</select>
				</div>
				<div class="form-group validated col-sm-4 col-xs-6">
					<label class="form-control-label">Search by society </label>
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
				{{-- <div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div> --}}
			</div>
		</form>

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
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('#')}}</th>
							<th>{{ __('User Name')}} </th>
							<th>{{ __('Id')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Total Services')}} </th>
							<th>{{ __('Invoice')}} </th>
							{{-- <th>{{ __('Actions')}}</th> --}}
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $key=>$user)
						<tr>
							<td>{{ ++$key }}</td>
							<td>{{ $user->name }}</td>
							<td>{{ $user->unique_id ?? '' }}</td>
							<td>{{ $user->society->name }}</td>
							<td>
								<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
									<span class="btn btn-success btn-sm"> <b> {{ $user->totalServices }} </b> </span>
									<a href="{{ route('userservices.show', $user->id) }}" title="View Detail"
										class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
								</div>
							</td>
							<td>
								@if (Auth::user()->user_level_id < 4) <a
									href="{{ route('minvoice.create', $user->id) }}" title="Create Invoice"
									class="btn btn-primary btn-sm" data-toggle="kt-tooltip" data-placement="bottom"
									data-skin="brand" title="Click to create Invoice"> Create Invoice</a>
									@endif

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