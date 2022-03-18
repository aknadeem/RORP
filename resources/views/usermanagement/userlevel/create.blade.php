@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">             
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement') }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('userlevels.index') }}"><span class="kt-subheader__desc">{{ __('UserLevels')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __($userlevel->id > 0 ? "edit" : "create")}} </span>
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
                            {{ __(($userlevel->id > 0 ? "Edit" : "Create").' User Level') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('userlevels.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   {{ __('User Levels') }}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($userlevel->id) ? route('userlevels.update', $userlevel->id ) : route('userlevels.store') }}" method="post">
                    @csrf
                    @if($userlevel->id)
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body mb-0 pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">
	                            <div class="form-group validated col-sm-12">
									<label class="form-control-label" for="inputSuccess1"><b> {{ __('Title*:') }} </b> </label>

									<input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{ $userlevel->title ?? old('title') }}" required autocomplete="title" autofocus placeholder="{{ __('Enter User Level Title') }}"> 
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
								</div>
	                        </div>
                            <div class="row">
                                <table class="table table-striped- table-bordered dtr-inline" >
                                    <tr>
                                        <th> {{ __('Modules')}} </th>
                                        <th> {{ __('Permissions')}}
                                            <button type="button" class="btn btn-bold btn-label-brand btn-sm" data-toggle="modal" title="Create Permission" data-target="#kt_modal_1" style="float: right;"><i class="fa fa-plus mb-1"></i>{{ __('Create Permission')}}</button> 

                                            {{-- &nbsp; &nbsp; <a onclick="SelectAll()"> Select all </a> --}}
                                            &nbsp; &nbsp; 
                                           <input id="checkAll" class="btn btn-primary btn-sm p-1 text-white" type="button" title="Check Uncheck All" value="Check All">

                                        </th>
                                    </tr>
                                    @forelse ($new_permissions as  $module => $permissions)
                                        <tr class="permissionBoxes">
                                            <td> 
                                                <h6 class="mt-2 mb-2">{{$module}}&nbsp;&nbsp;  
                                                    <span>
                                                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand" title="Select Module Permission" style="float: right !important;">
                                                        <input type="checkbox" class="moduleSelect" module_name="{{$module}}">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </h6> 
                                            </td>
                                            <td>
                                                @foreach($permissions as $perm)
                                                   <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success mt-2" id="{{$module}}">
                                                    <input type="checkbox" value="{{$perm->id}}" {{ (in_array($perm->id, old('permissions', [])) || $userlevel->permissions->contains($perm->id)) ? 'checked' : '' }} name="permissions[]"> {{$perm->title}}
                                                        <span></span>
                                                    </label> &nbsp; &nbsp;
                                                @endforeach
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td> No Permissions Found </td>
                                            </tr>
                                        @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            <button type="reset" class="btn btn-secondary btn-sm">Cancel</button>
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
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                    {{ __('Create Permission')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('permissions.store') }}"  method="POST">
                    @csrf

                    <input type="hidden" name="from_level" value="from_level">
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select Module*')}} </b></label>
                                <select class="form-control kt-select2 @error('module_id') is-invalid @enderror" id="kt_select2" name="module_id" style="width:100%;">
                                    <option selected disabled> <b> {{ __('Select Module')}} </b> </option>
                                    @forelse($allmodules as $module)
                                    <option value="{{$module->id}}">{{ $module->slug }}</option>    
                                    @empty
                                        <option disabled> No module Found </option>
                                    @endforelse
                                </select>
                                @error('modules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label" for="title"> <b> {{ __('Title*') }} </b> </label>

                                <input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{ $permission->title ?? old('title') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Permission Title') }}">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script>
    $(function() {
        $(document).on('click', '#checkAll', function() {
            if ($(this).val() == 'Check All') {
              $('.permissionBoxes input').prop('checked', true);
              $(this).val('Uncheck All');
            } else {
              $('.permissionBoxes input').prop('checked', false);
              $(this).val('Check All');
            }
        });
        $(document).on('click', '.moduleSelect', function() {
            var module_name = $(this).attr('module_name');

            // alert(module_name);
            if($(this).prop('checked')){
                $('#'+module_name+' input').prop('checked', true);
            }else{
                $('#'+module_name+' input').prop('checked', false);
            }
        });
    });
</script>
@endsection