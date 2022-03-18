@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Events Management')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('event.index') }}"><span class="kt-subheader__desc">{{ __('events')}}</span></a>
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
                            {{ __(($event->id > 0 ? "Edit" : "Create").' Event') }}
                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="{{ route('event.index') }}" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               {{ __('Events')}}
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ ($event->id) ? route('event.update', $event->id ) : route('event.store') }}" method="post" enctype="multipart/form-data">
                    @csrf


                    @if($event->id > 0)
                        @php
                            $exSoc = $event->society_id;
                            $exSector = $event->sector_id;
                            $event_date = '';
                            $event_date = $event->event_date ;
                            $date =  $event->event_date->format('Y-m-d');
                        @endphp
                            @method('PUT')
                    @else
                        @php
                            $exSoc = 0;
                            $exSector = 0;
                            $event_date = '';
                            $date =  '';
                        @endphp
                    @endif

                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                                    <input type="text" class="form-control" name="title" value="{{ $event->title ?? old('title') }}" placeholder="Event Title" required autofocus />
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-control-label"><b>{{ __('Event Date*') }}</b></label>
                                    <div class="input-group date">
                                        <input type="text" name="event_date" class="kt_datepicker_validate form-control @error('event_date') is-invalid @enderror" value="{{$date}}" placeholder="Select date" style="border-radius: 3px;">
                                        @error('event_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Society') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('society_id') is-invalid @enderror" id="SocietySelect" name="society_id" data-live-search="true">
                                        
                                        <option selected disabled>  {{ __('Select Society')}}</option>
                                        
                                        @foreach ($societies as $soc)
                                            <option @if ($exSoc == $soc->id) selected @endif value="{{$soc->id}}"> {{$soc->name}} </option> 
                                        @endforeach 
                                            
                                    </select>

                                    @error('society_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                 <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Select Sector') }}</b></label>

                                    <select class="form-control kt-selectpicker @error('sector_id') is-invalid @enderror" name="sectors[]" id="SectorSelect" data-live-search="true" multiple>
                                        <option disabled>  {{ __('Select Sector')}}</option>
                                        @if ($event->sectors->count() > 0)
                                            @foreach ($event->sectors as $sector)
                                                <option selected value="{{$sector->id}}"> {{ $sector->sector_name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('sector_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                     <label class="form-control-label"><b>{{ __('Image') }}</b></label>
                                        <input type="file" name="image" class="form-control">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="form-control-label"><b>{{ __('Event Venue') }}</b></label>
                                    <input type="text" class="form-control" name="event_venue" placeholder="Event Venue" value="{{ $event->event_venue ?? old('event_venue') }}" />
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-control-label mb-2"><b>{{ __('Description') }}</b></label>

                                    <textarea class="summernote" name="description" id="kt_summernote_1">{!! $event->description ?? '' !!}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                            <a type="reset"  class="btn btn-secondary btn-sm">{{ __('Cancel')}}</a>
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
        $("#SocietySelect").change(function(){
            var society_id = parseInt($(this).val());
            var exSector = <?php echo json_encode($exSector); ?>;
            // console.log(exSector);
            var sector_html = '';
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
                    sector_html+='<option '+selected+' value='+society.sectors[i].id+'>'+society.sectors[i].sector_name+'</option>'; 
                }
            }else{
                // console.log("No City Found");
                sector_html='<option> No Sector Found </option>';
            }
            $('#SectorSelect').html(sector_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
    </script>
@endsection