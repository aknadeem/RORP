@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">         
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Department')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="{{ route('departments.index') }}"><span class="kt-subheader__desc">{{ __('departments')}}</span></a>
                
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
            </div>

            <div class="kt-subheader__toolbar">
                <a href="{{URL::previous()}}" class="btn btn-default btn-bold">
                    Back </a>  &nbsp;
            </div>
        </div>
    </div>
    @php
        // filter department from departments array
        $search_society_id = request()->search_society_id;
        if($search_society_id !='all' AND $search_society_id !=''){
            $departments = $departments->where('society_id',$search_society_id);
        }else{
            $departments = $departments;
        }
    @endphp

    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <form action="" method="get">
            <div class="alert alert-light alert-elevate" role="alert">
                <div class="col-md-2"></div>
                <div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
                <div class="form-group validated col-sm-6 ">
                    <label class="form-control-label"><b></b></label>
                    <select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true" required>
                        <option selected disabled>  {{ __('Select Society')}}</option>
                        <option {{ ($search_society_id == 'all') ? 'selected' : '' }} value="all">  {{ __('All Departments')}}</option>
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
                <div class="kt-section__content kt-section__content--solid mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                </div>
            </div>
        </form>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Departments')}}
					</h3>

                    
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            @can('create-departments')
							<a href="{{ route('departments.create') }}" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create Department"><i class="fa fa-plus mb-1"></i>{{ __('Create')}}</a>
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
                            <th>{{ __('ID')}} {{$search_society_id}}</th>
                            <th>{{ __('Society')}}</th>
                            <th>{{ __('Department Name')}} </th>
                            <th>{{ __('Head Of Department(HOD)')}} </th>
                            <th>{{ __('Sub Departments')}} </th>
                            <th>{{ __('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $dep)
                            <tr>
                                <td>{{$dep->id}}</td>
                                <td>{{ __($dep->society->name) }}</td>
                                <td>{{ __($dep->name) }}</td>
                                <td>

                                    @if (!$dep->hod)
                                    @can('create-departments')
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addHodModal" title="Add Hod" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-plus mb-1"></i>Add</button>
                                    @endcan
                                    @else
                                        <button type="button" class="btn btn-bold btn-label-brand btn-sm viewHodModal" title="View Hod's" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-eye mb-1"></i>view </button>
                                    @endif
                                </td>
                                <td>
                                    @can('create-society-management')
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addSubdepartmentModal" title="Add Subdepartment" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-plus mb-1"></i>Add</button>
                                    @endcan

                                    @can('view-society-management')
                                        @if ($dep->subdepartments->count() > 0)
                                            <button type="button" class="btn btn-bold btn-label-brand btn-sm viewSubdepartments" title="View Subdepartments" dep_title="{{ __($dep->name) }}" dep_id="{{$dep->id}}"><i class="fa fa-eye mb-1"></i>view </button>
                                        @endif
                                    @endcan
                                </td>
                                <td>
                                    @can('update-departments')
                                    <a href="{{route('departments.edit', $dep->id)}}" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit department"></i> </a> &nbsp;
                                    @endcan
                                    @can('delete-departments')
                                    <!--<a href="{{route('departments.destroy', $dep->id)}}" class="text-danger delete-confirm" del_title="Department {{$dep->name}}"><i class="fa fa-trash-alt fa-lg" title="{{ __('Delete department') }}"></i></a>-->
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
                    {{ __('Add HOD')}} <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('department.addhod') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                    	<input type="hidden" name="department_id" id="dep_id">
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Department')}} </b></label>
                                <input type="text" class="form-control" name="department" readonly id="dep_name" disabled>
                                @error('hod_id	')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select HOD*')}} </b></label>
                                <select class="form-control kt-select2 @error('module_id') is-invalid @enderror" id="kt_select2" name="hod_id" style="width:100%;">
                                    <option selected disabled> <b> {{ __('Select HOD')}} </b> </option>
                                    @forelse($hods as $user)
                                    	<option value="{{$user->id}}">{{ $user->name }}</option>    
                                    @empty
                                        <option disabled> No HOD Found </option>
                                    @endforelse
                                </select>
                                @error('hod_id	')
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
                        <span id="DepTitleHod"></span> HOD'S </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_widget4_tab1_content">
                                        <div class="kt-widget4" id="HodNewDesign">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   {{--  <div class="row">
                        <div class="kt-notification-v2 col-md-12" id="dropdownHods">
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                    {{-- <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSubdepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    {{ __('Add Subdepartment')}} <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="{{ route('subdepartments.store') }}"  method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- <input type="hidden" name="department_id" id="dep_id"> --}}

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('Select Department*')}} </b></label>
                                <select class="form-control kt-selectpicker @error('department_id') is-invalid @enderror" id="kt_select2_1" data-live-search="true" required name="department_id">
                                    <option selected disabled> <b> {{ __('Select Department')}} </b> </option>
                                    @forelse($departments as $department)
                                        <option value="{{$department->id}}">{{ $department->name }}</option>    
                                    @empty
                                        <option disabled> No department Found </option>
                                    @endforelse
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b>{{ __('SubDepartment Name*')}} </b></label>
                                <input type="text" placeholder="{{ __('Enter Sub Department Name') }}" class="form-control" name="name" required>
                                @error('name')
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
    <div class="modal fade" id="SubdepartmentData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="MainDepTitleHod"></span> Sub Departments </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <input type="hidden" name="department_id" id="dep_id"> --}}
                    <div class="row">
                        <div class="kt-notification-v2 col-md-12" id="dropdownSubDepartments">
                            <a href="/userprofile" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> t</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> test title </div> <div class="kt-notification-v2__item-desc"> level title</div></div></a>
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
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>

<script src="{{ asset('js/ssm_datatable.js?v=1') }}" type="text/javascript"></script>

<script>
	$(".addHodModal").click(function(event) {
        var dep_title = $(this).attr("dep_title");
        var dep_id = $(this).attr("dep_id");
        $('#kt_modal_1').modal('show');
        $('#dep_name').val(dep_title);
        $('#dep_id').val(dep_id);
    });

    $(".addSubdepartmentModal").click(function(event) {
        var dep_title = $(this).attr("dep_title");
        var dep_id = $(this).attr("dep_id");
        $('#addSubdepartmentModal').modal('show');
        // $('#dep_name').val(dep_title);
        // $('#dep_id').val(dep_id);
    });

    $(".viewHodModal").click(function(event) {
    var vdep_title = $(this).attr("dep_title");
    var dep_id = $(this).attr("dep_id");
    // alert
    $('#kt_modal_loadData').modal('show');
    $('#MainDepTitleHod ').html(vdep_title);
    // $('#dep_id').val(dep_id);
        $.ajax({
            type: "GET",
            contentType: "application/json",
            url: "{{ url('department/get-hods/') }}/"+dep_id,
            // data: JSON.stringify(obj1),
            dataType: "json",
            success: function (data) {
                var append_data = "";
                var new_data = "";
                console.log(data);
                //alert(data1.d);
                var jsonData = data['hods'];
                // console.log(jsonData);
                for (var i = 0; i < jsonData.length; i++) {
                    append_data +='<div class="kt-widget4__item">'+  
                        '<div class="kt-widget4__info">'+
                            '<a class="kt-widget4__username">'+
                                jsonData[i].hod.name +
                            '</a>'+
                            '<p class="kt-widget4__text">'+
                                jsonData[i].hod.userlevel.title+
                            '</p>'+
                        '</div>'+
                        '<a href="/user-management/hod/deattach-department/'+jsonData[i].department_id+'/user/'+jsonData[i].hod_id+'" title="Remove Hod From This Department" class="btn btn-bold btn-label-danger btn-sm"><i class="fa fa-trash"></i> Remove </a>'+
                    '</div>';
                }
                if(append_data == ''){
                    append_data = '<h6 class="text-danger"> No HOD Found </h6>';
                }
                // $("#dropdownHods").append(append_data);
                $("#HodNewDesign").html(append_data);
            }
        });
   });

    $(".viewSubdepartments").click(function(event) {
        // alert('hello');
    var vdep_title = $(this).attr("dep_title");
    var dep_id = parseInt($(this).attr("dep_id"));
    // alert
    $('#SubdepartmentData').modal('show');
    $('#DepTitleHod ').html(vdep_title);
    // $('#dep_id').val(dep_id);
    // alert(dep_id);
    var departments = <?php echo json_encode($departments); ?>;
    var single_department = departments.find(d => d.id === dep_id);
    var append_data = "";
    var subdeparments = single_department.subdepartments;
    // console.log(single_department);
        if(subdeparments.length > 0){
            for (var i = 0; i < subdeparments.length; i++) {

                append_data += '<a href="/userprofile" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">'+subdeparments[i].name[0] +'</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> '+subdeparments[i].name +' </div> <div class="kt-notification-v2__item-desc"> '+subdeparments[i].slug +' </div></div></a>';
            }
            
            if(append_data == ''){
                append_data = '<h6 class="text-danger"> No Subdeparments found </h6>';
            }
        }
        $("#dropdownSubDepartments").html(append_data);
    });
</script>
    <script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
@endsection