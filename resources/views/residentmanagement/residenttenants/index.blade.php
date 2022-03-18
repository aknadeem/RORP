@extends('layouts.base')
@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">         
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}} </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentdata.index') }}"><span class="kt-subheader__desc">{{ __('Residents')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __('Tenants')}}</span>
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
		$tenants = $tenants->where('society_id',$search_society_id);
		}else{
		$tenants = $tenants;
		}
		
		if($search_landlord_id !='all' AND $search_landlord_id !=''){
		$tenants = $tenants->where('landlord_id',$search_landlord_id);
		}else{
		$tenants = $tenants;
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
						@foreach ($tenants as $resident)
						<option @if ($search_landlord_id==$resident->landlord_id)
							selected
							@endif value="{{$resident->landlord_id}}"> {{$resident->landlord->name ?? ''}}
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
						{{ __('Resident Tenants') }}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-resident-management')
								<a href="{{ route('residenttenant.create') }}" class="btn btn-brand btn-elevate btn-icon-sm btn-sm" title="Create Tenant">
									<i class="fa fa-plus mb-1"></i>Create </a>
							@endcan
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable kt_table_11">
					<thead>
						<tr>
							<th>ID</th>
							<th>Image</th>
							<th> Landlord Name </th>
							<th> Name </th>
							<th> Society </th>
							<th> Otp Status </th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($tenants as $key=>$user)
							<tr>
								<td>{{++$key}}</td>
								<td>
									<div class="kt-avatar kt-avatar--outline" id="kt_user_avatar">
										<div class="kt-avatar__holder" style="background-image: url({{ asset('uploads/user/'.$user->image) }}); height: 50px;"></div>
										<label class="kt-avatar__upload" user_id="{{$user->id}}"  title="" data-original-title="Change avatar">
											<i class="fa fa-pen"></i>
										</label>
									</div>
								<td>{{$user->landlord_name}}</td>
								<td>{{$user->name}}</td>
								<td> <b>{{$user->society->name ?? ''}}</b>  </td>
								<td>
									@if ($user->user_data ?? '')
										<a class="btn btn-brand btn-elevate btn-icon-sm btn-sm text-white"> <i class="fa fa-check" title="Create Account"></i> Pin Verified  </a> 
									@else
										@if ($user->e_pin > 0 && $user->m_pin > 0)
											@if ($user->pin_verified == 1)
												<a href="#" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i class="fa fa-check" ></i> Pin Verified </a> 
											@else
												<b> Pin is Not Verified </b>
											@endif
										@else
										<b> Pin Can't Send </b>
										@endif
									@endif
								</td>
								<td>
									@can('create-resident-management')
	                               		<a href="{{ route('residenttenant.edit', $user->id) }}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Tenant"></i> </a> &nbsp;
	                               	@endcan
									@can('delete-resident-management')
									    <a href="{{route('residentdata.destroy', $user->id)}}" class="text-danger delete-confirm" del_title="User {{$user->name}}"><i class="fa fa-trash-alt fa-lg" title="Delete Tenant"></i></a>
									@endcan
								</td>
							</tr>
						@empty
						<tr>
						    <td colspan="7" class="text-danger text-center"> No tenant found </td>
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
				<h5 class="modal-title" id="exampleModalLabel">{{ __('Update Profile Picture')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group validated col-sm-12">
						<label class="form-control-label"><b>{{ __('Select Image*') }}</b></label>
						<form method="post" action="{{ route('storeUpdateImageWeb') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
			                @csrf
			                <input type="hidden" name="user_id" id="user_id">
			            </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
@endsection
@section('top-styles')
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
	<script src="{{ asset('assets/js/pages/crud/datatables/extensions/buttons.js?v=1') }} "></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1.0') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/js/pages/dropzone.js?v=1') }}" type="text/javascript"></script>
	<script>
	$(".kt-avatar__upload").click(function(event) {
	    var user_id = $(this).attr("user_id");
	    $('#kt_modal_1').modal('show');
	    // $('#soc_name').val(soc_name);
	    $('#user_id').val(user_id);
	});
    Dropzone.options.dropzone =
    {
        maxFilesize: 12,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
           return time+file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        timeout: 5000,
        success: function(file, response) 
        {
        	if(response['message'] === 'yes'){
        		location.reload();
        	}
            console.log(response);
        },
        error: function(file, response)
        {
           return false;
        }
	};
</script>
@endsection