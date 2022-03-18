@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('submodules.index') }}"><span class="kt-subheader__desc">{{ __('Modules')}}</span></a>

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
                            {{ __(($submodule->id > 0 ? "Edit" : "Create").' Sub Module') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('submodules.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
        	                   {{ __('Sub Departments')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($submodule->id) ? route('submodules.update', $submodule->id ) : route('submodules.store') }}" method="post">
                    @csrf

                    @php
                    $exMod = 0;
                    if($submodule->id > 0){
                        $exMod = $submodule->module_id;
                    }
                    @endphp
                    
                    @if($submodule->id)
                        @method('PUT')
                    @endif

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                        	<div class="row">
                                <div class="form-group validated col-sm-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><b>{{ __('Select Module*') }}</b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('module_id') is-invalid @enderror" name="module_id" data-live-search="true" required>
                                                <option selected disabled>  {{ __('Select Module')}}</option>
                                                @forelse($modules as $mod)

                                                <option value="{{$mod->id}}" {{ ($exMod == $mod->id) ? 'selected' : '' }}>{{ $mod->title }}</option>    
                                                @empty
                                                    <option disabled> No Module </option>
                                                @endforelse
                                            </select>
                                            @error('module_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="input-group-append">
                                                <a data-toggle="modal" title="Add Module" data-target="#kt_modal_1"  class="btn btn-primary">&nbsp;<i class="fa fa-plus" style="color:#fff;"></i></a> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
	                            <div class="form-group validated col-sm-6">
									<label class="form-control-label"><b>{{ __('Title*') }}</b></label>
									<input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{ $submodule->title ?? old('title') }}" required autocomplete="title" autofocus placeholder="{{ __('Enter Sub Module Title') }}">

                                    @error('title')
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
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">{{ __('Create Module')}}</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form" method="POST" action="{{ route('modules.store') }}">
                    @csrf
    				<div class="modal-body">
                        <input type="hidden" name="from_user" value="from_user">
    					<div class="row">
                            <div class="form-group validated col-sm-12">
    							<label class="form-control-label">{{ __('Name*') }}</label>
    							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>

    							@error('name')
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

<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>

@endsection