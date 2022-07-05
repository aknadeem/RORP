<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?></h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>

				<a href="<?php echo e(route('userlevels.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('UserLevels')); ?></span></a>
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
						<?php echo e(__('UserLevels')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<!--<?php if(Auth::user()->user_level_id < 2): ?>-->
							<!--	<a href="<?php echo e(route('userlevels.create')); ?>" class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create User Group"><i class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?> </a>-->
							<!--<?php endif; ?>-->
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th><?php echo e(__('ID')); ?></th>
							<th><?php echo e(__('Title')); ?></th>
							<th><?php echo e(__('Slug')); ?></th>
							<th><?php echo e(__('Permissions')); ?></th>
							<?php if(Auth::user()->user_level_id < 3): ?> <th><?php echo e(__('Action')); ?></th>
								<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $userlevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e(__($level->id)); ?></td>
							<td><?php echo e(__($level->title)); ?></td>
							<td><?php echo e(__($level->slug)); ?></td>
							<td>
								<div class="btn-group dropdown">
									<button type="button" class="btn btn-outline-info btn-sm">
										<?php echo e(__('Permissions')); ?> (<?php echo e($level->permissions->count()); ?>)
									</button>
									<!--<button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
									<!--</button>-->
									<!--<div class="dropdown-menu" style="height:300px !important; overflow:scroll !important;">-->
									<!--	<?php $__empty_1 = true; $__currentLoopData = $level->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>-->
									<!--		<span class="dropdown-item"> <?php echo e(__($permission->slug ?? '')); ?> </span>-->
									<!--	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>-->
									<!--		<span class="dropdown-item">  <?php echo e(__('No Permission Given Yet!')); ?></span>-->
									<!--		<div class="dropdown-divider"></div>-->
									<!--		<a class="dropdown-item" href="<?php echo e(route('userlevels.edit', $level->id)); ?>"><?php echo e(__('Add Permissions')); ?></a>-->
									<!--	<?php endif; ?>-->
									<!--</div>-->
								</div>
							</td>
							<?php if(Auth::user()->user_level_id < 3): ?> <td>
								<a href="<?php echo e(route('userlevels.edit', $level->id)); ?>" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="<?php echo e(__('Edit Row')); ?>"></i> </a> &nbsp;
								<!--<a href="<?php echo e(route('userlevels.destroy', $level->id)); ?>" class="text-danger delete-confirm"  del_title="User Level <?php echo e($level->title); ?>"> <i class="fa fa-trash fa-lg" title="<?php echo e(__('delete Row')); ?>"></i> </a> &nbsp;-->
								</td>
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
<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet"
	type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/userlevel/index.blade.php ENDPATH**/ ?>