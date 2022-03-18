@extends('layouts.base')

@if($detail)
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Departmental Service Request Detail
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						{{$detail->service->service_title ?? ''}}</span>
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
						</div>
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<div class="kt-widget__user">
									<a href="#" class="kt-widget__username">
										{{$detail->service->service_title ?? '-'}}
									</a>

									<span
										class="kt-badge kt-badge--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[$detail->request_status] ?? ''}} kt-badge--inline">
										{{
										\App\Helpers\Constant::REQUEST_STATUS_VAL[$detail->request_status]
										?? '' }}
									</span>
								</div>
							</div>
							<div class="kt-widget__subhead mb-1 pb-0">
								<span class="mr-4 pr-3 text-danger"><i class="flaticon2-list fa-lg"></i> charges:
									&nbsp;
									{{
									number_format($detail->service->service_charges) }}</span>
								<span><i class="flaticon2-layers"> </i> Charges Type: &nbsp; <b>
										{{
										$detail->service->type_value }} </b> </span>
							</div>
							<br>
							<div class="kt-widget__info">
								<div class="kt-widget__desc">
									{!! $detail->description ?? '' !!}
								</div>
							</div>
						</div>
					</div>
					<div class="kt-widget__bottom">
						{{-- <div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Society: &nbsp; </b> <br> {{$detail->society->name ??
									''}}
								</span>
							</div>
						</div>

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Sector: </b> <br>
									{{$detail->user->profile->sector->sector_name ?? ''}}
								</span>
							</div>
						</div> --}}

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Department:</b> <br> {{$detail->department->name ??
									'-'}}
								</span>
							</div>
						</div>

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Sub Department:</b> <br>
									{{$detail->subdepartment->name ?? '-'}}
								</span>
							</div>
						</div>

						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b> Refer To: </b> <br>
									{{$detail->referto->name ?? '-'}}
									<br>
									<b>
										[{{
										ucfirst($detail->referto->level_slug
										??
										'')}}]
									</b>
								</span>
							</div>
						</div>
						<div class="kt-widget__item pt-2">
							<div class="kt-widget__details">
								<span class="text-dark"> <b>Request From: </b> <br>{{$detail->RequestBy->name ?? ''}}
									<br>
									<b>
										[{{
										ucfirst($detail->RequestBy->level_slug
										??
										'')}}]
									</b>
									&nbsp;
								</span>
							</div>
						</div>
						{{-- <div class="kt-widget__item">
							<div class="kt-widget__icon">
								<i class="flaticon-network"></i>
							</div>
							<div class="kt-widget__details">
								<div class="kt-section__content kt-section__content--solid">
									<div class="kt-media-group">
										<a href="#" class="kt-media kt-media--sm kt-media--circle"
											data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title=""
											data-original-title="John Myer">
											<img src="assets/media/users/100_1.jpg" alt="image">
										</a>
										<a href="#" class="kt-media kt-media--sm kt-media--circle"
											data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title=""
											data-original-title="Micheal York">
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
								<li class="nav-item mt-4">
									<h5 class="text-brand"> External Logs: </h5>
								</li>
							</ul>
						</div>
					</div>
					<div class="kt-portlet__body mb-2">
						<div class="tab-content kt-margin-t-20">
							<!--Begin:: Tab Content-->
							<div class="tab-pane active" id="kt_apps_contacts_view_tab_1" role="tabpanel">
								<div class="kt-notes kt-scroll kt-scroll--pull" data-scroll="true"
									style="height: 523px; overflow: scroll;">
									<div class="kt-notes__items ">
										@forelse ($detail->logs->where('log_type','External') as $log)

										<div class="kt-notes__item pb-4">
											<div class="kt-notes__media">
												<span class="kt-notes__icon">
													<i class="fa fa-arrow-up"></i>
												</span>
											</div>
											<div class="kt-notes__content">
												<div class="kt-notes__section">
													<div class="kt-notes__info pt-2">
														<a href="#" class="kt-notes__title">
															{{$detail->service->service_title}}
														</a>
														<span class="kt-notes__desc">
															{{$log->created_at->format('d/m/Y') ?? '-'}}
														</span>

														<span
															class="kt-badge kt-badge--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[$log->request_status] ?? ''}} kt-badge--inline">
															{{
															\App\Helpers\Constant::REQUEST_STATUS_VAL[$log->request_status]
															?? '' }} </span>
													</div>
												</div>
												<span class="kt-notes__body">
													{{$log->remarks ?? '-'}}
												</span>
												<span class="kt-notes__body">
													By:
													<b> {{$log->user->name ?? '-'}}
														[{{ $log->user->level_slug}}]</b>
												</span>
											</div>
										</div>
										@empty
										<div class="kt-notes__item">
											<h6 class="text-danger"> No external Log found </h6>
										</div>
										@endforelse
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
									<h5 class="text-brand"> Internal Logs: </h5>
								</li>
							</ul>
						</div>
					</div>

					<div class="kt-portlet__body">
						<div class="kt-notes kt-scroll kt-scroll--pull" data-scroll="true"
							style="height: 400px; overflow: scroll;">

							<div class="kt-notes__items">
								@forelse ($detail->logs->where('log_type','Internal') as $log)

								<div class="kt-notes__item pb-4">
									<div class="kt-notes__media">
										<span class="kt-notes__icon">
											<i class="fa fa-arrow-up"></i>
										</span>
									</div>
									<div class="kt-notes__content">
										<div class="kt-notes__section">
											<div class="kt-notes__info pt-2">
												<a href="#" class="kt-notes__title">
													{{$detail->service->service_title}}
												</a>
												<span class="kt-notes__desc">
													{{$log->created_at->format('d/m/Y') ?? '-'}}
												</span>

												<span
													class="kt-badge kt-badge--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[$log->request_status] ?? ''}} kt-badge--inline">
													{{
													\App\Helpers\Constant::REQUEST_STATUS_VAL[$log->request_status]
													?? '' }} </span>
											</div>
										</div>
										<span class="kt-notes__body">
											{{$log->remarks ?? '-'}}
										</span>
										<span class="kt-notes__body">
											By:
											<b> {{$log->user->name ?? '-'}}
												[{{ $log->user->level_slug}}]</b>
										</span>
									</div>
								</div>
								@empty
								<div class="kt-notes__item ml-0 pl-0">
									<h6 class="text-center text-danger mt-5"> No Internal log found, add from
										<i class="fa fa-arrow-down fa-lg"></i>
									</h6>
								</div>
								@endforelse
							</div>
						</div>
					</div>

					@if (\App\Helpers\Constant::REQUEST_STATUS_VAL[$detail->request_status] != 'Closed')
					<div class="kt-portlet__foot">
						<div class="kt-chat__input">
							<form action="{{ route('depart_service.internalLogs', $detail->id) }}" method="post">
								@csrf
								<input type="hidden" name="departmental_service_id" value="{{$detail->service->id}}">
								<div class="kt-chat__editor validated">
									<textarea class="form-control @error('internal_comment') is-invalid @enderror"
										@error('internal_comment') autofocus @enderror name="internal_comment" row="5"
										required></textarea>
									@error('internal_comment')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="kt-chat__toolbar">
									<div class="kt_chat__actions text-right mt-2">
										<button type="submit" class="btn btn-brand btn-sm btn-bold" style="width:100%;">
											<i class="fa fa-paper-plane"></i> Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@endif

				</div>
				<!--End:: Portlet-->
			</div>

		</div>
	</div>
	<!-- end:: Content -->
</div>
@endsection
@section('scripts')
</script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=2') }}" type="text/javascript"></script>
@endsection
@else
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<h5 class="text-center text-danger m-4"> No Detail found </h5>
</div>

@endsection
@endif