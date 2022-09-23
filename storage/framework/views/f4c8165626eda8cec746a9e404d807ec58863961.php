<?php $__env->startSection('content'); ?>

<style>
	.dataTables_filter {
		margin-top: -56px !important;
	}

	.dataTables_wrapper .dataTables_processing {
		background: #1c5b90;
		border: 1px solid #1c5b90;
		border-radius: 3px;
		color: #fff;
	}
</style>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('Service Management')); ?></h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="<?php echo e(route('requestservice.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Service
						Requests')); ?></span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="fa fa-hands-helping"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Services Requests')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-service-management')): ?>
							<a href="<?php echo e(route('requestservice.create')); ?>"
								class="btn btn-brand btn-sm btn-elevate btn-icon-sm" title="Request Service"><i
									class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table id="requestservicesDatatable"
					class="table table-striped table-hover table-checkable kt_table_11">
					<thead>
						<tr>
							<td class="form-group" colspan="4">
								<label class="form-control-label">Search by <b>Society</b></label>
								<select class="form-control filter-select px-1" data-column="2">
									<option selected disabled value=""> Select Society </option>
									<option value="all"> All </option>
									<?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($society->id); ?>"> <?php echo e($society->name); ?> [<?php echo e($society->code); ?>]
									</option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</td>

							<td class="form-group" colspan="2">
								<label class="form-control-label">Search by <b>Type</b></label>
								<select data-column="10" class="form-control filter-select px-1">
									<option selected disabled> Select Type </option>
									<option value="all"> All
									</option>
									<?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cp_department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($cp_department->id); ?>"> <?php echo e($cp_department->name); ?> </option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</td>
							<td class="form-group" colspan="2">
								<label class="form-control-label">Search by <b>Subtype</b></label>
								<select class="form-control filter-select px-1" data-column="11">
									<option selected disabled> Select Subtype </option>
									<option value="all"> All </option>
									<?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php $__currentLoopData = $department->subdepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($sub->id); ?>"> <?php echo e($sub->name); ?> </option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</td>
							<td class="form-group" colspan="2">
								<label class="form-control-label">Search by <b>Status</b></label>
								<select class="form-control filter-select px-1" data-column="7" name="search_status">
									<option selected disabled> Select Status </option>
									<option value="all"> All </option>
									<option value="open"> Open </option>
									<option value="closed"> Closed </option>
									<option value="completed"> Completed </option>
									<option value="in_process"> InProcess </option>
									<option value="in_correct"> InCorrect </option>
								</select>
							</td>
						</tr>

						<tr>
							<th># </th>
							<th> Code </th>
							<th> Society </th>
							<th> Resident </th>
							<th> Service </th>
							<th> Service Type </th>
							<th> Price </th>
							<th> Price Incl Tax </th>
							<th> Status </th>
							<th>Actions</th>
							<th>Type</th>
							<th>Subtype</th>
						</tr>
					</thead>
					<tbody>
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

								<?php if(Auth::user()->user_level_id < 5): ?> <label
									class="kt-radio kt-radio--solid kt-radio--info">
									<input type="radio" value="re_assign" id="re-assigneOpen" name="service_status"
										required />Re-Assign
									<span></span>
									</label>
									<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if(Auth::user()->user_level_id < 5): ?> <div class="row re_assign_rowOpen" style="display: none;">
						<div class="form-group validated col-sm-12">
							<label for="message-text" class="form-control-label">Assign To:*</label>
							<select
								class="form-control re_assign_row_inputOpen kt-selectpicker <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
								name="user_id" data-live-search="true">

								<option selected disabled value="">Select Supervisor</option>
								<?php $__currentLoopData = $subdep_sups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdep_sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($subdep_sup->supervisor_id); ?>"><?php echo e($subdep_sup->supervisor->name); ?>

								</option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
				</div>
				<?php endif; ?>

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

					<label class="col-form-label" style="float: right !important;"> <b>Package Price: Rs.<span
								id="pckgPrice"></span> </b> <br>

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
<script>
	$(document).ready(function () {
        var DTable =  $('#requestservicesDatatable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            ajax: "<?php echo e(route('getRequestServicesList')); ?>",
            "pageLength":10,
            // dom: 'Bfrtip', // not showing length menu
            "lengthMenu":[[10,30,50,-1],[10,30,50,"all"]],
            dom: 'Blfrtip', // with length menu
            columns:[
                // {data:'id', name:'id'}
                {data:'DT_RowIndex'},
                {data:'code'},
                {data:'society_id'},
                {data:'user_id'},
                {data:'service_id'},
                {data:'service_type'},
                {data:'total_price'},
                {data:'price_include_tax'},
                {data:'status'},
                {data:'Actions'},
                {data:'type_id'},
                {data:'sub_type_id'},
            ],
            buttons: [
                'copy',
                {
                    extend: 'excel',
                    title: 'Simple Services'
                },
                {
                    extend: 'pdf',
                    title: 'Simple Services'
                },
                {
                    extend: 'print',
                    title: 'Simple Services'
                }
            ],
            columnDefs: [ {
                targets: 2,
                className: 'bolded'
                }
            ],
        });
        
        let columnSociety = DTable.column(2);
        columnSociety.visible(false);
        
        let columnType = DTable.column(10);
        columnType.visible(false);
        
        let columnSubType = DTable.column(11);
        columnSubType.visible(false);
        
        setInterval(function(){
        // this will run after every 5 Minutes
            $('#requestservicesDatatable').DataTable().ajax.reload()
        }, 300000);

        $(".filter-select").change(function () {
            if($(this).val() == 'all'){
                DTable.column($(this).data('column')).search('').draw();
            }else{
                DTable.column($(this).data('column')).search($(this).val()).draw();
            }
        });
    });
</script>

<script>
	$("input[name='service_status']").click(function () {
     	// alert('hello');
     	if ($('#re-assigneOpen').is(':checked')) {
            $('.re_assign_rowOpen').show();
            $('.re_assign_row_inputOpen').prop('required', true);
            
        }else if ($('#re-assigne').is(':checked')) {
        	// alert('hello');
            $('.re_assign_row').show();
            $('.re_assign_row_input').prop('required', true);
            $('.refer_dep_row').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            $('.re_assign_rowOpen').hide();
            $('.refer_dep_row_input').prop('required', false);

        }else if($('#change_deparment').is(':checked')){
            $('.refer_dep_row').show();
            $('.refer_dep_row_input').prop('required', true);
            $('.re_assign_row').hide();
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            $('.re_assign_row_input').prop('required', false);
        }else {
            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
            $('.re_assign_row_input').val('');
            $('.refer_dep_row').hide();
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            $('.refer_dep_row_input').prop('required', true);
            $('.refer_dep_row_input').val();
        }
    });

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

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/servicemanagement/service_request/index.blade.php ENDPATH**/ ?>