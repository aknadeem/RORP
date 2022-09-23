<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('Invoice')); ?></h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>

				<a href="<?php echo e(route('invoice.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Invoices')); ?></span></a>
			</div>
		</div>
	</div>

	<?php
	// filter department from departments array
	$search_society_id = request()->search_society_id;
	$search_user_id = request()->search_user_id;
	if($search_society_id !='all' AND $search_society_id !=''){

	$invoices = $invoices->where('soc_id', $search_society_id);
	}else{
	$invoices = $invoices;
	}

	if($search_user_id !='all' AND $search_user_id !=''){

	$invoices = $invoices->where('user_id', $search_user_id);
	}else{
	$invoices = $invoices;
	}
	?>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		<form action="" method="get" class="loader">
			<div class="alert alert-light alert-elevate row" role="alert">
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>

				<div class="form-group validated col-sm-5">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true"
						required>
						<option selected disabled> <?php echo e(__('Select Society')); ?></option>
						<option <?php echo e(($search_society_id=='all' ) ? 'selected' : ''); ?> value="all"> <?php echo e(__('All
							Societies')); ?></option>
						<?php $__empty_1 = true; $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<option <?php echo e(($search_society_id==$soc->id) ? 'selected' : ''); ?> value="<?php echo e($soc->id); ?>"><?php echo e($soc->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<option disabled> No Society Found </option>
						<?php endif; ?>
					</select>
				</div>

				<div class="form-group validated col-sm-5">
					<label class="form-control-label"><b></b></label>
					<select class="form-control kt-selectpicker" name="search_user_id" data-live-search="true" required>
						<option selected disabled> <?php echo e(__('Select User')); ?></option>
						<option <?php echo e(($search_user_id=='all' ) ? 'selected' : ''); ?> value="all"> <?php echo e(__('All Users')); ?>

						</option>
						<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<option <?php echo e(($search_user_id==$user->id) ? 'selected' : ''); ?> value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<option disabled> No user Found </option>
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
						<i class="fa fa-tags"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Invoices')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-invoices')): ?>
							<a href="<?php echo e(route('custominvoice.create')); ?>"
								class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Create Invoice"><i
									class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?></a>
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
							<th><?php echo e(__('#')); ?></th>
							<th><?php echo e(__('User')); ?></th>
							<th><?php echo e(__('Due Date')); ?> </th>
							<th><?php echo e(__('Price')); ?> </th>
							<th><?php echo e(__('Discount')); ?> </th>
							<th><?php echo e(__('Final Price')); ?> </th>
							<th><?php echo e(__('Payment')); ?> </th>
							<th><?php echo e(__('Action')); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
							<td><?php echo e(++$key); ?></td>
							<td><?php echo e($invoice->user->name); ?></td>
							<td> <b class="text-warning"> <?php echo e($invoice->due_date->format('M d, Y')); ?> </b></td>
							<td><?php echo e(number_format($invoice->price,0)); ?></td>
							<td> <span class="text-success"> <b> <?php echo e(number_format($invoice->discount_amount,0)); ?> </b>
								</span> </td>
							<td> <span class="text-danger"> <b> <?php echo e(number_format($invoice->final_price,0)); ?> </b></td>
							<td>
								<?php if($invoice->is_payed < 1): ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add-payment-invoices')): ?> <span
									invoice-id="<?php echo e($invoice->id); ?>" user_id="<?php echo e($invoice->user_id); ?>"
									invoice-price="<?php echo e($invoice->price - $invoice->discount_amount - $invoice->payed_amount); ?>"
									class="btn btn-primary btn-sm openPaymentModel" title="Add Payment">
									<i class="fa fa-plus"></i>Payment
									</span>
									<?php else: ?>
									<span style="width: 100px;"><span class="btn btn-bold btn-sm"> <?php echo e(ucfirst($invoice->status ?? 'pending')); ?>

										</span></span>
									<?php endif; ?>
									<?php else: ?>
									<span class="btn btn-success btn-sm ">
										<i class="fa fa-check"></i> Paid
									</span>
									<?php endif; ?>
							</td>

							<td>
								<a href="<?php echo e(route('custominvoice.show', $invoice->id)); ?>" class="text-brand"> <i
										class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;

								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-invoices')): ?>
								<a href="<?php echo e(route('custominvoice.edit', $invoice->id)); ?>" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="View Detail"></i> </a> &nbsp;
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<tr>
							<td colspan="10" class="text-danger text-center"> No Data Available </td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
	<!-- begin:: End Content  -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal-popup'); ?>
<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Add Payment </h5>
				<button type="button" class="close paymentModel" aria-label="Close"></button>
			</div>

			<form class="loader" action="<?php echo e(route('custominvoice.payment')); ?>" method="POST" id="PckgServiceModelForm">
				<?php echo csrf_field(); ?>
				<input name="user_id" type="hidden" id="user_id" />
				<input name="custom_invoice_id" type="hidden" id="invoice_id" />
				<input name="invoice_price" type="hidden" id="invoice_price" />
				<div class="modal-body">

					<label class="col-form-label" style="float: right !important;"> <b>Amount: Rs. <span
								id="pckgPrice"></span> </b> <br>

						<span id="FinalAmt" class="text-danger"></span> <br>
					</label>

					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label"> <b> Invoice No: </b></label>
						<div class="col-8 mt-2">
							<span id="invoiceNumber"></span>
						</div>
					</div>
					<span id="AmtError" class="text-danger"></span><br>
					<div class="row">
						<div class="col-lg-6">
							<label class="form-control-label"><b><?php echo e(__('Payment Date:*')); ?></b></label>
							<div class="input-group date">

								<?php
								$now_date = Carbon\Carbon::now()->format('Y-m-d');
								?>
								<input type="text" name="paid_date" required
									class=" kt_datepicker_validate form-control <?php $__errorArgs = ['payed_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
									value="<?php echo e($now_date); ?>" placeholder="Select date" style="border-radius: 3px;">

								<?php $__errorArgs = ['payed_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
								<div class="invalid-feedback"><?php echo e($message); ?></div>
								<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

							</div>
						</div>

						<div class="col-lg-6">
							<label class="form-control-label"><b><?php echo e(__('Amount:*')); ?></b></label>
							<div class="input-group date">
								<input type="number" step="any" min="0" max="" required id="payed_amount"
									name="paid_amount" class="form-control <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
									placeholder="Enter Amount">

								<?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
								<div class="invalid-feedback"><?php echo e($message); ?></div>
								<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col-md-12 mt-2">
							<label for="message-text" class="form-control-label"> <b> Remarks:* </b></label>
							<textarea class="form-control" name="remarks" required></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm paymentModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
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
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1')); ?>" type="text/javascript">
</script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>

<script>
	$(document).ready(function () {
   		//close Add Payment Model
   		$('.paymentModel').click(function () {
			$('#PckgServiceModelForm').trigger('reset');
			$('#AddPaymentModel').modal('toggle');
			$('#AmtError').html('');
			$('#FinalAmt').html('');
		});
   		// open Add Payment Modal
   		$('.openPaymentModel').click(function () {
			$('#AddPaymentModel').modal('show');
			var invoiceId = $(this).attr('invoice-id');
			var user_id = $(this).attr('user_id');
	        var invoice_price = parseFloat($(this).attr('invoice-price'));
	        $('#user_id').val(user_id);
	        $('#invoice_id').val(invoiceId);
	        $('#invoice_price').val(invoice_price);
	        $('#invoiceNumber').html('<b class="text-danger"> '+invoiceId+'</b>');
	        $('#pckgPrice').html(invoice_price);
	        $('#payed_amount').val(invoice_price);
	        $('#payed_amount').attr({"max" : invoice_price,"min" : invoice_price,});
	        $('#payed_amount').on('input', function() {
	    		var final_amount = invoice_price; // remaing amount
	    		var payAmount = parseFloat($(this).val()); // get payment Amount
	    		if(payAmount > final_amount || payAmount < final_amount){
	    			$('#AmtError').html('<b> Amount Cannot be greater Than: '+invoice_price+' OR Less Then '+invoice_price+' 0 </b>');
	    			$('#FinalAmt').html('');
	    		}else{
	    			var new_amount = final_amount - payAmount;
	    			$('#FinalAmt').html('Remaining Amount: <b> '+new_amount+'</b>');
	    			$('#AmtError').html('');
	    		}
	    	});
		});
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/invoices/custominvoice/index.blade.php ENDPATH**/ ?>