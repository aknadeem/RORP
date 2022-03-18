@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('servicedevices.index') }}"><span class="kt-subheader__desc">{{ __('Service Devices')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-hands-helping"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Service Devices')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-service-management')
								<a href="{{ route('servicedevices.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Service Device"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Service')}} </th>
							<th>{{ __('Title')}} </th>
							<th>{{ __('Price')}} </th>
							<th>{{ __('Tax')}} </th>
							<th class="text-center">{{ __('Price Incl Tax')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						
						@forelse ($service_devices as $key=>$device)
							<tr>
							 	<td>{{ ++$key }}</td>
	                            <td>{{ __($device->service->title) }}</td>
	                            <td>{{ __($device->device_title) }}</td>
	                            <td class="text-left">{{ number_format($device->device_price,0) }}</td>
	                            <td>{{$device->total_tax}}%</td>
	                            <td class="text-center">{{$device->price_include_tax}}</td>
	                            <td>
	                            	@can('update-service-management')
									<a href="{{route('servicedevices.edit', $device->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Device"></i> </a> &nbsp;
									@endcan
									@can('delete-service-management')
									<a href="{{route('servicedevices.destroy', $device->id)}}" class="text-danger delete-confirm" del_title="Device {{ substr($device->device_title, 0, 20)}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Device') }}"></i></a>
									@endcan
								</td>                       
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
	</div>
    <!-- begin:: End Content  -->
</div>
@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection