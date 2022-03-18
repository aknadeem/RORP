@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('societies.index') }}"><span class="kt-subheader__desc">{{ __('Societies')}}</span></a>
                
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
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
                            {{ __(($society->id > 0 ? "Edit" : "Create").' Society') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('societies.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
        	                   {{ __('Societies')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($society->id) ? route('societies.update', $society->id ) : route('societies.store') }}" method="post">
                    @csrf

                    @php
                        $exprovince = 0;
                        $exCity = 0;
                        if($society->id > 0){
                            $exCity = $society->city->id;
                            $exprovince = $society->province->id;
                        }
                    @endphp

                    @if($society->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">
	                            <div class="form-group validated col-sm-6">
									<label class="form-control-label"><b>{{ __('Society Code*') }}</b></label>
									<input type="text" max="4" class="form-control kt_society_code @error('code') is-invalid @enderror"  name="code" value="{{ $society->code ?? old('code') }}" required autofocus placeholder="{{ __('Enter Society Code') }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
								</div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Name*') }}</b></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" value="{{ $society->name ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Society Name') }}">

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="country_id" value="1">

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Province*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('province_id') is-invalid @enderror" id="kt_select2" name="province_id" data-live-search="true">
                                            <option selected disabled value="">  {{ __('Select Province')}}</option>
                                            @forelse($provinces as $province)

                                            <option value="{{$province->id}}" {{ ($exprovince == $province->id) ? 'selected' : '' }}>{{ $province->name }}</option>    
                                            @empty
                                                <option disabled value=""> No Province Found </option>
                                            @endforelse
                                    </select>

                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select City*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('city_id') is-invalid @enderror" name="city_id"  id="CitySelect" data-live-search="true">
                                    <option selected disabled value="">  {{ __('Select City')}}</option>
                                    @if ($exCity > 0 ?? '')
                                        <option selected value="{{$exCity}}">{{ $society->city->name }}</option>
                                    @endif
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-12">
                                    <label class="form-control-label"><b>{{ __('Address*') }}</b></label>

                                    <input type="text" class="form-control @error('address') is-invalid @enderror"  name="address" value="{{ $society->address ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Society Address') }}">

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
@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
    
    <script>
        $("#kt_select2").change(function(){
            var province_id = parseInt($(this).val());
            var city_id = <?php echo json_encode($exCity); ?>;
            // console.log(city_id);
            var html_data = '';
            var selected = '';
            var provinces = <?php echo json_encode($provinces); ?>;
            var single_province = provinces.find(x => x.id === province_id);

            if(single_province.cities.length > 0){
                for (var i = 0; i < single_province.cities.length; i++) {
                    // console.log(single_province.cities[i].name);
                    if(city_id == single_province.cities[i].id){
                        selected = 'selected';
                    }

                    html_data+='<option '+ selected +' value='+single_province.cities[i].id+'>'+single_province.cities[i].name+'</option>'; 
                }
            }else{
                // console.log("No City Found");
                html_data = '<option value=""> No City Found </option>';
            }
            $('#CitySelect').html(html_data);
            $('.kt-selectpicker').selectpicker("refresh");
        });

    </script>
@endsection