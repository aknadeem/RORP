@extends('layouts.base')
@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">         
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}} </h3>
                {{-- <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentdata.index') }}"><span class="kt-subheader__desc">{{ __('Residents')}}</span></a> --}}
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __('Pending Accounts')}}</span>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Pending Accounts') }}
					</h3>
				</div>
				{{-- <div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
						    @can('create-user-management')
							<a href="{{ route('residentdata.create') }}" class="btn btn-brand btn-elevate btn-icon-sm btn-sm" title="Create User">
								
									<i class="fa fa-plus mb-1"></i>Create </a>
								@endcan

						</div>
					</div>
				</div> --}}
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>#</th>
							<th> Name </th>
							<th> SMS Pin </th>
							<th> Email Pin </th>
							<th> Date </th>
							<th> Email </th>
							<th> Account <Status></Status> </th>
							@can('delete-pending-account-user-management')
								<th>Actions</th>
							@endcan
						</tr>
					</thead>
					<tbody>
						@foreach ($residents as $key=>$user)
							<tr>
								<td>{{++$key}}</td>
								<td>{{$user->name ?? ''}}</td>
								<td> <b class="text-brand"> {{$user->m_pin ?? ''}} </b></td>
								<td> <b class="text-danger"> {{$user->e_pin ?? ''}} </b></td>
								<td> <b class=@if(today()->format('Y-m-d') == $user->created_at->format('Y-m-d')) "text-success" @endif> {{$user->created_at->format('d M, Y H:i A') ?? ''}} </b></td>
								<td>{{$user->email ?? ''}}</td>
								<td>
								@if ($user->user_data ?? '')
									<a class="btn btn-brand btn-elevate btn-icon-sm btn-sm text-white"> <i class="fa fa-check" title="Create Account"></i> Verified  </a> 
								@else
									@if ($user->pin_verified == 1)
										<a href="{{ route('create_resident.account', $user->id) }}" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i class="fa fa-plus" title="Create Account"></i> Create Account  </a> 
									@else
										<span> Pin is Not Varified </span>
									@endif
								@endif
								</td>
									<td>
									    @can('update-user-management')
		                                <a href="{{ route('residentdata.edit', $user->id) }}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit User"></i> </a> &nbsp;
		                                @endcan
		                                
										@can('delete-pending-account-user-management')
										    <a href="{{route('residentdata.destroy', $user->id)}}" class="text-danger delete-confirm" del_title="User {{$user->name}}"><i class="fa fa-trash-alt fa-lg" title="Delete User"></i></a>
										@endcan
									</td>
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
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
	<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
@endsection