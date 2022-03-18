@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('ResidentManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('residenttenant.index') }}"><span class="kt-subheader__desc">{{ __('Tenants')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __($resident->id > 0 ? "edit" : "create")}} </span>
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
                                {{ __(($resident->id > 0 ? "Edit" : "Create").' Tenant') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('residenttenant.index') }}" class="btn btn-brand  btn-sm btn-bold  kt-margin-l-10 mt-3">
            	                   {{ __('Tenants')}}
            	                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader" action="{{ ($resident->id) ? route('residenttenant.update', $resident->id ) : route('storeTenant') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @php
                        $exLevel = 0;
                        $required = 'required';
                        $exSector_id = 0;
                        $exlandlord = 0;
                        $ex_soc = 0;
                        if($resident->id){
                            $exLevel = 1;
                            $exSector_id= $resident->society_sector_id;
                            $exlandlord= $resident->landlord_id;
                            $ex_soc= $resident->society_id;
                            $required = '';
                        }
                        @endphp

                        @if($resident->id)
                            @method('PUT')
                        @endif

                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                            	<div class="row">
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Resident Type*')}}</b></label>
                                        <select class="form-control kt-selectpicker @error('type') is-invalid @enderror" required data-live-search="true" name="type">
                                            <option selected value="{{'tenant' ?? old('type')}}"> <b>Tenant</b> </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <span> --}}
                                        <div class="form-group validated col-sm-3">
                                            <label class="form-control-label"><b>{{ __('Select LandLord*')}}</b></label>
                                            <select class="form-control kt-selectpicker @error('landlord_id') is-invalid @enderror" data-live-search="true" name="landlord_id" required>
                                                <option disabled selected value=""> Select Landlord </option>
                                                @foreach ($landlords as $landlord)
                                                    <option @if ($exlandlord == $landlord->resident_id)
                                                        selected
                                                    @endif value="{{$landlord->resident_id}}"> <b> {{$landlord->name}}</b>  [ {{$landlord->unique_id}} ] </option>
                                                @endforeach
                                            </select>
                                            @error('landlord_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group validated col-sm-3">
                                            <label class="form-control-label"><b>{{ __('Landlord Name') }}</b></label>
                                            <input type="text" name="landlord_name" class="form-control @error('landlord_name') is-invalid @enderror" value="{{ $resident->landlord_name ?? old('landlord_name') }}" placeholder="{{ __('Enter Landlord Name') }}">
                                            @error('landlord_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Name') }}</b> *</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $resident->name ?? old('last_name') }}" autofocus required placeholder="{{ __('Enter Name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Father Name') }}</b> *</label>
                                        <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ $resident->name ?? old('father_name') }}"  required placeholder="{{ __('Enter Father Name') }}">
                                        @error('father_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Password') }}</b> {{($required !='' ? '*' : '')}}</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"  {{$required}} placeholder="{{ __('Enter Your Password') }}">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('CNIC*') }}</b></label>
                                        <input type="text" name="cnic" class="form-control kt_inputmask_cnic @error('cnic') is-invalid @enderror" value="{{ $resident->cnic ?? old('cnic') }}" required placeholder="{{ __('Enter Cnic') }}">
                                        @error('cnic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Email*') }}</b></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $resident->email ?? old('email') }}" placeholder="{{ __('Enter Email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="mobile_number"><b>{{ __('Mobile Number*') }}</b></label>
                                        <input type="{{ ($resident->id) ? 'number' : 'text'}}" name="mobile_number" value="{{ $resident->mobile_number ?? old('mobile_number') }}"  class="form-control kt_inputmask_8_1 @error('mobile_number') is-invalid @enderror" required placeholder="{{ __('Enter Emergency Number') }}">
                                        @error('mobile_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="emergency_contact"><b>{{ __('Emergenct Number*') }}</b></label>
                                        <input type="{{ ($resident->id) ? 'number' : 'text'}}" name="emergency_contact" value="{{ $resident->emergency_contact ?? old('emergency_contact') }}"  class="form-control kt_inputmask_8_1 @error('emergency_contact') is-invalid @enderror" required placeholder="{{ __('Enter Emergency Number') }}">
                                        @error('emergency_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="occuptaion"><b>{{ __('Occupation*') }}</b></label>
                                        <input type="text" name="occuptaion" value="{{ $resident->occuptaion ?? old('occuptaion') }}"  class="form-control @error('occuptaion') is-invalid @enderror" required placeholder="{{ __('Enter occuptaion') }}">
                                        @error('occuptaion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="business_number"><b>{{ __('Business Number*') }}</b></label>
                                        <input type="{{ ($resident->id) ? 'number' : 'text'}}" name="business_number" value="{{ $resident->business_number ?? old('business_number') }}"  class="form-control @error('business_number') is-invalid @enderror" required placeholder="{{ __('Enter business_number') }}">
                                        @error('business_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Gender')}}</b></label>
                                        <select class="form-control kt-selectpicker @error('gender') is-invalid @enderror" name="gender" style="width:100%;">
                                            <option selected disabled value=""> Select Gender </option>
                                            <option 
                                            @if ($resident->gender == 'male')
                                               selected @endif value="male"> Male </option>
                                            <option @if ($resident->gender == 'female')
                                               selected @endif value="female"> Female </option>
                                            <option @if ($resident->gender == 'other')
                                               selected @endif value="other"> Other </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="martial_status"><b>{{ __('Martial Status*') }}</b></label>
                                        <input type="text" name="martial_status" value="{{ $resident->martial_status ?? old('martial_status') }}"  class="form-control @error('martial_status') is-invalid @enderror" required placeholder="{{ __('Martial Status') }}">
                                        @error('martial_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Select Society*')}}</b></label>
                                        <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" required data-live-search="true" id="SocSelect" name="society_id">
                                            <option selected disabled value=""> Select Society</option>
                                            @forelse($societies as $society)
                                                <option @if ($ex_soc =  $society->id)
                                                    selected
                                                @endif value="{{$society->id}}"> {{$society->name}}</option>
                                            @empty
                                                <option selected disabled> Select Society</option>
                                            @endforelse
                                        </select>
                                        @error('society_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label"><b>{{ __('Select Sector*')}}</b></label>

                                        <select class="form-control kt-selectpicker @error('society_sector_id') is-invalid @enderror" data-live-search="true" id="SectorSelect"  required name="society_sector_id" style="width:100%;">
                                            <option selected disabled value=""> Select Sector</option>
                                            @if ($exSector_id > 0)
                                            <option value="{{$exSector_id}}">{{$resident->sector->sector_name}}</option>
                                            @endif
                                        </select>

                                        @error('society_sector_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="address"><b>{{ __('Address*') }}</b></label>
                                        <input type="text" name="address" value="{{ $resident->address ?? old('address') }}"  class="form-control @error('address') is-invalid @enderror" required placeholder="{{ __('Enter address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="previous_address"><b>{{ __('Previous Address') }}</b></label>
                                        <input type="text" name="previous_address" value="{{ $resident->previous_address ?? old('previous_address') }}"  class="form-control @error('previous_address') is-invalid @enderror" placeholder="{{ __('Enter previous_address Number') }}">
                                        @error('previous_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-3">
                                        <label class="form-control-label" for="mail_address"><b>{{ __('Mailing Address') }}</b></label>
                                        <input type="text" name="mail_address" value="{{ $resident->mail_address ?? old('mail_address') }}"  class="form-control @error('mail_address') is-invalid @enderror" placeholder="{{ __('Enter mail_address Number') }}">
                                        @error('mail_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label class="form-control-label" for="mail_address"><b>Police form  </b></label>
                                        <input type="file" name="police_form"  class="form-control">
                                    </div>
                                    <div class="form-group col">
                                        <label class="form-control-label" for="mail_address"><b> Agreement  </b></label>
                                        <input type="file" name="tenant_agreement"  class="form-control">
                                    </div> 

                                    @if ($resident->is_account != 'yes' || $resident == '')
                                        <div class="form-group col-md-3">
                                            <label class="pb-2"> <b>Create Account</b></label> <br>
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                <input type="checkbox" value="yes" name="is_account"> Yes
                                                    <span></span>
                                            </label> &nbsp;&nbsp;
                                        </div>
                                    @endif
    	                        </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> {{ __('Submit')}}</button>
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
	{{-- load create group modal --}}
    @include('_partial.create_group_modal')
@endsection
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script>
    $("#SocSelect").change(function(){
        var soc_id = parseInt($(this).val());
        var exSector_id = <?php  echo json_encode($exSector_id); ?>;
        var sector_html= '';
        var selected = '';
        var societies = <?php echo json_encode($societies); ?>;
        var soc = societies.find(x => x.id === soc_id);
        if(soc.sectors.length > 0){
            sector_html='<option selected disabled value=""> Select Society</option>';
            for (var i = 0; i < soc.sectors.length; i++) {
                if(exSector_id == soc.sectors[i].id){
                    selected = 'selected';
                }
                sector_html+='<option '+selected+' value='+soc.sectors[i].id+'>'+soc.sectors[i].sector_name+'</option>'; 
            }
        }else{
            sector_html='<option> No Sector Found </option>';
        }
        $('#SectorSelect').html(sector_html);
        $('.kt-selectpicker').selectpicker("refresh");
    });

    // $("#ResidentType").change(function(){
    //     var resident_type = $(this).val();
    //     if(resident_type =='tenant'){
    //         $("#LandlordOption").fadeIn();
    //         $("#LandlordName").fadeIn();
    //         $('#Landlord_ID').prop('disabled', false);
    //         $('#Landlord_ID').prop('required', true);
    //         $('#Landlord_ID-example').selectpicker('refresh');
    //     }else{
    //         $('#Landlord_ID').prop('disabled', true);
    //         $('#Landlord_ID').prop('required', false);
    //         $('#Landlord_ID-example').selectpicker('refresh');
    //         $("#LandlordOption").hide();
    //         $("#LandlordName").hide();
    //     }
    // });
</script>
@endsection