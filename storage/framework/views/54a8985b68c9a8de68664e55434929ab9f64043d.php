<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">                   
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('Complaints')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('complaints.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Complaints')); ?></span></a>
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
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
						<?php echo e(__('Complaint Refers')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-complaint-management')): ?>
						        
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
							<th><?php echo e(__('ID')); ?></th>
							<th><?php echo e(__('Title')); ?> </th>
							<th><?php echo e(__('Added By')); ?> </th>
							<th><?php echo e(__('Department')); ?> </th>
                            <th><?php echo e(__('Status')); ?> </th>
							<th><?php echo e(__('Action')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $complaint_refers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$refer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php if($refer->complaint): ?>
    						<?php
    							if ($refer->complaint->complaint_status == 'open'){
    								$btncolor = 'btn-danger';
    							}elseif ($refer->complaint->complaint_status == 'in_process') {
    								$btncolor = 'btn-warning';
    							}
    							elseif ($refer->complaint->complaint_status == 'completed') {
    								$btncolor = 'btn-brand';
    							}elseif ($refer->complaint->complaint_status == 'closed') {
    								$btncolor = 'btn-success';
    							}else{
    								$btncolor = 'btn-danger';
    							}
    						?>
							<tr>
                            <td><?php echo e(++$key); ?></td>
                            <td> <a data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand" title="Click to view Detail" href="<?php echo e(route('complaints.show', $refer->complaint->id)); ?>"> <?php echo e($refer->complaint->complaint_title); ?> </a> </td>
                            <td><?php echo e($refer->complaint->user->name); ?> <br> <?php echo e($refer->complaint->user->userlevel->title); ?> </td>
                            <td><?php echo e($refer->complaint->department->name); ?> </td>
                            <td>
                            	<span class="btn <?php echo e($btncolor); ?> btn-sm"><?php echo e($refer->complaint->complaint_status); ?></span>
                            </td>
                            <td>
                            	<div class="btn-group" role="group">
                                <?php if($refer->complaint->complaint_status == 'in_process' OR $refer->complaint->complaint_status == 'open' OR $refer->complaint->complaint_status == 're_assign' ): ?>
                                    <span data-toggle="modal" data-target="#todoWorking" data-target-id="<?php echo e($refer->complaint->id); ?>" complaint_status="<?php echo e($refer->complaint->complaint_status); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                                        <i class="flaticon-edit"></i>
                                    </span>

                                <?php elseif($refer->complaint->complaint_status == 'incorrect' OR $refer->complaint->complaint_status == 'change_deparment' OR $refer->complaint->complaint_status == 'un_satisfied'): ?>
                                <?php if($refer->referto->user_level_id < 5 ): ?>
                                    <span data-toggle="modal" data-target="#close-re-assign" data-target-id="<?php echo e($refer->complaint->id); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update">
                                        <i class="flaticon-edit"></i>
                                    </span>
                                <?php endif; ?>
                                
                                <?php endif; ?>
                                <?php if($refer->referto->user_level_id < 5): ?>
                                        <a href="<?php echo e(route('complaints.destroy',$refer->complaint->id)); ?>" class="btn btn-label-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        <a href="<?php echo e(route('complaintedit',$refer->complaint->id)); ?>" class="btn btn-label-info btn-sm"><i class="fa fa-edit"></i></a>
                                    <?php endif; ?>
								</div>
                            </td>
                        </tr>
                        <?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>

				<!--end: Datatable -->
			</div>
		</div>
	</div>
    <!-- begin:: End Content  -->
</div>

<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Working</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="<?php echo e(route('sup_complaint_update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input class="form-control" name="complaint_id" type="hidden" id="complaint_id" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                            	<label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="in_process" id="pending" name="working_status" required /> 
                                    In Process
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
                                    <input type="radio" id="completed" value="completed" name="working_status" required />Completed
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" id="completed_i" value="incorrect" name="working_status" required />InCorrect
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
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="<?php echo e(route('complaintfeedback')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input class="form-control" name="complaint_id" type="hidden" id="feed_cid" />
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="message-text" class="form-control-label">Feedback:*</label>

                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--success">
                                    <input type="radio" value="satisfied" name="feedback_status" required /> 
                                    Satisfied
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio"  value="no_comment" name="feedback_status" required />No Comments
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--danger">
                                    <input type="radio" value="un_satisfied" name="feedback_status" required />Un Satisfied
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
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="<?php echo e(route('complaintStatusChange')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input class="form-control" name="complaint_id" type="hidden" id="cid" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" value="closed"  name="working_status" required /> 
                                    Close
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigne" name="working_status" required />Re-Assign
                                    <span></span>
                                    </label>
                                <?php
                                    $user_level_id = Auth::user()->user_level_id;
                                ?>

                                <?php if($user_level_id == 4): ?>
                                    <label class="kt-radio kt-radio--solid kt-radio--warning">
                                        <input type="radio" value="change_deparment" id="change_deparment" name="working_status" required /> Forward
                                        <span></span>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row re_assign_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Assign To:*</label>
                            <select class="form-control refer_dep_row_input kt-selectpicker <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="user_id" data-live-search="true">

                                <option selected disabled>Select Supervisor</option>
                                <?php $__currentLoopData = $subdep_sups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdep_sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subdep_sup->supervisor_id); ?>"><?php echo e($subdep_sup->supervisor->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row refer_dep_row" style="display: none;">
                        <div class="form-group validated col-sm-12">
                            <label for="message-text" class="form-control-label">Forward To:*</label>
                            <select class="form-control re_assign_row_input kt-selectpicker <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="department_id" data-live-search="true">

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
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('top-styles'); ?>
    <link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script>
     $("input[name='working_status']").click(function () {
        if ($('#re-assigne').is(':checked')) {
            $('.re_assign_row').show();
            $('.re_assign_row_input').prop('required', true);

            $('.refer_dep_row').hide();
            $('.refer_dep_row_input').prop('required', false);

        }else if($('#change_deparment').is(':checked')){
            $('.refer_dep_row').show();
            $('.refer_dep_row_input').prop('required', true);

            $('.re_assign_row').hide();
            $('.re_assign_row_input').prop('required', false);
        }
        else {
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
        $("#todoWorking").on("show.bs.modal", function (e) {
            var complaint_id = $(e.relatedTarget).data("target-id");
            var complaint_status = $(e.relatedTarget).attr("complaint_status");
            $("#complaint_id").val(complaint_id);
        });
    });
      $(document).ready(function () {
        $("#close-re-assign").on("show.bs.modal", function (e) {
            var cid = $(e.relatedTarget).data("target-id");
            $("#cid").val(cid);
        });
    });
      $(document).ready(function () {
        $("#feedback").on("show.bs.modal", function (e) {
            var feed_cid = $(e.relatedTarget).data("target-id");
            $("#feed_cid").val(feed_cid);
        });
    });
</script>
    <script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/complaints/refers/index.blade.php ENDPATH**/ ?>