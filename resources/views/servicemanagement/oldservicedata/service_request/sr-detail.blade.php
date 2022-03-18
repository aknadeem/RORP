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

		<!--Begin::App-->
		<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

			<!--Begin:: App Aside Mobile Toggle-->
			<button class="kt-app__aside-close" id="kt_user_profile_aside_close">
				<i class="la la-close"></i>
			</button>

			<!--End:: App Aside Mobile Toggle-->

			<!--Begin:: App Aside-->
			<div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">

				<!--Begin::Portlet-->
				<div class="kt-portlet kt-portlet--height-fluid-">
					<div class="kt-portlet__head kt-portlet__head--noborder">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
							</h3>
						</div>
					
					</div>
					<div class="kt-portlet__body">

						<!--begin::Widget -->
						<div class="kt-widget kt-widget--user-profile-2">
							<div class="kt-widget__head">
								<div class="kt-widget__media">
									
									<div class="kt-widget__pic kt-widget__pic--info kt-font-info kt-font-boldest kt-font-light">
										A
									</div>
								</div>
								<div class="kt-widget__info">
									<a href="#" class="kt-widget__username">
										{{$s_request->user->name}}
									</a>
									<span class="kt-widget__desc">

										{{ ucfirst(str_replace('-', ' ', $s_request->user->level_slug))  }}
									</span>
								</div>
							</div>
							<div class="kt-widget__body">
								<div class="kt-widget__section">
									
								</div>
								<div class="kt-widget__content">
									<div class="kt-widget__stats kt-margin-r-20">
										<div class="kt-widget__icon">
											<i class="flaticon-price-tag"></i>
										</div>
										<div class="kt-widget__details">
											<span class="kt-widget__title">Total Price</span>
											<span class="kt-widget__value"><span>Rs: {{ number_format($total_price,0)}}</span></span>
										</div>
									</div>
									<div class="kt-widget__stats">
										<div class="kt-widget__icon">
											<i class="flaticon2-open-text-book"></i>
										</div>
										<div class="kt-widget__details">
											<span class="kt-widget__title">Status</span>
											{{$s_request->status}}</span>
										</div>
									</div>
								</div>
								<div class="kt-widget__item">
									<div class="kt-widget__contact">
										<span class="kt-widget__label">Email:</span>
										<a href="#" class="kt-widget__data">{{$s_request->user->email}}</a>
									</div>
									<div class="kt-widget__contact">
										<span class="kt-widget__label">Phone:</span>
										<a href="#" class="kt-widget__data">{{$s_request->user->contact_no ?? '---'}}</a>
									</div>
									<div class="kt-widget__contact">
										<span class="kt-widget__label">Society:</span>
										<span class="kt-widget__data"> <b> {{$s_request->user->society->name ?? '---'}} </b> </span>
									</div>
								</div>
							</div>
						</div>

						<!--end::Widget -->

					</div>
				</div>

				<!--End::Portlet-->
			</div>

			<!--End:: App Aside-->

			<!--Begin:: App Content-->
			<div class="kt-grid__item kt-grid__item--fluid kt-app__content">

				<div class="row">
					<div class="col-xl-6">

						<!--begin:: Widgets/Finance Summary-->
						<div class="kt-portlet kt-portlet--height-fluid">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Request Summary
									</h3>
								</div>
								<div class="kt-portlet__head-toolbar">

									@php
										$user_id = Auth::user()->id;
										$user_level_id = Auth::user()->user_level_id;
									@endphp

									@if ($user_level_id == $s_request->refer_to OR $user_level_id < 3)

										@if ($s_request->is_invoiced === 1)
											<a class="btn btn-primary btn-bold btn-sm" href="{{ route('invoice.show', $s_request->invoice->request_service_id) }}" data-toggle="kt-tooltip" data-placement="bottom" title="Click to View Invoice...">Invoice</a>
										@else
											@if ($s_request->service->billing_type !='no_billing')
												<a class="btn btn-primary btn-bold btn-sm" href="{{ route('invoice.show', $s_request->id) }}" data-toggle="kt-tooltip" data-placement="bottom" title="Click to Create Invoice...">Create Invoice</a>
											@endif
										@endif
									@endif
								</div>
							</div>
							<div class="kt-portlet__body mb-0 pb-0">
								<div class="kt-widget12">
									<div class="kt-widget12__content">
										<div class="kt-widget12__item mb-4">
											<div class="kt-widget12__info">
												<span class="kt-widget4__title">Service</span>
												<span class="kt-widget12__value">{{$s_request->service->title}}</span>
											</div>
											<div class="kt-widget12__info">
												<span class="kt-widget12__desc">Type</span>
												<span class="kt-widget12__value">{{$s_request->servicetype->name}}</span>
											</div>
										</div>
										<div class="kt-widget12__item mb-4">
											<div class="kt-widget12__info">
												<span class="kt-widget12__desc">Sub Type</span>
												<span class="kt-widget12__value">{{$s_request->subtype->name}}</span>
											</div>
											<div class="kt-widget12__info">
												<span class="kt-widget12__desc">Installation Charges</span>
												<span class="kt-widget12__value">Rs: {{ number_format($s_request->service->installation_fee,0)}}</span>
											</div>
										</div>

										@if ($s_request->package !='')
											<div class="kt-widget12__item mb-4">
												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">Service Package</span>
													<span class="kt-widget12__value"> {{$s_request->package->title}} </span>
												</div>
												<div class="kt-widget12__info">
													<span class="kt-widget12__desc"> Package Price </span>
													<span class="kt-widget12__value">Rs: {{ number_format($s_request->package->price,0)}}</span>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>

						<!--end:: Widgets/Finance Summary-->
					</div>
{{-- {{dd($s_request->devices->toArray())}} --}}
					@if($s_request->devices->count() > 0)
					<div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">

						<!--begin:: Widgets/Devices-->
						<div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Devices
									</h3>
								</div>
							</div>


							<div class="kt-portlet__body">

								<!--begin: Datatable -->
								<table class="table table-striped table-hover table-checkable" id="kt_table_1">
									<thead>
										<tr>
											<th>{{ __('Title')}} </th>
											<th>{{ __('Price')}} </th>
											<th>{{ __('Type')}} </th>
										</tr>
									</thead>
									<tbody>
										@forelse ($s_request->devices as $device)
											<tr>
											 	<td>{{$device->device_title}}</td>
											 	<td>{{ number_format($device->device_price,0)}}</td>
											 	<td><a href="#" class="btn btn-sm {{($device->device_status == 'required') ? 'btn-label-success' : 'btn-label-brand'}}  btn-bold">{{$device->device_status}}</a></td>
											</tr>
				                        @empty
										<tr>
											<td colspan="7" class="text-danger text-center"> No Data Available </td>
										</tr>						
										@endforelse
									</tbody>
								</table>

								<!--end: Datatable -->
							</div>
						</div>
						<!--end:: Widgets/Devices-->
					</div>

					@endif
				</div>
			</div>

			<!--End:: App Content-->
		</div>

		<!--End::App-->
	</div>

	<!-- end:: Content -->
</div>


@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js') }}" type="text/javascript"></script>
@endsection