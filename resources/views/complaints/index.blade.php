@extends('layouts.base')
@section('content')


<style>
    .dataTables_filter {
        margin-top: -56px !important;
    }

    .dataTables_wrapper .dataTables_processing {
        background: #1c5b90;
        border: 1px solid #1c5b90;
        border-radius: 3px;
        color: #fff;
    }
</style>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Complaints')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('complaints.index') }}"><span class="kt-subheader__desc">{{
                        __('Societies')}}</span></a>
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
                        {{ __('Complaints')}}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('complaints.create') }}"
                                class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create Complaint"><i
                                    class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <table id="ComplaintYajraTable" class="table table-striped table-hover table-checkable kt_table_11">
                    <thead>
                        <tr>
                            <td class="form-group" colspan="4">
                                <label class="form-control-label">Search <b>Society</b></label>
                                <select class="form-control filter-select" data-column="2">
                                    <option selected disabled value=""> Select Society </option>
                                    <option value="all"> All </option>
                                    @foreach ($societies as $society)
                                    <option value="{{$society->id}}"> {{$society->name}} [{{$society->code}}]
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Department</b></label>
                                <select data-column="5" class="form-control filter-select">
                                    <option selected disabled> Select Department </option>
                                    <option value="all"> All
                                    </option>
                                    @foreach ($departments as $cp_department)
                                    <option value="{{$cp_department->name}}"> {{$cp_department->name}} </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Subdepartment</b></label>
                                <select class="form-control filter-select" data-column="6">
                                    <option selected disabled> Select SubDepartment </option>
                                    <option value="all"> All </option>
                                    @foreach ($departments as $department)
                                    @foreach ($department->subdepartments as $sub)
                                    <option value="{{$sub->name}}"> {{$sub->name}} </option>
                                    @endforeach
                                    @endforeach
                                </select>
                            </td>
                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Status</b></label>
                                <select class="form-control filter-select" data-column="7" name="search_status">
                                    <option selected disabled> Select Status </option>
                                    <option value="all"> All </option>
                                    <option value="open"> Open </option>
                                    <option value="closed"> Closed </option>
                                    <option value="completed"> Completed </option>
                                    <option value="in_process"> InProcess </option>
                                    <option value="in_correct"> InCorrect </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th> Society </th>
                            <th>Complaint Title </th>
                            <th>Added By </th>
                            <th>Department </th>
                            <th>Sub Department </th>
                            <th>Date </th>
                            <th>Status </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- begin:: End Content  -->
</div>

<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('complaintStatusChange') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input class="form-control" name="complaint_id" type="hidden" id="cid" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" value="closed" name="working_status" required />
                                    Close
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigne" name="working_status"
                                        required />Re-Assign
                                    <span></span>
                                </label>
                                @php
                                $user_level_id = Auth::user()->user_level_id;
                                @endphp

                                @if ($user_level_id < 5) <label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="change_deparment" id="change_deparment"
                                        name="working_status" required /> Forward
                                    <span></span>
                                    </label>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="row re_assign_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Assign To:*</label>
                            <select
                                class="form-control refer_dep_row_input kt-selectpicker @error('user_id') is-invalid @enderror"
                                name="user_id" data-live-search="true">

                                <option selected disabled>Select Supervisor</option>
                                @foreach ($subdep_sups as $subdep_sup)
                                <option value="{{ $subdep_sup->supervisor_id }}">{{ $subdep_sup->supervisor->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row refer_dep_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Forward To:*</label>
                            <select
                                class="form-control re_assign_row_input kt-selectpicker @error('department_id') is-invalid @enderror"
                                name="department_id" data-live-search="true">

                                <option selected disabled>Select Department</option>
                                @forelse ($forward_departments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name}}</option>
                                @empty
                                <option disabled> No Department Found </option>
                                @endforelse
                            </select>
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

<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Working</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('sup_complaint_update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input class="form-control" name="complaint_id" type="hidden" id="complaint_id" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="in_process" id="in_process" name="working_status"
                                        required />
                                    In Process
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
                                    <input type="radio" id="completed" value="completed" name="working_status"
                                        required />Completed
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" id="incorrect" value="incorrect" name="working_status"
                                        required />InCorrect
                                    <span></span>
                                </label>
                                @if(Auth::user()->user_level_id < 5) <label
                                    class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigneOpen" name="working_status"
                                        required />Re-Assign
                                    <span></span>
                                    </label>
                                    @endif

                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->user_level_id < 5) <div class="row re_assign_rowOpen" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Assign To:*</label>
                            <select
                                class="form-control re_assign_row_inputOpen kt-selectpicker @error('user_id') is-invalid @enderror"
                                name="user_id" data-live-search="true">

                                <option selected disabled value="">Select Supervisor</option>
                                @foreach ($subdep_sups as $subdep_sup)
                                <option value="{{ $subdep_sup->supervisor_id }}">{{ $subdep_sup->supervisor->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                </div>
                @endif

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
    $(document).ready(function () {
        var DTable =  $('#ComplaintYajraTable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            // "searching": false,
            // "bRetrieve": 'true',
            ajax: "{{ route('getComplaintsWithRefresh')}}",
            // "start": "0",
            // "length": "10",
            "pageLength":10,
            // dom: 'Bfrtip', // not showing length menu
            dom: 'Blfrtip', // with length menu
            "lengthMenu":[[10,30,50,-1],[10,30,50,"all"]],
            columns:[
                // {data:'id', name:'id'}
                {data:'DT_RowIndex'},
                {data:'code'},
                {data:'society_id'},
                {data:'complaint_title'},
                {data:'addedby'},
                {data:'department_id'},
                {data:'sub_department_id'},
                {data:'created_at'},
                {data:'complaint_status'},
                {data:'Actions'},
            ],
            buttons: [
                'copy',
                {
                    extend: 'excel',
                    title: 'Complaints'
                },
                {
                    extend: 'pdf',
                    title: 'Complaints'
                },
                {
                    extend: 'print',
                    title: 'Complaints'
                }
            ],
            columnDefs: [ {
                targets: [1,7],
                className: 'bolded'
                }
            ],
        });
        
        setInterval(function(){
        // this will run after every 5 Minutes
            $('#ComplaintYajraTable').DataTable().ajax.reload()
        }, 300000);

        $(".filter-select").change(function () {
            if($(this).val() == 'all'){
                DTable.column($(this).data('column')).search('').draw();
            }else{
                DTable.column($(this).data('column')).search($(this).val()).draw();
            }
        });
        
        let columnSociety = DTable.column(1);
        columnSociety.visible(false);
    
        $("#todoWorking").on("show.bs.modal", function (e) {
            var complaint_id = $(e.relatedTarget).data("target-id");
            var complaint_status = $(e.relatedTarget).attr("complaint_status");
            $("#complaint_id").val(complaint_id);
        });
    });

     $("input[name='working_status']").click(function () {
         
        //  re-assigneOpen
        if ($('#re-assigneOpen').is(':checked')) {
            $('.re_assign_rowOpen').show();
            $('.re_assign_row_inputOpen').prop('required', true);
            
        }else if($('#in_process').is(':checked')){
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            
        }else if($('#completed').is(':checked')) {
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            
        }else{
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);  
        }
         
        if ($('#re-assigne').is(':checked')) {
            $('.re_assign_row').show();
            $('.re_assign_row_input').prop('required', true);

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', false);

        }else if($('#change_deparment').is(':checked')){
            $('.refer_dep_row').show();
            $('.refer_dep_row_input').prop('required', true);

            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
        }
        else {
            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
            $('.re_assign_row_input').val('');
            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', true);
            $('.refer_dep_row_input').val();
        }
    });

    $(document).ready(function () {
        $("#close-re-assign").on("show.bs.modal", function (e) {
            var cid = $(e.relatedTarget).data("target-id");
            $("#cid").val(cid);
        });
    });

</script>

<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('js/ssm_datatable.js?v=1.0') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection