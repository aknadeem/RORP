@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">               
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentfamily.index') }}"><span class="kt-subheader__desc">{{ __('Resident Family')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __($user->id > 0 ? "edit" : "create")}} </span>
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
                                {{ __(($user->id > 0 ? "Edit" : "Create").' User Family') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('residentfamily.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
            	                   {{ __('User Family')}}
            	                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader" action="{{ ($user->id) ? route('residentfamily.update', $user->id ) : route('residentfamily.store') }}" method="post">
                        @csrf
                        @php
                        $exresident = 0;
                            if($user->resident_data_id){
                                $exresident = $user->resident_data_id;
                            }
                        @endphp

                        @if($user->id > 0)
                            @method('PUT')
                        @endif

                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                            	<div class="row">
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Select Resident*') }}</b></label>
                                        <select class="form-control kt-selectpicker @error('resident_data_id') is-invalid @enderror" name="resident_data_id" required data-live-search="true">
                                                <option selected disabled>  {{ __('Select Resident')}}</option>
                                                @forelse($residents as $resident)
                                                <option value="{{$resident->id}}" {{ ($exresident == $resident->id) ? 'selected' : '' }}>{{ $resident->name }}</option>    
                                                @empty
                                                    <option disabled> No Resident Found </option>
                                                @endforelse
                                        </select>
                                        @error('resident_data_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

    	                            <div class="form-group validated col-sm-3">
    									<label class="form-control-label"><b>{{ __('Name') }}</b> *</label>
    									<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name ?? old('name') }}" autofocus placeholder="{{ __('Enter Name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    								</div>

                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Guardian Name') }}</b> *</label>
                                        <input type="text" name="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror" value="{{ $user->name ?? old('guardian_name') }}" autofocus required placeholder="{{ __('Enter Father Name') }}">
                                        @error('guardian_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('CNIC*') }}</b></label>
                                        <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror" value="{{ $user->cnic ?? old('cnic') }}" required placeholder="{{ __('Enter Cnic') }}">
                                        @error('cnic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Email*') }}</b></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email ?? old('email') }}" required placeholder="{{ __('Enter Email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Mobile Number*') }}</b></label>
                                        <input type="{{ ($user->id) ? 'number' : 'text'}}" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ $user->mobile_number ?? old('mobile_number') }}" required placeholder="{{ __('Enter Mobile Number') }}">
                                        @error('mobile_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Relation*') }}</b></label>
                                        <input type="text" name="relation" class="form-control @error('relation') is-invalid @enderror" value="{{ $user->relation ?? old('relation') }}" required placeholder="{{ __('Enter Relation') }}">
                                        @error('relation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Gender')}}</b></label>
                                        <select class="form-control kt-selectpicker @error('gender') is-invalid @enderror" name="gender" style="width:100%;">
                                            <option selected disabled> Select Gender </option>
                                            <option 
                                            @if ($user->gender == 'male')
                                               selected @endif value="male"> Male </option>
                                            <option @if ($user->gender == 'female')
                                               selected @endif value="female"> Female </option>
                                            <option @if ($user->gender == 'other')
                                               selected @endif value="other"> Other </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
    	                        </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> {{ __('Submit')}}</button>
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

@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection