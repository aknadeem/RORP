@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Events Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('servicepackages.index') }}"><span class="kt-subheader__desc">{{ __('Service Packages')}}</span></a>

                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($servicepackage->id > 0 ? "Edit" : "Create")) }}</span>
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
                            {{ __(($servicepackage->id > 0 ? "Edit" : "Create").' Service Package') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('servicepackages.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Service Packages')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($servicepackage->id) ? route('servicepackages.update', $servicepackage->id ) : route('servicepackages.store') }}" method="post">
                    @csrf
                    {{-- // for edit bcz same form is used for create or edit --}}
                    @if($servicepackage->id > 0)
                        @php
                            $exService = $servicepackage->service->id;
                        @endphp
                    @method('PUT')
                    @else
                        @php
                            $exService = 0;
                        @endphp
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first" id="AppendsItemRow">
                            <div class="row">
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Service') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('service_id') is-invalid @enderror" name="service_id" data-live-search="true">
                                        
                                        <option selected disabled>  {{ __('Select Service')}}</option>
                                        
                                        @foreach ($services as $service)
                                            <option @if ($exService == $service->id) selected @endif value="{{$service->id}}"> {{$service->title}} </option> 
                                        @endforeach 
                                            
                                    </select>

                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $servicepackage->title ?? old('title') }}" placeholder="Enter Package Title" required autofocus />

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Price*') }}</b></label>
                                    <input type="number" min="0" step="any" class="form-control" name="price" value="{{ $servicepackage->price ?? old('title') }}" placeholder="Enter Package Price" required/>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>{{ __('Select Package Tax:')}} </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('tax_id') is-invalid @enderror" name="tax_id[]" data-live-search="true" multiple>
                                                <option selected disabled>{{ __('Select Package Tax')}}</option>
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
                                <div class="form-group col-md-12">
                                    <label class="form-control-label"><b>{{ __('Detail') }}</b></label>
                                    <textarea name="detail" id="kt-ckeditor-1">{!! $servicepackage->detail ?? '' !!}</textarea>

                                    @error('detail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions mb-4">
                            <span>
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                                
                                <a href="{{URL::previous()}}" type="reset"  class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>
                            </span>
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
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
   <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}" type="text/javascript"></script> 

  <script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js') }}" type="text/javascript"></script> 
@endsection

