@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('servicedevices.index') }}"><span class="kt-subheader__desc">{{ __('Service Devices')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __('Create')}}</span>
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
                            {{ __(($service_device->id > 0 ? "Edit" : "Create").' Service Device') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('servicedevices.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Service Devices')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($service_device->id) ? route('servicedevices.update', $service_device->id ) : route('servicedevices.store') }}" method="post">
                    @csrf

                    @if($service_device->id > 0)
                        @php
                            $exService = $service_device->service_id;
                        @endphp
                            @method('PUT')
                    @else
                        @php
                            $exService = 0;
                        @endphp
                    @endif

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="row">
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Service*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('service_id') is-invalid @enderror" id="ServiceTypeSelect" name="service_id" data-live-search="true" autofocus="true" required>
                                        <option selected disabled>  {{ __('Select Service ')}}</option>
                                        @foreach ($services as $ser)
                                            <option @if ($exService == $ser->id) selected @endif value="{{$ser->id}}"> {{$ser->title}} </option> 
                                        @endforeach 
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Device Title*') }}</b></label>
                                   <input type="text" class="form-control" name="device_title" value="{{ $service_device->device_title ?? old('device_title') }}" placeholder="Enter Device Title" required />
                                    @error('device_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Device Price*') }}</b></label>
                                   <input type="number" step="any" min="0" class="form-control" name="device_price" value="{{ $service_device->device_price ?? old('device_price') }}" placeholder="Enter Device Price" required />
                                    @error('device_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>{{ __('Select Device Tax:')}} </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('tax_id') is-invalid @enderror" name="tax_id[]" data-live-search="true" multiple>
                                                <option selected disabled>{{ __('Select Device Tax')}}</option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{$tax->id}}"> {{$tax->tax_title}} [ {{$tax->tax_percentage}}% ] </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <a data-toggle="modal" title="Add Tax" data-target="#Tax_Modal"  class="btn btn-primary">&nbsp;<i class="fa fa-plus" style="color:#fff;"></i></a> 
                                            </div>
                                            @error('tax_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group validated col-sm-12">
                                    <label class="mt-4 ml-2"> <b> Device Type*: </b> </label>
                                    <div class="kt-radio-inline ml-2">
                                        <label class="kt-radio kt-radio--success">
                                            <input type="radio" checked name="device_status" value="required"> Required
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--brand">
                                            <input type="radio" name="device_status" value="optional"> Optional
                                            <span></span>
                                        </label>
                                    </div>
                                    @error('device_status')
                                        <span class="form-text text-danger"> {{ $message }} </span>
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
    @include('_partial.create_tax_modal')
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
@endsection