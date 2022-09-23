<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('Service Management')); ?></h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<span class="kt-subheader__desc"><?php echo e(__('smart-service-request')); ?></span>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<?php
		// filter department from departments array
		$search_society_id = request()->search_society_id ?? '';
		$search_department_id = request()->search_department_id ?? '';
		$search_subdep_id = request()->search_subdep_id ?? '';
		$search_status = request()->search_status ?? '';

		if($search_society_id !='all' AND $search_society_id !=''){
		$service_requests = $service_requests->where('society_id',$search_society_id);
		}else{
		$service_requests = $service_requests;
		}

		if($search_department_id !='all' AND $search_department_id !=''){
		$service_requests = $service_requests->where('type_id',$search_department_id);
		}else{
		$service_requests = $service_requests;
		}

		if($search_subdep_id !='all' AND $search_subdep_id !=''){
		$service_requests = $service_requests->where('sub_type_id',$search_subdep_id);
		}else{
		$service_requests = $service_requests;
		}

		if($search_status !='all' AND $search_status !=''){
		$service_requests = $service_requests->where('status',$search_status);
		}else{
		$service_requests = $service_requests;
		}
		?>

		<form action="" method="get">
			<div class="row alert alert-light alert-elevate" role="alert">
				<!--<div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>-->

				<div class="form-group validated col-sm-3 col-xs-6">
					<label class="form-control-label">Society</label>
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

				<div class="form-group validated col-sm-3 col-xs-6">
					<label class="form-control-label">Type</label>
					<select class="form-control kt-selectpicker" name="search_department_id" data-live-search="true"
						required>
						<option selected disabled> Select Department </option>
						<option <?php if($search_department_id=='all' ): ?> selected <?php endif; ?> value="all"> All </option>
						<?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cp_department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option <?php if($search_department_id==$cp_department->id): ?>
							selected
							<?php endif; ?> value="<?php echo e($cp_department->id); ?>"> <?php echo e($cp_department->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="form-group validated col-sm-3 col-xs-6">
					<label class="form-control-label">SubType</label>
					<select class="form-control kt-selectpicker" name="search_subdep_id" data-live-search="true"
						required>
						<option selected disabled> Select SubDepartment </option>
						<option <?php if($search_subdep_id=='all' ): ?> selected <?php endif; ?> value="all"> All </option>
						<?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $department->subdepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option <?php if($search_subdep_id==$sub->id): ?>
							selected
							<?php endif; ?> value="<?php echo e($sub->id); ?>"> <?php echo e($sub->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>

				<div class="form-group validated col-sm-2 col-xs-6">
					<label class="form-control-label">Status</label>
					<select class="form-control kt-selectpicker" name="search_status" data-live-search="true">
						<option selected disabled> Select Status </option>
						<option <?php if($search_status=='all' ): ?> selected <?php endif; ?> value="all"> All </option>
						<option <?php if($search_status=='open' ): ?> selected <?php endif; ?> value="open"> Open </option>
						<option <?php if($search_status=='closed' ): ?> selected <?php endif; ?> value="closed"> Closed </option>
						<option <?php if($search_status=='approved' ): ?> selected <?php endif; ?> value="approved"> Approved </option>
						<option <?php if($search_status=='in_process' ): ?> selected <?php endif; ?> value="in_process"> In Process
						</option>
						<option <?php if($search_status=='in_correct' ): ?> selected <?php endif; ?> value="in_correct"> In Correct
						</option>
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
						<i class="fa fa-hands-helping"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Smart Service Request')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable kt_table_11">
					<thead>
						<tr>
							<th><?php echo e(__('#')); ?> </th>
							<th><?php echo e(__('Service')); ?> </th>
							<th><?php echo e(__('User')); ?> </th>
							<th><?php echo e(__('Service Type')); ?> </th>
							<th><?php echo e(__('Package')); ?> </th>
							<th><?php echo e(__('Remarks')); ?> </th>
							<th><?php echo e(__('Actions')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$user_id = Auth::user()->id;
						$user_level_id = Auth::user()->user_level_id;
						?>

						<?php $__empty_1 = true; $__currentLoopData = $service_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$service_req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

						
						<tr>
							<td><?php echo e(++$key); ?></td>
							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-service-management')): ?>
								<a href="<?php echo e(route('requestservice.show', $service_req->id)); ?>" data-toggle="kt-tooltip"
									data-placement="bottom" data-skin="brand" title="Click to view Detail Detail">
									&nbsp; <?php echo e($service_req->service->title); ?>

								</a> &nbsp;
								<?php else: ?>
								<?php echo e($service_req->service->title); ?>

								<?php endif; ?>
							</td>
							<td> <?php echo e($service_req->user->name); ?> </td>
							<td><?php echo e($service_req->service->billing_type); ?></td>
							<td><?php echo e(__($service_req->package->title ?? '--')); ?></td>

							<td>
								<span class="btn btn-<?php echo e($service_req->status_color); ?> btn-sm"><?php echo e($service_req->status); ?></span>

								<?php if($user_level_id < 5 ): ?> <?php if($service_req->service->billing_type !='no_billing' AND
									$service_req->package_id > 0): ?>

									<?php if($service_req->status != 'approved'): ?>
									<?php if($service_req->status == 'paid' && $account_hod_id == $user_id): ?>
									<?php else: ?>
									<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-service-management')): ?>
									<span request-id="<?php echo e($service_req->id); ?>"
										data-pckg-price="<?php echo e($service_req->package->price); ?>"
										request_ispaid="<?php echo e($service_req->is_paid); ?>"
										class="btn btn-sm btn-clean btn-icon btn-icon-md openPckgModel" title="Update">
										<i class="flaticon-edit"></i>
									</span>
									<?php endif; ?>
									<?php endif; ?>
									<?php endif; ?>
									<?php else: ?>
									<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-service-management')): ?>
									<span data-toggle="modal" data-target="#close-re-assign"
										data-target-id="<?php echo e($service_req->id); ?>"
										class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
										<i class="flaticon-edit"></i>
									</span>
									<?php endif; ?>
									<?php endif; ?>

									<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-service-management')): ?>
									<span data-toggle="modal" data-target="#todoWorking"
										data-target-id="<?php echo e($service_req->id); ?>" service_status="<?php echo e($service_req->status); ?>"
										class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
										<i class="flaticon-edit"></i>
									</span>
									<?php endif; ?>
									<?php endif; ?>

							</td>

							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-service-management')): ?>
								<a href="<?php echo e(route('requestservice.edit', $service_req->id)); ?>" data-toggle="kt-tooltip"
									data-placement="bottom" data-skin="brand" title="Click to Edit"
									class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit Service Request"></i>
								</a> &nbsp;
								<?php endif; ?>
								
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
	</div>
	<!-- begin:: End Content  -->
</div>

<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"> Add Remarks </h5>
				<button type="button" class="close closetodoWorkingModel" aria-label="Close"></button>
			</div>
			<form action="<?php echo e(route('request.addremarks')); ?>" method="POST" id="todoWorkingForm">
				<?php echo csrf_field(); ?>
				<input class="form-control" name="service_request_id" type="hidden" id="service_request_id" />
				<div class="modal-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Status:*</label>
						<div class="col-9">
							<div class="kt-radio-inline">
								<label class="kt-radio kt-radio--solid kt-radio--warning">
									<input type="radio" value="in_process" id="pending" name="service_status"
										required />
									In Process
									<span></span>
								</label>

								<label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
									<input type="radio" id="completed" value="completed" name="service_status"
										required />Completed
									<span></span>
								</label>

								<label class="kt-radio kt-radio--solid kt-radio--dark">
									<input type="radio" id="incorrect" value="incorrect" name="service_status"
										required />InCorrect
									<span></span>
								</label>

							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-12">
							<label for="message-text" class="form-control-label">Comments:*</label>

							<textarea class="form-control" name="comments" required></textarea>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm closetodoWorkingModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Service Status</h5>
				<button type="button" class="close closeModel" aria-label="Close"></button>
			</div>
			<form action="<?php echo e(route('request.updatestatus')); ?>" method="POST" id="close-re-assignForm">
				<?php echo csrf_field(); ?>
				<input class="form-control" name="service_request_id" type="hidden" id="cid" />
				<div class="modal-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Status:*</label>
						<div class="col-9">
							<div class="kt-radio-inline">
								<label class="kt-radio kt-radio--solid kt-radio--dark">
									<input type="radio" value="closed" name="service_status" required />
									Close
									<span></span>
								</label>
								<label class="kt-radio kt-radio--solid kt-radio--info">
									<input type="radio" value="re_assign" id="re-assigne" name="service_status"
										required />Re-Assign
									<span></span>
								</label>
								<?php
								$user_level_id = Auth::user()->user_level_id;
								?>

								
								<label class="kt-radio kt-radio--solid kt-radio--warning">
									<input type="radio" value="modified" id="change_deparment" name="service_status"
										required /> Forward
									<span></span>
								</label>
								
							</div>
						</div>
					</div>
					<div class="row re_assign_row" style="display: none;">
						<div class="form-group validated col-sm-12">
							<label for="message-text" class="form-control-label">Assign To:*</label>
							<select
								class="form-control refer_dep_row_input kt-selectpicker <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
								name="user_id" data-live-search="true">

								<option selected disabled>Select Supervisor</option>
								<?php $__currentLoopData = $subdep_sups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdep_sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($subdep_sup->supervisor_id); ?>"><?php echo e($subdep_sup->supervisor->name); ?>

								</option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
					</div>

					<div class="row refer_dep_row" style="display: none;">
						<div class="form-group validated col-sm-12">
							<label for="message-text" class="form-control-label">Forward To:*</label>
							<select
								class="form-control re_assign_row_input kt-selectpicker <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
								name="department_id" data-live-search="true">

								<option selected disabled>Select Department</option>
								<?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<option value="<?php echo e($dep->id); ?>"><?php echo e($dep->name); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<option disabled> No Department Found </option>
								<?php endif; ?>
							</select>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-12">
							<label for="message-text" class="form-control-label">Comments:*</label>

							<textarea class="form-control" name="comments" required></textarea>
						</div>

					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm closeModel">Close</button>
					<button type="submit" class="btn btn-primary btn-sm">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="PckgServiceModel" tabindex="-1" role="dialog" aria-labelledby="PckgServiceModel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Service Package Request </h5>
				<button type="button" class="close closePckgModel" aria-label="Close"></button>
			</div>

			<form action="<?php echo e(route('request.pckgservice')); ?>" method="POST" id="PckgServiceModelForm">
				<?php echo csrf_field(); ?>
				<input class="form-control" name="service_request_id" type="hidden" id="pckgRequestId" />
				<div class="modal-body">

					<label class="col-form-label" style="float: right !important;"> <b>Package Price: Rs.
							<span id="pckgPrice"></span> </b> <br>

						<span id="FinalAmt" class="text-danger"></span> <br>
					</label>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label"> <b> Status:* </b></label>
						<div class="col-9">
							<div class="kt-radio-inline">
								<label class="kt-radio kt-radio--solid kt-radio--info approveOption"
									style="display: none;">
									<input type="radio" value="approved" id="approve_pckg" name="service_status"
										required />Approve
									<span></span>
								</label>

								<label class="kt-radio kt-radio--solid kt-radio--warning">
									<input type="radio" value="cancel" name="service_status" required /> Cancel
									<span></span>
								</label>
							</div>
						</div>
					</div>

					<span id="AmtError" class="text-danger"></span> <br>

					<div class="row" id="pckg_dates" style="display: none;">
						<div class="col-lg-6">
							<label class="form-control-label"><b><?php echo e(__('Start Date*')); ?></b></label>
							<div class="input-group date">
								<input type="text" name="pckg_start_date"
									class=" kt_datepicker_validate form-control <?php $__errorArgs = ['pckg_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
									placeholder="Select date" style="border-radius: 3px;">

								<?php $__errorArgs = ['pckg_start_date'];
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
							<label class="form-control-label"><b><?php echo e(__('Add Discount')); ?></b></label>
							<div class="input-group date">
								<input type="number" min="0" max="" id="discount_amount" name="discount_amount"
									class="form-control <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
									placeholder="Add Discount">

								<?php $__errorArgs = ['discount_amount'];
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
					<div class="row">
						<div class="col-md-12 mt-2">
							<label for="message-text" class="form-control-label"> <b> Comments:* </b></label>
							<textarea class="form-control" name="comments" required></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm closePckgModel">Close</button>
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
<script src="<?php echo e(asset('assets/js/pages/crud/datatables/extensions/buttons.js?v=1')); ?> "></script>

<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1')); ?>" type="text/javascript">
</script>
<script>
	$("input[name='service_status']").click(function () {
     	// alert('hello');
        if ($('#re-assigne').is(':checked')) {

        	// alert('hello');
            $('.re_assign_row').show();
            $('.re_assign_row_input').prop('required', true);

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', false);

        }else if($('#change_deparment').is(':checked')){
            $('.refer_dep_row').show();
            $('.refer_dep_row_input').prop('required', true);

            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
        }else {
            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
            $('.re_assign_row_input').val('');

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', true);
            $('.refer_dep_row_input').val();
        }

    });

</script>
<script>
	$(document).ready(function () {
	        $('#todoWorking').on('show.bs.modal', function (e) {
	            var service_request_id = $(e.relatedTarget).data('target-id');
	            var service_status = $(e.relatedTarget).attr('service_status');
	            $('#service_request_id').val(service_request_id);
	        });
	    });

	    $(document).ready(function () {
	        $('#close-re-assign').on('show.bs.modal', function (e) {
	            var cid = $(e.relatedTarget).data('target-id');
	            $('#cid').val(cid);
	        });
	    });

	    $(document).ready(function () {
	        $('#feedback').on('show.bs.modal', function (e) {
	            var feed_cid = $(e.relatedTarget).data('target-id');
	            $('#feed_cid').val(feed_cid);
	        });
	    });

	    // clear form on modal close
	   	$('.closetodoWorkingModel').click(function () {
			$('#todoWorkingForm').trigger( 'reset' );
			$('#todoWorking').modal('toggle');
		});

		$('.closeModel').click(function () {
			$('#close-re-assignForm').trigger( 'reset' );
			$('#close-re-assign').modal('toggle');
		});

		$('.closePckgModel').click(function () {
			$('#PckgServiceModelForm').trigger('reset');
			$('#PckgServiceModel').modal('toggle');
		});

		$('.openPckgModel').click(function () {
			$('#PckgServiceModel').modal('show');

			var rid = $(this).attr('request-id');
			var is_paid = $(this).attr('request_ispaid');


	        var pckg_price = $(this).data('pckg-price');
	        $('#pckgRequestId').val(rid);
	        $('#pckgPrice').html(pckg_price);

	        
	        if(is_paid == 1){
	        	$('.approveOption').show();
	        }

	        $('#approve_pckg').prop('required', true);
	        $('#discount_amount').attr({"max" : pckg_price}); // add max in discount 
	        
	        $('#discount_amount').on('input', function() {
	    		var final_amount = parseFloat(pckg_price);
	    		var discountAmount = parseFloat($(this).val());

	    		// alert(discountAmount);
	    		if(discountAmount > final_amount || discountAmount < 0){
	    			$('#AmtError').html('<b>Discount Amount Cannot be greater Than: '+pckg_price+' OR Less Then 0 </b>');
	    			$('#FinalAmt').html('');
	    		}else{

	    			// var discountAmt = discountAmount/final_amount*100;
	    			var new_amount = final_amount - discountAmount;
	    			$('#FinalAmt').html('<b> Final Amount: '+new_amount+'</b>');
	    			// $('#new_final_amount').val(new_amount);
	    			// $('#DiscountPercent').val(discountPercent);
	    			$('#AmtError').html('');
	    		}
	    	});
		});
		$("input[name='service_status']").click(function () {
        // for Service Package Request
        if ($('#approve_pckg').is(':checked')) {
        	$('#pckg_dates').show();
        	$('#approve_pckg').prop('required', true);
        }else{
        	$('#pckg_dates').hide();
        	$('#approve_pckg').prop('required', false);
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/servicemanagement/service_request/smart-service-requests.blade.php ENDPATH**/ ?>