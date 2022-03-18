@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Social Media')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('socialmedia.index') }}"><span class="kt-subheader__desc">{{ __('Societies')}}</span></a>
               
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
						{{ __('Social Media List')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-pages')
								<a href="{{ route('socialmedia.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Department"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('ID')}}</th>
							<th>{{ __('Title')}} </th>
							<th>{{ __('Link Url')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($socialmedia as $media)
							<tr>
							 	<td>{{ $media->id }}</td>
	                            <td>{{ __($media->title) }} </td>
	                            <td>{{ __($media->link_url) }} </td>

	                            <td>
	                            	@can('update-pages')
										<a href="{{route('societies.edit', $socity->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit dep"></i> </a> &nbsp;
									@endcan
									@can('delete-pages')
										<a href="{{route('societies.destroy', $socity->id)}}" class="text-danger delete-confirm" del_title="Society {{$socity->name}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Module') }}"></i></a>
									@endcan
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

    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>
@endsection