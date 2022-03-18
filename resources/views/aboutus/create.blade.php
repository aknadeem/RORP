@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('About Us')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('aboutus.index') }}"><span class="kt-subheader__desc">{{ __('About Us')}}</span></a>
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
                            {{ __(($about->id > 0 ? "Edit" : "Create").' About') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('aboutus.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('About')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($about->id) ? route('aboutus.update', $about->id ) : route('aboutus.store') }}" method="post">
                    @csrf
                    
                    @php
                        $ex_soc = 0;
                        if($about->id){
                            $ex_soc= $about->society_id;
                        }
                        @endphp
                        
                    @if($about->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $about->title ?? old('title') }}" autofocus />

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Select Society*')}}</b></label>
                                        <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" required data-live-search="true" name="society_id">
                                            <option disabled selected  value=""> Select Society</option>
                                            @forelse($societies as $society)
                                                <option @if ($ex_soc = $society->id) selected
                                                @endif value="{{$society->id}}"> {{$society->name}}</option>
                                            @empty
                                                <option selected disabled value=""> Select Society</option>
                                            @endforelse
                                        </select>
                                        @error('society_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>
                                    
                                     <textarea class="form-control summernote" name="description" id="kt_summernote_1">{!! $about->description ?? '' !!}</textarea>
                                
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            {{-- <button  type="reset" class="btn btn-secondary btn-sm">{{ __('Cancel')}}</button> --}}

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
    <script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
@endsection