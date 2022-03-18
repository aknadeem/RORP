@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <a href="{{ route('departments.index') }}" class="kt-subheader__title"> DepartmentalServices </a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('depart_services.index') }}"><span
                        class="kt-subheader__title">ServiceRequests</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span>
                    <span class="kt-subheader__title active">ReceivedRequests</span></span>
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
                        Received Requests
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">

                            <a href="#" class="btn btn-brand btn-elevate btn-sm btn-icon-sm addQuickComplint"
                                title="Click to add New Request"><i class="fa fa-plus mb-1"></i>Create Request</a>
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
                            <th>Status</th>
                            <th>RequestBy </th>
                            <th>Refer To </th>
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
                            <td class="text-wrap">{{Str::limit($item->service->service_title,50, '...')}}</td>



                            <td class="text-muted"> <span
                                    class="badge bg-{{ \App\Helpers\Constant::REQUEST_STATUS_COLOR[$item->request_status] }} text-white font-size-5">
                                    {{ $item->request_status_val }} </td>
                            <td class="text-muted"> {{$item->RequestBy->name ?? ''}} <br>
                                {{$item->RequestBy->name ?? ''}}
                            </td>

                            <td class="text-muted"> {{$item->RequestBy->name ?? ''}} <br>
                                {{$item->RequestBy->name ?? ''}}
                            </td>

                            <td> <small> {!! Str::limit($item->description,30, '...') !!} </small> </td>
                            <td>
                                <a href="{{ route('request_depart_service.show', $item->id) }}" class="text-brand">
                                    <i class="fa fa-eye fa-lg" title="Click to view detail"></i> </a>
                                &nbsp;&nbsp;

                                {{-- <a href="#" class="text-warning addQuickComplint" Service_id="{{ $item->id }}"> <i
                                        class="fa fa-edit fa-lg" title="Click to Edit"></i>
                                </a> --}}

                                <a href="#" class="text-warning OpenUpdateStatusModal" Requestid="{{ $item->id }}"
                                    Serviceid="{{ $item?->service?->id }}" ServiceAddedBy="{{ $item?->addedby }}">
                                    <i class="fa fa-edit fa-lg " title="Click to Update Status"></i>
                                </a>

                                &nbsp;

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
@include('departmentalservices.servicerequests._AddServiceRequestModal')
@endsection

@section('modal-popup')

<div class="modal fade" id="UpdateRequestStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="UpdateRequestStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Request Status: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('request_depart_service_status') }}" method="POST">
                @csrf
                <input name="request_id" type="hidden" id="request_id" />
                <input name="service_id" type="hidden" id="service_id" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">

                                @if(Auth::user()->user_level_id <= 4) <label
                                    class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[2]}}">
                                    <input type="radio" value="{{ \App\Helpers\Constant::REQUEST_STATUS['InProcess']}}"
                                        id="InProcess" name="request_status" required />
                                    InProcess
                                    <span></span>
                                    </label>


                                    {{-- <label
                                        class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[3]}}">
                                        <input type="radio"
                                            value="{{ \App\Helpers\Constant::REQUEST_STATUS['InCorrect']}}"
                                            id="InCorrect" name="request_status" required />
                                        InCorrect
                                        <span></span>
                                    </label> --}}

                                    <label
                                        class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[4]}}">
                                        <input type="radio"
                                            value="{{ \App\Helpers\Constant::REQUEST_STATUS['Completed']}}"
                                            id="Completed" name="request_status" required />
                                        Completed
                                        <span></span>
                                    </label>
                                    @endif


                                    @if(Auth::user()->user_level_id <= 3) <label
                                        class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[5]}}">
                                        <input type="radio"
                                            value="{{ \App\Helpers\Constant::REQUEST_STATUS['Approved']}}" id="Approved"
                                            name="request_status" required />
                                        Approved
                                        <span></span>
                                        </label>
                                        @endif
                                        @if(Auth::user()->user_level_id <= 2) <label
                                            class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[6]}}">
                                            <input type="radio"
                                                value="{{ \App\Helpers\Constant::REQUEST_STATUS['Closed']}}" id="Closed"
                                                name="request_status" required />
                                            Closed
                                            <span></span>
                                            </label>
                                            @endif

                                            <span id="SatisFiedUnsatisfied" style="display:none;">
                                                <label
                                                    class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[7]}}">
                                                    <input type="radio"
                                                        value="{{ \App\Helpers\Constant::REQUEST_STATUS['Satisfied']}}"
                                                        id="Satisfied" name="request_status" required />
                                                    Satisfied
                                                    <span></span>
                                                </label>

                                                <label
                                                    class="kt-radio kt-radio--solid kt-radio--{{\App\Helpers\Constant::REQUEST_STATUS_COLOR[8]}}">
                                                    <input type="radio"
                                                        value="{{ \App\Helpers\Constant::REQUEST_STATUS['UnSatisfied']}}"
                                                        id="UnSatisfied" name="request_status" required />
                                                    UnSatisfied
                                                    <span></span>
                                                </label>
                                            </span>


                                            {{-- @if(Auth::user()->user_level_id < 5) <label
                                                class="kt-radio kt-radio--solid kt-radio--info">
                                                <input type="radio" value="re_assign" id="re-assigneOpen"
                                                    name="working_status" required />Re-Assign
                                                <span></span>
                                                </label>
                                                @endif --}}

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="message-text" class="form-control-label">Comments:*</label>
                            <textarea class="form-control" name="comments" required></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
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
    var LogUserid = "{{ Auth::user()->user_level_id }}" 
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
    $(".OpenUpdateStatusModal").click(function(event) {
        let ServiceAddedBy = parseInt($(this).attr('ServiceAddedBy'))
        let request_id = parseInt($(this).attr('Requestid'))
        let service_id = parseInt($(this).attr('Serviceid'))
        $('#UpdateRequestStatusModal').modal('show')

        if(LogUserid == ServiceAddedBy){
            $('#SatisFiedUnsatisfied').show()
        }else{
            $('#SatisFiedUnsatisfied').hide()
        }
        // 

        $('#request_id').val(request_id)
        $('#service_id').val(service_id)
    });    
</script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection