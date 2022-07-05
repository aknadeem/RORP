<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('Complaints')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="<?php echo e(route('complaints.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Complaints')); ?></span></a>
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
                            <?php echo e(__(($complaint->id > 0 ? "Edit" : "Create").' Complaint')); ?>

                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="<?php echo e(route('complaints.index')); ?>" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   <?php echo e(__('Complaint')); ?>

        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="<?php echo e(($complaint->id) ? route('complaints.update', $complaint->id ) : route('complaints.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>

                    <?php
                        // $exprovince = 0;

                        // if($complaint->id > 0){
                        //    $exprovince = $complaint->province->id;
                        // }
                    ?>

                    <?php if($complaint->id): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                        	<div class="row">

                            <div class="form-group validated col-sm-2">
                                <label class="form-control-label"><b><?php echo e(__('Type*')); ?></b></label>
                                <select class="form-control kt-select2 <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="kt_select2" name="user_type" style="width:100%;">
                                    <option <?php if($complaint->user_type == 'Self'): ?>
                                        selected 
                                    <?php endif; ?> value="self"> Self </option>
                                    <option <?php if($complaint->user_type == 'on_behalf'): ?>
                                        selected 
                                    <?php endif; ?> value="on_behalf"> On Behaf </option>
                                    
                                </select>
                            </div>

                            <div class="form-group validated col-sm-5">
                                <label class="form-control-label"><b><?php echo e(__('POC Name*')); ?></b></label>
                                    <input type="text" class="form-control" value="<?php echo e($complaint->poc_name); ?>" name="poc_name"/>
                            </div>

                            <div class="form-group validated col-sm-5">
                                <label class="form-control-label"><b><?php echo e(__('POC Contact Number*')); ?></b></label>
                                    <input type="text" class="form-control kt_inputmask_8_1" name="poc_number" value="<?php echo e($complaint->poc_number); ?>" />
                            </div>
                            <?php
                            $disabled = '';

                            $web_user = Auth::guard('web')->user();
                            if($web_user != ''){
                                $user_level_id = $web_user->user_level_id;
                            }else{
                                $api_user = Auth::guard('api')->user();
                                $user_level_id = $api_user->user_level_id;
                            }


                            if($complaint->department->hod){
                               if ($complaint->department->hod->hod->user_level_id == $user_level_id OR $user_level_id < $complaint->department->hod->hod->user_level_id){

                                    $disabled = '';
                                } else{
                                    $disabled = 'disabled';
                                } 
                            }
                                
                            ?>

                            <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b><?php echo e(__('Department Name*')); ?></b></label>
                                    <select <?php echo e($disabled); ?> class="form-control kt-selectpicker <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="department_id" data-live-search="true">

                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <option <?php if($complaint->department_id == $department->id): ?>
                                                selected 
                                            <?php endif; ?> value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
	                            

                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b><?php echo e(__('Sub Department*')); ?></b></label>

                                    <select class="form-control kt-selectpicker <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-live-search="true" name="sub_department_id" >
                                        <?php $__currentLoopData = $subdepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdepartment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <option <?php if($complaint->sub_department_id == $subdepartment->id): ?>
                                                        selected 
                                                    <?php endif; ?> value="<?php echo e($subdepartment->id); ?>"><?php echo e($subdepartment->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>      
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b><?php echo e(__(' Complaint Title*')); ?></b></label>
                                    <input type="text" value="<?php echo e($complaint->complaint_title); ?>" class="form-control" name="complaint_title"/>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b><?php echo e(__(' Complaint Description*')); ?></b></label>
                                    <input type="text" value="<?php echo e($complaint->complaint_description); ?>" class="form-control" name="complaint_description"/>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b><?php echo e(__('Complaint Location*')); ?></b></label>
                                    <input type="text" value="<?php echo e($complaint->complaint_location); ?>" class="form-control" name="complaint_location"/>
                                </div>

	                        </div>

                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Submit')); ?></button>
                            
                            <a href="<?php echo e(URL::previous()); ?>" type="reset"  class="btn btn-secondary btn-sm"><?php echo e(__('Cancel')); ?></a>
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

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>

    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/complaints/edit.blade.php ENDPATH**/ ?>