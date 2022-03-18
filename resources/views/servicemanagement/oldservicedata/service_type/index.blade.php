@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('servicetypes.index') }}"><span class="kt-subheader__desc">{{ __('Service Types')}}</span></a>

               {{-- < span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('servicetypes.index') }}"><span class="kt-subheader__desc">{{ __('events')}}</span></a> --}}
               
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Service Types')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<a href="{{ route('servicetypes.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Service Type"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('ID')}}</th>
							<th>{{ __('Title')}} </th>
							<th>{{ __('Created')}} </th>
							<th>{{ __('Updated')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						
						@forelse ($service_types as $type)
							<tr>
							 	<td>{{ $type->id }}</td>
	                            <td>{{ __($type->title) }}</td>
	                            <td>{{ $type->created_at }}</td>
	                            <td>{{ $type->updated_at }}</td>

	                            <td>
									<a href="{{route('servicetypes.edit', $type->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Service Type"></i> </a> &nbsp;
									<a href="{{route('servicetypes.destroy', $type->id)}}" class="text-danger delete-confirm" del_title="Service Type {{ substr($type->title, 0, 20)}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Service Type') }}"></i></a>
								</td>                       
							</tr>
                        @empty
						<tr>
							<td colspan="4" class="text-danger text-center"> No Data Available </td>
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
@endsection