@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Deal & Discounts')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('deals.index') }}"><span class="kt-subheader__desc">{{ __('Deals&Discount')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	    @php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$deals = $deals->where('society_id',$search_society_id);
		}else{
		$deals = $deals;
		}
		@endphp
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 col-xs-6">
					<label class="form-control-label">Select Society</label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true">
						<option selected disabled value=""> Select Society </option>
						<option @if ($search_society_id=='all' ) selected @endif value="all"> All </option>
						@foreach ($societies as $society)
						<option @if ($search_society_id==$society->id)
							selected
							@endif value="{{$society->id}}"> {{$society->name}} [{{$society->code}}]</option>
						@endforeach
					</select>
				</div>
				<div class="kt-section__content kt-section__content--solid mt-3 pt-3">
					<button type="submit" class="btn btn-primary btn-sm mt-1">Search</button>
				</div>
			</div>
		</form>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-tags"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Deals&Discounts')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('create-deals-and-discounts')
								<a href="{{ route('deals.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Deal&Discount"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Title')}} </th>
							<th>{{ __('Society')}} </th>
							<th>{{ __('Start Date')}} </th>
							<th>{{ __('End Date')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($deals as $key=>$deal)
							<tr>
							 	<td>{{ ++$key }}</td>
							 	<td>{{ __($deal->title) }}</td>
							 	<td><b> {{ $deal->society->name }}</b></td>
							 	<td> <b class="text-success"> {{ $deal->start_date->format('d M, Y') }} </b> </td>
							 	<td> <b class="text-danger"> {{ $deal->end_date->format('d M, Y') }}</b> </td>
	                            <td>
	                            	@can('view-deals-and-discounts')
										<a href="{{route('deals.show', $deal->id)}}" class="text-brand"> <i class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;
									@endcan

									@can('update-deals-and-discounts')
										<a href="{{route('deals.edit', $deal->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Deal&Discount"></i> </a> &nbsp;
									@endcan

									@can('delete-deals-and-discounts')
										<a href="{{route('deals.destroy', $deal->id)}}" class="text-danger delete-confirm" del_title="Deals&Discounts {{$deal->title}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete Deals&Discounts') }}"></i></a>
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
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection