@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement') }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('users.index') }}"><span class="kt-subheader__desc">{{ __('Users')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">{{ __('Set Permissions') }}</span>
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
    <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ __('Set Permissions') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('users.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
            	                   {{ __('Users List') }}
            	                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" action="{{ route('setuserpermissions', $user->id )}}" method="POST">
                        @csrf
                        @if($user->id)
                            @method('PUT')
                        @endif
                        <div class="kt-portlet__body pb-0">
                            <div class="kt-section kt-section--first mb-0">
                            	<div class="row">
    	                            <div class="form-group validated col-sm-12">
    									<label class="form-control-label" > <b> {{ __('User*') }} </b> </label>

    									<input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name}}" readonly>
                                        <input type="hidden" name="name" value="{{ $user->name}}">
    								</div>
    	                        </div>
                                <div class="row">
                                    <table class="table table-striped- table-bordered dtr-inline" >
                                        <tr>
                                            <th> {{ __('Modules')}} </th>
                                            <th> {{ __('Permissions')}}
                                            @if(auth()->user()->user_level_id == 1)
                                            <button type="button" class="btn btn-brand btn-bold btn-label-brand btn-sm " data-toggle="modal" title="Create Permission" data-target="#kt_modal_1" style="float: right;"><i class="fa fa-plus mb-1"></i>{{ __('Create Permission')}}</button>
                                        @endif
                                        &nbsp; &nbsp; 
                                        <input id="checkAll" class="btn btn-primary btn-sm p-1 text-white" type="button" title="Check Uncheck All" value="Check All">
                                            </th>
                                        </tr>
                                        @forelse ($permissions as  $module => $permissions)
                                            <tr class="permissionBoxes">
                                                <td><h6 class="mt-2 mb-2">{{ucfirst($module)}} &nbsp;&nbsp;  
                                                    <span>
                                                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand" title="Select Module Permission" style="float: right !important;">
                                                        <input type="checkbox" class="moduleSelect" module_name="{{$module}}">
                                                            <span></span>
                                                        </label>
                                                    </span> 
                                                </h6> 
                                                    
                                                </td>
                                                <td id="{{$module}}">
                                                    @foreach($permissions as $perm)
                                                    
                                                       <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success checkboxes">
                                                        <input type="checkbox" value="{{$perm->id}}" {{ (in_array($perm->id, old('permissions', [])) || $user->permissions->contains($perm->id)) ? 'checked' : '' }} name="permissions[]"> {{$perm->title}}
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
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Update')}}</button>
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
</div>
@endsection
@section('modal-popup')
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title" id="exampleModalLabel">{{ __('Create Permission')}}</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
    			<form class="kt-form" action="{{ route('permissions.store') }}"  method="POST">
                    @csrf

                    <input type="hidden" name="from_user" value="from_group">
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

                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label" for="title"> <b> {{ __('Title*') }} </b></label>

                                <input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{ $permission->title ?? old('title') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Permission Title') }}">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
    					<button type="submit" class="btn btn-primary btn-sm">Submit</button>
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
                if($(this).prop('checked')){
                    $('#'+module_name+' input').prop('checked', true);
                }else{
                    $('#'+module_name+' input').prop('checked', false);
                }
            });
        });
    </script>
@endsection