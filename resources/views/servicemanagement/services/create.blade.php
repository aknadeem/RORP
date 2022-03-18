@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Service Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('services.index') }}"><span class="kt-subheader__desc">{{ __('Services')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __('Create')}}</span>
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
                            {{ __(($service->id > 0 ? "Edit" : "Create").' Service') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('services.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Services')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($service->id) ? route('services.update', $service->id ) : route('services.store') }}" method="post">
                    @csrf

                    @if($service->id > 0)
                        @php
                            $type_id = $service->type_id;
                            $exSubtype = $service->sub_type_id;
                        @endphp
                            @method('PUT')
                    @else
                        @php
                            $type_id = 0;
                            $exSubtype = 0;
                        @endphp
                    @endif

                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Service Type*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('type_id') is-invalid @enderror" id="ServiceTypeSelect" name="type_id" data-live-search="true" autofocus="true" required>
                                        <option selected disabled value="">  {{ __('Select Service Type')}}</option>
                                        @forelse($departments as $dep)
                                            <option @if ($type_id == $dep->id) selected @endif value="{{$dep->id}}"> {{$dep->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Service SubType*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('sub_type_id') is-invalid @enderror" id="ServiceSubTypeSelect" name="sub_type_id" required data-live-search="true">
                                        @if ($exSubtype > 0)
                                            <option selected value="{{$exSubtype}}"> {{$service->subtype->name ?? ''}}</option>
                                        @else   
                                            <option selected disabled>  {{ __('Select Service SubType')}}</option> 
                                        @endif
                                        
                                    </select>
                                    @error('sub_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                   <input type="text" class="form-control" name="title" value="{{ $service->title ?? old('title') }}" placeholder="Enter Title" required />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Installation Price') }}</b></label>
                                   <input type="number" min="0" step="any" class="form-control" name="installation_fee" value="{{ $service->installation_fee ?? old('installation_fee') }}" placeholder="Enter Installation Price" />
                                    @error('installation_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select  Billing Type*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('billing_type') is-invalid @enderror" id="ServiceTypeSelect" name="billing_type" required data-live-search="true">
                                        <option selected disabled>  {{ __('Select Billing Type')}}</option>
                                        <option 
                                        @if ($service->billing_type == 'monthly') selected @endif
                                          value="monthly">  {{ __('Monthly')}}</option>
                                        <option @if ($service->billing_type == 'one_time') selected @endif  value="one_time">  {{ __('One Time')}}</option>
                                        <option @if ($service->billing_type == 'no_billing') selected @endif  value="no_billing">  {{ __('No Billing')}}</option>
                                    </select>
                                    @error('billing_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>{{ __('Select Service Tax:')}} </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('tax_id') is-invalid @enderror" name="tax_id[]" data-live-search="true" multiple>
                                               {{--  <option selected disabled>{{ __('Select Service Tax')}}</option> --}}
                                                @forelse($taxes as $tax)
                                                    <option value="{{$tax->id}}" {{ (in_array($tax->id, old('tax_id', [])) || $service->tax_details->contains($tax->id)) ? 'selected' : '' }} > {{$tax->tax_title}} [ {{$tax->tax_percentage}}% ] </option>
                                                @empty
                                                @endforelse
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

                                <div class="form-group validated col-sm-12">
                                    <label class="form-control-label"><b>{{ __('Desription') }}</b></label>
                                    <textarea name="description" class="form-control" cols="30" rows="4">{{$service->description ?? '   '}}</textarea>
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
    @include('_partial.create_tax_modal')
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js?v=1') }}" type="text/javascript"></script>
    <script>
        $("#ServiceTypeSelect").change(function(){
            var department_id = parseInt($(this).val());
            var exSubtype = <?php  echo json_encode($exSubtype); ?>;
            var subdepart_html = '';
            var selected = '';
            // alert(department_id);
            var departments = <?php echo json_encode($departments); ?>;
            var depart = departments.find(x => x.id === department_id);
            if(depart.subdepartments.length > 0){
                subdepart_html+='<option selected Disabled value=""> Select Subdepartment </option>'; 
                for (var i = 0; i < depart.subdepartments.length; i++) {
                    if(exSubtype == depart.subdepartments[i].id){
                        selected = 'selected';
                    }
                    subdepart_html+='<option '+selected+' value='+depart.subdepartments[i].id+'>'+depart.subdepartments[i].name+'</option>'; 
                }
            }else{
                subdepart_html='<option value="" disabled> No Sub Department Found </option>';
            }
            $('#ServiceSubTypeSelect').html(subdepart_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
    </script>
@endsection