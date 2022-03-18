@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <a href="{{ route('departments.index') }}" class="kt-subheader__title">{{ __('Departments')}}</a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('subdepartments.index') }}"><span
                        class="kt-subheader__title">{{ __('sub departments')}}</span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <h5><span class="kt-subheader__desc">{{ __('Quick Complaints')}}</span></h5>
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
                        {{ __('Quick Complaints')}}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            @can('add-quick-complaint-departments')
                            <a href="#" class="btn btn-brand btn-elevate btn-sm btn-icon-sm addQuickComplint"
                                title="Click to add Quick Complaint"><i
                                    class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin: Datatable -->
                <table class="table table-striped table-hover table-checkable" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>{{ __('#')}}</th>
                            <th>{{ __('Subdepartment')}} </th>
                            <th>{{ __('Title')}} </th>
                            <th>{{ __('Detail')}} </th>
                            <th>{{ __('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quick_complaints as $key=>$complaint)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$complaint->subdepartment->name}}</td>
                            <td>{{Str::limit($complaint->title,10, '...')}}</td>
                            <td>{{Str::limit($complaint->detail,10, '...')}}</td>
                            <td>
                                <a href="#" class="text-brand viewQuickComplaint"
                                    quick_data="{{ json_encode($complaint) }}"> <i class="fa fa-eye fa-lg"
                                        title="Click to view detail"></i> </a>
                                &nbsp;&nbsp;

                                @can('add-quick-complaint-departments')
                                <a href="#" class="text-warning addQuickComplint" quick_id="{{ $complaint->id }}"
                                    quick_title="{{ $complaint->title }}" quick_detail="{{ $complaint->detail }}"
                                    quick_sub_id="{{ $complaint->sub_department_id }}"> <i class="fa fa-edit fa-lg"
                                        title="Click to Edit"></i>
                                </a> &nbsp;
                                @endcan
                                @can('delete-quick-complaints-departments')
                                <a href="{{route('qkcomplaints.destroy', $complaint->id)}}"
                                    class="text-danger delete-confirm"
                                    del_title="Quick Complaint {{$complaint->title}}"><i class="fa fa-trash-alt fa-lg"
                                        title="Click to Delete"></i></a>
                                @endcan
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
@endsection

@section('modal-popup')
<div class="modal fade" id="addQucikComplaintModal" tabindex="-1" role="dialog" aria-labelledby="addQucikComplaintModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add Quick Complaint <span id="DepTitle"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form class="kt-form loader" method="POST" id="addQuickComplaintForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="quick_complaint_id" id="quick_complaint_id">
                    <div class="row">
                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label"> <b> Select Subdepartment* </b></label>
                            <select class="form-control kt-selectpicker" name="sub_department_id" id="sub_department_id"
                                style="width:100%;" data-live-search="true" required>
                                <option selected disabled value=""> <b> {{ __('Select Subdepartment')}} </b> </option>
                                @forelse ($subdepartments as $sub)
                                <option value="{{ $sub->id }}"> <b> {{ $sub->name }} </b> </option>
                                @empty
                                @endforelse
                            </select>
                            <div class="invalid-feedback" id="quick_subdepE"></div>
                        </div>
                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label"> <b>{{ __('Title*')}} </b></label>
                            <input type="text" class="form-control" name="quick_title" id="quick_title"
                                placeholder="Enter Complaint Title">
                            <div class="invalid-feedback" id="quick_titleE"></div>
                        </div>
                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label"> <b>{{ __('Detail')}} </b></label>
                            <textarea class="form-control" name="quick_detail" id="quick_detail" rows="3"
                                placeholder="Enter Complaint Detail"></textarea>
                            <div class="invalid-feedback" id="quick_detailE"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                    <button type="button" class="btn btn-secondary btn-sm"
                        data-dismiss="modal">{{ __('Close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="quickComplaintView" tabindex="-1" role="dialog" aria-labelledby="quickComplaintView"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="TitleSuperviosr"></span> Quick Complaint Detail </h5> <br>
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

    $(".addQuickComplint").click(function(event) {
            var quick_id = parseInt($(this).attr("quick_id") || 0)
            $('#addQucikComplaintModal').modal('show')
            $('#quick_complaint_id').val(quick_id)

            if(quick_id > 0){
                var quick_title = $(this).attr("quick_title") || ''
                var quick_sub_id = parseInt($(this).attr("quick_sub_id") || 0)
                var quick_detail = $(this).attr("quick_detail") || ''
                $("#sub_department_id").val(quick_sub_id).change()
                $("#quick_title").val(quick_title);
                $("#quick_detail").val(quick_detail);
            }
            $('#addQucikComplaintModal').on('submit',function(event){
            event.preventDefault();
            // var files = $('#attachments')[0].files;
            var quick_complaint_id = parseInt($("#quick_complaint_id").val());
            var sub_department_id = $("#sub_department_id").val();
            var quick_title = $("#quick_title").val();
            var quick_detail = $("#quick_detail").val();
            
            let form_url = "{{ url('quick-complaints/qkcomplaints') }}"
            let form_type = "POST"
            if(quick_complaint_id > 0){
                form_url = "{{ url('quick-complaints/qkcomplaints') }}/"+quick_complaint_id
                form_type = "PUT"
            }
            var quickComplaintForm = $(this).serialize(); 
            $.ajax({
                url: form_url,
                type:form_type,
                data: {'sub_department_id':sub_department_id,
                'quick_title':quick_title,'quick_detail':quick_detail,
                _token: '{{csrf_token()}}'} ,
                success:function(response){
                    console.log(response);
                    if(response?.success == 'no'){
                        $('#quick_subdepE').html(response?.error?.subdepartment_id)
                        $('#quick_titleE').html(response?.error?.quick_title)
                        $('#quick_detailE').html(response?.error?.quick_detail)
                    }else{
                        // $('#StatusError').html('')
                        location.reload();
                        // swal({
                        //     title: "Success",
                        //     text: response.message,
                        //     type: "success",
                        //     confirmButtonText: "Ok",
                        //     closeOnConfirm: true
                        // }, function () {
                        //     $('#addQucikComplaintModal').modal('hide');
                        //     location.reload();
                        // });
                    }
                },
                error:function(e){
                    console.log(e);
                },
            });
        });

        });

        // add assistent Manager for subdepartment
        $(".addAssModal").click(function(event) {   
            var dep_title = $(this).attr("dep_title");
            var dep_id = $(this).attr("dep_id");
            $('#kt_modalManager').modal('show');
            // $('#dep_name').val(dep_title);
            // $('#subdepartment_id').val(dep_id);
            var service_html = '';
            var selected = '';
            var subdepartments = '';
            // alert(dep_id);
            if(subdepartments.length > 0){
                service_html='<option> Select Service </option>';
                for (var i = 0; i < subdepartments.length; i++) {
                    if(subdepartments[i].id == dep_id){
                        selected = 'selected';
                    }
                    service_html+='<option '+selected+' value='+subdepartments[i].id+'>'+subdepartments[i].name+ '</option>';

                    selected='';
                }
            }else{
                service_html='<option> No Sub Type Found </option>';
            }
            $('#LoadSubDepartment').html(service_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });

        $(".viewHodModal").click(function(event) {
            var vdep_title = $(this).attr("dep_title");
            var dep_id = $(this).attr("dep_id");
            // alert(dep_id);
            $('#kt_modal_loadData').modal('show');
            $('#TitleSuperviosr ').html(vdep_title);
            // $('#subdepartment_id').val(dep_id);
            $('#supervisorsloader').show();
            $.ajax({
                type: "GET",
                contentType: "application/json",
                url:"{{ url('/department/sub/get-supervisors/') }}/"+dep_id,
                // data: JSON.stringify(obj1),
                dataType: "json",
                success: function (data) {
                    var append_data = "";
                    var jsonData = data['supervisors'];
                    // console.log(jsonData);
                    for (var i = 0; i < jsonData.length; i++) {

                    append_data +='<div class="kt-widget4__item">'+  
                        '<div class="kt-widget4__info">'+
                            '<a href="/user-management/users/'+jsonData[i].supervisor.id+'" title="View Profile" class="kt-widget4__username">'+
                                jsonData[i].supervisor.name +
                            '</a>'+
                            '<p class="kt-widget4__text">'+
                                jsonData[i].supervisor.userlevel.title+
                            '</p>'+
                        '</div>'+
                        '<a href="/user-management/deattach-supervisor/'+jsonData[i].sub_department_id+'/user/'+jsonData[i].supervisor_id+'" title="Remove Supervisor From This SubDepartment" class="btn btn-bold btn-label-danger btn-sm"><i class="fa fa-trash"></i> Remove </a>'+
                    '</div>';

                    }
                    if(append_data == ''){
                        append_data = '<h6 class="text-danger"> No Supervisor Found </h6>';
                    }
                    $("#SubdepartmentSupervisors").html(append_data);
                },
                    complete: function(){
                    $('#supervisorsloader').hide();
                }
            });
        });
</script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection