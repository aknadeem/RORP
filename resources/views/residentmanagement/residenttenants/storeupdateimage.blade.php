@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">               
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residentdata.index') }}"><span class="kt-subheader__desc">{{ __('Residents')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">create </span>
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
                                StoreUpdate Image
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('residentdata.index') }}" class="btn btn-label-info btn-bold  btn-icon-h kt-margin-l-10 mt-3">
            	                   {{ __('residents')}}
            	                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader" action="{{ route('storeUpdateImage', 1 ) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="kt-section kt-section--first">
                            	<div class="row">
                                <div class="input-group col-sm-3">
                                    <input type="file" name="image" class="form-control">
                                </div>
    	                        </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary"> {{ __('Submit')}}</button>
                                <button type="reset" class="btn btn-secondary">{{ __('Cancel')}}</button>
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
	{{-- load create group modal --}}
    @include('_partial.create_group_modal')
@endsection
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection