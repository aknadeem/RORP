@extends('layouts.base')
@section('content')
    @php
        // implement Search Filter From Subdepartments Collection
        $search_society_id = request()->search_society;
        $search_department_id = request()->search_department;
        if($search_society_id > 0 AND $search_department_id > 0){
            $subdepartments = $subdepartments->where('society_id', $search_society_id)->where('department_id',$search_department_id);
        }else if($search_society_id !='all' AND $search_society_id !=''){
            $subdepartments = $subdepartments->where('society_id', $search_society_id);
        }else if($search_department_id !='all' AND $search_department_id !=''){
            $subdepartments = $subdepartments->where('department_id',$search_department_id);
        }else{
           $subdepartments = $subdepartments; 
        }
    @endphp
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">     
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Department')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('subdepartments.index') }}"><span class="kt-subheader__desc">{{ __('sub departments')}}</span></a>
                
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

       <!-- Start Search pannel -->
        <form action="" method="get">
            <div class="alert alert-light alert-elevate" role="alert">
                <div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
                <div class="form-group validated col-sm-5 ">
                    <label class="form-control-label"><b></b></label>
                    <select class="form-control kt-selectpicker" name="search_society" data-live-search="true" required>
                        <option selected disabled>  {{ __('Select Society')}}</option>
                        <option {{ ($search_society_id == 'all') ? 'selected' : '' }} value="all">  {{ __('All')}}</option>

                        @forelse($societies as $soc)
                            <option {{ ($search_society_id == $soc->id) ? 'selected' : '' }} value="{{$soc->id}}">{{ $soc->name }}</option>  
                        @empty
                            <option disabled> No Society Found </option>
                        @endforelse
                    </select>

                    @error('search_society_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group validated col-sm-5 ">
                    <label class="form-control-label"><b></b></label>
                    <select class="form-control kt-selectpicker" name="search_department" data-live-search="true" required>
                        <option selected disabled>  {{ __('Select Department')}}</option>
                        <option {{ ($search_department_id == 'all') ? 'selected' : '' }} value="all">  {{ __('All')}}</option>

                        @forelse($departments as $soc_dep)
                            <option {{ ($search_department_id == $soc_dep->id) ? 'selected' : '' }} value="{{$soc_dep->id}}">{{ $soc_dep->name }}</option>  
                        @empty
                            <option disabled> No Society Found </option>
                        @endforelse
                    </select>

                    @error('search_department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-2 kt-section__content kt-section__content--solid mt-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <!-- End Search pannel -->

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Sub Departments')}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
						    @can('create-subdepartments')
							<a href="{{ route('subdepartments.create') }}" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create Sub Department"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
							<th>{{ __('Name')}} </th>
							<th>{{ __('Department')}} </th>
							<th>{{ __('Assistant Manager')}} </th>
                            <th>{{ __('Supervisors')}} </th>
							<th>{{ __('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($subdepartments as $dep)
							<tr>
								<td>{{$dep->id}}</td>
								<td>{{ __($dep->name) }}</td>
								<td>{{ __($dep->department->name)}} </td>
                                <td>
                                    @if ($dep->asstmanager)
                                        {{ __($dep->asstmanager->user->name ?? '')}}
                                    @else
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addAssModal" title="Add Assistent Manager" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-plus mb-1"></i>Add</button>

                                    @endif

                                    </td>
								<td>
                                    <button type="button" class="btn btn-bold btn-label-success btn-sm addHodModal" title="Add Supervisor" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-plus mb-1"></i>Add</button>
                                    <button type="button" class="btn btn-bold btn-label-brand btn-sm viewHodModal" title="View Supervisors" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-eye mb-1"></i>view </button>
								</td>
								<td>
									<a href="{{route('subdepartments.edit', $dep->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit subdepartment"></i> </a> &nbsp;
                                    @can('delete-subdepartments')
									<a href="{{route('subdepartments.destroy', $dep->id)}}" class="text-danger delete-confirm" del_title="SubDepartment {{$dep->name}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete SubDepartment') }}"></i></a>
									@endcan
								</td>
							</tr>
						@endforeach
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
                    {{ __('Add Supervisors')}} <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('sub.addsupervisor') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                    	<input type="hidden" name="subdepartment_id" id="subdepartment_id">
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Sub Department')}} </b></label>
                                <input type="text" class="form-control" name="subdepartment" readonly id="dep_name" disabled>
                                @error('subdepartment_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select Supervisors*')}} </b></label>
                                <select class="form-control kt-selectpicker @error('supervisor_id') is-invalid @enderror" data-live-search="true" name="supervisor_id[]" required multiple>
                                    <option selected disabled value=""> <b> {{ __('Select Supervisor')}} </b> </option>
                                    @forelse($supervisors as $user)
                                    	<option value="{{$user->id}}">{{ $user->name }}</option>    
                                    @empty
                                        <option disabled value=""> No Supervisor found Found </option>
                                    @endforelse
                                </select>
                                @error('supervisor_id')
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


    <div class="modal fade" id="kt_modalManager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    {{ __('Add Assistent Manager')}} <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('sub.addmanager') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                    
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select Subdepartment*')}} </b></label>
                                <select class="form-control kt-selectpicker @error('subdepartment_id') is-invalid @enderror" data-live-search="true" id="LoadSubDepartment" name="subdepartment_id"  required>
                                    <option selected disabled value=""> <b> {{ __('Select Subdepartment')}} </b> </option>
                                    {{-- load with javascrpt --}}
                                </select>
                                @error('subdepartment_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select Assistant Manager*')}} </b></label>
                                <select class="form-control kt-selectpicker @error('manager_id') is-invalid @enderror" data-live-search="true" required  name="manager_id" >
                                    <option selected disabled value=""> <b> {{ __('Select Manager')}} </b> </option>
                                    @forelse($managers as $mgr)
                                        <option value="{{$mgr->id}}">{{ $mgr->name }}</option>    
                                    @empty
                                        <option disabled value=""> No Assistant Manager Found </option>
                                    @endforelse
                                </select>
                                @error('supervisor_id')
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
                        <span id="TitleSuperviosr"></span> Supervisors </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="department_id" id="dep_id">
                    <div class="row">
                        <div class="kt-notification-v2 col-md-12" id="SupervisorsList">
                            <h6 class="mt-4 text-center text-danger" id="supervisorsloader" style="display: none;"> Loading......</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_widget4_tab1_content">
                                        <div class="kt-widget4" id="SubdepartmentSupervisors">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1.0.1') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
	<script>
    	$(".addHodModal").click(function(event) {
            var dep_title = $(this).attr("dep_title");
            var dep_id = $(this).attr("dep_id");
            $('#kt_modal_1').modal('show');
            $('#dep_name').val(dep_title);
            $('#subdepartment_id').val(dep_id);
        });

        // add assistent Manager for subdepartment
        $(".addAssModal").click(function(event) {   
            var dep_title = $(this).attr("dep_title");
            var dep_id = $(this).attr("dep_id");
            $('#kt_modalManager').modal('show');
            var service_html = '';
            var selected = '';
            var subdepartments = <?php echo json_encode($subdepartments); ?>;
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
                url: "{{ url('department/sub/get-supervisors/') }}/"+dep_id,
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
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1.0.1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ssm_datatable.js?v1.0.1') }}" type="text/javascript"></script>
@endsection