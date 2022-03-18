@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">  
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('subtypes.index') }}"><span class="kt-subheader__desc">{{ __('Service Type')}}</span></a>
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
                            {{ __(($service_subtype->id > 0 ? "Edit" : "Create").' SubType') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('subtypes.index') }}" class="btn btn-brand btn-bold  btn-sm kt-margin-l-10 mt-3">
                               {{ __('Service SubTypes')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($service_subtype->id) ? route('subtypes.update', $service_subtype->id ) : route('subtypes.store') }}" method="post">
                    @csrf

                    @if($service_subtype->id > 0)
                        @php
                            $exType = $service_subtype->type_id;
                        @endphp
                            @method('PUT')
                    @else
                        @php
                            $exType = 0;
                        @endphp
                    @endif

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="row" id="AppendServiceSubTypes">
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Service Type*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('type_id') is-invalid @enderror" name="type_id" data-live-search="true">
                                        <option selected disabled>  {{ __('Select Service Type')}}</option>

                                        @foreach ($servicetypes as $type)
                                            <option @if ($exType == $type->id) selected @endif value="{{$type->id}}"> {{$type->title}} </option> 
                                        @endforeach 
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label> <b>{{ __('Title*')}}</b></label>
                                    <div class="input-group ">
                                        <input type="text" class="form-control" name="title[]" value="{{ $service_subtype->title}}" placeholder="Enter Ttile" required autofocus />
                                    </div>

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if ($service_subtype->id < 1)
                                <div class="row">
                                    <a id="AddMoreTypes" class="btn btn-label-info btn-bold  btn-icon-h kt-margin-l-10 mt-3">
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
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>

<script>
    $(document).ready(function () {
        var count = 0;
        $("#AddMoreTypes").click(function (e) {
            e.preventDefault(); //stop form submitting
            count = count + 1;

            var html_code =  '<div class="form-group col-md-6" id=inputCol'+count+'>'+
                    '<label> <b>{{ __('Title*')}}</b></label>'+
                    '   <div class="input-group">'+
                    '<input type="text" class="form-control" name="title[]" placeholder="Enter Title" required autofocus />'+
                    '<div class="input-group-append">'+
                        '<a data-row="inputCol'+count+'" class="btn btn-danger remove_row" title="Remove Permission"> &nbsp; <i class="fa fa-times fa-lg" style="color:#fff;" ></i></a>'+
                    '</div>'+
                '</div>'+
            '</div>';
            $("#AppendServiceSubTypes").append(html_code);
        });
        $(document).on("click", ".remove_row", function () {
            var remove_sector = $(this).data("row");
            $("#" + remove_sector).remove();
        });
    });
</script>

@endsection