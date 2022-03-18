@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('permissions.index') }}"><span class="kt-subheader__desc">{{ __('Permissions')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						Permissions
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@if (Auth::user()->user_level_id == 1)
								@can('create-user-management')
									<a href="{{ route('permissions.create') }}" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create User Group"><i class="fa fa-plus mb-1"></i>Create </a>
								@endcan
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('Id')}}</th>
							<th> {{ __('Title')}} </th>
							<th> {{ __('Slug')}} </th>
							<th> {{ __('Module')}} </th>
							@if (Auth::user()->user_level_id ==1)
									@can('delete-user-management')
							<!--<th>{{ __('Actions')}}</th>-->
							@endcan
								@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($permissions as $permission)
							<tr>
								<td>{{ __($permission->id)}}</td>
								<td>{{ __($permission->title)}}</td>
								<td>{{ __($permission->slug)}}</td>
								<td>{{ __($permission->module->slug ?? '')}}</td>
								@if (Auth::user()->user_level_id ==1)
									@can('delete-user-management')
									<!--<td>-->
									<!--	<a href="{{route('permissions.destroy', $permission->id)}}" class="text-danger delete-confirm" del_title="Permission {{$permission->title}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Permission') }}"></i></a>-->
									</td>
									@endcan
								@endif
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
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script>
"use strict";
var KTDatatablesAdvancedColumnRendering = function() {
	var initTable1 = function() {
		var table = $('#kt_table_1');
		// begin first table
		table.DataTable({
			responsive: true,
			paging: true,
			
		});
	};
	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		}
	};
}();
jQuery(document).ready(function() {
	KTDatatablesAdvancedColumnRendering.init();
});
</script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection