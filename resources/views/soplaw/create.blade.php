@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Sops')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('sops.index') }}"><span class="kt-subheader__desc">{{ __('Sop')}}</span></a>
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
                            {{ __(($sop->id > 0 ? "Edit" : "Create").' Sop&Law') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('sops.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Sops & Law')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($sop->id) ? route('sops.update', $sop->id ) : route('sops.store') }}" method="post">
                    @csrf

                    @if($sop->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $sop->title ?? old('title') }}" required autofocus />
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>

                                    <textarea name="description" id="kt-ckeditor-1">{!! $sop->description ?? '' !!}</textarea>
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
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>


    <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js') }}" type="text/javascript"></script>
@endsection

