@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __('User Services')}}</span>
               
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
						{{ __('User Services')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('#')}}</th>
							<th>{{ __('User Name')}} </th>
							<th>{{ __('Cnic')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Total Services')}} </th>
							<th>{{ __('Invoice')}} </th>
							{{-- <th>{{ __('Actions')}}</th> --}}
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $key=>$user)
							<tr>
	                            <td>{{ ++$key }}</td>
	                            <td>{{ $user->name }}</td>
	                            <td>{{ $user->cnic ?? '-' }}</td>
	                            <td>{{ $user->society->name }}</td>
	                            <td>

	                            	<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
										<span class="btn btn-success btn-sm"> <b> {{ $user->totalServices }} </b> </span>
										<a  href="{{ route('userservices.show', $user->id) }}" title="View Detail" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
									</div>
	                            </td>
	                            <td>
	                            	<a  href="{{ route('minvoice.create', $user->id) }}" title="Create Invoice" class="btn btn-primary btn-sm">  Create Invoice</a>
	                            </td>             
							</tr>
                        @empty
                        					
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