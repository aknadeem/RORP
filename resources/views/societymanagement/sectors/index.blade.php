@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society Blocks')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('societyblocks.index') }}"><span class="kt-subheader__desc">{{ __('Societies')}}</span></a>
                
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
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
						{{ __('Societies')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<a href="{{ route('societyblocks.create') }}" class="btn btn-brand btn-elevate btn-icon-sm" title="Create Block"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('ID')}}</th>
							<th>{{ __('Name')}} </th>
							<th>{{ __('Sector')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Action')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($societyblocks as $block)
							<tr>
								<td>{{$block->id}}</td>
								<td>{{ __($block->block_name) }}</td>
								<td>{{ __($block->sector->sector_name) }}</td>
								<td>{{ __($block->society->name) }}</td>
								<td>
                                    @can('update-society-management')
									   <a href="{{route('societyblocks.edit', $block->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit dep"></i> </a> &nbsp;
                                    @endcan
                                    @can('delete-society-management')
									<a href="{{route('societyblocks.destroy', $block->id)}}" class="text-danger delete-confirm" del_title="Society {{$block->block_name}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Module') }}"></i></a>
                                    @endcan
								</td>
							</tr>
						@empty
						<tr>
							<td colspan="6" class="text-danger text-center"> No Data Available </td>
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

@section('modal-popup')
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    {{ __('Add Sector')}} <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('society.addsector') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                    	<input type="hidden" name="society_id" id="soc_id">
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Society')}} </b></label>
                                <input type="text" class="form-control" name="department" readonly id="soc_name" disabled>
                                @error('society_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Sector*')}} </b></label>
                                <input type="text" class="form-control" name="sector_name" placeholder="Add Sector Name" required>
                                @error('sector_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
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
<script>
	$(".addHodModal").click(function(event) {
    var soc_name = $(this).attr("soc_name");
    // alert(soc_name);
    var soc_id = $(this).attr("soc_id");
    $('#kt_modal_1').modal('show');
    $('#soc_name').val(soc_name);
    $('#soc_id').val(soc_id);
});
</script>

    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>
@endsection