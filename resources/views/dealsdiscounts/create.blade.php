@extends('layouts.base')

@section('content')

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Deal & Discounts')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('deals.index') }}"><span class="kt-subheader__desc">{{ __('Deals&Discounts')}}</span></a>

                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc"> {{ ($deal->id > 0 ? "Edit" : "Create") }} </span>
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
                            {{ __(($deal->id > 0 ? "Edit" : "Create").' Deal&Discount') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('deals.index') }}" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
                               {{ __('Deals')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($deal->id) ? route('deals.update', $deal->id ) : route('deals.store') }}" method="post">
                    @csrf
                    @php
                        $exSoc = 0;
                        $exSector = array();
                        $exvendor = 0;
                        $start_date = '';
                        $end_date = '';
                    @endphp
                    @if($deal->id)
                    @php
                        $exSoc = $deal->society_id;
                        $exSector = $deal->sectors->pluck('id');
                        $exvendor = $deal->vendor_id;
                        $start_date = $deal->start_date->format('Y-m-d');
                        $end_date = $deal->end_date->format('Y-m-d');
                    @endphp
                        @method('PUT')
                    @endif
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Society* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" name="society_id" data-live-search="true" required id="societySelect">
                                                    <option selected value="">  {{ __('Select Society')}}</option>
                                                    @forelse($societies as $soc)

                                                    <option value="{{$soc->id}}" {{ ($exSoc == $soc->id) ? 'selected' : '' }}>{{ $soc->name }}</option>    
                                                    @empty
                                                        <option value=""> No Society Found </option>
                                                    @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Sector* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker" name="sectors[]" data-live-search="true" required multiple id="sectorSelect">
                                                <option value=""> Select Sector</option> 
                                                @if ($exSector != '')
                                                @foreach($deal->sectors as $sector)
                                                    <option {{ (in_array($sector->id, old('sectors', [])) || $deal->sectors->contains($sector->id)) ? 'selected' : '' }}  value="{$sector->id}}"> {{ $sector->sector_name }} </option> 
                                                @endforeach
                                                @else
                                                    <option value=""> Select Sector</option> 
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Vendor* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker @error('vendor_id') is-invalid @enderror" name="vendor_id" data-live-search="true" required id="vendorSelect">
                                                <option disabled>  {{ __('Select Vendor')}}</option>

                                                @if ($exvendor > 0)
                                                    <option value="{{$exvendor}}" selected>  {{ $deal->vendor->title}}</option> 
                                                @else
                                                    <option selected disabled>  {{ __('Select Vendor')}}</option> 
                                                @endif
                                            </select>
                                           {{--  <div class="input-group-append">
                                                <a data-toggle="modal" title="Add Permission" data-target="#kt_modal_vendor"  class="btn btn-primary">&nbsp;<i class="fa fa-plus" style="color:#fff;"></i></a> 
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $deal->title ?? old('title') }}" placeholder="Enter Title" autofocus />

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-control-label"><b>{{ __('Start Date*') }}</b></label>
                                    <div class="input-group date">
                                        <input type="text" name="start_date" class=" kt_datepicker_validate form-control @error('start_date') is-invalid @enderror" value="{{ $start_date ?? old('start_date') }}" placeholder="Select Start Date" style="border-radius: 3px;">
                                        
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-control-label"><b>{{ __('End Date*') }}</b></label>
                                    <div class="input-group date">
                                        <input type="text" name="end_date" class=" kt_datepicker_validate form-control @error('end_date') is-invalid @enderror" value="{{ $end_date ?? old('end_date') }}" placeholder="Select End Date" style="border-radius: 3px;">
                                        
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>
                                    <textarea class="summernote" id="kt_summernote_1" name="description">{!! $deal->description ?? '' !!}</textarea>
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
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
    <script>
        $("#societySelect").change(function(){
            var society_id = parseInt($(this).val());
            var exSector = <?php echo json_encode($exSector); ?>;
            var exvendor = <?php echo json_encode($exSector); ?>;
            console.log(society_id);
            var sector_html = '';
            var vendor_html = '';
            var selected = '';
            
            
            var societies = <?php echo json_encode($societies); ?>;
            var society = societies.find(x => x.id === society_id);

            // console.log(society);
            if(society.sectors.length > 0){
                for (var i = 0; i < society.sectors.length; i++) {
                    // console.log(single_province.cities[i].name);
                    if(exSector == society.sectors[i].id){
                        selected = 'selected';
                    }

                    sector_html+='<option value='+society.sectors[i].id+'>'+society.sectors[i].sector_name+'</option>'; 
                }
            }else{
                // console.log("No City Found");
                sector_html='<option> No Sector Found </option>';
            }
            // // filter vendor for selected society
            // isEmpty
            if(society.vendors.length > 0){
                for (var i = 0; i < society.vendors.length; i++) {
                    if(exvendor == society.vendors[i].id){
                        selected = 'selected';
                    }
                    vendor_html+='<option value='+society.vendors[i].id+'>'+society.vendors[i].title+'</option>'; 
                }
            }else{
                // console.log("No City Found");
                vendor_html='<option> No Vendor Found </option>';
            }
            $('#sectorSelect').html(sector_html);
            $('#vendorSelect').html(vendor_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
    </script>
@endsection