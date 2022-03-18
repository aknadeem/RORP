@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Notifications')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('customNotifications') }}"><span class="kt-subheader__desc">{{ __('Custom Notifications')}}</span></a>
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
                            create Notification
                            {{-- {{ __(($deal->id > 0 ? "Edit" : "Create").' Deal&Discount') }} --}}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('customNotifications') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Custom Notifications')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="{{ route('notification.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Society* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker societySelect @error('society_id') is-invalid @enderror" name="society_id" data-live-search="true" required>
                                                <option selected disabled>  {{ __('Select Society')}}</option>
                                                @forelse($societies as $soc)
                                                <option value="{{$soc->id}}" >{{ $soc->name }}</option>    
                                                @empty
                                                    <option disabled> No Society Found </option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Sector* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker sectorSelect @error('sector_id') is-invalid @enderror" name="sector_id[]" data-live-search="true" required multiple id="sectorSelect">
                                                <option disabled>  {{ __('Select Sector')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $deal->title ?? old('title') }}" autofocus />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="form-control-label"><b> Upload Attachment </b></label>
                                    <input type="file" class="form-control" name="attachment" />
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-2 pt-2">
                                    &nbsp;&nbsp; <label class="pb-2"> <b> Heigh Light </b></label> <br>
                                   &nbsp;&nbsp; <label class="kt-checkbox kt-checkbox--bold kt-checkbox--primary">
                                    <input type="checkbox" value="1" name="is_highlight"> is Highlight
                                        <span></span>
                                    </label> &nbsp; 
                                </div>
                                
                                <div class="col-md-12 form-group">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>
                                    
                                    <textarea class="summernote" name="description" id="kt_summernote_1"></textarea>
                                    
                                    @error('description')
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
    
    <script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
    
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript"></script>
<script>
    $(".societySelect").change(function(){
        var society_id = parseInt($(this).val());
        var sector_html = '';
        var selected = '';
        var societies = <?php echo json_encode($societies); ?>;
        var society = societies.find(x => x.id === society_id);
        // console.log(society);
        if(society.sectors.length > 0){
            for (var i = 0; i < society.sectors.length; i++) {
                sector_html+='<option value='+society.sectors[i].id+'>'+society.sectors[i].sector_name+'</option>'; 
            }
        }else{
            sector_html='<option> No Sector Found </option>';
        }
        $('#sectorSelect').html(sector_html);
        $('.kt-selectpicker').selectpicker("refresh");
        });
</script>
@endsection