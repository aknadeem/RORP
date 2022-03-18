@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Incident Reporting')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('reports.index') }}"><span class="kt-subheader__desc">{{ __('Incidents')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($incident->id > 0 ? "Edit" : "Create")) }}</span>
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
                            {{ __(($incident->id > 0 ? "Edit" : "Create").' Incident Report') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('reports.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Incidents')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($incident->id) ? route('reports.update', $incident->id ) : route('reports.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if($incident->id)
                        @method('PUT')
                    @endif

                    @php
                        $exSoc = 0;
                        $exSec = 0;
                        if($incident->id > 0){
                            $exSoc = $incident->society->id;
                            $exSec = $incident->sector->id;
                        }
                    @endphp
                    
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $incident->title ?? old('title') }}" autofocus required placeholder="Enter Title" />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Societies*') }}</b></label>
                                    <select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
                                            <option disabled> <b> Select Societies </b></option>
                                            @forelse ($societies as $society)
                                                <option @if ($exSoc == $society->id)
                                                    selected
                                                @endif  value="{{$society->id}}"> {{$society->name}} </option> 
                                            @empty
                                                <option> <b> No Society Found </b> </option>
                                            @endforelse
                                    </select>
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Sector*') }}</b></label>
                                    <select class="form-control kt-selectpicker" name="society_sector_id" data-live-search="true" required>
                                            <option disabled> <b> Select Sector </b></option>
                                            @forelse ($sectors as $sector)
                                                <option @if ($exSec == $sector->id)
                                                    selected
                                                @endif   value="{{$sector->id}}"> {{$sector->sector_name}} </option> 
                                            @empty
                                                <option> <b> No Sector Found </b> </option>
                                            @endforelse
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Image') }}</b></label>
                                        <input type="file" name="image" class="form-control">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>

                                    <textarea class="form-control summernote" name="description" id="kt_summernote_1">{!! $incident->description ?? '' !!}</textarea>
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