@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('modules.index') }}"><span class="kt-subheader__desc">{{ __('modules')}}</span></a>
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
						{{ __('Modules')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-society-management')
								<a href="{{ route('modules.create') }}" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create User Group"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Slug')}} </th>
							<th>{{ __('Action')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($modules as $key=>$module)
							<tr>
								<td>{{++$key}}</td>
								<td>{{ __($module->title) }}</td>
								<td>{{ __($module->slug)}}</td>
								<td>
									@can('update-society-management')
										<a href="{{route('modules.edit', $module->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Module"></i> </a> &nbsp;
									@endcan
									@can('delete-society-management')
										<!--<a href="{{route('modules.destroy', $module->id)}}" class="text-danger delete-confirm" del_title="Module {{$module->title}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Module') }}"></i></a>-->
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

{{-- @section('modal-popup')
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title" id="exampleModalLabel"> Create Permission </h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form" method="POST" action="{{ route('modules.store') }}">
                    @csrf
    				<div class="modal-body">
    					<div class="row">
                            <div class="form-group validated col-sm-12">
    							<label class="form-control-label">{{ __('Title*') }}</label>
    							<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" required autofocus>

    							@error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
    						</div>
    					</div>
    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    					<button type="submit" class="btn btn-primary">Submit</button>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>
@endsection --}}
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