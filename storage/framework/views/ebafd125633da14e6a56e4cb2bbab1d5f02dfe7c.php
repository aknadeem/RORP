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
                <h3 class="kt-subheader__title"><?php echo e(__('Complaints')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('complaints.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Societies')); ?></span></a>
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
                        <?php echo e(__('Complaints')); ?>

                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="<?php echo e(route('complaints.create')); ?>"
                                class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create Complaint"><i
                                    class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <table id="ComplaintYajraTable" class="table table-striped table-hover table-checkable kt_table_11">
                    <thead>
                        <tr>
                            <td class="form-group" colspan="4">
                                <label class="form-control-label">Search <b>Society</b></label>
                                <select class="form-control filter-select" data-column="2">
                                    <option selected disabled value=""> Select Society </option>
                                    <option value="all"> All </option>
                                    <?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($society->id); ?>"> <?php echo e($society->name); ?> [<?php echo e($society->code); ?>]
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>

                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Department</b></label>
                                <select data-column="5" class="form-control filter-select">
                                    <option selected disabled> Select Department </option>
                                    <option value="all"> All
                                    </option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cp_department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cp_department->name); ?>"> <?php echo e($cp_department->name); ?> </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Subdepartment</b></label>
                                <select class="form-control filter-select" data-column="6">
                                    <option selected disabled> Select SubDepartment </option>
                                    <option value="all"> All </option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $department->subdepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sub->name); ?>"> <?php echo e($sub->name); ?> </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="form-group" colspan="2">
                                <label class="form-control-label">Search <b>Status</b></label>
                                <select class="form-control filter-select" data-column="7" name="search_status">
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
                            <th>#</th>
                            <th>Code</th>
                            <th> Society </th>
                            <th>Complaint Title </th>
                            <th>Added By </th>
                            <th>Department </th>
                            <th>Sub Department </th>
                            <th>Date </th>
                            <th>Status </th>
                            <th>Actions</th>
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

<div class="modal fade" id="close-re-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="<?php echo e(route('complaintStatusChange')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input class="form-control" name="complaint_id" type="hidden" id="cid" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" value="closed" name="working_status" required />
                                    Close
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigne" name="working_status"
                                        required />Re-Assign
                                    <span></span>
                                </label>
                                <?php
                                $user_level_id = Auth::user()->user_level_id;
                                ?>

                                <?php if($user_level_id < 5): ?> <label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="change_deparment" id="change_deparment"
                                        name="working_status" required /> Forward
                                    <span></span>
                                    </label>
                                    <?php endif; ?>
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
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="todoWorking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complaint Working</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="loader" action="<?php echo e(route('sup_complaint_update')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input class="form-control" name="complaint_id" type="hidden" id="complaint_id" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Status:*</label>
                        <div class="col-9">
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid kt-radio--warning">
                                    <input type="radio" value="in_process" id="in_process" name="working_status"
                                        required />
                                    In Process
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--success" id="completeOption">
                                    <input type="radio" id="completed" value="completed" name="working_status"
                                        required />Completed
                                    <span></span>
                                </label>

                                <label class="kt-radio kt-radio--solid kt-radio--dark">
                                    <input type="radio" id="incorrect" value="incorrect" name="working_status"
                                        required />InCorrect
                                    <span></span>
                                </label>
                                <?php if(Auth::user()->user_level_id < 5): ?> <label
                                    class="kt-radio kt-radio--solid kt-radio--info">
                                    <input type="radio" value="re_assign" id="re-assigneOpen" name="working_status"
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
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
        var DTable =  $('#ComplaintYajraTable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            // "searching": false,
            // "bRetrieve": 'true',
            ajax: "<?php echo e(route('getComplaintsWithRefresh')); ?>",
            // "start": "0",
            // "length": "10",
            "pageLength":10,
            // dom: 'Bfrtip', // not showing length menu
            dom: 'Blfrtip', // with length menu
            "lengthMenu":[[10,30,50,-1],[10,30,50,"all"]],
            columns:[
                // {data:'id', name:'id'}
                {data:'DT_RowIndex'},
                {data:'code'},
                {data:'society_id'},
                {data:'complaint_title'},
                {data:'addedby'},
                {data:'department_id'},
                {data:'sub_department_id'},
                {data:'created_at'},
                {data:'complaint_status'},
                {data:'Actions'},
            ],
            buttons: [
                'copy',
                {
                    extend: 'excel',
                    title: 'Complaints'
                },
                {
                    extend: 'pdf',
                    title: 'Complaints'
                },
                {
                    extend: 'print',
                    title: 'Complaints'
                }
            ],
            columnDefs: [ {
                targets: [1,7],
                className: 'bolded'
                }
            ],
        });
        
        setInterval(function(){
        // this will run after every 5 Minutes
            $('#ComplaintYajraTable').DataTable().ajax.reload()
        }, 300000);

        $(".filter-select").change(function () {
            if($(this).val() == 'all'){
                DTable.column($(this).data('column')).search('').draw();
            }else{
                DTable.column($(this).data('column')).search($(this).val()).draw();
            }
        });
        
        let columnSociety = DTable.column(1);
        columnSociety.visible(false);
    
        $("#todoWorking").on("show.bs.modal", function (e) {
            var complaint_id = $(e.relatedTarget).data("target-id");
            var complaint_status = $(e.relatedTarget).attr("complaint_status");
            $("#complaint_id").val(complaint_id);
        });
    });

     $("input[name='working_status']").click(function () {
         
        //  re-assigneOpen
        if ($('#re-assigneOpen').is(':checked')) {
            $('.re_assign_rowOpen').show();
            $('.re_assign_row_inputOpen').prop('required', true);
            
        }else if($('#in_process').is(':checked')){
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            
        }else if($('#completed').is(':checked')) {
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);
            
        }else{
            $('.re_assign_rowOpen').hide();
            $('.re_assign_row_inputOpen').prop('required', false);  
        }
         
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

    $(document).ready(function () {
        $("#close-re-assign").on("show.bs.modal", function (e) {
            var cid = $(e.relatedTarget).data("target-id");
            $("#cid").val(cid);
        });
    });

</script>

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/ssm_datatable.js?v=1.0')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/complaints/index.blade.php ENDPATH**/ ?>