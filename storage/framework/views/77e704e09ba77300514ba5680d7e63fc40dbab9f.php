<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="<?php echo e(route('permissions.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Permissions')); ?></span></a>
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
						Permissions
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if(Auth::user()->user_level_id == 1): ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-user-management')): ?>
									<a href="<?php echo e(route('permissions.create')); ?>" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create User Group"><i class="fa fa-plus mb-1"></i>Create </a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th><?php echo e(__('Id')); ?></th>
							<th> <?php echo e(__('Title')); ?> </th>
							<th> <?php echo e(__('Slug')); ?> </th>
							<th> <?php echo e(__('Module')); ?> </th>
							<?php if(Auth::user()->user_level_id ==1): ?>
									<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user-management')): ?>
							<!--<th><?php echo e(__('Actions')); ?></th>-->
							<?php endif; ?>
								<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td><?php echo e(__($permission->id)); ?></td>
								<td><?php echo e(__($permission->title)); ?></td>
								<td><?php echo e(__($permission->slug)); ?></td>
								<td><?php echo e(__($permission->module->slug ?? '')); ?></td>
								<?php if(Auth::user()->user_level_id ==1): ?>
									<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user-management')): ?>
									<!--<td>-->
									<!--	<a href="<?php echo e(route('permissions.destroy', $permission->id)); ?>" class="text-danger delete-confirm" del_title="Permission <?php echo e($permission->title); ?>"><i class="fa fa-trash-alt fa-lg" title="<?php echo e(__('Delete Permission')); ?>"></i></a>-->
									</td>
									<?php endif; ?>
								<?php endif; ?>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
    <!-- begin:: End Content  -->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('top-styles'); ?>
<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script>
"use strict";
var KTDatatablesAdvancedColumnRendering = function() {
	var initTable1 = function() {
		var table = $('#kt_table_1');
		// begin first table
		table.DataTable({
			responsive: true,
			paging: true,
			
		});
	};
	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		}
	};
}();
jQuery(document).ready(function() {
	KTDatatablesAdvancedColumnRendering.init();
});
</script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/permission/index.blade.php ENDPATH**/ ?>