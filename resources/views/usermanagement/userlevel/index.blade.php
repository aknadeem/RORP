@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">{{ __('UserManagement') }}</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>

				<a href="{{ route('userlevels.index') }}"><span class="kt-subheader__desc">{{
						__('UserLevels')}}</span></a>
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
						{{ __('UserLevels') }}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<!--@if (Auth::user()->user_level_id < 2)-->
							<!--	<a href="{{ route('userlevels.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create User Group"><i class="fa fa-plus mb-1"></i>{{ __('Create') }} </a>-->
							<!--@endif-->
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('ID') }}</th>
							<th>{{ __('Title') }}</th>
							<th>{{ __('Slug') }}</th>
							<th>{{ __('Permissions') }}</th>
							@if (Auth::user()->user_level_id < 3) <th>{{ __('Action') }}</th>
								@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($userlevels as $level)
						<tr>
							<td>{{ __($level->id) }}</td>
							<td>{{ __($level->title) }}</td>
							<td>{{ __($level->slug) }}</td>
							<td>
								<div class="btn-group dropdown">
									<button type="button" class="btn btn-outline-info btn-sm">
										{{ __('Permissions') }} ({{$level->permissions->count()}})
									</button>
									<!--<button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
									<!--</button>-->
									<!--<div class="dropdown-menu" style="height:300px !important; overflow:scroll !important;">-->
									<!--	@forelse ($level->permissions as $permission)-->
									<!--		<span class="dropdown-item"> {{ __($permission->slug ?? '')}} </span>-->
									<!--	@empty-->
									<!--		<span class="dropdown-item">  {{ __('No Permission Given Yet!') }}</span>-->
									<!--		<div class="dropdown-divider"></div>-->
									<!--		<a class="dropdown-item" href="{{ route('userlevels.edit', $level->id) }}">{{ __('Add Permissions') }}</a>-->
									<!--	@endforelse-->
									<!--</div>-->
								</div>
							</td>
							@if (Auth::user()->user_level_id < 3) <td>
								<a href="{{ route('userlevels.edit', $level->id) }}" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="{{ __('Edit Row') }}"></i> </a> &nbsp;
								<!--<a href="{{ route('userlevels.destroy', $level->id) }}" class="text-danger delete-confirm"  del_title="User Level {{$level->title}}"> <i class="fa fa-trash fa-lg" title="{{ __('delete Row') }}"></i> </a> &nbsp;-->
								</td>
								@endif
						</tr>
						@endforeach
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
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet"
	type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection