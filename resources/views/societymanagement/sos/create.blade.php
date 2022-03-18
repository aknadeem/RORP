@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society SOS')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('society_sos.index') }}"><span class="kt-subheader__desc">{{ __('Incidents')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($sos->id > 0 ? "Edit" : "Create")) }}</span>
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
                            {{ __(($sos->id > 0 ? "Edit" : "Create").' SOS') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('society_sos.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('SOS List')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($sos->id) ? route('society_sos.update', $sos->id ) : route('society_sos.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if($sos->id)
                        @method('PUT')
                    @endif

                    @php
                        $exSoc = 0;
                        if($sos->id > 0){
                            $exSoc = $sos->society->id;
                        }
                    @endphp
                    
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label"><b>{{ __('Latitude*') }}</b></label>
                                    <input type="number" step="any" class="form-control" name="lat" value="{{ $sos->lat ?? old('lat') }}" autofocus required placeholder="Enter Latitude" />
                                    @error('lat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="form-control-label"><b>{{ __('Longitude*') }}</b></label>
                                    <input type="number" step="any" class="form-control" name="long" value="{{ $sos->long ?? old('long') }}" autofocus required placeholder="Enter Latitude" />
                                    @error('long')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b>{{ __('Select Societies*') }}</b></label>
                                    <select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
                                            <option disabled> <b> Select Societies </b></option>
                                            @forelse ($societies as $society)
                                                <option @if ($exSoc == $society->id)
                                                    selected
                                                @endif  value="{{$society->id}}"> {{$society->name}} </option> 
                                            @empty
                                                <option> <b> No Society Found </b> </option>
                                            @endforelse
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>

                                    <textarea class="form-control summernote" name="description" id="kt_summernote_1">{!! $sos->description ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            <a type="reset"  class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>
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
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
@endsection