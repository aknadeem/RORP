@extends('layouts.base')

@section('top-styles')

<link href="{{ asset('assets/css/pages/wizard/wizard-2.css') }} " rel="stylesheet" type="text/css" />
@endsection

@section('content')
    
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Wizard 2 </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                        Pages </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                        Wizard 2 </a>

                    <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <a href="#" class="btn kt-subheader__btn-primary">
                        Actions &nbsp;

                        <!--<i class="flaticon2-calendar-1"></i>-->
                    </a>
                    <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="left">
                        <a href="#" class="btn btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--md">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <path d="M11,14 L9,14 C8.44771525,14 8,13.5522847 8,13 C8,12.4477153 8.44771525,12 9,12 L11,12 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,12 L15,12 C15.5522847,12 16,12.4477153 16,13 C16,13.5522847 15.5522847,14 15,14 L13,14 L13,16 C13,16.5522847 12.5522847,17 12,17 C11.4477153,17 11,16.5522847 11,16 L11,14 Z" fill="#000000" />
                                </g>
                            </svg>

                            <!--<i class="flaticon2-plus"></i>-->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-grid  kt-wizard-v2 kt-wizard-v2--white" id="kt_wizard_v2" data-ktwizard-state="step-first">
                    <div class="kt-grid__item kt-wizard-v2__aside">

                        <!--begin: Form Wizard Nav -->
                        <div class="kt-wizard-v2__nav">

                            <!--doc: Remove "kt-wizard-v2__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                            <div class="kt-wizard-v2__nav-items kt-wizard-v2__nav-items--clickable">
                                <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                    <div class="kt-wizard-v2__nav-body">
                                        <div class="kt-wizard-v2__nav-icon">
                                            <i class="flaticon-globe"></i>
                                        </div>
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Add Society
                                            </div>
                                            <div class="kt-wizard-v2__nav-label-desc">
                                                Setup New Society
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step">
                                        <div class="kt-wizard-v2__nav-body">
                                            <div class="kt-wizard-v2__nav-icon">
                                                <i class="flaticon-bus-stop"></i>
                                            </div>
                                            <div class="kt-wizard-v2__nav-label">
                                                <div class="kt-wizard-v2__nav-label-title">
                                                    Add Society Sectors
                                                </div>
                                                <div class="kt-wizard-v2__nav-label-desc">
                                                    Setup Society Sectors
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step">
                                        <div class="kt-wizard-v2__nav-body">
                                            <div class="kt-wizard-v2__nav-icon">
                                                <i class="flaticon-bus-stop"></i>
                                            </div>
                                            <div class="kt-wizard-v2__nav-label">
                                                <div class="kt-wizard-v2__nav-label-title">
                                                    Add Department
                                                </div>
                                                <div class="kt-wizard-v2__nav-label-desc">
                                                    Setup Society Sectors
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="kt-wizard-v2__nav-item" data-ktwizard-type="step">
                                    <div class="kt-wizard-v2__nav-body">
                                        <div class="kt-wizard-v2__nav-icon">
                                            <i class="flaticon-confetti"></i>
                                        </div>
                                        <div class="kt-wizard-v2__nav-label">
                                            <div class="kt-wizard-v2__nav-label-title">
                                                Completed!
                                            </div>
                                            <div class="kt-wizard-v2__nav-label-desc">
                                                Review and Submit
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span id="Wizerdtitle">
                                    
                                </span>
                                
                            </div>
                        </div>

                        <!--end: Form Wizard Nav -->
                    </div>
                    <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v2__wrapper col">

                        <!--begin: Form Wizard Form-->
                        <form class="kt-form loader" id="kt_form" action="{{ ($society->id) ? route('societies.update', $society->id ) : route('societies.store') }}" method="post">

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

                            <!--begin: Form Wizard Step 1-->
                            <div class="kt-wizard-v2__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                                <div class="kt-heading kt-heading--md">Enter Society Details </div>
                                <div class="kt-form__section kt-form__section--first">
                                    <div class="kt-wizard-v2__form">
                                        <div class="form-group validated">

                                            <label class="form-control-label"><b>{{ __('Society Code*') }}</b></label>

                                            <input type="text" class="form-control @error('code') is-invalid @enderror"  name="code" value="{{ $society->code ?? old('code') }}" required autofocus placeholder="{{ __('Enter Society Code') }}">

                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group validated">
                                            <label class="form-control-label"><b>{{ __('Name*') }}</b></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" value="{{ $society->name ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Society Name') }}">

                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <input type="hidden" name="country_id" value="1">
                                        <div class="form-group validated">
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


                                        <div class="form-group validated">
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


                                        <div class="form-group validated">
                                            <label class="form-control-label"><b>{{ __('Address*') }}</b></label>

                                            <input type="text" class="form-control @error('address') is-invalid @enderror"  name="address" value="{{ $society->address ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Society Name') }}">

                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--end: Form Wizard Step 1-->
                            <!--begin: Form Wizard Step 2-->
                            
                            <!--end: Form Wizard Step 2-->
                            <!--begin: Form Wizard Step 6-->

                            <span id="WizerdBody">
                                <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                                    <div class="kt-heading kt-heading--md">Setup Society Sectors </div>
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v2__form">
                                            <div class="row" id="SectorRow">
                                                <div class="col-xl-6" id=inputCol>
                                                    <div class="form-group">
                                                        <label> <b>{{ __('Sector*')}} </b></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="sector_name[]" value="{{ old('sector_name') }}" placeholder="Add Sector Name" required>
                                                            <div class="input-group-append">
                                                                <a data-row="inputCol" class="btn btn-outline-danger remove_row" title="Remove Sector"> <i class="fa fa-times-circle fa-lg" style="color:#1c5b90;" ></i></a> 
                                                            </div>
                                                        </div>
                                                        @error('sector_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <a id="add_sector" class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus fa-lg" ></i> Add Sector </a> 
                                        </div>
                                    </div>
                                </div>
                            </span>

                            <div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
                                <div class="kt-heading kt-heading--md"> Add Department </div>
                                <div class="kt-form__section kt-form__section--first">
                                    <div class="kt-wizard-v2__form">
                                        <div class="row" id="DepartmentRow">
                                            <div class="col-xl-6" id=departmentCol>
                                                <div class="form-group">
                                                    <label> <b>{{ __('Add Department*')}} </b></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="department_name[]" value="{{ old('department_name') }}" placeholder="Add Department Name" required>
                                                        <div class="input-group-append">
                                                            <a data-row="departmentCol" class="btn btn-outline-danger remove_department" title="Remove Department"> <i class="fa fa-times-circle fa-lg" style="color:#1c5b90;" ></i></a> 
                                                        </div>
                                                    </div>
                                                    @error('department_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <a id="add_Department" class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus fa-lg" ></i> Add Department </a> 
                                    </div>
                                </div>
                            </div>


                            

                            <!--end: Form Wizard Step 6-->

                            <!--begin: Form Actions -->
                            <div class="kt-form__actions">
                              <h4> Submit Your Form </h4> <br><br>
                                <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                    Previous
                                </button>
                                <button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
                                    Submit
                                </button>

                               {{--  <a class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" id="AddNewWizerd">
                                    Add Floor
                                </a>
                                &nbsp; --}}
                                <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                    Next Step
                                </button>

                                
                            </div>

                            <!--end: Form Actions -->
                        </form>

                        <!--end: Form Wizard Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content -->
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/pages/custom/wizard/wizard-2.js?v=1') }}" type="text/javascript"></script>

<script>
    
    var count = 0;

    $("#AddNewWizerd").click(function(){
        // e.preventDefault();
        count = count + 1;
        var html_code = '<div class="kt-wizard-v2__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending" >'+
        '<div class="kt-wizard-v2__nav-body">'+
            '<div class="kt-wizard-v2__nav-icon">'+
                '<i class="flaticon-bus-stop"></i>'+
            '</div>'+
            '<div class="kt-wizard-v2__nav-label">'+
                '<div class="kt-wizard-v2__nav-label-title">'+
                    'Add Society Sectors['+count+']'+
                '</div>'+
                '<div class="kt-wizard-v2__nav-label-desc">'+
                    'Setup Society Sectors'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';

    var html_body='<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">'+
        '<div class="kt-heading kt-heading--md">Setup Society Sectors </div>'+
            '<div class="kt-form__section kt-form__section--first">'+
                '<div class="kt-wizard-v2__form">'+
                    '<div class="row" id="SectorRow">'+
                        '<div class="col-xl-6" id=inputCol>'+
                            '<div class="form-group">'+
                                '<label> <b>{{ __('Sector*')}} </b></label>'+
                                '<div class="input-group">'+
                                    '<input type="text" class="form-control" name="sector_name[]"  placeholder="Add Sector Name" required>'+
                                    '<div class="input-group-append">'+
                                        '<a data-row="inputCol" class="btn btn-outline-danger remove_row" title="Remove Sector"> <i class="fa fa-times-circle fa-lg" style="color:#1c5b90;" ></i></a> '+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+

                    '<a id="add_sector" class="btn btn-bold btn-sm btn-label-brand"> <i class="la la-plus fa-lg" ></i> Add Sector </a>'+ 
                '</div>'+
            '</div>'+
        '</div>';


    $("#WizerdBody").append(html_body);
    $("#Wizerdtitle").append(html_code);


    });
</script>



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
            html_data = '<option> No City Found </option>';
        }
        $('#CitySelect').html(html_data);
        $('.kt-selectpicker').selectpicker("refresh");
    });

</script>



<script>
    // $(document).ready(function(){});

    $(document).ready(function () {
        var count = 0;
        var dep_count = 0;
        $("#add_sector").click(function (e) {
            e.preventDefault(); //stop form submitting
            count = count + 1;
            var html_code = '<div class="col-xl-6" id=inputCol'+count+'>'
                            +'<div class="form-group">'+
                                '<label> <b>{{ __('Sector*')}} </b></label>'+
                                '<div class="input-group">'+
                                    '<input type="text" class="form-control" name="sector_name[]" placeholder="Add Sector Name" required>'+
                                    '<div class="input-group-append">'+
                                        '<a data-row="inputCol'+count+'" class="btn btn-outline-danger remove_row" title="Remove Sector"> <i class="fa fa-times-circle fa-lg" style="color:#1c5b90;" ></i></a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
            $("#SectorRow").append(html_code);
        });

        $("#add_Department").click(function (e) {
            e.preventDefault(); //stop form submitting
            dep_count = dep_count + 1;
            var department_html = '<div class="col-xl-6" id=departmentCol'+dep_count+'>'
                            +'<div class="form-group">'+
                                '<label> <b>{{ __('Department Name*')}} </b></label>'+
                                '<div class="input-group">'+
                                    '<input type="text" class="form-control" name="department_name[]" placeholder="Add Department Name" required>'+
                                    '<div class="input-group-append">'+
                                        '<a data-row="departmentCol'+dep_count+'" class="btn btn-outline-danger remove_row" title="Remove Sector"> <i class="fa fa-times-circle fa-lg" style="color:#1c5b90;" ></i></a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

            $("#DepartmentRow").append(department_html);
        });

        $(document).on("click", ".remove_row", function () {
            var remove_sector = $(this).data("row");
            $("#" + remove_sector).remove();
        });

        $(document).on("click", ".remove_department", function () {
            var remove_department = $(this).data("row");
            $("#" + remove_department).remove();
        });

    });
</script>



@endsection