<div class="modal fade" id="TenantsList" tabindex="-1" role="dialog" aria-labelledby="TenantsList" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Resident Information </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
                        <!--begin:: Widgets/Notifications-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        {{$resident->name ?? ''}}
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#kt_widget6_tab1_content" role="tab">
                                                Tenant's
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab2_content" role="tab">
                                                Family's
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab3_content" role="tab">
                                                Servent's
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab4_content" role="tab">
                                                Handy Men's
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab5_content" role="tab">
                                                Vehicle's
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_widget6_tab1_content" aria-expanded="true">
                                        <table class="table table-striped table-hover kt_table_2">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> Name </th>
                                                    <th> Father Name </th>
                                                    <th> Email </th>
                                                    <th> CNIC </th>
                                                    <th> Marital Status</th>
                                                    <th> Otp Status </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- {{print_r($resident)}} --}}
                                                @forelse ($resident->tenants as $key=>$tenant)
                                                    <tr>
                                                        <td> {{++$key}} </td>
                                                        <td> {{$tenant->name}} </td>
                                                        <td> {{$tenant->father_name}} </td>
                                                        <td> {{$tenant->email}} </td>
                                                        <td>{{$tenant->cnic}}</td>
                                                        <td>{{$tenant->martial_status}}</td>
                                                        <td>
                                                            @if ($tenant->e_pin > 0 && $tenant->m_pin > 0)
                                                                @if ($tenant->pin_verified == 1)
                                                                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i class="fa fa-check" ></i> Pin Verified </a> 
                                                                @else
                                                                    <b> Pin is Not Verified </b>
                                                                @endif
                                                            @else
                                                            <b> Pin Can't Send </b>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="kt_widget6_tab2_content" aria-expanded="false">
                                        <div class="kt-notification">
                                            <table class="table table-striped table-hover kt_table_2">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Name </th>
                                                        <th> CNIC</th>
                                                        <th> Email </th>
                                                        <th> Mobile Number </th>
                                                        <th> Relation </th>
                                                        <th> Gender </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{print_r($resident)}} --}}
                                                    @forelse ($resident->familes as $key=>$family)
                                                        <tr>
                                                            <td> {{++$key}} </td>
                                                            <td> {{ucfirst($family->name)}} </td>
                                                            <td> {{$family->cnic}} </td>
                                                            <td> {{$family->email}} </td>
                                                            <td>{{$family->mobile_number}}</td>
                                                            <td>{{ucfirst($family->relation)}}</td>
                                                            <td>{{ucfirst($family->gender)}}</td>
                                                        </tr>
                                                    @empty
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="kt_widget6_tab3_content" aria-expanded="false">
                                        <div class="kt-notification">

                                            <table class="table table-striped table-hover kt_table_2">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Name </th>
                                                        <th> Father Name </th>
                                                        <th> CNIC</th>
                                                        <th> Mobile Number </th>
                                                        <th> Occupation</th>
                                                        <th> Gender </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{print_r($resident)}} --}}
                                                    @forelse ($resident->servents as $key=>$servent)
                                                        <tr>
                                                            <td> {{++$key}} </td>
                                                            <td> {{ucfirst($servent->name ?? '')}} </td>
                                                            <td> {{ucfirst($servent->father_name ?? '')}} </td>
                                                            <td> {{$servent->cnic ?? ''}} </td>
                                                            <td>{{$servent->mobile_number ?? ''}}</td>
                                                            <td>{{ucfirst($servent->occupation ?? 'Nil')}}</td>
                                                            <td>{{ucfirst($servent->gender ?? 'Nil')}}</td>
                                                        </tr>
                                                    @empty
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="kt_widget6_tab4_content" aria-expanded="false">
                                        <div class="kt-notification">
                                            <table class="table table-striped table-hover kt_table_2">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Name </th>
                                                        <th> Father Name </th>
                                                        <th> Type </th>
                                                        <th> CNIC</th>
                                                        <th> Mobile Number </th>
                                                        <th> Gender</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{print_r($resident)}} --}}
                                                    @forelse ($resident->handymen as $key=>$hman)
                                                        <tr>
                                                            <td> {{++$key}} </td>
                                                            <td> {{ucfirst($hman->name ?? '')}} </td>
                                                            <td> {{ucfirst($hman->father_name ?? '')}} </td>
                                                            <td> {{ucfirst($hman->handy_type->title ?? '')}} </td>
                                                            <td> {{$hman->cnic ?? ''}} </td>
                                                            <td>{{$hman->mobile_number ?? ''}}</td>
                                                            <td>{{ucfirst($hman->gender ?? 'Nil')}}</td>
                                                        </tr>
                                                    @empty
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="kt_widget6_tab5_content" aria-expanded="false">
                                        <div class="kt-notification">
                                            <table class="table table-striped table-hover kt_table_2">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Vehicle Type </th>
                                                        <th> Vehicle Name </th>
                                                        <th> Modal Year </th>
                                                        <th> Make </th>
                                                        <th> Number </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{print_r($resident)}} --}}
                                                    @forelse ($resident->vehicles as $key=>$vehicle)
                                                        <tr>
                                                            <td> {{++$key}} </td>
                                                            <td> {{ucfirst($vehicle->vehicleType->title ?? '')}} </td>
                                                            <td> {{ucfirst($vehicle->vehicle_name ?? '')}} </td>
                                                            <td> {{$vehicle->model_year ?? ''}} </td>
                                                            <td> {{ucfirst($vehicle->make ?? '')}} </td>
                                                            <td> {{$vehicle->vehicle_number ?? ''}} </td>
                                                        </tr>
                                                    @empty
                                                        <tr> <td colspan="6" class="text-center text-danger">no data found</td> </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Notifications-->
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
@section('top-styles')
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1') }}" type="text/javascript"></script>
	<script src="{{ asset('js/ssm_datatable.js?v=1.0') }}" type="text/javascript"></script>

	<script>
		$('#viewTenantsModal').click(function () {
        $('#TenantsList').modal('show');
    });

    $('.UnitTypeModelClosed').click(function () {
        $('#AddUnitTypeModel').modal('hide');
    });
	</script>
@endsection