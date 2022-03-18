@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Vendors')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('vendors.index') }}"><span class="kt-subheader__desc">{{ __('Vendors')}}</span></a>
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
                            {{ __(($vendor->id > 0 ? "Edit" : "Create").' Vendor') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('vendors.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Vendors')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($vendor->id) ? route('vendors.update', $vendor->id ) : route('vendors.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php
                        $exSoc = 0;
                    @endphp
                    @if($vendor->id)
                    @php
                        $exSoc = $vendor->society_id;
                    @endphp
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label> <b>Select Society* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" name="society_id" data-live-search="true" required>
                                                    <option selected disabled>  {{ __('Select Society')}}</option>
                                                    @forelse($societies as $soc)

                                                    <option value="{{$soc->id}}" {{ ($exSoc == $soc->id) ? 'selected' : '' }}>{{ $soc->name }}</option>    
                                                    @empty
                                                        <option disabled> No Society Found </option>
                                                    @endforelse
                                            </select>

                                             @error('society_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 form-group">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $vendor->title ?? old('title') }}" required />

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror                                    
                                </div>

                                <div class="col-md-4 form-group">
                                    <label class="form-control-label"><b>{{ __('Logo') }}</b></label>
                                    <input type="file" class="form-control" name="logo" />

                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror                                    
                                </div>

                                <div class="col-md-8 form-group">
                                    <label class="form-control-label"><b>{{ __('Address') }}</b></label>
                                    <input type="text" class="form-control" name="address" value="{{ $vendor->address ?? old('address') }}"  />

                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror                                    
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            {{-- <button  type="reset" class="btn btn-secondary">{{ __('Cancel')}}</button> --}}

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
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create Module')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" method="POST" action="{{ route('modules.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="from_user" value="from_user">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" required autofocus>

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            {{ __('Cancel')}}
                    </button>

                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js') }}" type="text/javascript"></script> 
@endsection

