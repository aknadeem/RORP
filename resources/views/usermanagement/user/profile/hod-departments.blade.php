	@extends('layouts.base')
	@section('content')
	<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">              
	    <!-- begin:: Content Head -->
	    <div class="kt-subheader kt-grid__item" id="kt_subheader">
	        <div class="kt-container kt-container--fluid">
	            <div class="kt-subheader__main">
	                <h3 class="kt-subheader__title">{{ __('UserManagement') }}</h3>
	                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
	                <a href="{{ route('users.index') }}"><span class="kt-subheader__desc">{{ __('Users')}}</span></a>
	                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
	                <span class="kt-subheader__desc">{{ __('Profile') }}</span>
	            </div>
	        </div>
	    </div>
	    <!-- end:: Content Head -->
	    <!-- begin:: Content -->
	    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
			<!--Begin::App-->
			<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
				<!--Begin:: App Aside Mobile Toggle-->
				<button class="kt-app__aside-close" id="kt_user_profile_aside_close">
					<i class="la la-close"></i>
				</button>
				<!--End:: App Aside Mobile Toggle-->
				<!--Begin:: App Aside-->
				<div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">
					<!--begin:: Widgets/Applications/User/Profile1-->
					<div class="kt-portlet pt-2">
						<div class="kt-portlet__body kt-portlet__body--fit-y">
							<!--begin::Widget -->
							<div class="kt-widget kt-widget--user-profile-1">
								<div class="kt-widget__head mt-2">
									<div class="kt-widget__media mt-2">
										@if ($user->user_image !='')
											<img src="{{url('uploads/userprofile/'.$user->user_image)}}" alt="image" style="border-radius: 10%;">
										@else
											<img src="{{url('assets/media/users/default.jpg')}}" alt="image" style="border-radius: 10%;">
										@endif
									</div>
									<div class="kt-widget__content pt-3">
										<div class="kt-widget__section">
											<a href="#" class="kt-widget__username">
												{{$user->name ?? ''}}
												@if ($user->status_type == 'Active')
													<i title="Status: {{$user->status_type}}" class="flaticon2-correct kt-font-success "></i>
												@else
													<i title="Status: {{$user->status_type}}" class="flaticon2-warning kt-font-warning "></i>
												@endif
											</a>
											<span class="kt-widget__subtitle">
												{{$user->userlevel->title ?? ''}}
											</span>
										</div>
									</div>
								</div>
								<div class="kt-widget__body">
									<div class="kt-widget__content" style="text-align: left !important;">
										<div class="kt-widget__info">
											<span class="kt-widget__label">Email:</span>
											<a href="#" class="kt-widget__data ">{{$user->email ?? ''}}</a>
										</div>
										<div class="kt-widget__info">
											<span class="kt-widget__label">Phone:</span>
											<a href="#" class="kt-widget__data text-left">{{$user->contact_no ?? '-'}}</a>
										</div>
										<div class="kt-widget__info">
    										<span class="kt-widget__label">Society:</span>
    										<span class="kt-widget__data"> {{$user->society->name ?? ''}} </span>
    									</div>
									</div>
									<div class="kt-widget__items">
										<a href="{{ route('users.show',$user->id) }}" class="kt-widget__item">
											<span class="kt-widget__section">
												<span class="kt-widget__icon">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
															<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
														</g>
													</svg> </span>
												<span class="kt-widget__desc">
													Personal Information
												</span>
											</span>
										</a>
										<a href="{{ route('changePasswordView',$user->id) }}" class="kt-widget__item kt-widget__item">
											<span class="kt-widget__section">
												<span class="kt-widget__icon">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24" />
															<path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3" />
															<path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3" />
															<path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3" />
														</g>
													</svg> </span>
												<span class="kt-widget__desc">
													Change Password
												</span>
											</span>
										</a>
										@if ($user->user_level_id == 2)
											<a href="{{ route('admin.getsocieties',$user->id) }}" class="kt-widget__item kt-widget__item--active ">
												<span class="kt-widget__section">
													<span class="kt-widget__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
														    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														        <rect x="0" y="0" width="24" height="24"/>
														        <path d="M13.5,21 L13.5,18 C13.5,17.4477153 13.0522847,17 12.5,17 L11.5,17 C10.9477153,17 10.5,17.4477153 10.5,18 L10.5,21 L5,21 L5,4 C5,2.8954305 5.8954305,2 7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,21 L13.5,21 Z M9,4 C8.44771525,4 8,4.44771525 8,5 L8,6 C8,6.55228475 8.44771525,7 9,7 L10,7 C10.5522847,7 11,6.55228475 11,6 L11,5 C11,4.44771525 10.5522847,4 10,4 L9,4 Z M14,4 C13.4477153,4 13,4.44771525 13,5 L13,6 C13,6.55228475 13.4477153,7 14,7 L15,7 C15.5522847,7 16,6.55228475 16,6 L16,5 C16,4.44771525 15.5522847,4 15,4 L14,4 Z M9,8 C8.44771525,8 8,8.44771525 8,9 L8,10 C8,10.5522847 8.44771525,11 9,11 L10,11 C10.5522847,11 11,10.5522847 11,10 L11,9 C11,8.44771525 10.5522847,8 10,8 L9,8 Z M9,12 C8.44771525,12 8,12.4477153 8,13 L8,14 C8,14.5522847 8.44771525,15 9,15 L10,15 C10.5522847,15 11,14.5522847 11,14 L11,13 C11,12.4477153 10.5522847,12 10,12 L9,12 Z M14,12 C13.4477153,12 13,12.4477153 13,13 L13,14 C13,14.5522847 13.4477153,15 14,15 L15,15 C15.5522847,15 16,14.5522847 16,14 L16,13 C16,12.4477153 15.5522847,12 15,12 L14,12 Z" fill="#000000"/>
														        <rect fill="#FFFFFF" x="13" y="8" width="3" height="3" rx="1"/>
														        <path d="M4,21 L20,21 C20.5522847,21 21,21.4477153 21,22 L21,22.4 C21,22.7313708 20.7313708,23 20.4,23 L3.6,23 C3.26862915,23 3,22.7313708 3,22.4 L3,22 C3,21.4477153 3.44771525,21 4,21 Z" fill="#000000" opacity="0.3"/>
														    </g>
														</svg>  </span>
													<span class="kt-widget__desc">
														Societies
													</span>
												</span>
											</a>
										@endif

										@if ($user->user_level_id == 3)
											<a href="{{ route('admin.getsocieties',$user->id) }}" class="kt-widget__item kt-widget__item--active">
												<span class="kt-widget__section">
													<span class="kt-widget__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
														    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														        <rect x="0" y="0" width="24" height="24"/>
														        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
														        <path d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z" fill="#000000" opacity="0.3"/>
														        <path d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z" fill="#000000" opacity="0.3"/>
														    </g>
														</svg> 
													</span>
													<span class="kt-widget__desc">
														Departments
													</span>
												</span>
											</a>
										@endif

										@if ($user->user_level_id == 4)
											<a href="{{ route('admin.getsocieties',$user->id) }}" class="kt-widget__item kt-widget__item--active">
												<span class="kt-widget__section">
													<span class="kt-widget__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
														    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														        <rect x="0" y="0" width="24" height="24"/>
														        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
														        <path d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z" fill="#000000" opacity="0.3"/>
														        <path d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z" fill="#000000" opacity="0.3"/>
														    </g>
														</svg> 
													</span>
													<span class="kt-widget__desc">
														Sub Departments
													</span>
												</span>
											</a>
										@endif
									</div>
								</div>
							</div>
							<!--end::Widget -->
						</div>
					</div>
					<!--end:: Widgets/Applications/User/Profile1-->
				</div>
				<!--End:: App Aside-->
				<!--Begin:: App Content-->
				@if ($user->user_level_id == 3)
					<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
		                <div class="row">
		                	<style>
		                		div.dataTables_wrapper div.dataTables_filter input {
		                			width: 88% !important;
		                		}
		                	</style>
		                    <div class="col-xl-12">
		                        <div class="kt-portlet">
		                            <div class="kt-portlet__head">
		                                <div class="kt-portlet__head-label">
		                                    <h3 class="kt-portlet__head-title"> HOD Departments
		                                    </h3>
		                                </div>
		                                <div class="kt-portlet__head-toolbar">
											 <a class="btn btn-label-brand btn-bold btn-sm OpenAttachHodModel">
												<i class="fa fa-plus"></i> Add Department
											</a>
										</div>
		                            </div>
		                            <div class="kt-portlet__body">
		                                <div class="kt-section kt-section--first">
		                                    <div class="kt-section__body">
		                                        <!--begin: Datatable -->
												<table class="table table-striped table-hover table-checkable" id="kt_table_1">
													<thead>
														<tr>
															<th>{{ __('#')}}</th>
								                            <th>{{ __('Society name')}}</th>
															<th>{{ __('Department Name')}}</th>
															<th class="text-center">{{ __('Action')}} </th>
														</tr>
													</thead>
													<tbody>
														@forelse ($user->departments as $key=>$dep)
															<tr>
																<td>{{++$key}} {{$dep->department_id}}</td>
																<td>{{$dep->department->society->name}}</td>
																<td>{{$dep->department->name}}</td>
																<td class="text-center">
																	@if (auth()->user()->user_level_id < 3)
																		@can('delete-user-management')
    <a href="{{route('deattach.department', [$dep->department_id,$user->id])}}" class="text-danger confirm-status" msg="Are You Sure To <b class='text-danger'>DeAttach</b> <b class='text-success'>{{$dep->department->name}}</b> From <b>{{$user->name}}</b>"><i class="fa fa-trash-alt fa-lg" title="DeAttach"></i></a>
																		@endcan
																	@endif
																</td>
															</tr>
														@empty
														<tr>
															<td colspan="4"> No Department Found </td>
														</tr>
														@endforelse
													</tbody>
												</table>
												<!--end: Datatable -->
		                                    </div>
		                                </div>
		                            </div>
		                           
		                        </div>
		                    </div>
		                </div>
		            </div>

			        {{-- Pass User id To Deattach Department From HOD --}}
			        <form method="get" id="status-form" > 
				        {{-- @method('PUT') --}}
				        @csrf
				    	<input type="hidden" name="user_id" value="{{$user->id}}">
				    </form>

	            @else
	            	<h6 class="ml-5 pl-5 text-danger mt-4"> permission Denied </h6>
	            @endif
				<!--End:: App Content-->
			</div>
			<!--End::App-->
		</div>
	    <!-- begin:: End Content  -->
	</div>
	@endsection
	@section('modal-popup')
	    <div class="modal fade" id="AttachHodModel" tabindex="-1" role="dialog" aria-labelledby="PermissonsModal" aria-hidden="true">
	    	<div class="modal-dialog" role="document">
	    		<div class="modal-content">
	    			<div class="modal-header">
	    				<h5 class="modal-title">{{ __('Attach Department')}}</h5>
	    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	    				</button>
	    			</div>
	    			<form class="kt-form" action="{{ route('department.addhod') }}"  method="POST">
	                    @csrf
	                    <div class="modal-body">
	                    	<input type="hidden" name="hod_id" value="{{$user->id}}">

	                        <div class="row">
	                            <div class="form-group validated col-sm-12">
	                                <label class="form-control-label"> <b>  HOD </b></label>
	                                <input type="text" class="form-control" name="hod_id" readonly id="hod_id" readonly disabled  value="{{$user->name}}">
	                                @error('hod_id	')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="form-group validated col-sm-12">
	                                <label class="form-control-label"> <b>{{ __('Select Department*')}} </b></label>
	                                <select class="form-control kt-selectpicker @error('department_id') is-invalid @enderror" name="department_id" required>
	                                    <option selected disabled> <b> {{ __('Select Department')}} </b> </option>
	                                    @forelse($departments as $dep)
	                                    	<option value="{{$dep->id}}">{{ $dep->name }}</option>    
	                                    @empty
	                                        <option disabled> No HOD Found </option>
	                                    @endforelse
	                                </select>
	                                @error('department_id	')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
	                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
	                    </div>
	                </form>
	    		</div>
	    	</div>
	    </div>
	@endsection
	@section('top-styles')
	    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
	@endsection
	@section('scripts')
		<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
		<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

		<script>
			$('.OpenAttachHodModel').click(function () {
				$('#AttachHodModel').modal('show');
				var invoiceId = $(this).attr('invoice-id');
				var user_id = $(this).attr('user_id');
		        $('#user_id').val(user_id);
			});
		</script>
	@endsection