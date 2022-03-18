@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Sops')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('sops.index') }}"><span class="kt-subheader__desc">{{ __('Sops')}}</span></a>
               
            </div>
        </div>
    </div>
    @php
        $society_id = $society_id ?? 0;
    @endphp
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	    
	    @include('_partial.search_with_societies', ['society_id' => $society_id,'societies'=> $societies, 'filter_url' => 'sop.filter'])
	    
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Sops List')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-pages')
								<a href="#" id="viewAttachment" class="btn btn-success btn-sm btn-elevate btn-icon-sm" title="View Attachment"><i class="fa fa-eye mb-1"></i>{{ __('Attachment')}}</a>
								&nbsp;

								<a href="#" id="addSopAttachment" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Add Attachment"><i class="fa fa-file-upload mb-1"></i>{{ __('Attachment')}}</a>
								&nbsp;
								<a href="{{ route('sops.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Sop"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('ID')}}</th>
							<th>{{ __('Title')}} </th>
							<th>{{ __('Societies')}} </th>
							<th>{{ __('Description')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($sops as $sop)
							<tr>
							 	<td>{{ $sop->id }}</td>
	                            <td>  {{ __($sop->title) }} </td>
	                            <td> <button type="button" class="btn btn-bold btn-label-brand btn-sm twoFourSevenSocieties" title="View Societies" two_four_title="{{ __($sop->title) }}" two_four_id="{{$sop->id}}"><i class="fa fa-eye mb-1"></i>view </button> </td>
	                            <td> {!! Str::limit(preg_replace("/<img[^>]+\>/i", "(image) ", $sop->description), 50) !!} </td>
	                            <td>
	                            	@can('view-pages')
	                            	<a href="{{ route('sops.show', $sop->id) }}"> <i class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;
	                            	@endcan

	                            	@can('create-pages')
										<a href="{{route('sops.edit', $sop->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Sop"></i> </a> &nbsp;
									@endcan
									@can('create-pages')
										<a href="{{route('sops.destroy', $sop->id)}}" class="text-danger delete-confirm" del_title="Sop {{$sop->title}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Sop') }}"></i></a>
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
@section('modal-popup')
<div class="modal fade" id="SubdepartmentData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="ModalTitle"></span> [Societies]</h5> <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                {{-- <input type="hidden" name="department_id" id="dep_id"> --}}
                <div class="row">
                    <div class="kt-notification-v2 col-md-12" id="dropdownSocieties">
                        No Data Found
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
            </div>
        </div>
    </div>
</div>
	@include('_partial.upload_pages_attachment', ['upload_url'=> route('uploadsop.attachment')])

	@include('_partial.view_attachments', ['modal_title' => 'Sop','attachments'=> $attachments, 'delete_url' => 'deletesop_attachment'])
@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
     <script>
    	$(".twoFourSevenSocieties").click(function(event) {
        	// alert('hello');
		    var two_four_seven_title = $(this).attr("two_four_title");
		    var two_four_id = parseInt($(this).attr("two_four_id"));
		    // alert
		    $('#SubdepartmentData').modal('show');

		    $('#ModalTitle').html(two_four_seven_title);
		    var two4sevens = <?php echo json_encode($sops); ?>;
		    var two_four_seven = two4sevens.find(d => d.id === two_four_id);
		    var append_data = "";
		    var two_four_seven_societies = two_four_seven.societies;
		    // console.log(two_four_seven_societies);
	        if(two_four_seven_societies.length > 0){
	            for (var i = 0; i < two_four_seven_societies.length; i++) {
	                append_data += '<a href="#" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">'+two_four_seven_societies[i].name[0] +'</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> '+two_four_seven_societies[i].name +' </div> <div class="kt-notification-v2__item-desc"> '+two_four_seven_societies[i].slug +' </div></div></a>';
	            }
	        }
	        if(append_data == ''){
                append_data = '<h6 class="text-danger"> No Societies found </h6>';
            }
	        $("#dropdownSocieties").html(append_data);
		});
		
		
        $("#viewAttachment").click(function(event) {
    		$('#viewAttachmentList').modal('show');
    	});
    </script>
@endsection