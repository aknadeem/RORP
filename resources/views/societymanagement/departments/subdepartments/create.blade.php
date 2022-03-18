@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Departments')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('subdepartments.index') }}"><span class="kt-subheader__desc">{{ __('Sub Departments')}}</span></a>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ __(($subdepartment->id > 0 ? "Edit" : "Create").' Sub Department') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('subdepartments.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
        	                   {{ __('Sub Departments')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($subdepartment->id) ? route('subdepartments.update', $subdepartment->id ) : route('subdepartments.store') }}" method="post">
                    @csrf

                    @php
                    $exdep = 0;
                    $exManger = 0;
                    if($subdepartment->id > 0){
                        $exdep = $subdepartment->department->id;
                        if($subdepartment->asstmanager !=''){
                            $exManger = $subdepartment->asstmanager->manager_id;
                        }
                    }
                    @endphp
                    
                    @if($subdepartment->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>{{ __('Select Department*')}} </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('department_id') is-invalid @enderror" name="department_id" data-live-search="true">
                                                @forelse($departments as $dep)
                                                    <option value="{{$dep->id}}" {{ ($exdep == $dep->id) ? 'selected' : '' }}>{{ $dep->name }}</option>    
                                                @empty
                                                    <option disabled value=""> No Department Found </option>
                                                @endforelse
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Assistant Manager*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('manager_id') is-invalid @enderror" name="manager_id" data-live-search="true">
                                            <option selected disabled value="">  {{ __('Select Assistant Manager')}}</option>
                                            @forelse($managers as $mang)
                                                <option value="{{$mang->id}}" {{ ($exManger == $mang->id) ? 'selected' : '' }}>{{ $mang->name }}</option>
                                            @empty
                                                <option disabled value=""> No Assistant Manager Found </option>
                                            @endforelse
                                    </select>

                                    @error('manager_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

	                            <div class="form-group validated col-sm-6">
									<label class="form-control-label" for="inputSuccess1"><b>{{ __('Name*') }}</b></label>
									<input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" value="{{ $subdepartment->name ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Sub Department Name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
								</div>
								
								<div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Turnaround time (TAT)*')}}</b></label>
                                    <select
                                        class="form-control kt-selectpicker"
                                        data-live-search="true" name="ta_time" required
                                        id="ta_timeUpdate">
                                        <option value="" selected disabled> Select Turnaround time </option>
                                        <option {{ ($subdepartment->ta_time == "30 Minutes") ? 'selected' : '' }}  value="30 Minutes"> 30 Minutes </option>
                                        <option {{ ($subdepartment->ta_time ==  '45 Minutes') ? 'selected' : '' }} value="45 Minutes"> 45 Minutes </option>
                                        <option {{ ($subdepartment->ta_time == '1 Hourd') ? 'selected' : '' }} value="1 Hour"> 1 Hour </option>
                                        <option {{ ($subdepartment->ta_time == '3 Hours') ? 'selected' : '' }} value="3 Hours"> 3 Hours </option>
                                        <option {{ ($subdepartment->ta_time == '6 Hours') ? 'selected' : '' }} value="6 Hours"> 6 Hours </option>
                                        <option {{ ($subdepartment->ta_time == '9 Hours') ? 'selected' : '' }} value="9 Hours"> 9 Hours </option>
                                        <option {{ ($subdepartment->ta_time == '1 Day') ? 'selected' : '' }} value="1 Day"> 1 Day </option>
                                        <option {{ ($subdepartment->ta_time == '2 Days') ? 'selected' : '' }} value="2 Days"> 2 Days </option>
                                        <option {{ ($subdepartment->ta_time =='3 Days') ? 'selected' : '' }} value="3 Days"> 3 Days </option>
                                    </select>
                                    @error('ta_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
	                        </div>
                        </div>
                    </div>
                    @canany(['create-subdepartments','update-subdepartments'])
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            <a href="{{URL::previous()}}" type="reset"  class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>
                        </div>
                    </div>
                    @endcan
                </form>

                <!--end::Form-->
            </div>

            <!--end::Portlet-->
        </div>
    </div>
</div>
    <!-- begin:: End Content  -->
</div>

@endsection
@section('modal-popup')
    {{-- <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">{{ __('Create Sub Department')}}</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form loader" method="POST" action="{{ route('subdepartments.store') }}">
                    @csrf
    				<div class="modal-body">
    					<div class="row">
                            <div class="form-group validated col-sm-12">
    							<label class="form-control-label">{{ __('Name*') }}</label>
    							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>

    							@error('name')
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
    </div> --}}
@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

@endsection