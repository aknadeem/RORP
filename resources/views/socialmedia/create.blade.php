@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Social Media')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('complaints.index') }}"><span class="kt-subheader__desc">{{ __('Social Media')}}</span></a>
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
                            {{ __(($socialmedia->id > 0 ? "Edit" : "Create").' Social Media') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('complaints.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Social Media')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($socialmedia->id) ? route('socialmedia.update', $socialmedia->id ) : route('socialmedia.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if($socialmedia->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Social Type*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('social_type') is-invalid @enderror" name="social_type" data-live-search="true">
                                           <option selected disabled>  {{ __('Select Social Type')}}</option>
                                            <option @if ($socialmedia->social_type == 'facebook')
                                               selected
                                            @endif value="facebook"> Facebook </option> 
                                            <option @if ($socialmedia->social_type == 'twitter')
                                               selected
                                            @endif value="twitter"> Twitter </option> 
                                            <option @if ($socialmedia->social_type == 'instagram')
                                               selected
                                            @endif value="instagram"> Instagram </option> 
                                            <option @if ($socialmedia->social_type == 'youtube')
                                               selected
                                            @endif value="youtube"> Youtube </option> 
                                            
                                            <option @if ($socialmedia->social_type == 'linkedin')
                                               selected
                                            @endif value="linkedin"> LinkedIn </option> 
                                            <option @if ($socialmedia->social_type == 'others')
                                               selected
                                            @endif value="others"> Others </option> 
                                    </select>

                                    @error('social_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $socialmedia->title ?? old('title') }}" autofocus placeholder="Enter Title"/>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('URL*') }}</b></label>
                                    <input type="text" class="form-control" name="link_url" value="{{ $socialmedia->link_url ?? old('link_url') }}" placeholder="Enter URL" />
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Societies*') }}</b></label>
                                    <select class="form-control kt-selectpicker" name="societies[]" multiple data-live-search="true" required>
                                        <option disabled> <b> Select Societies </b></option>
                                        @forelse ($societies as $society)
                                            <option {{ (in_array($society->id, old('societies', [])) || $socialmedia->societies->contains($society->id)) ? 'selected' : '' }} value="{{$society->id}}"> {{$society->name}} </option> 
                                        @empty
                                            <option> <b> No Society Found </b> </option>
                                        @endforelse
                                    </select>
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
@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection