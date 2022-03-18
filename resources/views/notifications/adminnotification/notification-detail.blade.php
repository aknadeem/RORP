@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Notification Detail
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						{{$custom->title ?? '-'}} </span>
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
						<div class="kt-widget__content">
							<div class="kt-widget__head">
								<div class="kt-widget__user">
									<a href="#" class="kt-widget__username">
										{!! $custom->title ?? '-' !!}
									</a>
									<span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success p-2">{{  $custom->date_format }}</span>
									
								</div>
							</div>

							<div class="kt-widget__subhead mb-2">
								
								<span><i class="flaticon2-user"></i>  Total User: &nbsp; <b>{{ count($notifications)}}</b> </span>
							</div>

							<div class="kt-widget__info">
								<div class="kt-widget__desc">
									{!! $custom->description ?? '-' !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--End:: Portlet-->
		<div class="row">
			<div class="col-xl-8">
				<!--Begin:: Portlet-->
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head kt-portlet__head--lg">
						<div class="kt-portlet__head-label">
						<span class="kt-portlet__head-icon">
						<i class="kt-font-brand fa fa-bell"></i>
						</span>
						<h3 class="kt-portlet__head-title">
						{{ __('Notification Sended To: ')}}
						</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<!--begin: Datatable -->
						<table class="table table-striped table-hover table-checkable" id="kt_table_1">
							<thead>
								<tr>
								<th>{{ __('ID')}}</th>
								<th>{{ __('Sender')}} </th>
								<th>{{ __('Time')}} </th>
								<th>{{ __('Send To')}} </th>
								<th>{{ __('User Type')}} </th>
								<th>{{ __('Read At')}} </th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = 1;
								@endphp
								@forelse ($notifications as $notify)
									<tr>
									<td>{{$i++}}</td>
										<td>{{ __($notify->data['sender_name'] ?? '') }}</td>
										<td>{{ $notify->created_at->format('d M, Y h:i A')}}</td>
										<td>{{ __($notify->notifiable->name ?? '') }}</td>
										<td>{{ __($notify->notifiable->level_slug ?? '') }}</td>

										<td> 
										@if ($notify->read_at != null)

										<span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success p-2"> {{ $notify->read_at->diffForHumans()}} </span>
										@else

										<span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-danger p-2">Unread</span>
										@endif
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
				<!--End:: Portlet-->
			</div>
		</div>
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