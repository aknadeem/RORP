@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('UserManagement')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('modules.index') }}"><span class="kt-subheader__desc">{{ __('modules')}}</span></a>

                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __($module->id > 0 ? "edit" : "create")}} </span>
                
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
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ __(($module->id > 0 ? "Edit" : "Create").' Module') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('modules.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   {{ __('Modules')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($module->id) ? route('modules.update', $module->id ) : route('modules.store') }}" method="post">
                    @csrf
                    @if($module->id)
                        @method('PUT')
                    @endif

                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">
	                            <div class="form-group validated col-sm-6">
									<label class="form-control-label" for="inputSuccess1"><b>{{ __('Title*') }}</b></label>

									<input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{ $module->title ?? old('title') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter Module Title') }}">

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
    				<h5 class="modal-title">{{ __('Create Permission')}}</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form loader" method="POST" action="{{ route('modules.store') }}">
                    @csrf
    				<div class="modal-body">
    					<div class="row">
                            <div class="form-group validated col-sm-12">
    							<label class="form-control-label">{{ __('Title*') }}</label>
    							<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" required autofocus>

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

@endsection