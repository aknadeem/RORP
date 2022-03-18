@extends('layouts.base')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					{{ $page_title ?? ''}}
				</h3> 
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						Detail </span>
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
		<!--End:: Portlet-->
		<div class="row">
				<!--Begin:: Portlet-->
				<div class="kt-portlet kt-portlet--head-noborder">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title  kt-font-brand">
								{{ $detail->title }}
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body kt-portlet__body--fit-top">
						<div class="kt-section kt-section--space-sm">
							{!! $detail->description !!}
						</div>
					</div>
				</div>
				<!--End:: Portlet-->
		</div>
		@if ($detail->societies->count() > 0)
		<div class="row">
			<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							{{ __('Sectors')}}
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<!--begin: Datatable -->
					<table class="table table-striped table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
								<th>{{ __('#')}}</th>
								<th>{{ __('Society Code')}} </th>
								<th>{{ __('Society Name')}} </th>
							</tr>
						</thead>
						<tbody>
							@forelse ($detail->societies as $key=>$soc)
								<tr>
								 	<td>{{ ++$key }}</td>
		                            <td>{{ $soc->code }}</td>
		                            <td>{{ $soc->name }}</td>           
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
		@endif
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