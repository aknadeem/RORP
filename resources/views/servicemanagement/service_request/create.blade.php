@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('requestservice.index') }}"><span
                        class="kt-subheader__desc">{{ __('Service Requests')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __(($service_request->id > 0 ? "Edit" : "Create")) }}</span>
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
                                {{ __(($service_request->id > 0 ? "Edit" : "Create").' Request Service') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('requestservice.index') }}"
                                    class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
                                    {{ __('Services')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form"
                        action="{{ ($service_request->id) ? route('requestservice.update', $service_request->id ) : route('requestservice.store') }}"
                        method="post">
                        @csrf
                        @if($service_request->id > 0)
                        @php
                        $service_id = $service_request->service_id;
                        $type_id = $service_request->type_id;
                        $exSubtype = $service_request->sub_type_id;
                        $expackage_id = $service_request->package_id;
                        $exDevice_id = $service_request->device_id;
                        $behaf_user_id = $service_request->user_id;
                        @endphp
                        @method('PUT')
                        @else
                        @php
                        $service_id = 0;
                        $type_id = 0;
                        $exSubtype = 0;
                        $expackage_id = 0;
                        $behaf_user_id = 0;
                        $exDevice_id = 0;
                        @endphp
                        @endif
                        <div class="kt-portlet__body pb-0">
                            <div class="kt-section kt-section--first mb-0 pb-0">
                                <div class="row">
                                    <div class="form-group validated col-sm-2">
                                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand checkboxes mt-4">
                                            is behalf <input @if ($service_request->is_behalf)
                                            checked
                                            @endif type="checkbox" value="1" name="is_behalf">
                                            <span></span>
                                        </label> &nbsp;
                                    </div>
                                    <div class="form-group validated col-sm-8">
                                        <label class="form-control-label"><b>{{ __('Select Resident*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('behaf_user_id') is-invalid @enderror"
                                            id="" name="behalf_user_id" data-live-search="true" autofocus="true"
                                            required>
                                            <option selected disabled> {{ __('Select Resident')}}</option>
                                            @foreach ($users as $resident)
                                            <option @if ($behaf_user_id==$resident->id) selected @endif
                                                value="{{ $resident->id }}">
                                                {{ $resident->name ?? '' }} [{{ $resident->unique_id ?? '' }}]
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label
                                            class="form-control-label"><b>{{ __('Select Service Type*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('type_id') is-invalid @enderror"
                                            id="ServiceTypeSelect" name="type_id" data-live-search="true"
                                            autofocus="true" required>
                                            <option selected disabled> {{ __('Select Service Type')}}</option>
                                            @foreach ($departments as $dep)
                                            <option @if ($type_id==$dep->id) selected @endif value="{{$dep->id}}">
                                                {{$dep->name}} </option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-6">
                                        <label
                                            class="form-control-label"><b>{{ __('Select Service SubType*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('sub_type_id') is-invalid @enderror"
                                            id="ServiceSubTypeSelect" name="sub_type_id" required
                                            data-live-search="true">
                                            @if ($exSubtype > 0)
                                            <option selected value="{{$exSubtype}}"> {{$service_request->subtype->name}}
                                            </option>
                                            @else
                                            <option selected disabled> {{ __('Select Service SubType')}}</option>
                                            @endif

                                        </select>
                                        @error('sub_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Select Service*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('service_id') is-invalid @enderror"
                                            id="ServiceSelect" name="service_id" required data-live-search="true">
                                            @if ($service_id > 0)
                                            <option selected value="{{$service_id}}">
                                                {{$service_request->service->title}}</option>
                                            @else
                                            <option selected disabled> {{ __('Select Service')}}</option>
                                            @endif

                                        </select>
                                        @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label
                                            class="form-control-label"><b>{{ __('Select Service Package') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('package_id') is-invalid @enderror"
                                            id="ServicePackageSelect" name="package_id" data-live-search="true">
                                            @if ($expackage_id > 0)
                                            <option selected value="{{$expackage_id}}">
                                                {{$service_request->service->title}}</option>
                                            @else
                                            <option selected disabled> {{ __('Select Service Package')}}</option>
                                            @endif

                                        </select>
                                        @error('package_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label
                                            class="form-control-label"><b>{{ __('Select Service Device') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('device_id') is-invalid @enderror"
                                            name="device_id" id="ServiceDevicesRequired" data-live-search="true">
                                            <option selected disabled>Select Optional Device</option>
                                            @foreach ($service_devices->where('device_status','required') as $device)
                                            <option @if ($exDevice_id==$device->id) selected @endif
                                                value="{{$device->id}}">{{$device->device_title}} [Rs:
                                                {{$device->device_price}} ]</option>
                                            @endforeach
                                        </select>
                                        @error('device_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-6">
                                        <label
                                            class="form-control-label"><b>{{ __('Select Service Optional Device') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('device_id') is-invalid @enderror"
                                            name="op_device_id[]" data-live-search="true" multiple>
                                            <option disabled>Select Optional Device</option>
                                            @foreach ($service_devices->where('device_status','optional') as $device)
                                            <option @if ($service_request->devices->contains($device->id)) @endif
                                                value="{{$device->id}}">{{$device->device_title}} [Rs:
                                                {{$device->device_price}} ]</option>

                                            @endforeach
                                        </select>
                                        @error('device_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-12">
                                        <label class="form-control-label"><b>{{ __('Desription') }}</b></label>

                                        <textarea name="description"
                                            id="kt-ckeditor-1">{!! $news->description ?? '' !!}</textarea>
                                        @error('Description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>

                                <a href="{{URL::previous()}}" type="reset"
                                    class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>
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
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript">
</script>
<script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=1') }}" type="text/javascript">
</script>
<script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js?v=1') }}" type="text/javascript"></script>
<script>
    $("#ServiceTypeSelect").change(function(){
            var department_id = parseInt($(this).val());
            var exSubtype = <?php  echo json_encode($exSubtype); ?>;
            var subdepart_html = '';
            var selected = '';
            var departments = <?php echo json_encode($departments); ?>;
            var depart = departments.find(x => x.id === department_id);
            if(depart.subdepartments.length > 0){
                subdepart_html='<option> Select SubType </option>';
                for (var i = 0; i < depart.subdepartments.length; i++) {
                    if(exSubtype == depart.subdepartments[i].id){
                        selected = 'selected';
                    }
                    subdepart_html+='<option '+selected+' value='+depart.subdepartments[i].id+'>'+depart.subdepartments[i].name+'</option>'; 
                }
            }else{
                subdepart_html='<option> No Sub Department Found </option>';
            }
            $('#ServiceSubTypeSelect').html(subdepart_html);

            $('.kt-selectpicker').selectpicker("refresh");
        });

        $("#ServiceSubTypeSelect").change(function(){
            // alert(department_id)
            var subtype_id = parseInt($(this).val());
            var service_id = <?php  echo json_encode($service_id); ?>;
            var service_html = '';
            var selected = '';
            var services = <?php echo json_encode($services); ?>;
            var servicefilters = services.filter(x => x.sub_type_id === subtype_id);
            // console.log(servicefilters);
            if(servicefilters.length > 0){
                service_html='<option> Select Service </option>';
                for (var i = 0; i < servicefilters.length; i++) {
                    if(service_id == servicefilters[i].id){
                        selected = 'selected';
                    }
                    service_html+='<option '+selected+' value='+servicefilters[i].id+'>'+servicefilters[i].title+'</option>'; 
                }
            }else{
                service_html='<option> No service Found </option>';
            }
            $('#ServiceSelect').html(service_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });

        $("#ServiceSelect").change(function(){
            // alert(department_id)
            var ServiceId = parseInt($(this).val());
            var expackage_id = <?php  echo json_encode($expackage_id); ?>;
            var package_html = '';
            var required_html = '';
            var optional_html = '';
            var selected = '';
            var servicePackages = <?php echo json_encode($services_packages); ?>;
            var serviceDevicesAll = <?php echo json_encode($service_devices); ?>;
            var pckgfilters = servicePackages.filter(x => x.service_id === ServiceId);
            // console.log(servicefilters);

            var serviceDevice = serviceDevicesAll.filter(y => y.service_id === ServiceId);
            var required_devices = serviceDevice.filter(x => x.device_status == 'required');
            var optional_devices = serviceDevice.filter(x => x.device_status == 'optional');
            if(required_devices.length > 0){
                required_html='<option value=""> Select Required Devices </option>';
                for (var i = 0; i < required_devices.length; i++) {
                    if(expackage_id == required_devices[i].id){
                        selected = 'selected';
                    }
                    required_html+='<option '+selected+' value='+required_devices[i].id+'>'+required_devices[i].device_title+'[ Rs: '+required_devices[i].device_price+' ] </option>'; 
                }
            }else{
                required_html='<option value=""> No Device Found </option>';
            }
            $('#ServiceDevicesRequired').html(required_html);
            $('.kt-selectpicker').selectpicker("refresh");


            if(pckgfilters.length > 0){
                package_html='<option value=""> Select Service Package </option>';
                for (var i = 0; i < pckgfilters.length; i++) {
                    if(expackage_id == pckgfilters[i].id){
                        selected = 'selected';
                    }
                    package_html+='<option '+selected+' value='+pckgfilters[i].id+'>'+pckgfilters[i].title+'[ Rs. '+pckgfilters[i].price+' ] </option>'; 
                }
            }else{
                package_html='<option value=""> No Packages Found </option>';
            }
            $('#ServicePackageSelect').html(package_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
</script>
@endsection