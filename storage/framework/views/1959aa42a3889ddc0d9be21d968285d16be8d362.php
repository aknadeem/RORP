<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?> </h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="<?php echo e(route('users.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('users')); ?></span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<?php
		// filter department from departments array
		$search_society_id = request()->search_society_id;
		if($search_society_id !='all' AND $search_society_id !=''){
		$users = $users->where('society_id', $search_society_id);
		}else{
		$users = $users;
		}
		?>
		<form action="" method="get" class="loader">
			<div class="alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 ">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
						<option selected disabled> <?php echo e(__('Select Society')); ?></option>
						<option <?php echo e(($search_society_id=='all' ) ? 'selected' : ''); ?> value="all">
							<?php echo e(__('All Societies')); ?>

						</option>
						<?php $__empty_1 = true; $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<option <?php echo e(($search_society_id==$soc->id) ? 'selected' : ''); ?> value="<?php echo e($soc->id); ?>">
							<?php echo e($soc->name); ?>

						</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<option disabled>No Society Found</option>
						<?php endif; ?>
					</select>
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
						User List
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-user-management')): ?>
							<a href="<?php echo e(route('users.create')); ?>" class="btn btn-brand btn-elevate btn-sm btn-icon-sm"
								title="Create User">
								<i class="fa fa-plus mb-1"></i>Create </a>
							<?php endif; ?>

							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-management')): ?>
							<a href="<?php echo e(route('residentaccounts')); ?>"
								class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Click to View Residents">
								<i class="fa fa-eye mb-1"></i> Residents </a>
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
							<th>ID</th>
							<th> Name </th>
							<th> Society </th>
							<th> permissions </th>
							<th> UserLevel </th>
							<th> Status </th>
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-user-management')): ?>
							<th>Actions</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$htitle = 'Click to View Detail';
						?>
						<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

						<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-management')): ?>
						<?php
						$url = route('users.show', $user->id);
						$title = 'Click to view Detail';
						?>
						<?php else: ?>
						<?php
						$url = '#';
						$title = 'Unauthorized to view Detail';
						?>
						<?php endif; ?>

						<tr>
							<td><?php echo e(++$key); ?></td>
							<td> <a href="<?php echo e(($user->user_level_id < 6) ? route('users.show', $user->id) : $url); ?>"
									title="<?php echo e(($user->user_level_id < 6) ? $htitle : $title); ?>"> <?php echo e($user->name); ?> </a>
							</td>
							<td> <b> <?php echo e($user->society->name ?? ''); ?> </b> </td>
							<td>
								<?php if(count($user->permissions) > 0): ?>
								<a href="<?php echo e(route('user-permissions', $user->id)); ?>" title="view Permissions"
									class="btn btn-primary btn-sm">
									<i class="fa fa-lock fa-lg"></i>
									<?php echo e(__('Permissions')); ?>

								</a>
								<?php else: ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assign-permission-user-management')): ?>
								<a href="<?php echo e(route('edituserpermissions', $user->id)); ?>" title="Add Permissions"
									class="btn btn-success">
									<i class="fa fa-plus fa-lg"></i>
									<?php echo e(__('Permissions')); ?>

								</a>
								<?php else: ?>
								<span> Unauthorized </span>
								<?php endif; ?>
								<?php endif; ?>
							</td>
							<td><?php echo e($user->userlevel->title ?? ''); ?></td>
							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('enable-disable-accounts-user-management')): ?>
								<a href="<?php echo e(route('user.toggleStatus',$user->id)); ?>"
									class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success  confirm-status">

									<label class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
										<input <?php echo e($user->is_active == 1 ? 'checked' : ''); ?> type="checkbox" >
										<span <?php echo e($user->is_active == 1 ? 'title=Deactive' : 'title=Active'); ?>

											class="slider <?php echo e($user->is_active == 1 ? '' : ''); ?>"></span>
									</label>
								</a>
								<?php else: ?>
								<span> <b> <?php echo e($user->is_active == 1 ? 'Active' : 'Inactive'); ?> </b> </span>
								<?php endif; ?>
							</td>
							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-user-management')): ?>
								<a href="<?php echo e(route('users.edit', $user->id)); ?>" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit User"></i> </a> &nbsp;
								<?php endif; ?>
								<!--<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user-management')): ?>-->
								<!--    <a href="<?php echo e(route('users.destroy', $user->id)); ?>" class="text-danger delete-confirm" del_title="User <?php echo e($user->name); ?>"><i class="fa fa-trash-alt fa-lg" title="Delete User"></i></a>-->
								<!--<?php endif; ?>-->
							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>
				<!--end: Datatable -->
			</div>
		</div>
	</div>

	<form method="post" id="status-form">
		<?php echo method_field('PUT'); ?>
		<?php echo csrf_field(); ?>
	</form>


	<!-- begin:: End Content  -->
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('top-styles'); ?>
<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet"
	type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1.0.1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/user/index.blade.php ENDPATH**/ ?>