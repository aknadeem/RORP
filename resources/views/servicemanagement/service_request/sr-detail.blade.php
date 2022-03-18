@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Service Management
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						Service Request Detail </span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<a href="{{URL::previous()}}" class="btn btn-default btn-bold">
					Back </a>
			</div>
		</div>
	</div>
	<!-- end:: Subheader -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!--begin:: Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<div class="kt-widget kt-widget--user-profile-3">
					<div class="kt-widget__top">
						<div class="kt-widget__media">
							@if ($s_request->user->user_image !='')
								<img src="{{url('uploads/userprofile/'.$s_request->user->user_image)}}" alt="image" style="border-radius: 20%;">
							@else
								<img src="{{url('assets/media/users/default.jpg?v=1')}}" alt="image" style="border-radius: 20%;">
							@endif
						</div>

						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									{{$s_request->user->name ?? '' }}
									
									@if($s_request->user->is_active == 1)
									    <i class="flaticon2-correct kt-font-success"></i>
									@endif
								</a>
								@php
									$user_id = Auth::user()->id;
									$user_level_id = Auth::user()->user_level_id;
								@endphp
								<div class="kt-widget__action">

									@can('view-invoices')

										@if ($s_request->is_invoiced === 1)

											<a href="{{ route('invoice.show', $s_request->invoice->request_service_id) }}" class="btn btn-brand btn-sm" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand" title="Click to View Invoice..."> <i class="fa fa-eye"></i> Invoice </a>

										@else
											@if ($s_request->service->billing_type !='no_billing')
        										@can('create-invoices')	
        										<a class="btn btn-brand btn-bold btn-sm" href="{{ route('invoice.show', $s_request->id) }}" data-placement="bottom" data-toggle="kt-tooltip" data-skin="brand" title="Click to Create Invoice..."> <i class="fa fa-plus"></i> Create Invoice</a>
        										@endcan
											@endif
										@endif
									@endcan
								</div>
							</div>
							
							<div class="kt-widget__subhead mb-2 mt-0">
									
								<span class="text-dark"><i class="flaticon2-phone"></i> <b> Resident Name: &nbsp; </b>
									{{ $s_request->user->name ?? ''}}</span> &nbsp;&nbsp;
									
									<span class="text-dark"><i class="flaticon2-phone"></i> <b> Resident Number: &nbsp; </b>
									{{ $s_request->user->contact_no ?? ''}}</span> &nbsp;&nbsp;
								<span class="text-dark"><i class="flaticon2-map"></i> <b> Address: &nbsp; </b>
									{{$s_request->user->profile->address ?? ''}} </span>
							</div>
							<div class="kt-widget__subhead mb-2 mt-0">

								<!--<a href="#">  <i class="flaticon2-new-email"></i> {{$s_request->user->email ?? ''}}</a>-->
									<span class="text-dark"><i class="flaticon2-calendar"></i>  Request date: &nbsp;
									<b class="text-success"> {{ $s_request->created_at->format('d M, Y') ?? ''}} </b></span> &nbsp;&nbsp;
									
									<span class="text-dark"><i class="flaticon2-placeholder"></i> Society: &nbsp; <b> {{$s_request->user->society->name ?? ''}} </b> </span> &nbsp;&nbsp;
								<b><i class="flaticon2-user"></i> Refer To: {{$s_request->referto->name ?? ''}} <small> [ {{ $s_request->referto->level_slug ?? '' }} ] </small> </b>
								
							</div>
							<div class="kt-widget__bottom mt-0">
								<div class="kt-widget__item ml-0 pt-1" style="width:0%;">
									<div class="kt-widget__icon">
										<i class="flaticon-price-tag"></i>
									</div>
									<div class="kt-widget__details">
										<span class="kt-widget__title">Total Price:</span>
										<span class="kt-widget__value"><span>Rs </span>{{ $s_request->total_price ?? ''}} </span>
									</div>
								</div>
								<div class="kt-widget__item ml-0 pt-1" style="width:0%;">
									<div class="kt-widget__icon">
										<i class="flaticon2-percentage"></i>
									</div>
									<div class="kt-widget__details">
										<span class="kt-widget__title">Total Tax:</span>
										<span class="kt-widget__value">{{$s_request->total_tax ?? ''}} <span> % </span></span>
									</div>
								</div>
								<div class="kt-widget__item ml-0 pt-1" >
									<div class="kt-widget__icon">
										<i class="flaticon-price-tag"></i>
									</div>
									<div class="kt-widget__details">
										<span class="kt-widget__title">Price Included Tax:</span>
										<span class="kt-widget__value"><span>Rs </span>{{$s_request->price_include_tax ?? ''}}</span>
									</div>
								</div>
							</div>
							<div class="kt-widget__info">	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end:: Portlet-->
		<!--Begin::App-->
		<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
			<!--Begin:: App Aside-->
			    
			    @if ($s_request->package !='')
				<div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">
				<!--Begin::Portlet-->
				<div class="kt-portlet kt-portlet--height-fluid">
					<div class="kt-portlet__head kt-portlet__head pl-4 ml-1">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Service Package
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body pl-3">
						<!--begin::Widget -->
						@if ($s_request->package !='')
						<div class="kt-widget kt-widget--user-profile-2">
							<div class="col-md-12 col-lg-12 col-xl-12 pt-0">
								<!--begin:: Widgets/Stats2-1 -->
								<div class="kt-widget1 pt-0 mt-0 pl-0 pb-0">
									<div class="kt-widget1__item pb-1">
										<div class="kt-widget1__info">
											<h4 class="kt-widget1__title">Service Package</h4>
											<span class="kt-widget1__desc" style="font-size:1.1em;"> {{$s_request->package->title ?? ''}} </span>
										</div>
									</div>
									<div class="kt-widget1__item pt-2 pb-1">
										<div class="kt-widget1__info">
											<h4 class="kt-widget1__title">Package Price</h4>
											<span class="kt-widget1__desc"> Rs: {{ number_format($s_request->package->price,0)}} </span>
										</div>
									</div>

									<div class="kt-widget1__item pt-2 pb-1">
										<div class="kt-widget1__info">
											<h4 class="kt-widget1__title">Total Tax </h4>
											<span class="kt-widget1__desc"> {{$s_request->package->total_tax ?? ''}} </span>
										</div>
									</div>
									<div class="kt-widget1__item pt-2 pb-0">
										<div class="kt-widget1__info">
											<h4 class="kt-widget1__title">Price Inluded Tax</h4>
											<span class="kt-widget1__desc"> Rs: {{ $s_request->package->price_include_tax ?? ''}} </span>
										</div>
									</div>
								</div>
								<!--end:: Widgets/Stats2-1 -->
							</div>
						</div>
						@else
						<h6 class="text-danger text-center pt-5 mt-5"> No Package Found </h6>
						@endif
						<!--end::Widget -->
					</div>
				</div>
				<!--End::Portlet-->
				</div>
				@endif
			
			<!--End:: App Aside-->
			<!--Begin:: App Content-->
			<div class="kt-portlet kt-portlet--mobile">
				<!--begin:: Widgets/User Progress -->
				<div class="kt-portlet kt-portlet--height-fluid">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Service Detail
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body pl-3">
						<div class="tab-content">
							<div class="tab-pane active" id="kt_widget31_tab1_content">
								<div class="kt-widget31">
									<div class="kt-widget31__item">
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Service Title
												</a>
												<p class="kt-widget31__text">
													{{$s_request->service->title ?? ''}}
												</p>
											</div>
										</div>
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Service Type
												</a>
												<p class="kt-widget31__text">
													{{$s_request->servicetype->name ?? ''}}
												</p>
											</div>
										</div>
									</div>

									<div class="kt-widget31__item">
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Billing Type
												</a>
												<p class="kt-widget31__text">
													{{$s_request->service->billing_type ?? ''}}
											</div>
										</div>
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Date
												</a>
												<p class="kt-widget31__text">
													{{$s_request->service->created_at->format('d M, Y') ?? ''}}
												</p>
											</div>
										</div>
									</div>

									<div class="kt-widget31__item">
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Service SubType
												</a>
												<p class="kt-widget31__text">
													{{$s_request->subtype->name ?? ''}}
												</p>
											</div>
										</div>
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Installation Charges
												</a>
												<p class="kt-widget31__text">
												 	<b> {{ number_format($s_request->service->installation_fee,0)}} </b>
												</p>
											</div>
										</div>
									</div>

									<div class="kt-widget31__item">
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Service Tax
												</a>
												<p class="kt-widget31__text">
													{{$s_request->service->total_tax ?? ''}}
												</p>
											</div>
										</div>
										<div class="kt-widget31__content">
											<div class="kt-widget31__info">
												<a href="#" class="kt-widget31__username">
													Charges Include Tax
												</a>
												<p class="kt-widget31__text">
													<b> {{$s_request->service->price_include_tax ?? ''}} </b>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<!--end:: Widgets/User Progress -->
			</div>
			<!--End:: App Content-->
		</div>
		<!--End::App-->
			
		<!-- begin:: Service Devices  -->
		@if (count($s_request->devices) > 0)
			<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							{{ __('Service Devices')}}
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<!--begin: Datatable -->
					<table class="table table-striped table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
								<th>{{ __('#')}}</th>
								<th>{{ __('Service')}} </th>
								<th>{{ __('Title')}} </th>
								<th>{{ __('Price')}} </th>
								<th>{{ __('Tax')}} </th>
								<th class="text-center">{{ __('Price Incl Tax')}} </th>
								{{-- <th>{{ __('Actions')}}</th> --}}
							</tr>
						</thead>
						<tbody>
							@forelse ($s_request->devices as $key=>$device)
								<tr>
								 	<td>{{ ++$key }}</td>
		                            <td>{{ __($device->service->title) }}</td>
		                            <td>{{ __($device->device_title) }}</td>
		                            <td class="text-left">{{ __($device->device_price) }}</td>
		                            <td>{{$device->total_tax}}%</td>
		                            <td class="text-center">{{$device->price_include_tax}}</td>
		                            {{-- <td>
		                            	@can('update-service-management')
										<a href="{{route('servicedevices.edit', $device->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Device"></i> </a> &nbsp;
										@endcan
										@can('delete-service-management')
										<a href="{{route('servicedevices.destroy', $device->id)}}" class="text-danger delete-confirm" del_title="Device {{ substr($device->device_title, 0, 20)}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Device') }}"></i></a>
										@endcan
									</td>  --}}                      
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
		@endif
		
		<div class="row">
		    <div class="col-md-12">
		        @if ($s_request->description != '')
    			<div class="kt-portlet kt-portlet--mobile">
    				<div class="kt-portlet__head kt-portlet__head--lg">
    					<div class="kt-portlet__head-label">
    						<h3 class="kt-portlet__head-title">
    							{{ __('Service Description')}}
    						</h3>
    					</div>
    				</div>
    				<div class="kt-portlet__body">
    				    {!! $s_request->description ?? 'No description added' !!}
    				</div>
    			</div>
    		@endif
		    </div>
		    <div class="col-md-6">
		        <div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
						    Service Logs
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="kt-scroll kt-scroll--pull" style="height: 500px; overflow: scroll;">
						<div class="kt-notes">
							<div class="kt-notes__items">
								@forelse ($s_request->logs as $log)
								<div class="kt-notes__item pb-4">
									<div class="kt-notes__media">
										<span class="kt-notes__icon">
											<i class="fa fa-arrow-down"></i>
										</span>
									</div>
									<div class="kt-notes__content">
										<div class="kt-notes__section">
											<div class="kt-notes__info pt-2">
												<a href="#" class="kt-notes__title">
													{{ $s_request->service->title }}
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
									<h5 class="text-danger ml-5 pl-4"> No Internal Log </h5>
								</div>
								@endforelse
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		    
		    <div class="col-md-6">
		        <div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
						    Service Internal Logs
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="kt-scroll kt-scroll--pull" style="height: 350px; overflow: scroll;">
						<div class="kt-notes">
							<div class="kt-notes__items">
								@forelse ($s_request->internallogs as $log)
								<div class="kt-notes__item pb-4">
									<div class="kt-notes__media">
										<span class="kt-notes__icon">
											<i class="fa fa-arrow-down"></i>
										</span>
									</div>
									<div class="kt-notes__content">
										<div class="kt-notes__section">
											<div class="kt-notes__info pt-2">
												<a href="#" class="kt-notes__title">
													{{ $s_request->service->title }}
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
									<h5 class="text-danger ml-5 pl-4"> No Internal Log </h5>
								</div>
								@endforelse
							</div>
						</div>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-chat__input">
						<form action="{{ route('store.service_internal_log') }}" method="post">
							@csrf
							<input type="hidden" name="service_request_id" value="{{ $s_request->id}}">
							<div class="kt-chat__editor validated">
								<textarea class="form-control @error('internal_comment') is-invalid @enderror"
									@error('internal_comment') autofocus @enderror name="internal_comment" row="5"
									required @if ($s_request->status == 'closed')
										disabled
									@endif></textarea>
								@error('internal_comment')
								<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
							<div class="kt-chat__toolbar">
								<div class="kt_chat__actions text-right mt-2">
									<button type="submit" @if ($s_request->status == 'closed')
										disabled
										@endif class="btn btn-brand btn-sm btn-bold" style="width:100%;"> <i
											class="fa fa-paper-plane"></i> Save</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		    </div>
		</div>
		<!-- end:: End Service Devices -->
	</div>
	<!-- end:: Content -->
</div>
@endsection
@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
@endsection