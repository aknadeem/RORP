@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('servicepackages.index') }}"><span class="kt-subheader__desc">{{ __('Services')}}</span></a>
               
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
						{{ __('Service Packages')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-service-management')
								<a href="{{ route('servicepackages.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Service Package"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Package')}} </th>
							<th>{{ __('Price')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						
						@forelse ($servicepackages as $key=>$package)
							<tr>
							 	<td>{{ ++$key }}</td>
	                            <td>{{ __($package->service->title) }}</td>
	                            <td>{{ __($package->title) }}</td>
	                            <td>{{ __($package->price) }}</td>

	                            <td>
	                            	@can('update-service-management')
										<a href="{{route('servicepackages.edit', $package->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Service Package"></i> </a> &nbsp;
									@endcan
									@can('delete-service-management')
										<a href="{{route('servicepackages.destroy', $package->id)}}" class="text-danger delete-confirm" del_title="Service Package {{ substr($package->title, 0, 20)}} "><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Service Package') }}"></i></a>
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

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    
    <script src="{{ asset('js/ssm_datatable.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>
@endsection