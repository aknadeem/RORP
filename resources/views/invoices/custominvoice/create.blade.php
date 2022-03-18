@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content"> 
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Custom Invoice')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('custominvoice.index') }}"><span class="kt-subheader__desc">{{ __('Custom Invoice')}}</span></a>
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
                            {{ __(($invoice->id > 0 ? "Edit" : "Create").' Invoice') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('custominvoice.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3" title="Click to View Fine&Planties">
                               {{ __('Custom Invoices')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ ($invoice->id) ? route('custominvoice.update', $invoice->id ) : route('custominvoice.store') }}" method="post">
                    @csrf
                    @if($invoice->id > 0)
                        @php
                            $ex_user = $invoice->user_id;
                        @endphp
                        @method('PUT')
                    @else
                        @php
                            $ex_user = 0;
                        @endphp
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Title:*') }}</b></label>
                                   <input type="text" class="form-control" name="title" value="{{ $invoice->title ?? old('title') }}" placeholder="Enter Title" autofocus="true" required />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Amount:*') }}</b></label>
                                   <input type="number" step="any" class="form-control" name="price" value="{{ $invoice->price ?? old('price') }}" placeholder="Enter Amount" />
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select User:*') }}</b></label>
                                    <select class="form-control kt-selectpicker @error('user_id') is-invalid @enderror" name="user_id" data-live-search="true"  required>
                                        <option selected disabled>  {{ __('Select User')}}</option>
                                        @foreach ($users as $user)
                                            <option {{ old('user_id') || $ex_user ? 'selected' : '' }} {{-- @if ($ex_user == $user->id) selected @endif --}} value="{{$user->id}}"> {{$user->name}} </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Due Date:*') }}</b></label>
                                   <input type="text" class="form-control kt_datepicker_validate" name="due_date" value="{{ $invoice->due_date ?? old('due_date') }}" placeholder="Enter Due Date" />
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group validated col-sm-12">
                                    <label class="form-control-label"><b>{{ __('Desription') }}</b></label>
                                    <textarea name="description" class="form-control" cols="30" rows="4">{{$invoice->description ?? '   '}}</textarea>
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

@section('scripts')
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/editors/ckeditor-classic.js?v=1') }}" type="text/javascript"></script>
@endsection