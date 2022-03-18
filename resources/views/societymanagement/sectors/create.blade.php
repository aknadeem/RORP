@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society Block')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('societyblocks.index') }}"><span class="kt-subheader__desc">{{ __('Society Blocks')}}</span></a>
               <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc">{{ __($societyblock->id > 0 ? "edit" : "create")}} </span>
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
                            {{ __(($societyblock->id > 0 ? "Edit" : "Create").' Society Block') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('societies.index') }}" class="btn btn-label-info btn-bold  btn-icon-h kt-margin-l-10 mt-3">
        	                   {{ __('Socities')}}
        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($societyblock->id) ? route('societyblocks.update', $societyblock->id ) : route('societyblocks.store') }}" method="post">
                    @csrf

                    @php
                    $exSec = 0;
                    $exSoc = 0;
                    if($societyblock->id > 0){
                        $exSoc = $societyblock->society->id;
                        $exSec = $societyblock->sector->id;
                    }
                    @endphp
                    
                    @if($societyblock->id)
                        @method('PUT')
                    @endif

                    

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                        	<div class="row">

                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b>{{ __('Name*') }}</b></label>
                                    <input type="text" class="form-control @error('block_name') is-invalid @enderror"  name="block_name" value="{{ $societyblock->block_name ?? old('block_name') }}" required autofocus placeholder="{{ __('Enter Society Block Name') }}">

                                    @error('block_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b>{{ __('Select SocietyBlock*') }}</b></label>

                                    <select class="form-control kt-select2 @error('society_id') is-invalid @enderror" id="kt_select2" name="society_id" style="width:100%;">
                                            <option selected disabled>  {{ __('Select Socity')}}</option>
                                            @forelse($socites as $soc)

                                            <option value="{{$soc->id}}" {{ ($exSoc == $soc->id) ? 'selected' : '' }}>{{ $soc->name }}</option>    
                                            @empty
                                                <option disabled> No Department Found </option>
                                            @endforelse
                                    </select>

                                    @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b>{{ __('Select Sector*') }}</b></label>

                                    {{-- <button type="button" class="btn btn-bold btn-label-brand btn-sm pb-1 pt-1" data-toggle="modal" title="Create Department" data-target="#kt_modal_1" style="float:right;"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</button> --}}

                                    <select class="form-control kt-select2 @error('society_sector_id') is-invalid @enderror" id="kt_select2_1" name="society_sector_id" style="width:100%;">
                                            <option selected disabled>  {{ __('Select Sector')}}</option>
                                            @forelse($sectors as $sec)

                                            <option value="{{$sec->id}}" {{ ($exSec == $sec->id) ? 'selected' : '' }}>{{ $sec->sector_name }}</option>    
                                            @empty
                                                <option disabled> No Department Found </option>
                                            @endforelse
                                    </select>

                                    @error('society_sector_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
	                            
	                        </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                            <a href="{{URL::previous()}}" type="reset"  class="btn btn-secondary">{{ __('Cancel')}}</a>
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
                <form class="kt-form" method="POST" action="{{ route('societyblocks.store') }}">
                    @csrf
    				<div class="modal-body">
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
    					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
    					<button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>
@endsection

@section('scripts')

<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js') }}" type="text/javascript"></script>

@endsection