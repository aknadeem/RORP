@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}} </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('users.index') }}"><span class="kt-subheader__desc">{{ __('Residents Users')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @php
		// filter department from departments array
		$search_society_id = request()->search_society_id;
		if($search_society_id !='all' AND $search_society_id !=''){
		$users = $users->where('society_id', $search_society_id);
		}else{
		$users = $users;
		}
		@endphp
		<form action="" method="get" class="loader">
			<div class="alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 ">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
						<option selected disabled> {{ __('Select Society')}}</option>
						<option {{ ($search_society_id == 'all') ? 'selected' : '' }} value="all">
							{{ __('All Societies')}}
						</option>
						@forelse($societies as $soc)
						<option {{ ($search_society_id == $soc->id) ? 'selected' : '' }} value="{{$soc->id}}">
							{{ $soc->name }}
						</option>
						@empty
						<option disabled>No Society Found</option>
						@endforelse
					</select>
				</div>
				<div class="kt-section__content kt-section__content--solid mt-4">
					<button type="submit" class="btn btn-primary btn-sm">Search</button>
				</div>
			</div>
		</form>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						Residents
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							@can('view-user-management')
								<a href="{{ route('users.index') }}" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Click to view users">
								<i class="fa fa-eye mb-1"></i>Users</a>
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
							<th>ID</th>
							<th> Name </th>
							<th> Society </th>
							<th> permissions </th>
							<th> UserLevel </th>
							<th> Status </th>
							@can('update-user-management')
								<th>Actions</th>
							@endcan
						</tr>
					</thead>
					<tbody>
					    @php
					    $htitle = 'Click to View Detail';
					    @endphp
						@foreach ($users as $key=>$user)
						@can('view-resident-management')
    						@php
    						    $url = route('users.show', $user->id);
    						    $title = 'Click to view Detail';
    						@endphp
						@else
                            @php
    						    $url = '#';
    						    $title = 'Unauthorized to  view Detail';
    						@endphp
                        @endcan 
						
						        <tr>
								<td>{{++$key}}</td>
								<td> <a href="{{ ($user->user_level_id < 6) ? route('users.show', $user->id) : $url }}" title="{{ ($user->user_level_id < 6) ? $htitle : $title }}"> {{$user->name}} </a> </td>
								<td> <b> {{$user->society->name ?? '' }} </b> </td>
								<td>
									@if (count($user->permissions) > 0)
										<a href="{{ route('user-permissions', $user->id) }}" title="view Permissions" class="btn btn-primary">
										<i class="fa fa-lock fa-lg"></i>
											{{ __('Permissions') }}
										</a>
									@else
									@can('assign-permission-user-management')
										<a href="{{ route('edituserpermissions', $user->id) }}" title="Add Permissions" class="btn btn-success">
										<i class="fa fa-plus fa-lg"></i>
											{{ __('Permissions') }}
										</a>
									@else
									<span> Unauthorized </span>
									@endcan
									@endif
								</td>
								<td>{{$user->userlevel->title ?? ''}}</td>
								<td>
									@can('enable-disable-accounts-user-management')
	                                <a href="{{ route('user.toggleStatus',$user->id) }}" class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success  confirm-status">
	                                
	                                <label class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success" >
	                                    <input   {{ $user->is_active == 1 ? 'checked' : '' }} type="checkbox" >
	                                    <span  {{ $user->is_active == 1 ? 'title=Deactive' : 'title=Active' }} class="slider {{ $user->is_active == 1 ? '' : '' }}"></span>
	                                </label>
	                                </a>
	                                @else
	                                <span> <b> {{ $user->is_active == 1 ? 'Active' : 'Inactive' }} </b> </span>
	                                @endcan         
	                             </td>
								<td>
									@can('update-user-management')
	                                <a href="{{ route('users.edit', $user->id) }}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit User"></i> </a> &nbsp;
	                                @endcan
									<!--@can('delete-user-management')-->
									<!--    <a href="{{route('users.destroy', $user->id)}}" class="text-danger delete-confirm" del_title="User {{$user->name}}"><i class="fa fa-trash-alt fa-lg" title="Delete User"></i></a>-->
									<!--@endcan-->
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<!--end: Datatable -->
			</div>
		</div>
	</div>

	<form method="post" id="status-form"> 
        @method('PUT')
        @csrf
    </form>


    <!-- begin:: End Content  -->
</div>
@endsection


@section('top-styles')
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1.0.1') }}" type="text/javascript"></script>
@endsection

