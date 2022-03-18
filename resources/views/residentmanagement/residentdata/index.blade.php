@extends('layouts.base')
@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('ResidentManagement')}} </h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="{{ route('residentdata.index') }}"><span class="kt-subheader__desc">{{
						__('Residents')}}</span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		@php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$residents = $residents->where('society_id',$search_society_id);
		}else{
		$residents = $residents;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 col-xs-6">
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
						{{ __('Resident Data') }}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-resident-management')
							<a href="{{ route('residentdata.create') }}"
								class="btn btn-brand btn-elevate btn-icon-sm btn-sm" title="Create User">
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
							<th>#</th>
							<th>Image</th>
							<th> Name </th>
							<th> Society </th>
							<th> SMS Pin </th>
							<th> Email Pin </th>
							<th> Otp Status </th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($residents as $key=>$user)
						<tr>
							<td>{{++$key}} - {{$user->id}}</td>
							<td>
								<div class="kt-avatar kt-avatar--outline">
									<div class="kt-avatar__holder"
										style="@if ($user->image !='') background-image: url({{ asset('storage/residents/'.$user->image) }}); @endif">
									</div>
									<label class="kt-avatar__upload OpenResidentImageModal"
										resident_id="{{$user->id ?? 0}}" resident_name="{{$user->name ?? ''}}"
										title="Click to Update Image">
										<i class="fa fa-pen"></i>
									</label>
								</div>
							<td>{{$user->name}}</td>
							<td>{{$user->society->name}}</td>
							<td><b>{{$user->m_pin}}</b></td>
							<td><b class="text-danger">{{$user->e_pin}}</b></td>
							<td>
								@if ($user->pin_verified == 1)
								<a href="#" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i
										class="fa fa-check"></i> Pin Verified </a>
								@else
								<b> Pin is Not Verified </b>
								@endif
							</td>
							<td>
								@can('create-resident-management')
								<a href="{{ route('residentdata.edit', $user->id) }}" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit Resident"></i> </a> &nbsp;
								@endcan
								@can('delete-resident-management')
								<a href="{{route('residentdata.destroy', $user->id)}}"
									class="text-danger delete-confirm" del_title="User {{$user->name}}"><i
										class="fa fa-trash-alt fa-lg" title="Delete Resident"></i></a>
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
@section('modal-popup')
<div class="modal fade" id="ResidentImageModal" tabindex="-1" role="dialog" aria-labelledby="ResidentImageModal"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span id="ResidentName"></span>
				</h5> <br>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form method="post" id="ResidentImageForm">
				@csrf
				<div class="modal-body pb-0">
					<input type="hidden" name="resident_id" id="ResidentIdModal">
					<div class="row">
						<div class="form-group validated col-8">
							<label class="form-control-label"> <b>Upload Image*:</b></label>
							<input type="file" class="form-control" name="image_file" required>
							{{-- <div class="invalid-feedback">
								<h6> Only png,jpg and jpeg are allowed and maximum size is 3MB
								</h6>
							</div> --}}
							<div class="invalid-feedback" id="image_file_error"></div>
						</div>

						<div class="col-4 mt-2 img-holder">
							{{-- <img class="d-flex me-3 avatar-lg" src="../assets/images/users/user-8.jpg"
								alt="Generic placeholder image"> --}}
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
					<button class="btn btn-brand btn-sm">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet"
	type="text/css" />
@endsection
@section('scripts')

<script>
	$(".OpenResidentImageModal").click(function(event) {
        var resident_id = parseInt($(this).attr("resident_id"));
        var resident_name = $(this).attr("resident_name") ?? 'Update Image';
		console.log(resident_name);
        $('#ResidentIdModal').val(resident_id);
        $('#ResidentName').html(resident_name);
        $('#ResidentImageModal').modal('show');
    });

	$('#ResidentImageForm').on('submit', function(e) {
		e.preventDefault();
		let residentId = parseInt($('#ResidentIdModal').val());
		let form_type = 'POST'
		let form_url = "{{ url('/resident-management/residentdata/store-update-image/')}}/"+residentId
		$.ajax({
			type: form_type,
			url: form_url,
			data: new FormData(this),
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			beforeSend : function(msg) {
				$('#ResidentImageForm').find('div.invalid-feedback').text('')
			},
			success: function(msg) {
				console.log(msg);
				if(msg?.success == 'no'){
					$.each(msg?.error, function(prefix, val){
						$('#ResidentImageForm').find('div#'+prefix+'_error').text(val[0])
					});
				}else{
					$("#ResidentImageForm").trigger("reset");
					$('#ResidentImageModal').modal('hide');
					// $('#employee-datatable').DataTable().ajax.reload(null, false);
					Swal.fire(
						'Saved',
						msg.message,
						'success'
					)
				}
			}
		});
	});


</script>


<script src="{{ asset('assets/js/pages/crud/datatables/extensions/buttons.js?v=1') }} "></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1.0') }}" type="text/javascript"></script>
{{-- <script src="{{ asset('assets/js/pages/dropzone.js?v=1.0') }}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1.0') }}" type="text/javascript">
</script>

<script>
	//Reset input file in modal
	$('input[type="file"][name="image_file"]').val('');
        //Image preview on upload time
        $('input[type="file"][name="image_file"]').on('change', function(){
            var img_path = $(this)[0].value;
            var img_holder = $('.img-holder');
            var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
            // alert(extension);
            if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                    if(typeof(FileReader) != 'undefined'){
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>', {'src':e.target.result,'class':'','style':'max-width:80%;margin-bottom:1px;'}).appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    }else{
                        $(img_holder).html('This browser does not support FileReader');
                    }
            }else{
                $(img_holder).empty();
            }
        });
        // End Image Preview Code on Modal Form
</script>
@endsection