@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentvehicle.index') }}"><span class="kt-subheader__desc">{{ __('User
                        Vehicle')}}</span></a>
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
                                {{ __(($user->id > 0 ? "Edit" : "Create").' Vehicle') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('residentvehicle.index') }}" class="btn btn-brand btn-sm  mt-3">
                                    {{ __('User Vehicles')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader"
                        action="{{ ($user->id) ? route('residentvehicle.update', $user->id ) : route('residentvehicle.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @php
                        $exType = 0;
                        $exresident = 0;
                        if($user->id){
                        $exType = $user->vehicle_type_id;
                        $exresident = $user->resident_data_id;
                        }
                        @endphp

                        @if($user->id)
                        @method('PUT')
                        @endif

                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Select Resident*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('resident_data_id') is-invalid @enderror"
                                            name="resident_data_id" required data-live-search="true">
                                            <option selected disabled> {{ __('Select Resident')}}</option>
                                            @forelse($residents as $resident)
                                            <option value="{{$resident->id}}" {{ ($exresident==$resident->id) ?
                                                'selected' : '' }}>{{ $resident->name }}</option>
                                            @empty
                                            <option disabled> No Resident Found </option>
                                            @endforelse
                                        </select>
                                        @error('resident_data_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Vehicle Name') }}</b> *</label>
                                        <input type="text" name="vehicle_name"
                                            class="form-control @error('vehicle_name') is-invalid @enderror"
                                            value="{{ $user->vehicle_name ?? old('vehicle_name') }}" autofocus required
                                            placeholder="{{ __('Enter Name') }}">
                                        @error('vehicle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Model Year') }}</b> *</label>
                                        <input type="number" name="model_year"
                                            class="form-control @error('model_year') is-invalid @enderror"
                                            value="{{ $user->model_year ?? old('model_year') }}" autofocus required
                                            placeholder="{{ __('Enter Year') }}">
                                        @error('model_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Select Vehicle Type*')
                                                }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('vehicle_type_id') is-invalid @enderror"
                                            name="vehicle_type_id" required data-live-search="true">
                                            <option selected disabled> {{ __('Select Vehicle type')}}</option>
                                            @forelse($vehicle_types as $type)
                                            <option value="{{$type->id}}" {{ ($exType==$type->id) ? 'selected' : ''
                                                }}>{{ $type->title }}</option>
                                            @empty
                                            <option disabled> No Type Found </option>
                                            @endforelse
                                        </select>
                                        @error('vehicle_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Make*') }}</b></label>
                                        <input type="text" name="make"
                                            class="form-control @error('make') is-invalid @enderror"
                                            value="{{ $user->make ?? old('make') }}" required
                                            placeholder="{{ __('Enter Make') }}">
                                        @error('make')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Vehicle Number*') }}</b></label>
                                        <input type="text" name="vehicle_number"
                                            class="form-control @error('vehicle_number') is-invalid @enderror"
                                            value="{{ $user->vehicle_number ?? old('vehicle_number') }}" required
                                            placeholder="{{ __('Enter Make') }}">
                                        @error('vehicle_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-4">
                                        <label class="form-control-label"><b>{{ __('Vehicle Image') }}</b></label>
                                        <input type="file" name="vehicle_image" accept="image/*"
                                            class="form-control @error('vehicle_image') is-invalid @enderror"
                                            value="{{ $user->vehicle_image ?? old('vehicle_image') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> {{ __('Submit')}}</button>
                                <a href="{{URL::previous()}}" type="reset" class="btn btn-secondary btn-sm">{{
                                    __('Cancel')}}</a>
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