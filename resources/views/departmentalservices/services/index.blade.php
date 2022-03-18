@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <a href="{{ route('departments.index') }}" class="kt-subheader__title">DepartmentalServices</a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('depart_services.index') }}"><span class="kt-subheader__title">Services</span></a>
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
                        Departmental Services
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">

                            <a href="#" class="btn btn-brand btn-elevate btn-sm btn-icon-sm addQuickComplint"
                                title="Click to add New Service"><i class="fa fa-plus mb-1"></i>Create</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin: Datatable -->
                <table class="table table-striped table-hover table-checkable" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Subdepartment </th>
                            <th>Title</th>
                            <th>Charges</th>
                            <th>Charges Type</th>
                            <th>Detail </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $key=>$item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td class="font-weight-lighter">{{$item->department->name}}</td>
                            <td>{{$item->subdepartment->name}}</td>
                            <td class="text-wrap">{{Str::limit($item->service_title,50, '...')}}</td>
                            <td class="font-weight-bold h5">{{$item->service_charges}}</td>
                            <td class="text-muted"> {{
                                \App\Helpers\Constant::CHARGES_TYPE_VAL[$item->charges_type] }} </td>
                            <td> <small> {!! Str::limit($item->description,30, '...') !!} </small> </td>
                            <td>
                                <a href="#" class="text-brand viewQuickComplaint" quick_data="{{ json_encode($item) }}">
                                    <i class="fa fa-eye fa-lg" title="Click to view detail"></i> </a>
                                &nbsp;&nbsp;

                                <a href="#" class="text-warning addQuickComplint" Service_id="{{ $item->id }}"> <i
                                        class="fa fa-edit fa-lg" title="Click to Edit"></i>
                                </a> &nbsp;

                                <a href="{{route('depart_services.destroy', $item->id)}}"
                                    class="text-danger delete-confirm" del_title="{{$item->service_title}}"><i
                                        class="fa fa-trash-alt fa-lg" title="Click to Delete"></i></a>

                            </td>
                        </tr>
                        @empty
                        @endforelse

                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- begin:: End Content  -->
</div>
@include('departmentalservices.services._AddServiceModal')
@endsection

@section('modal-popup')
<div class="modal fade" id="quickComplaintView" tabindex="-1" role="dialog" aria-labelledby="quickComplaintView"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="TitleSuperviosr"></span> Quick Complaint Detail
                </h5> <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="height: 300px;">
                <div class="row">
                    <div class="form-group validated col-sm-12">
                        <label class="form-control-label"> <b>{{ __('Subdepartment')}} </b></label>
                        <h6 class="text-dark" id="QuickDepartmentV"> Subdepartment Name </h6>
                    </div>
                    <div class="form-group validated col-sm-6">
                        <label class="form-control-label"> <b>{{ __('AddedBy')}} </b></label>
                        <h6 class="text-success" id="QuickClientV"> Client Name </h6>
                    </div>
                    <div class="form-group validated col-sm-6">
                        <label class="form-control-label"> <b>{{ __('Date')}} </b></label>
                        <h6 class="text-dark text-bold" id="QuickDateV"> date </h6>
                    </div>
                    <div class="form-group validated col-sm-12">
                        <label class="form-control-label"> <b>{{ __('Quick Complaint Title')}} </b></label>
                        <h5 class="text-dark" id="QuickTitleV"> Quick Complaint Title </h5>
                    </div>
                    <div class="form-group validated col-sm-6">
                        <label class="form-control-label"> <b>{{ __('Detail')}} </b></label>
                        <p class="text-dark" id="QuickDetailV"> Detail </p>
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
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet"
    type="text/css" />
@endsection

@section('scripts')
<script>
    $(".viewQuickComplaint").click(function(event) {
        var quick_data = JSON.parse($(this).attr("quick_data") || '')
        console.log(quick_data)
        $('#quickComplaintView').modal('show')
        $('#QuickDepartmentV').html(quick_data?.subdepartment?.name)
        $('#QuickClientV').html(quick_data?.addedby?.name + ' <br> ['+ quick_data?.addedby?.level_slug +']')
        $('#QuickDateV').html(quick_data?.created_at_format)
        $('#QuickTitleV').html(quick_data?.title)
        $('#QuickDetailV').html(quick_data?.detail)
    });    
</script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection