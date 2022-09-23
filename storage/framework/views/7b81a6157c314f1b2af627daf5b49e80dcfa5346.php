<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('Fine&Plenties')); ?></h3>
				
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		<?php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		if($search_society_id !='all' AND $search_society_id !=''){
		$fines = $fines->where('society_id',$search_society_id);
		}else{
		$fines = $fines;
		}
		?>
		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<div class="col-md-2"></div>
				<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
				<div class="form-group validated col-sm-6 col-xs-6">
					<label class="form-control-label">Select Society</label>
					<select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true">
						<option selected disabled value=""> Select Society </option>
						<option <?php if($search_society_id=='all' ): ?> selected <?php endif; ?> value="all"> All </option>
						<?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option <?php if($search_society_id==$society->id): ?>
							selected
							<?php endif; ?> value="<?php echo e($society->id); ?>"> <?php echo e($society->name); ?> [<?php echo e($society->code); ?>]</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="kt-section__content kt-section__content--solid mt-3 pt-3">
					<button type="submit" class="btn btn-primary btn-sm mt-1">Search</button>
				</div>

			</div>
		</form>

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-hammer"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Fine&Plenties')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-fine-penalties')): ?>
							<a href="<?php echo e(route('fines.create')); ?>" class="btn btn-brand btn-sm btn-elevate btn-icon-sm"
								title="Add Fine"><i class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?></a>
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
							<th><?php echo e(__('Title')); ?> </th>
							<th><?php echo e(__('Society')); ?> </th>
							<th><?php echo e(__('Amount')); ?> </th>
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-fine-penalties')): ?>
							<th><?php echo e(__('Imposed Fine')); ?> </th>
							<?php endif; ?>

							<th><?php echo e(__('Actions')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $fines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$fine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
							<td><?php echo e(++$key); ?></td>
							<td><?php echo e($fine->title); ?></td>
							<td> <b> <?php echo e($fine->society->name ?? ''); ?> </b> </td>
							<td> <b class="text-danger"> <?php echo e(number_format($fine->fine_amount,0)); ?> </b> </td>
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-fine-penalties')): ?>
							<td>
								<a href="#" title="Imposed Fine" class="btn btn-brand btn-sm OpenImposedFine"
									Fine-ID="<?php echo e($fine->id); ?>" Fine-Title="<?php echo e($fine->title); ?>">
									<i class="fa fa-plus fa-lg"></i>
									<?php echo e(__('imposed Fine')); ?>

								</a>
							</td>
							<?php endif; ?>

							<td>
								<a href="<?php echo e(route('fines.show', $fine->id)); ?>" class="text-brand"> <i
										class="fa fa-eye fa-lg" title="View Detail"></i></a> &nbsp;
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-fine-penalties')): ?>
								<a href="<?php echo e(route('fines.edit', $fine->id)); ?>" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit"></i> </a> &nbsp;
								<?php endif; ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-fine-penalties')): ?>
								<a href="<?php echo e(route('fines.destroy', $fine->id)); ?>" class="text-danger delete-confirm"
									del_title="Fine <?php echo e(substr($fine->title, 0, 20)); ?>"><i class="fa fa-trash-alt fa-lg"
										title="<?php echo e(__('Delete')); ?>"></i></a>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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

<div class="modal fade" id="imposed_fine_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Imposed Fine </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
				</button>
			</div>
			<form action="<?php echo e(route('imposedfine.store')); ?>" method="POST">
				<?php echo csrf_field(); ?>
				<div class="modal-body pb-0 mb-0">
					<div class="row">
						<div class="form-group validated col-sm-12">
							<label class="form-control-label" for="title"> <b> <?php echo e(__('Fine Title:')); ?> </b></label>
							<input type="text" id="Fine-Title" class="form-control" readonly disabled>
						</div>

						<input type="hidden" name="fine_id" id="Fine-Id">

						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b><?php echo e(__('Select Residents*:')); ?></b></label>
							<select class="form-control kt-selectpicker" name="residents[]" data-live-search="true"
								autofocus="true" required multiple>
								<option disabled> <?php echo e(__('Select Residents')); ?></option>
								<?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($resident->id); ?>"> <?php echo e($resident->name); ?> </option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>

						<div class="form-group validated col-md-6">
							<label class="form-control-label"><b><?php echo e(__('Fine Date*')); ?></b></label>
							<div class="input-group date">
								<input type="text" name="fine_date" class="kt_datepicker_validate form-control"
									placeholder="Select date" style="border-radius: 3px;"
									value="<?php echo e(today()->format('Y-m-d')); ?>" required>
							</div>
						</div>

						<div class="form-group validated col-md-6">
							<label class="form-control-label"><b><?php echo e(__('Due Date*')); ?></b></label>
							<div class="input-group date">
								<input type="text" name="due_date" class="kt_datepicker_validate form-control"
									placeholder="Select date" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary btn-sm CloseImposedModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Fine Detail </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
				</button>
			</div>
			<div class="kt-portlet mb-0">
				<div class="kt-portlet__body mb-2 pb-0">
					<div class="kt-widget kt-widget--user-profile-3">
						<div class="kt-widget__top">
							<div class="kt-widget__media">
								<img src="<?php echo e(url('assets/media/users/default.jpg')); ?>" alt="image"
									style="border-radius: 20%;">
							</div>

							<div class="kt-widget__content">
								<div class="kt-widget__head">
									<a href="#" id="invoiceTo" class="kt-widget__username">
										username
									</a>
									<div class="kt-widget__action">
										<div class="kt-widget__details">
											<b>Total Fine:</b>
											<span class="kt-widget__value"> <b> <span>Rs </span> <span
														id="FineAmountVal"></span> </b>
										</div>
									</div>
								</div>

								<div class="kt-widget__subhead">
									<span><b id="UserUniqueID"></b></span>
									<span class="text-right" style="float: right;">
										status:
										<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success"
											id="FineStatus"></span>
									</span>
								</div>
								<div class="kt-widget__head">
									<a class="kt-widget__username">
										<span style="font-size: 13px;"> Fine By: </span> <span id="FineBy"></span>
									</a>
								</div>

								<div class="kt-widget__subhead">
									<span><i class="fa fa-tags"></i> <span id="AdminLevel"></span></span>
								</div>
								<div class="kt-widget__info text-right">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body mt-0 pt-0">
				<div class="row">
					<div class="form-group validated col-sm-6">
						<label class="form-control-label" for="title"> <b> <?php echo e(__('Title:')); ?> </b></label>
						<input type="text" id="FineTitle" class="form-control" readonly disabled>
					</div>
					<div class="form-group validated col-sm-3">
						<label class="form-control-label"> <b> <?php echo e(__('Fine Date:')); ?> </b> </label>
						<input type="text" class="form-control" id="FineDate" readonly>
					</div>
					<div class="form-group validated col-sm-3">
						<label class="form-control-label"> <b> <?php echo e(__('Due Date:')); ?> </b> </label>
						<input type="text" class="form-control" id="DueDate" readonly>
					</div>

					<div class="form-group validated col-sm-12">
						<label class="form-control-label" for="title"> <b> <?php echo e(__('Description:')); ?> </b> </label>
						<textarea class="form-control" id="FineDescription" cols="30" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Add Payment </h5>
				<button type="button" class="close paymentModel" aria-label="Close"></button>
			</div>

			<form action="<?php echo e(route('fine.payment')); ?>" method="POST" id="PckgServiceModelForm">
				<?php echo csrf_field(); ?>
				<input name="user_id" type="hidden" id="user_id" />
				<input name="fine_id" type="hidden" id="invoice_id" />
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
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1')); ?>" type="text/javascript">
</script>
<script>
	$(".OpenImposedFine").click(function(){
    	var fine_id = parseInt($(this).attr('Fine-ID'));
    	var fine_title = $(this).attr('Fine-Title');
        $('#imposed_fine_modal').modal('show');

        $('#Fine-Title').val(fine_title);
        $('#Fine-Id').val(fine_id);
    });

    $(".CloseImposedModel").click(function(){
    	$('#imposed_fine_modal').trigger('reset');
		$('#imposed_fine_modal').modal('toggle');
    });

    $(".ViewFineDetail").click(function(){
    	var fine_id = parseInt($(this).attr('FineID'));
        var fine_list = <?php  echo json_encode($fines); ?>;
        var fine_detail = fine_list.find(x => x.id === fine_id);
        $('#kt_modal_1').modal('show');
        $('#FineAmountVal').html(fine_detail['fine_amount']);
        $('#FineTitle').val(fine_detail['title']);
        $('#FineDate').val(fine_detail.fine_date);
        $('#DueDate').val(fine_detail.due_date);
        $('#invoiceTo').html(fine_detail.user.name);
        $('#UserUniqueID').html(fine_detail.user.unique_id);
        $('#FineBy').html(fine_detail.fineby.name);
        $('#AdminLevel').html(fine_detail.fineby.level_slug);
        $('#FineDescription').val(fine_detail.description);
        $('#FineStatus').html(fine_detail.fine_status);
    });

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
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/fines/index.blade.php ENDPATH**/ ?>