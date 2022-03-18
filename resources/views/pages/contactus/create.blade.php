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
                            <a href="{{ route('complaints.index') }}" class="btn btn-brand btn-bold kt-margin-l-10 mt-3">
                               {{ __('Social Media')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($socialmedia->id) ? route('socialmedias.update', $socialmedia->id ) : route('socialmedia.store') }}" method="post">
                    @csrf

                    @if($socialmedia->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $socialmedia->title ?? old('title') }}" autofocus />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-control-label"><b>{{ __('URL*') }}</b></label>
                                    <input type="text" class="form-control" name="link_url" value="{{ $socialmedia->link_url ?? old('link_url') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                            {{-- <button  type="reset" class="btn btn-secondary">{{ __('Cancel')}}</button> --}}

                            <a href="{{URL::previous()}}" type="reset"  class="btn btn-secondary">{{ __('Cancel')}}</a>
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
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
@endsection

