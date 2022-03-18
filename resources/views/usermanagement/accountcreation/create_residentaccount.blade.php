@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">               
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentdata.index') }}">
                    <span class="kt-subheader__desc">{{ __($user->type == 'resident' ? "Residents" : "Tenants")}} </span>
                </a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __("create")}} </span>
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
                                {{ __('Resident Account') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('users.index') }}" class="btn btn-brand btn-icon-h kt-margin-l-10 mt-3">
            	                   {{ __('Residents')}}
            	                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" action="{{ route('verify.account') }}" method="post">
                        @csrf
                        <div class="kt-portlet__body pb-0">
                            <div class="kt-section kt-section--first mb-0">
                            	<div class="row">

                                    <input type="hidden" name="resident_id" value="{{ $user->id}}">
    	                            <div class="form-group validated col-sm-6">
    									<label class="form-control-label"><b>{{ __('Name') }}</b> *</label>
    									<input type="text" name="name" class="form-control" value="{{ $user->name}}" required >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    								</div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Email*') }}</b></label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email}}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('CNIC*') }}</b></label>
                                        <input type="text" name="cnic" class="form-control" value="{{ $user->cnic}}">
                                        @error('cnic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label" for="contact_no"><b>{{ __('Contact Number*') }}</b></label>
                                        <input type="text" name="contact_no" value="{{ $user->mobile_number ?? old('contact_no') }}"  class="form-control @error('contact_no') is-invalid @enderror" >
                                    </div>
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Society*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" name="society_id" data-live-search="true">
                                            <option selected disabled value="">  {{ __('Select Society')}}</option>
                                            @forelse($societies as $soc)
                                                <option @if ($user->society_id == $soc->id) selected @endif  value="{{$soc->id}}"> {{$soc->name}} </option> 
                                            @empty
                                                <option disabled value=""> No Society Found </option>
                                            @endforelse
                                    </select>

                                    @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Sector*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('society_sector_id') is-invalid @enderror" name="society_sector_id" data-live-search="true">
                                            <option selected disabled value="">  {{ __('Select Sector')}}</option>
                                            @forelse($sectors as $sec)
                                                <option @if ($user->society_sector_id == $sec->id) selected @endif  value="{{$sec->id}}"> {{$sec->sector_name}} </option> 
                                            @empty
                                                <option disabled vlaue=""> No Sector Found </option>
                                            @endforelse
                                    </select>

                                    @error('society_sector_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label" for="contact_no"><b>{{ __('Address') }}</b></label>
                                    <input type="text" name="address" value="{{ $user->address ?? old('address') }}"  class="form-control @error('address') is-invalid @enderror" >
                                </div>

    							<div class="form-group validated col-sm-6">
    									<label class="form-control-label"><b>{{ __('Select User Level*')}}</b></label>
    									<select class="form-control kt-select2 @error('user_level_id') is-invalid @enderror" id="kt_select2" required name="user_level_id" style="width:100%;">
    									   <option selected disabled value=""> Select User Level </option>
        									@forelse($user_levels as $level)
                                                <option 
                                                    @if ($user->type == $level->slug) selected @endif value="{{$level->id}}">{{$level->title}}
                                                </option>	
                                            @empty
                                                <option disabled value=""> No User level Found </option>
                                            @endforelse
                                        </select>
                                        @error('user_level_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    								</div>
    	                        </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> {{ __('Submit')}}</button>
                                <button type="reset" class="btn btn-secondary btn-sm">{{ __('Cancel')}}</button>
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
    {{-- load create group modal --}}
    @include('_partial.create_group_modal')
@endsection
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection