@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Complaints')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('complaints.index') }}"><span class="kt-subheader__desc">{{ __('Complaints')}}</span></a>
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
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
						{{ __('Complaint Refers')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            @can('create-complaint-management')
						        {{-- <a href="{{ route('complaints.create') }}" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Department"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a> --}}
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
							<th>{{ __('ID')}}</th>
							<th>{{ __('Title')}} </th>
							<th>{{ __('Added By')}} </th>
							<th>{{ __('Department')}} </th>
                            <th>{{ __('Status')}} </th>
							<th>{{ __('Action')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($complaint_refers as $key=>$refer)
						@if($refer->complaint)
    						@php
    							if ($refer->complaint->complaint_status == 'open'){
    								$btncolor = 'btn-danger';
    							}elseif ($refer->complaint->complaint_status == 'in_process') {
    								$btncolor = 'btn-warning';
    							}
    							elseif ($refer->complaint->complaint_status == 'completed') {
    								$btncolor = 'btn-brand';
    							}elseif ($refer->complaint->complaint_status == 'closed') {
    								$btncolor = 'btn-success';
    							}else{
    								$btncolor = 'btn-danger';
    							}
    						@endphp
							<tr>
                            <td>{{ ++$key }}</td>
                            <td> <a data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand" title="Click to view Detail" href="{{ route('complaints.show', $refer->complaint->id) }}"> {{ $refer->complaint->complaint_title }} </a> </td>
                            <td>{{ $refer->complaint->user->name }} <br> {{ $refer->complaint->user->userlevel->title }} </td>
                            <td>{{ $refer->complaint->department->name }} </td>
                            <td>
                            	<span class="btn {{ $btncolor }} btn-sm">{{ $refer->complaint->complaint_status }}</span>
                            </td>
                            <td>
                            	<div class="btn-group" role="group">
                                @if ($refer->complaint->complaint_status == 'in_process' OR $refer->complaint->complaint_status == 'open' OR $refer->complaint->complaint_status == 're_assign' )
                                    <span data-toggle="modal" data-target="#todoWorking" data-target-id="{{$refer->complaint->id}}" complaint_status="{{$refer->complaint->complaint_status}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                                        <i class="flaticon-edit"></i>
                                    </span>

                                @elseif($refer->complaint->complaint_status == 'incorrect' OR $refer->complaint->complaint_status == 'change_deparment' OR $refer->complaint->complaint_status == 'un_satisfied')
                                @if ($refer->referto->user_level_id < 5 )
                                    <span data-toggle="modal" data-target="#close-re-assign" data-target-id="{{$refer->complaint->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                                        <i class="flaticon-edit"></i>
                                    </span>
                                @endif
                                {{-- @elseif($refer->complaint->complaint_status == 'completed')
                                    <span data-toggle="modal" data-target="#feedback" data-target-id="{{$refer->complaint->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                                        <i class="flaticon-edit"></i>
                                    </span> --}}
                                @endif
                                @if ($refer->referto->user_level_id < 5)
                                        <a href="{{ route('complaints.destroy',$refer->complaint->id) }}" class="btn btn-label-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        <a href="{{ route('complaintedit',$refer->complaint->id) }}" class="btn btn-label-info btn-sm"><i class="fa fa-edit"></i></a>
                                    @endif
								</div>
                            </td>
                        </tr>
                        @endif
						@endforeach
					</tbody>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
    <!-- begin:: End Content  -->
</div>

<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Working</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('sup_complaint_update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input class="form-control" name="complaint_id" type="hidden" id="complaint_id" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                            	<label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="in_process" id="pending" name="working_status" required /> 
                                    In Process
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
                                    <input type="radio" id="completed" value="completed" name="working_status" required />Completed
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" id="completed_i" value="incorrect" name="working_status" required />InCorrect
                                    <span></span>
                                </label>
                                
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


<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('complaintfeedback') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input class="form-control" name="complaint_id" type="hidden" id="feed_cid" />
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="message-text" class="form-control-label">Feedback:*</label>

                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--success">
                                    <input type="radio" value="satisfied" name="feedback_status" required /> 
                                    Satisfied
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio"  value="no_comment" name="feedback_status" required />No Comments
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--danger">
                                    <input type="radio" value="un_satisfied" name="feedback_status" required />Un Satisfied
                                    <span></span>
                                </label>
                                
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

<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="{{ route('complaintStatusChange') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input class="form-control" name="complaint_id" type="hidden" id="cid" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" value="closed"  name="working_status" required /> 
                                    Close
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigne" name="working_status" required />Re-Assign
                                    <span></span>
                                    </label>
                                @php
                                    $user_level_id = Auth::user()->user_level_id;
                                @endphp

                                @if ($user_level_id == 4)
                                    <label class="kt-radio kt-radio--solid kt-radio--warning">
                                        <input type="radio" value="change_deparment" id="change_deparment" name="working_status" required /> Forward
                                        <span></span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row re_assign_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Assign To:*</label>
                            <select class="form-control refer_dep_row_input kt-selectpicker @error('user_id') is-invalid @enderror"  name="user_id" data-live-search="true">

                                <option selected disabled>Select Supervisor</option>
                                @foreach ($subdep_sups as $subdep_sup)
                                    <option value="{{ $subdep_sup->supervisor_id }}">{{ $subdep_sup->supervisor->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row refer_dep_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Forward To:*</label>
                            <select class="form-control re_assign_row_input kt-selectpicker @error('department_id') is-invalid @enderror"  name="department_id" data-live-search="true">

                                <option selected disabled>Select Department</option>
                                @forelse ($departments as $dep)
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
@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')

<script>
     $("input[name='working_status']").click(function () {
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
</script>
<script>
	 $(document).ready(function () {
        $("#todoWorking").on("show.bs.modal", function (e) {
            var complaint_id = $(e.relatedTarget).data("target-id");
            var complaint_status = $(e.relatedTarget).attr("complaint_status");
            $("#complaint_id").val(complaint_id);
        });
    });
      $(document).ready(function () {
        $("#close-re-assign").on("show.bs.modal", function (e) {
            var cid = $(e.relatedTarget).data("target-id");
            $("#cid").val(cid);
        });
    });
      $(document).ready(function () {
        $("#feedback").on("show.bs.modal", function (e) {
            var feed_cid = $(e.relatedTarget).data("target-id");
            $("#feed_cid").val(feed_cid);
        });
    });
</script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
@endsection