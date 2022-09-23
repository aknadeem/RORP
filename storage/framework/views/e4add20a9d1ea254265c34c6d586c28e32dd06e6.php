<?php $__env->startSection('content'); ?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Subheader -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Imposed Fines
				</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group" id="kt_subheader_search">
					<span class="kt-subheader__desc" id="kt_subheader_total">
						Detail </span>
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				<a href="<?php echo e(URL::previous()); ?>" class="btn btn-default btn-bold">
					Back </a>
			</div>
		</div>
	</div>
	<!-- end:: Subheader -->
	<!-- begin:: Content -->
	<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!-- end:: Begin Fine Imposed Residents -->
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Imposed Fine
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th><?php echo e(__('#')); ?></th>
							<th><?php echo e(__('Resident')); ?></th>
							<th><?php echo e(__('Fine Date')); ?> </th>
							<th><?php echo e(__('Due Date')); ?> </th>
							<th><?php echo e(__('Fine By')); ?> </th>
							<th><?php echo e(__('Status')); ?> </th>
							<th><?php echo e(__('Action')); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$label_color = 'danger';
						?>
						<?php $__empty_1 = true; $__currentLoopData = $impose_fines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$impose): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

						<?php
						if($impose->fine_status == 'pending'){
						$label_color = 'warning';
						}else if($impose->fine_status == 'paid'){
						$label_color = 'success';
						}
						?>
						<tr>
							<td><?php echo e(++$key); ?></td>
							<td><?php echo e($impose->user->name); ?> <br>[ <b> <?php echo e($impose->user->unique_id); ?> </b> ] </td>
							<td>
								<span style="width: 100px;"><span
										class="btn btn-bold btn-sm btn-font-md  btn-label-success"> <?php echo e($impose->fine_date->format('d M, Y')); ?> </span></span>
							</td>
							<td>
								<span style="width: 100px;"><span
										class="btn btn-bold btn-sm btn-font-md  btn-label-danger"> <?php echo e($impose->due_date->format('d M, Y')); ?> </span></span>

							</td>
							<td><?php echo e($impose->fineby->name); ?> <br> [ <b> <?php echo e($impose->fineby->level_slug); ?> </b> ]</td>

							<td>
								<?php if($impose->fine_status == 'pending'): ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add-payment-fine-penalties')): ?>
								<button imposed-id="<?php echo e($impose->id ?? 0); ?>" fine-id="<?php echo e($impose->fine_id ?? 0); ?>"
									user_id="<?php echo e($impose->user_id ?? 0); ?>"
									invoice-price="<?php echo e($impose->fine->fine_amount ?? 0); ?>"
									UserName="<?php echo e($impose->user->name ?? ''); ?>"
									class="btn btn-bold btn-sm btn-label-brand openPaymentModel" data-container="body"
									data-toggle="kt-popover" data-placement="top" data-content="Click to add Payment">
									<i class="fa fa-plus"></i>Payment
								</button>
								<?php else: ?>
								<span style="width: 100px;"><span
										class="btn btn-bold btn-sm btn-label-<?php echo e($label_color); ?>"> <i
											class="fa fa-credit-card"></i> <?php echo e(ucfirst($impose->fine_status ?? '')); ?>

									</span></span>
								<?php endif; ?>
								<?php else: ?>
								<span style="width: 100px;"><span
										class="btn btn-bold btn-sm btn-label-<?php echo e($label_color); ?>"> <i
											class="fa fa-credit-card"></i> <?php echo e(ucfirst($impose->fine_status ?? '')); ?>

									</span></span>
								<?php endif; ?>
							</td>
							<td>
								<a href="<?php echo e(route('imposedfine.show', $impose->id)); ?>" data-container="body"
									data-toggle="kt-popover" data-placement="top" data-content="Click to view detail"
									class="btn btn-bold btn-sm btn-label-brand"> <i class="fa fa-eye fa-lg"></i>
									View</a> &nbsp;
							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<tr>
							<td colspan="7" class="text-danger text-center"> No Data Available </td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<!--end: Datatable -->
			</div>
		</div>
		<!-- end:: End Fine Imposed Residents  -->
	</div>
	<!-- end:: Content -->
</div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('modal-popup'); ?>
<?php echo $__env->make('_partial.fine_pay_view_modal', ['impose_fines'=> $impose_fines], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('top-styles'); ?>
<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet"
	type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/invoices/imposedfine/index.blade.php ENDPATH**/ ?>