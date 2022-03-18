@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Events Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('servicetypes.index') }}"><span class="kt-subheader__desc">{{ __('Service Types')}}</span></a>

                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($service_type->id > 0 ? "Edit" : "Create")) }}</span>
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
                            {{ __(($service_type->id > 0 ? "Edit" : "Create").' Service Type') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('servicetypes.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
                               {{ __('Service Types')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($service_type->id) ? route('servicetypes.update', $service_type->id ) : route('servicetypes.store') }}" method="post">
                        @csrf
                        @if($service_type->id > 0)
                            @method('PUT')
                        @endif

                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first">
                            <div class="row" id="AppendServiceTypes">
                                <div class="form-group col-md-6">
                                    <label> <b>{{ __('Title*')}}</b></label>
                                    <div class="input-group ">
                                        <input type="text" class="form-control" name="title[]" value="{{ $service_type->title ?? old('title') }}" placeholder="Enter Service Type" required autofocus />
                                        {{-- <div class="input-group-append">
                                            <a data-row="inputCol1" class="btn btn-danger remove_row" title="Remove Permission"> &nbsp; <i class="fa fa-times fa-lg" style="color:#fff;" ></i></a>
                                        </div> --}}
                                    </div>

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if ($service_type->id < 1)
                                <div class="row">
                                    <a id="AddMoreTypes" class="btn btn-label-info btn-bold  btn-sm kt-margin-l-10 mt-3">
                                       {{ __('Add More Types')}}
                                    </a>
                                </div>
                            @endif
                            
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


<script>
    $(document).ready(function () {
        var count = 0;
        $("#AddMoreTypes").click(function (e) {
            e.preventDefault(); //stop form submitting
            count = count + 1;

            var html_code =  '<div class="form-group col-md-6" id=inputCol'+count+'>'+
                    '<label> <b>{{ __('Title*')}}</b></label>'+
                    '   <div class="input-group">'+
                    '<input type="text" class="form-control" name="title[]" value="{{ $service_type->title ?? old('title') }}" placeholder="Enter Service type" required autofocus />'+
                    '<div class="input-group-append">'+
                        '<a data-row="inputCol'+count+'" class="btn btn-danger remove_row" title="Remove Permission"> &nbsp; <i class="fa fa-times fa-lg" style="color:#fff;" ></i></a>'+
                    '</div>'+
                '</div>'+
            '</div>';
            $("#AppendServiceTypes").append(html_code);
        });
        $(document).on("click", ".remove_row", function () {
            var remove_sector = $(this).data("row");
            $("#" + remove_sector).remove();
        });
    });
</script>
@endsection

