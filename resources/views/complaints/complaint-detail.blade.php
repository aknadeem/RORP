@extends('layouts.base')

@if($complaint)
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Compalint Detail
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						{{$complaint->complaint_title ?? '-'}}</span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<a href="{{URL::previous()}}" class="btn btn-default btn-bold">
					Back </a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!--Begin:: Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<div class="kt-widget kt-widget--user-profile-3">
					<div class="kt-widget__top">
						<div class="kt-widget__media">
						    @if($complaint)
    							@if($complaint->image !='')
    							<img src=" {{ asset('uploads/complaint/'.$complaint->image) ?? ''}}" alt="image">
    							@endif
							@endif
						</div>
						<div
							class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-bolder kt-font-light kt-hidden">
							JM
						</div>
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<div class="kt-widget__user">
									<a href="#" class="kt-widget__username">
										{{$complaint->complaint_title ?? '-'}}
									</a>
									<span
										class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success">{{$complaint->complaint_status ?? ''}}</span>
									@if($complaint->is_expired)
									<span
										class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-danger ml-3"> TAT Expired </span>
									@endif
								</div>
								<div class="kt-widget__action">
									{{-- <span class=""> <b>TAT: </b> <b class="text-dark">
											{{$complaint->ta_time ?? ''}}</b> </span> --}}

									<span class="btn btn-brand btn-sm" id="UpdateTAT" title="Click to Update TAT"
										exTat="{{ $complaint->ta_time ?? ''}}" complaint_id="{{ $complaint->id ?? ''}}">
										{{$complaint->ta_time ?? ''}}
										&nbsp;&nbsp;<i class="fa fa-edit"></i>
									</span>
									{{-- <div class="dropdown dropdown-inline">
										<a href="#" class="btn btn-brand btn-sm btn-upper dropdown-toggle"
											data-toggle="dropdown">
											Export
										</a>
									</div> --}}
								</div>
							</div>
							<div class="kt-widget__subhead mb-1 pb-0">
								<span class="mr-4 pr-3"><i class="flaticon2-phone"></i> <b> POC Number: &nbsp; </b>
									{{ $complaint->poc_number ?? ''}}</span> 
								<span><i class="flaticon2-calendar-3"></i> <b> POC Name: &nbsp; </b>
									{{$complaint->poc_name ?? ''}} </span>
							</div>
							
							<div class="kt-widget__subhead mb-2 mt-0">
									
								<span class="text-dark"><i class="flaticon2-phone"></i> <b> Resident Number: &nbsp; </b>
									{{ $complaint->user->contact_no ?? ''}}</span> &nbsp;&nbsp;
									
								<!--<span class="text-dark"><i class="flaticon2-map"></i> <b> Sector: &nbsp; </b>-->
								<!--	{{$complaint->user->profile->sector->sector_name ?? ''}} </span> &nbsp;-->
								
								<span class="text-dark"><i class="flaticon2-map"></i> <b> Address: &nbsp; </b>
									{{$complaint->user->profile->address ?? ''}} </span>
							</div>

							<div class="kt-widget__info">
								<div class="kt-widget__desc">
									{{$complaint->complaint_description ?? ''}}
								</div>
							</div>
						</div>
					</div>
					<div class="kt-widget__bottom">
						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Society: &nbsp; </b> <br> {{$complaint->society->name ?? ''}}
								</span>
							</div>
						</div>
						
						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Sector: </b> <br> {{$complaint->user->profile->sector->sector_name ?? ''}}
								</span>
							</div>
						</div>
						
						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Department:</b> <br> {{$complaint->department->name ?? '-'}}
								</span>
							</div>
						</div>

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Sub Department:</b> <br>
									{{$complaint->subdepartment->name ?? '-'}}
									</span>
							</div>
						</div>

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Refer To: </b> <br>
									{{$complaint->reffer->referto->name ?? '-'}} </span>
							</div>
						</div>
						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> {{ ucfirst($complaint->user->level_slug ?? '')}} &nbsp; </b> <br>
									{{$complaint->user->name ?? ''}} </span>
							</div>
						</div>
						{{-- <div class="kt-widget__item">
							<div class="kt-widget__icon">
								<i class="flaticon-network"></i>
							</div>
							<div class="kt-widget__details">
								<div class="kt-section__content kt-section__content--solid">
									<div class="kt-media-group">
										<a href="#" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="John Myer">
											<img src="assets/media/users/100_1.jpg" alt="image">
										</a>
										<a href="#" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="Micheal York">
											<span>+5</span>
										</a>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
		<!--End:: Portlet-->
		<div class="row">
			<div class="col-md-6">
				<!--Begin:: Portlet-->
				<div class="kt-portlet kt-portlet--tabs">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-toolbar">
							<ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand"
								role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#kt_apps_contacts_view_tab_1"
										role="tab">
										<i class="flaticon2-note"></i> Complaint Logs
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="tab-content kt-margin-t-20">
							<!--Begin:: Tab Content-->
							<div class="tab-pane active" id="kt_apps_contacts_view_tab_1" role="tabpanel">
								{{-- <form>
									<div class="form-group">
										<textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Type notes"></textarea>
									</div>
									<div class="row">
										<div class="col">
											<a href="#" class="btn btn-label-brand btn-bold">Add notes</a>
											<a href="#" class="btn btn-clean btn-bold">Cancel</a>
										</div>
									</div>
								</form> --}}{{-- 
								<div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div> --}}
								<div class="kt-notes kt-scroll kt-scroll--pull" data-scroll="true">
									<div class="kt-notes__items">
										@forelse ($complaint->complaints_logs as $log)

										<div class="kt-notes__item">
											<div class="kt-notes__media">
												<span class="kt-notes__icon">
													<i class="fa fa-arrow-up"></i>
												</span>
											</div>
											<div class="kt-notes__content">
												<div class="kt-notes__section">
													<div class="kt-notes__info pt-2">
														<a href="#" class="kt-notes__title">
															{{$complaint->complaint_title}}
														</a>
														<span class="kt-notes__desc">
															{{$log->log_date ?? '-'}}
														</span>
														@php
														if($log->status == 'open'){
														$bdge_color = 'kt-badge--info';
														}
														else if($log->status == 'in_process'){
														$bdge_color = 'kt-badge--warning';
														} else if($log->status == 'completed'){
														$bdge_color = 'kt-badge--success';
														}elseif($log->status == 'un_satisfied'){
														$bdge_color = 'kt-badge--danger';
														}else{
														$bdge_color = 'kt-badge--brand';
														}
														@endphp

														<span class="kt-badge {{$bdge_color ?? ''}} kt-badge--inline">
															{{$log->status ?? '-'}} </span>
													</div>
												</div>
												<span class="kt-notes__body">
													{{$log->comments ?? '-'}}
												</span>
												<span class="kt-notes__body">
												    By:
												    @if($log->comments == 'Turnaround time(TAT) Expired' && $log->user->user_level_id == 1)
													 <b> Automated system </b>
													@else
													<b> {{$log->user->name ?? '-'}}
														[{{ $log->user->level_slug}}]</b>
													@endif
												</span>
											</div>
										</div>
										@empty
										<div class="kt-notes__item">
											<h6 class="text-danger"> No Complaint Log </h6>
										</div>
										@endforelse


										{{-- <div class="kt-notes__item kt-notes__item--clean">
											<div class="kt-notes__media">
												<img class="kt-hidden" src="assets/media/users/100_1.jpg" alt="image">
												<span class="kt-notes__icon kt-font-boldest kt-hidden">
													<i class="flaticon2-cup"></i>
												</span>
												<h3 class="kt-notes__user kt-font-boldest kt-hidden">
													N B
												</h3>
												<span class="kt-notes__circle kt-hidden-"></span>
											</div>
											<div class="kt-notes__content">
												<div class="kt-notes__section">
													<div class="kt-notes__info">
														<a href="#" class="kt-notes__title">
															Remarks
														</a>
														<span class="kt-notes__desc">
															10:30AM 23 April, 2013
														</span>
													</div>
												</div>
												<span class="kt-notes__body">
													Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.
												</span>
											</div>
										</div> --}}
									</div>
								</div>
							</div>
							<!--End:: Tab Content-->
						</div>
					</div>
				</div>

				<!--End:: Portlet-->
			</div>

			<div class="col-md-6">
				<!--Begin:: Portlet-->
				<div class="kt-portlet kt-portlet--tabs">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-toolbar">
							<ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand"
								role="tablist">
								<li class="nav-item mt-4">
									<h5 class="text-brand"> Complaint Internal Logs: </h5>
								</li>
							</ul>
						</div>
					</div>
					@can('read-comment-complaint-management')
					<div class="kt-portlet__body">
						<div class="kt-scroll kt-scroll--pull" style="height: 300px; overflow: scroll;">
							<div class="kt-notes">
								<div class="kt-notes__items">
									@forelse ($complaint->complaint_internal_logs as $log)
									<div class="kt-notes__item pb-4">
										<div class="kt-notes__media">
											<span class="kt-notes__icon">
												<i class="fa fa-arrow-right"></i>
											</span>
										</div>
										<div class="kt-notes__content">
											<div class="kt-notes__section">
												<div class="kt-notes__info pt-2">
													<a href="#" class="kt-notes__title">
														{{$complaint->complaint_title}}
													</a>
													<span class="kt-notes__desc">
														{{$log->log_date ?? '-'}}
													</span>

													@php
													if($log->status == 'open'){
													$bdge_color = 'kt-badge--info';
													}
													else if($log->status == 'in_process'){
													$bdge_color = 'kt-badge--warning';
													} else if($log->status == 'completed'){
													$bdge_color = 'kt-badge--success';
													}elseif($log->status == 'un_satisfied'){
													$bdge_color = 'kt-badge--danger';
													}else{
													$bdge_color = 'kt-badge--brand';
													}
													@endphp

													<span class="kt-badge {{$bdge_color ?? ''}} kt-badge--inline">
														{{$log->status ?? ''}} </span>

													<!--<span class="kt-badge kt-badge--success kt-badge--inline"> {{$log->status ?? '-'}} </span>-->
												</div>
											</div>
											<span class="kt-notes__body">
												{{$log->comments ?? '-'}}
											</span>

											<span class="kt-notes__body">
												By: <b> {{$log->user->name ?? '-'}} [{{ $log->user->level_slug}}]</b>
											</span>
										</div>
									</div>
									@empty
									<div class="kt-notes__item mt-5 pt-5 ">
										<h5 class="text-danger ml-5 pl-4"> No Complaint Log </h5>
									</div>
									@endforelse
								</div>
							</div>
						</div>
					</div>
					@endcan

					@can('add-comment-complaint-management')
					<div class="kt-portlet__foot">
						<div class="kt-chat__input">
							<form action="{{ route('store.complaint_internal_log') }}" method="post">
								@csrf
								<input type="hidden" name="complaint_id" value="{{$complaint->id}}">
								<div class="kt-chat__editor validated">
									<textarea class="form-control @error('internal_comment') is-invalid @enderror"
										@error('internal_comment') autofocus @enderror name="internal_comment" row="5"
										required @if ($complaint->complaint_status == 'closed')
											disabled
										@endif></textarea>
									@error('internal_comment')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="kt-chat__toolbar">
									<div class="kt_chat__actions text-right mt-2">
										<button type="submit" @if ($complaint->complaint_status == 'closed')
											disabled
											@endif class="btn btn-brand btn-sm btn-bold" style="width:100%;"> <i
												class="fa fa-paper-plane"></i> Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@endcan
				</div>
				<!--End:: Portlet-->
			</div>
		</div>
	</div>
	<!-- end:: Content -->
</div>
@endsection

@section('modal-popup')
<div class="modal fade" id="addQucikComplaintModal" tabindex="-1" role="dialog" aria-labelledby="addQucikComplaintModal"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span id="TATComplaint"></span> Update TAT </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form class="kt-form" method="POST" id="UpdateComplaintTATForm">
				@csrf
				<div class="modal-body">
					<input type="hidden" name="complaint_id" id="complaint_id" value="{{ $complaint->id ?? 0}}">
					<div class="row">
						<div class="form-group validated col-sm-12">
							<label class="form-control-label"> <b>{{ __('Complaint Title')}} </b></label>
							<input type="text" class="form-control" name="ta_complainttitle" id="ta_complainttitle"
								placeholder="Complaint Title" value="{{$complaint->complaint_title ?? ''}}" readonly
								disabled>
						</div>
						
						<div class="form-group validated col-sm-12">
                            <label class="form-control-label"><b>{{ __('Turnaround time (TAT)*')}}</b></label>
                            <select
                                class="form-control kt-selectpicker"
                                data-live-search="true" name="ta_time" required
                                id="ta_timeUpdate">
                                <option value=""> Select Turnaround time </option>
                                <option value="30 Minutes"> 30 Minutes </option>
                                <option value="45 Minutes"> 45 Minutes </option>
                                <option value="1 Hour"> 1 Hour </option>
                                <option value="3 Hours"> 3 Hours </option>
                                <option value="6 Hours"> 6 Hours </option>
                                <option value="9 Hours"> 9 Hours </option>
                                <option value="1 Day"> 1 Day </option>
                                <option value="2 Days"> 2 Days </option>
                                <option value="3 Days"> 3 Days </option>
                            </select>
                            <div class="invalid-feedback" id="ta_timeUpdatE"></div>
                        </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
					<button type="button" class="btn btn-secondary btn-sm"
						data-dismiss="modal">{{ __('Close')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script>
	$("#UpdateTAT").click(function(event) {
	$('#addQucikComplaintModal').modal('show')
	var sel = "{{$complaint->ta_time ?? ''}}";
	
    // 	alert(sel);
	$("#ta_timeUpdate").val("{{$complaint->ta_time ?? ''}}");
	$('.kt-selectpicker').selectpicker("refresh");
	$('#UpdateComplaintTATForm').on('submit',function(event){
                event.preventDefault();
                // var files = $('#attachments')[0].files;
                var complaint_id = parseInt($("#complaint_id").val());
                var ta_time = $("#ta_timeUpdate").val();
                let form_url = "{{ url('complaint/tat/update/') }}/"+complaint_id
                let form_type = "POST"
                $.ajax({
                    url: form_url,
                    type:form_type,
                    data: {'complaint_id':complaint_id,
                    'ta_time':ta_time,
                    _token: '{{csrf_token()}}'} ,
                    success:function(response){
                        console.log(response);
                        if(response?.success == 'no'){
                            $('#ta_timeUpdateE').html(response?.error?.ta_time)
                        }else{
                            // $('#StatusError').html('')
                            // location.reload();
                            swal.fire({
                                title: "Success",
                                text: response.message,
                                type: "success",
                                confirmButtonText: "Ok",
                                closeOnConfirm: true
                            }, function () {
                                location.reload();
                                $('#addQucikComplaintModal').modal('hide');
                            });
                            
                            location.reload();
                        }
                    },
                    error:function(e){
                        console.log(e);
                    },
                });
            });

	// UpdateComplaintTATForm

	// $('#QuickDepartmentV').html(quick_data?.subdepartment?.name)
	// $('#QuickDateV').html(quick_data?.created_at_format)
	// $('#QuickTitleV').html(quick_data?.title)
	// $('#QuickDetailV').html(quick_data?.detail)
	
});
</script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=2') }}" type="text/javascript"></script>
@endsection
@else
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <h5 class="text-center text-danger m-4"> No Complaint found </h5>
</div>

@endsection
@endif