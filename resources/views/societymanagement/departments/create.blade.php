@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Department')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __(($department->id > 0 ? "Edit" : "Create")) }}</span>
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
                            {{ __(($department->id > 0 ? "Edit" : "Create").' Department') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('departments.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   {{ __('Departments')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($department->id) ? route('departments.update', $department->id ) : route('departments.store') }}" method="post">
                    @csrf

                    @php
                        $exSoc = 0;
                            if($department->id){
                                $exSoc = $department->society_id;
                            }
                        @endphp

                    @if($department->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first">
                        	<div class="row">

                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b>{{ __('Select Society*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" name="society_id" data-live-search="true">
                                        <option selected disabled value="">  {{ __('Select Society')}}</option>

                                        @forelse($societies as $soc)
                                            <option value="{{$soc->id}}" {{ ($exSoc == $soc->id) ? 'selected' : '' }}>{{ $soc->name }}</option>  
                                        @empty
                                            <option disabled> No Society Found </option>
                                        @endforelse
                                    </select>

                                    @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-3 pt-2">
                                    &nbsp;&nbsp; <label class="pb-2"> <b> Department For </b></label> <br>

                                   &nbsp;&nbsp; <label class="kt-checkbox kt-checkbox--bold kt-checkbox--primary">
                                        <input type="checkbox" @if($department->is_complaint) checked @endif  value="1" name="is_complaint"> Complaint
                                            <span></span>
                                    </label> &nbsp; 
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--primary">
                                        <input type="checkbox" @if($department->is_service) checked @endif  value="1" name="is_service"> Service
                                            <span></span>
                                    </label> &nbsp; 
                                </div>

	                            <div class="form-group validated col-sm-5">
									<label class="form-control-label"><b>{{ __('Name*') }}</b></label>
									<input type="text" class="form-control @error('title') is-invalid @enderror"  name="name" value="{{ $department->name ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Department Name') }}">

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
								</div>
	                        </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>

                           <a href="{{URL::previous()}}" type="reset"  class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>

                        </div>
                    </div>
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
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">{{ __('Create New Society')}}</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form loader" method="POST" action="{{ route('departments.store') }}">
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
    </div>
@endsection

@section('scripts')

<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

@endsection