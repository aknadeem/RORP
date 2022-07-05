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
                                        <?php echo e($resident->name ?? ''); ?>

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
                                                
                                                <?php $__empty_1 = true; $__currentLoopData = $resident->tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td> <?php echo e(++$key); ?> </td>
                                                        <td> <?php echo e($tenant->name); ?> </td>
                                                        <td> <?php echo e($tenant->father_name); ?> </td>
                                                        <td> <?php echo e($tenant->email); ?> </td>
                                                        <td><?php echo e($tenant->cnic); ?></td>
                                                        <td><?php echo e($tenant->martial_status); ?></td>
                                                        <td>
                                                            <?php if($tenant->e_pin > 0 && $tenant->m_pin > 0): ?>
                                                                <?php if($tenant->pin_verified == 1): ?>
                                                                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i class="fa fa-check" ></i> Pin Verified </a> 
                                                                <?php else: ?>
                                                                    <b> Pin is Not Verified </b>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                            <b> Pin Can't Send </b>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                <?php endif; ?>
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
                                                    
                                                    <?php $__empty_1 = true; $__currentLoopData = $resident->familes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td> <?php echo e(++$key); ?> </td>
                                                            <td> <?php echo e(ucfirst($family->name)); ?> </td>
                                                            <td> <?php echo e($family->cnic); ?> </td>
                                                            <td> <?php echo e($family->email); ?> </td>
                                                            <td><?php echo e($family->mobile_number); ?></td>
                                                            <td><?php echo e(ucfirst($family->relation)); ?></td>
                                                            <td><?php echo e(ucfirst($family->gender)); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    <?php endif; ?>
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
                                                    
                                                    <?php $__empty_1 = true; $__currentLoopData = $resident->servents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$servent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td> <?php echo e(++$key); ?> </td>
                                                            <td> <?php echo e(ucfirst($servent->name ?? '')); ?> </td>
                                                            <td> <?php echo e(ucfirst($servent->father_name ?? '')); ?> </td>
                                                            <td> <?php echo e($servent->cnic ?? ''); ?> </td>
                                                            <td><?php echo e($servent->mobile_number ?? ''); ?></td>
                                                            <td><?php echo e(ucfirst($servent->occupation ?? 'Nil')); ?></td>
                                                            <td><?php echo e(ucfirst($servent->gender ?? 'Nil')); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    <?php endif; ?>
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
                                                    
                                                    <?php $__empty_1 = true; $__currentLoopData = $resident->handymen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$hman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td> <?php echo e(++$key); ?> </td>
                                                            <td> <?php echo e(ucfirst($hman->name ?? '')); ?> </td>
                                                            <td> <?php echo e(ucfirst($hman->father_name ?? '')); ?> </td>
                                                            <td> <?php echo e(ucfirst($hman->handy_type->title ?? '')); ?> </td>
                                                            <td> <?php echo e($hman->cnic ?? ''); ?> </td>
                                                            <td><?php echo e($hman->mobile_number ?? ''); ?></td>
                                                            <td><?php echo e(ucfirst($hman->gender ?? 'Nil')); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr> <td colspan="7" class="text-center text-danger">no data found</td> </tr>
                                                    <?php endif; ?>
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
                                                    
                                                    <?php $__empty_1 = true; $__currentLoopData = $resident->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td> <?php echo e(++$key); ?> </td>
                                                            <td> <?php echo e(ucfirst($vehicle->vehicleType->title ?? '')); ?> </td>
                                                            <td> <?php echo e(ucfirst($vehicle->vehicle_name ?? '')); ?> </td>
                                                            <td> <?php echo e($vehicle->model_year ?? ''); ?> </td>
                                                            <td> <?php echo e(ucfirst($vehicle->make ?? '')); ?> </td>
                                                            <td> <?php echo e($vehicle->vehicle_number ?? ''); ?> </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr> <td colspan="6" class="text-center text-danger">no data found</td> </tr>
                                                    <?php endif; ?>
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
<?php $__env->startSection('top-styles'); ?>
	<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
	<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
	<script src="<?php echo e(asset('js/ssm_datatable.js?v=1.0')); ?>" type="text/javascript"></script>

	<script>
		$('#viewTenantsModal').click(function () {
        $('#TenantsList').modal('show');
    });

    $('.UnitTypeModelClosed').click(function () {
        $('#AddUnitTypeModel').modal('hide');
    });
	</script>
<?php $__env->stopSection(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/user/profile/_viewResidentInfo.blade.php ENDPATH**/ ?>