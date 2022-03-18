@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Social Media')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('news.index') }}"><span class="kt-subheader__desc">{{ __('News')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($news->id > 0 ? "Edit" : "Create")) }}</span>

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
                            {{ __(($news->id > 0 ? "Edit" : "Create").' News') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('news.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('News')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($news->id) ? route('news.update', $news->id ) : route('news.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if($news->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $news->title ?? old('title') }}" autofocus />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-5">
                                    <label class="form-control-label"><b>{{ __('Select Societies*') }}</b></label>
                                    <select class="form-control kt-selectpicker" name="societies[]" multiple data-live-search="true" required>
                                            <option disabled> <b> Select Societies </b></option>
                                            @forelse ($societies as $society)
                                                <option {{ (in_array($society->id, old('societies', [])) || $news->societies->contains($society->id)) ? 'selected' : '' }}  value="{{$society->id}}"> {{$society->name}} </option> 
                                            @empty
                                                <option> <b> No Society Found </b> </option>
                                            @endforelse
                                    </select>
                                </div>

                                <div class="form-group col-md-2 mt-2">
                                    <label class="form-control-label ml-2"><b>{{ __('Flash News') }}</b></label>
                                    <div class="kt-radio-inline mt-2 ml-2">
                                        <label class="kt-radio kt-radio--bold kt-radio--success">
                                            <input type="radio" @if ($news->is_flash == 1) checked @endif value="1" name="is_flash"> Yes
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--bold kt-radio--brand">
                                            <input type="radio"@if ($news->is_flash !=   1) checked  @endif value="0" name="is_flash"> No
                                            <span></span>
                                        </label>
                                    </div>
                                   {{--  @error('is_flash')
                                        <span class="form-text text-danger">Some help text goes here</span>
                                    @enderror --}}

                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Image') }}</b></label>
                                        <input type="file" name="image" class="form-control">

                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                     <label class="form-control-label"><b>{{ __('Pdf File') }}</b></label>
                                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">

                                    @error('pdf_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>

                                    <textarea class="form-control summernote" name="description" id="kt_summernote_1">{!! $news->description ?? '' !!}</textarea>
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