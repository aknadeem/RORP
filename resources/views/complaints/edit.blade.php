@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Complaints')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('complaints.index') }}"><span class="kt-subheader__desc">{{ __('Complaints')}}</span></a>
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
                            {{ __(($complaint->id > 0 ? "Edit" : "Create").' Complaint') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('complaints.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   {{ __('Complaint')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($complaint->id) ? route('complaints.update', $complaint->id ) : route('complaints.store') }}" method="post">
                    @csrf

                    @php
                        // $exprovince = 0;

                        // if($complaint->id > 0){
                        //    $exprovince = $complaint->province->id;
                        // }
                    @endphp

                    @if($complaint->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">

                            <div class="form-group validated col-sm-2">
                                <label class="form-control-label"><b>{{ __('Type*') }}</b></label>
                                <select class="form-control kt-select2 @error('module_id') is-invalid @enderror" id="kt_select2" name="user_type" style="width:100%;">
                                    <option @if ($complaint->user_type == 'Self')
                                        selected 
                                    @endif value="self"> Self </option>
                                    <option @if ($complaint->user_type == 'on_behalf')
                                        selected 
                                    @endif value="on_behalf"> On Behaf </option>
                                    
                                </select>
                            </div>

                            <div class="form-group validated col-sm-5">
                                <label class="form-control-label"><b>{{ __('POC Name*') }}</b></label>
                                    <input type="text" class="form-control" value="{{$complaint->poc_name}}" name="poc_name"/>
                            </div>

                            <div class="form-group validated col-sm-5">
                                <label class="form-control-label"><b>{{ __('POC Contact Number*') }}</b></label>
                                    <input type="text" class="form-control kt_inputmask_8_1" name="poc_number" value="{{$complaint->poc_number}}" />
                            </div>
                            @php
                            $disabled = '';

                            $web_user = Auth::guard('web')->user();
                            if($web_user != ''){
                                $user_level_id = $web_user->user_level_id;
                            }else{
                                $api_user = Auth::guard('api')->user();
                                $user_level_id = $api_user->user_level_id;
                            }


                            if($complaint->department->hod){
                               if ($complaint->department->hod->hod->user_level_id == $user_level_id OR $user_level_id < $complaint->department->hod->hod->user_level_id){

                                    $disabled = '';
                                } else{
                                    $disabled = 'disabled';
                                } 
                            }
                                
                            @endphp

                            <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Department Name*') }}</b></label>
                                    <select {{$disabled}} class="form-control kt-selectpicker @error('module_id') is-invalid @enderror"  name="department_id" data-live-search="true">

                                        @foreach ($departments as $department)

                                            <option @if ($complaint->department_id == $department->id)
                                                selected 
                                            @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
	                            

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Sub Department*') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('module_id') is-invalid @enderror" data-live-search="true" name="sub_department_id" >
                                        @foreach ($subdepartments as $subdepartment)
                                             <option @if ($complaint->sub_department_id == $subdepartment->id)
                                                        selected 
                                                    @endif value="{{ $subdepartment->id }}">{{ $subdepartment->name }}</option>
                                        @endforeach      
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __(' Complaint Title*') }}</b></label>
                                    <input type="text" value="{{ $complaint->complaint_title }}" class="form-control" name="complaint_title"/>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __(' Complaint Description*') }}</b></label>
                                    <input type="text" value="{{ $complaint->complaint_description }}" class="form-control" name="complaint_description"/>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Complaint Location*') }}</b></label>
                                    <input type="text" value="{{ $complaint->complaint_location }}" class="form-control" name="complaint_location"/>
                                </div>

	                        </div>

                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            {{-- <button  type="reset" class="btn btn-secondary btn-sm">{{ __('Cancel')}}</button> --}}
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

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
@endsection

