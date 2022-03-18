@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Society')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('societies.index') }}"><span class="kt-subheader__desc">{{ __('Societies')}}</span></a>
                
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Societies')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
						    @if (auth()->user()->user_level_id ==1)
                                @can('create-society-management')
    							    <a href="{{ route('societies.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Society"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
                                @endcan
                            @endif
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>{{ __('ID')}}</th>
                            <th>{{ __('Code')}}</th>
							<th>{{ __('Name')}} </th>
							<th>{{ __('Country')}} </th>
							<th>{{ __('Province')}} </th>
							<th>{{ __('City')}} </th>
							
                            @canany(['create-society-management','view-society-management'])
                                <th>{{ __('Sectors')}} </th>
                            @endcanany
                            @if (auth()->user()->user_level_id ==1)
                                @canany(['update-society-management','delete-society-management'])
    							    <th>{{ __('Actions')}}</th>
                                @endcanany
                            @endif
						</tr>
					</thead>
					<tbody>
						@forelse ($societies as $socity)
							<tr>
								<td>{{$socity->id}}</td>
                                <td>{{$socity->code}}</td>
								<td>{{ __($socity->name) }}</td>
								<td>{{ __($socity->country->name ?? '') }}</td>
								<td>{{ __($socity->province->name ?? '') }}</td>
								<td>{{ __($socity->city->name ?? '') }}</td>
								<td>
                                    @can('create-society-management')
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addSocModal" title="Add Sectors" soc_code="{{ __($socity->code) }}" soc_name="{{ __($socity->name) }}" soc_id="{{$socity->id}}"><i class="fa fa-plus mb-1"></i>Add</button>
                                    @endcan
                                    @can('view-society-management')
                                        <button type="button" class="btn btn-bold btn-label-brand btn-sm viewHodModal" title="View Sectors" dep_title="{{ __($socity->name) }}" dep_id="{{$socity->id}}"><i class="fa fa-eye mb-1"></i>view </button>
                                    @endcan
								</td>
								@if (auth()->user()->user_level_id ==1)
    								<td>
                                        @can('update-society-management')
    									   <a href="{{route('societies.edit', $socity->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Society"></i> </a> &nbsp;
                                        @endcan
                <!--                        @can('delete-society-management')-->
    									   <!--<a href="{{route('societies.destroy', $socity->id)}}" class="text-danger delete-confirm" del_title="Society {{$socity->name}}"><i class="fa fa-trash-alt fa-lg" title="Delete Society"></i></a>-->
                <!--                        @endcan-->
    								</td>
    							@endif
							</tr>
						@empty
						<tr>
							<td colspan="6" class="text-danger text-center"> No Data Available </td>
						</tr>						
						@endforelse
					</tbody>
				</table>
				<!--end: Datatable -->
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
                    <h5 class="modal-title">
                    <span id="AddSocTitle"></span> {{ __('Add Sector')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('society.addsector') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                    	<input type="hidden" name="society_id" id="soc_id" value="{{ old('society_id') }}">
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Society')}} </b></label>
                                <input type="text" class="form-control" name="soc_name" readonly id="soc_name" value="{{ old('soc_name') }}">
                                @error('society_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Sector*')}} </b></label>
                                <input type="text" class="form-control" name="sector_name" value="{{ old('sector_name') }}" placeholder="Add Sector Name" required>
                                @error('sector_name')
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
    <div class="modal fade" id="kt_modal_loadData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="SocietyTitle"></span> Sector's </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="department_id" id="dep_id">
                    <div class="row">
                        <div class="kt-notification-v2 col-md-12" id="dropdownHods">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script>

    var sector_name_error = '';
    sector_name_error = '<?php echo $errors->first('sector_name'); ?>';
    if(sector_name_error != ''){
    // alert(sector_name_error);
        $('#kt_modal_1').modal('show');
    }
	$(".addSocModal").click(function(event) {
        var soc_code = $(this).attr("soc_code");
        var soc_name = $(this).attr("soc_name");
        // alert(soc_code);
        var soc_id = $(this).attr("soc_id");
        $('#kt_modal_1').modal('show');
        $('#AddSocTitle').html(soc_code);
        $('#soc_name').val(soc_name);
        // $('#soc_code').val(soc_code);
        $('#soc_id').val(soc_id);
    });
</script>

<script>
    $(".viewHodModal").click(function(event) {
        var vdep_title = $(this).attr("dep_title");
        var dep_id = $(this).attr("dep_id");
        // alert
        $('#kt_modal_loadData').modal('show');
        $('#SocietyTitle').html(vdep_title);
        $('#dep_id').val(dep_id);
        // alert(dep_id);
        $.ajax({
            type: "GET",
            contentType: "application/json",
            url: "{{ url('society-management/get-sectors/') }}/" + dep_id,
            // data: JSON.stringify(obj1),
            dataType: "json",
            success: function (data) {
                var append_data = "";
                console.log(data);
                //alert(data1.d);
                var jsonData = data['sectors'];
                // console.log(data.hods[0].id);
                for (var i = 0; i < jsonData.length; i++) {
                    append_data += '<a href="#" title="View Detail" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">'+jsonData[i].sector_name[0] +'</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> '+jsonData[i].sector_name +' </div> <div class="kt-notification-v2__item-desc"> '+jsonData[i].sector_name +' </div></div></a>';
                }
                if(append_data == ''){
                    append_data = '<h6 class="text-danger"> No Sector Added Yet </h6>';
                }
                $("#dropdownHods").html(append_data);
            }
        });
    });
</script>

<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
    
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection