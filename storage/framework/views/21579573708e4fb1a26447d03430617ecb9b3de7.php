<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('permissions.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Permissions')); ?></span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"><?php echo e(__($permission->id > 0 ? "edit" : "create")); ?> </span>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <?php echo e(__(($permission->id > 0 ? "Edit" : "Create").' Permission')); ?>

                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="<?php echo e(route('permissions.index')); ?>" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                               <?php echo e(__('Permissions')); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="<?php echo e(($permission->id) ? route('permissions.update', $permission->id ) : route('permissions.store')); ?>" method="post">

                    <?php echo csrf_field(); ?>

                    <?php
                    $exModule = 0;
                        if($permission->id > 0){
                            $exModule = $permission->module->id;
                        }
                    ?>

                    <?php if($permission->id): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row" id="AppendPermissions">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Select Module* </b></label>
                                        <div class="input-group">
                                            <select class="form-control kt-selectpicker <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="module_id" data-live-search="true" required>
                                                    <option selected disabled>  <?php echo e(__('Select Module')); ?></option>
                                                    <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                                    <option value="<?php echo e($module->id); ?>" <?php echo e(($exModule == $module->id) ? 'selected' : ''); ?>><?php echo e($module->slug); ?></option>    
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <option disabled> No module Found </option>
                                                    <?php endif; ?>
                                            </select>
                                            <div class="input-group-append">
                                                <a data-toggle="modal" title="Add Permission" data-target="#kt_modal_1"  class="btn btn-primary">&nbsp;<i class="fa fa-plus" style="color:#fff;"></i></a> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 pt-2">
                                    &nbsp;&nbsp; <label class="pb-2"> <b>Choose Permission </b></label> <br>

                                   &nbsp;&nbsp; <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" value="create" name="title[]"> Create
                                            <span></span>
                                    </label> &nbsp; 
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" value="update" name="title[]"> Update
                                            <span></span>
                                    </label> &nbsp; 
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" value="delete" name="title[]"> Delete
                                            <span></span>
                                    </label> &nbsp; 
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" value="view" name="title[]"> View
                                            <span></span>
                                    </label> &nbsp;
                                </div>
                            </div>

                            <button id="AddNewPermission" class="btn btn-primary btn-sm"><?php echo e(__('Add Permission')); ?></button>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Submit')); ?></button>
                            <a href="<?php echo e(URL::previous()); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('Cancel')); ?></a>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

            <!--end::Portlet-->
        </div>
    </div>
</div>
    <!-- begin:: End Content  -->
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal-popup'); ?>
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('Create Module')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" method="POST" action="<?php echo e(route('modules.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="from_user" value="from_user">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"><b><?php echo e(__('Title*')); ?></b></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="title" required autofocus>

                                <?php $__errorArgs = ['title'];
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
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            <?php echo e(__('Cancel')); ?>

                    </button>

                        <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Submit')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>


<script>
$(document).ready(function () {
    var count = 0;
    $("#AddNewPermission").click(function (e) {
        e.preventDefault(); //stop form submitting
        count = count + 1;

        var html_code = '<div class="col-md-4" id=inputCol'+count+'>'
                        +'<div class="form-group">'+
                            '<label> <b> <?php echo e(__('Title')); ?> </b></label>'+
                            '<div class="input-group">'+
                                '<input type="text" class="form-control" name="title[]" placeholder="Add New  Permission" required>'+
                                '<div class="input-group-append">'+
                                    '<a data-row="inputCol'+count+'" class="btn btn-danger remove_row" title="Remove Permission"> &nbsp; <i class="fa fa-times fa-lg" style="color:#fff;" ></i></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
        $("#AppendPermissions").append(html_code);
    });
    $(document).on("click", ".remove_row", function () {
        var remove_sector = $(this).data("row");
        $("#" + remove_sector).remove();
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/permission/create.blade.php ENDPATH**/ ?>