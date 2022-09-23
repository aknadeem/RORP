@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Custom User')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="#"><span class="kt-subheader__desc">{{ __('List')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc"> Create User </span>

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
                               Add User Data <small> i.e SuperAdmin, Admin , HOD, Assistent Manager, Supervisor</small>
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('getCustomUser') }}"
                                    class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                                    {{ __('User Data')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form"
                        action="{{ route('storeCustomUser') }}"
                        method="post">
                        @csrf

                        @php
                            $exLevel = 0;
                            $exSoc = 0;
                            $exSector = 0;
                        @endphp

                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <input type="hidden" name="user_type" value="admin">

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Name') }}</b> *</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" autofocus required
                                            placeholder="{{ __('Enter Name') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Email*') }}</b></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required
                                            placeholder="{{ __('Enter Email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('CNIC') }}</b></label>
                                        <input type="text" name="cnic"
                                            class="form-control kt_inputmask_cnic @error('cnic') is-invalid @enderror"
                                            value="{{ old('cnic') }}" placeholder="00000-0000000-0">
                                        @error('cnic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label" for="contact_no"><b>{{ __('Contact Number*')
                                                }}</b></label>
                                        <input type="text" name="contact_no"
                                            value="{{ old('contact_no') }}"
                                            class="form-control kt_inputmask_8_1 @error('contact_no') is-invalid @enderror"
                                            required placeholder="{{ __('Enter Contact Number') }}">
                                        @error('contact_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Gender')}}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('gender') is-invalid @enderror"
                                            name="gender" style="width:100%;">
                                            <option selected disabled> Select Gender </option>
                                            <option value="male"> Male </option>
                                            <option value="female"> Female </option>
                                            <option value="other"> Other </option>
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Select Society*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('society_id') is-invalid @enderror"
                                            name="society_id" data-live-search="true" id="societySelect">
                                            <option selected disabled> {{ __('Select Society')}}</option>
                                            @forelse($societies as $soc)
                                            <option {{ ($exSoc == $soc->id) ? 'selected' : '' }} value="{{$soc->id}}">
                                                {{$soc->name}} </option>
                                            @empty
                                            <option disabled> No Society Found </option>
                                            @endforelse
                                        </select>

                                        @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Select Sector') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('society_id') is-invalid @enderror"
                                            name="sector_id" data-live-search="true" id="sectorSelect">
                                            <option selected disabled> {{ __('Select Sector')}}</option>
                                            @forelse($societies as $soc)
                                               @forelse($soc->sectors as $sector)
                                                    <option value="{{$sector->id}}">
                                                        {{$sector->sector_name}} </option>
                                                @empty
                                                    <option disabled> No Sector Found </option>
                                                @endforelse

                                            @empty
                                                <option disabled> No Society Found </option>
                                            @endforelse
                                        </select>

                                        @error('sector_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> {{ __('Submit')}}</button>
                                <a href="{{URL::previous()}}" type="reset" class="btn btn-secondary btn-sm">{{
                                    __('Cancel')}}</a>
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
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

<script>
    $("#societySelect").change(function(){
            var society_id = parseInt($(this).val());
            var sector_id = <?php  echo json_encode($exSector); ?>;
            console.log(sector_id);
            var sector_html = '';
            var selected = '';
            var societies = <?php echo json_encode($societies); ?>;
            var society = societies.find(x => x.id === society_id);

            console.log(society);
            if(society.sectors.length > 0){
                for (var i = 0; i < society.sectors.length; i++) {
                    console.log(society.sectors[i].sector_name);
                    if(sector_id == society.sectors[i].id){
                        selected = 'selected';
                    }
                    sector_html+='<option value='+society.sectors[i].id+'>'+society.sectors[i].sector_name+'</option>';
                }
            }else{
                // console.log("No City Found");
                sector_html='<option> No Sector Found </option>';
            }
            $('#sectorSelect').html(sector_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
</script>
@endsection
