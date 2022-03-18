@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">         
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}} </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentdata.index') }}"><span class="kt-subheader__desc">{{ __('Resident HandyMen')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		$search_landlord_id = request()->search_landlord_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$residenthandymen = $residenthandymen->where('society_id', $search_society_id);
		}else{
		$residenthandymen = $residenthandymen;
		}
		if($search_landlord_id !='all' AND $search_landlord_id !=''){
		$residenthandymen = $residenthandymen->where('resident_data_id', $search_landlord_id);
		}else{
		$residenthandymen = $residenthandymen;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-1"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-4 col-xs-4">
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
				
				<div class="form-group validated col-sm-4 col-xs-4">
					<label class="form-control-label">Select Landlord</label>
					<select class="form-control kt-selectpicker" name="search_landlord_id" data-live-search="true">
						<option selected disabled value=""> Select Landlord </option>
						<option @if ($search_landlord_id=='all' ) selected @endif value="all"> All </option>
						@foreach ($residenthandymen as $resident)
						<option @if ($search_landlord_id==$resident->resident_data_id)
							selected
							@endif value="{{$resident->resident_data_id}}"> {{$resident->residentdata->name}}
						</option>
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
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Handy Men Information') }}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
						@can('create-resident-management')
							<a href="{{ route('residenthandymen.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Handy men">
								<i class="fa fa-plus mb-1"></i>Create </a>
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
							<th>ID</th>
							<th> Name </th>
							<th> Landlord </th>
							<th> Society </th>
							<th> Type </th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($residenthandymen as $user)
							<tr>
								<td>{{$user->id}}</td>
								<td>{{$user->name}}</td>
								<td> <b> {{$user->residentdata->name}} </b> </td>
								<td>  {{$user->society->name}}  </td>
								<td>{{$user->handy_type->title}}</td>
								<td>
									@can('update-resident-management')
	                                	<a href="{{ route('residenthandymen.edit', $user->id) }}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit HandyMan"></i> </a> &nbsp;
	                                @endcan

									@can('delete-resident-management')
									    <a href="{{route('residenthandymen.destroy', $user->id)}}" class="text-danger delete-confirm" del_title="HandyMan {{$user->name}}"><i class="fa fa-trash-alt fa-lg" title="Delete HandyMen"></i></a>
									@endcan
								</td>
							</tr>
						@endforeach
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
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
@endsection