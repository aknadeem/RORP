@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('users.index') }}"><span class="kt-subheader__desc">{{ __('Users')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __($user->id > 0 ? "edit" : "create")}} </span>

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
                            {{-- @can('create_user') --}}
                            <h3 class="kt-portlet__head-title">
                                {{ __(($user->id > 0 ? "Edit" : "Create").' User') }} afd
                                <small> i.e SuperAdmin, Admin , HOD, Assistent Manager, Supervisor</small>
                            </h3>
                            {{-- @endcan --}}

                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('users.index') }}"
                                    class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                                    {{ __('Users')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form"
                        action="{{ ($user->id) ? route('users.update', $user->id ) : route('users.store') }}"
                        method="post">
                        @csrf
                        @php
                        $exLevel = 0;
                        $exSoc = 0; // socety id, for selecetd option
                        $exSector = 0; // socety id, for selecetd option
                        if($user->id){
                        $exLevel = $user->userlevel->id;
                        $exSoc = $user->society_id;
                        $exSector = $user->society_sector_id;
                        }
                        @endphp

                        @if($user->id)
                        @method('PUT')
                        @endif
                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <input type="hidden" name="user_type" value="admin">

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Name') }}</b> *</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ $user->name ?? old('name') }}" autofocus required
                                            placeholder="{{ __('Enter Name') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Email*') }}</b></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ $user->email ?? old('email') }}" required
                                            placeholder="{{ __('Enter Email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('CNIC') }}</b></label>
                                        <input type="text" name="cnic"
                                            class="form-control kt_inputmask_cnic @error('cnic') is-invalid @enderror"
                                            value="{{ $user->cnic ?? old('cnic') }}" placeholder="00000-0000000-0">
                                        @error('cnic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Password*') }}</b></label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __('Enter Password') }}">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label" for="contact_no"><b>{{ __('Contact Number*')
                                                }}</b></label>
                                        <input type="text" name="contact_no"
                                            value="{{ $user->contact_no ?? old('contact_no') }}"
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
                                            <option @if ($user->gender == 'male')
                                                selected @endif value="male"> Male </option>
                                            <option @if ($user->gender == 'female')
                                                selected @endif value="female"> Female </option>
                                            <option @if ($user->gender == 'other')
                                                selected @endif value="other"> Other </option>
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
                                            <option {{ ($exSoc==$soc->id) ? 'selected' : '' }} value="{{$soc->id}}">
                                                {{$soc->name}} </option>
                                            @empty
                                            <option disabled> No Society Found </option>
                                            @endforelse
                                        </select>

                                        @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> <b>Select Designation</b></label>
                                            <div class="input-group">
                                                <select
                                                    class="form-control kt-selectpicker @error('desgination_id') is-invalid @enderror"
                                                    name="desgination_id" data-live-search="true"
                                                    id="desginationSelect">
                                                    <option selected disabled> {{ __('Select Desgination')}}</option>
                                                </select>
                                                <a class="btn btn-primary btn-sm OpenaddTypeModal">OPen some </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> <b>{{ __('Select User Level*')}} </b></label>
                                            <div class="input-group">
                                                <select
                                                    class="form-control kt-selectpicker @error('user_level_id') is-invalid @enderror"
                                                    name="user_level_id" data-live-search="true" required>
                                                    <option selected disabled> {{ __('Select UserLevel')}}</option>

                                                    @forelse($user_levels as $level)
                                                    <option @if ($exLevel==$level->id)
                                                        selected
                                                        @endif value="{{$level->id}}">{{$level->title}}
                                                    </option>
                                                    @empty
                                                    <option disabled> No User level Found </option>
                                                    @endforelse
                                                </select>
                                                @error('user_level_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
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

@section('modal-popup')
{{-- load create group modal --}}
{{-- @include('_partial.create_group_modal') --}}

@include('_partial.add_types_modal')

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