<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('userlevels.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('UserLevels')); ?></span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"><?php echo e(__($userlevel->id > 0 ? "edit" : "create")); ?> </span>
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
                                <?php echo e(__(($userlevel->id > 0 ? "Edit" : "Create").' User Level')); ?>

                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="<?php echo e(route('userlevels.index')); ?>"
                                    class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
                                    <?php echo e(__('User Levels')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form"
                        action="<?php echo e(($userlevel->id) ? route('userlevels.update', $userlevel->id ) : route('userlevels.store')); ?>"
                        method="post">
                        <?php echo csrf_field(); ?>
                        <?php if($userlevel->id): ?>
                        <?php echo method_field('PUT'); ?>
                        <?php endif; ?>
                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <div class="form-group validated col-sm-12">
                                        <label class="form-control-label" for="inputSuccess1"><b> <?php echo e(__('Title*:')); ?>

                                            </b> </label>

                                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="title" value="<?php echo e($userlevel->title ?? old('title')); ?>" required
                                            autocomplete="title" autofocus
                                            placeholder="<?php echo e(__('Enter User Level Title')); ?>">
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
                                <div class="row">
                                    <table class="table table-striped- table-bordered dtr-inline">
                                        <tr>
                                            <th> <?php echo e(__('Modules')); ?> </th>
                                            <th> <?php echo e(__('Permissions')); ?>

                                                <button type="button" class="btn btn-bold btn-label-brand btn-sm"
                                                    data-toggle="modal" title="Create Permission"
                                                    data-target="#kt_modal_1" style="float: right;"><i
                                                        class="fa fa-plus mb-1"></i><?php echo e(__('Create
                                                    Permission')); ?></button>

                                                
                                                &nbsp; &nbsp;
                                                <input id="checkAll" class="btn btn-primary btn-sm p-1 text-white"
                                                    type="button" title="Check Uncheck All" value="Check All">

                                            </th>
                                        </tr>
                                        <?php $__empty_1 = true; $__currentLoopData = $new_permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="permissionBoxes">
                                            <td>
                                                <h6 class="mt-2 mb-2"><?php echo e($module); ?>&nbsp;&nbsp;
                                                    <span>
                                                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand"
                                                            title="Select Module Permission"
                                                            style="float: right !important;">
                                                            <input type="checkbox" class="moduleSelect"
                                                                module_name="<?php echo e($module); ?>">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </h6>
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success mt-2"
                                                    id="<?php echo e($module); ?>">
                                                    <input type="checkbox" value="<?php echo e($perm->id); ?>" <?php echo e((in_array($perm->id,
                                                    old('permissions', [])) ||
                                                    $userlevel->permissions->contains($perm->id)) ? 'checked' : ''); ?>

                                                    name="permissions[]"> <?php echo e($perm->title); ?>

                                                    <span></span>
                                                </label> &nbsp; &nbsp;
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td> No Permissions Found </td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                <button type="reset" class="btn btn-secondary btn-sm">Cancel</button>
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
<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <?php echo e(__('Create Permission')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form class="kt-form" action="<?php echo e(route('permissions.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="from_level" value="from_level">
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label"> <b><?php echo e(__('Select Module*')); ?> </b></label>
                            <select class="form-control kt-select2 <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="kt_select2" name="module_id" style="width:100%;">
                                <option selected disabled> <b> <?php echo e(__('Select Module')); ?> </b> </option>
                                <?php $__empty_1 = true; $__currentLoopData = $allmodules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($module->id); ?>"><?php echo e($module->slug); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option disabled> No module Found </option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['modules'];
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

                    <div class="row">
                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label" for="title"> <b> <?php echo e(__('Title*')); ?> </b> </label>

                            <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="title"
                                value="<?php echo e($permission->title ?? old('title')); ?>" required autocomplete="name" autofocus
                                placeholder="<?php echo e(__('Enter Permission Title')); ?>">

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
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Submit')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<script>
    $(function() {
        $(document).on('click', '#checkAll', function() {
            if ($(this).val() == 'Check All') {
              $('.permissionBoxes input').prop('checked', true);
              $(this).val('Uncheck All');
            } else {
              $('.permissionBoxes input').prop('checked', false);
              $(this).val('Check All');
            }
        });
        $(document).on('click', '.moduleSelect', function() {
            var module_name = $(this).attr('module_name');

            // alert(module_name);
            if($(this).prop('checked')){
                $('#'+module_name+' input').prop('checked', true);
            }else{
                $('#'+module_name+' input').prop('checked', false);
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/userlevel/create.blade.php ENDPATH**/ ?>