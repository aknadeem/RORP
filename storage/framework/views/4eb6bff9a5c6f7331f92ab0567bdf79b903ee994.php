<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                       
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('Department')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc"><?php echo e(__(($department->id > 0 ? "Edit" : "Create"))); ?></span>
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
                            <?php echo e(__(($department->id > 0 ? "Edit" : "Create").' Department')); ?>

                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-brand btn-sm btn-bold kt-margin-l-10 mt-3">
        	                   <?php echo e(__('Departments')); ?>

        	                </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form loader" action="<?php echo e(($department->id) ? route('departments.update', $department->id ) : route('departments.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>

                    <?php
                        $exSoc = 0;
                            if($department->id){
                                $exSoc = $department->society_id;
                            }
                        ?>

                    <?php if($department->id): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first">
                        	<div class="row">

                                <div class="form-group validated col-sm-4">
                                    <label class="form-control-label"><b><?php echo e(__('Select Society*')); ?></b></label>
                                    <select class="form-control kt-selectpicker <?php $__errorArgs = ['society_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="society_id" data-live-search="true">
                                        <option selected disabled value="">  <?php echo e(__('Select Society')); ?></option>

                                        <?php $__empty_1 = true; $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($soc->id); ?>" <?php echo e(($exSoc == $soc->id) ? 'selected' : ''); ?>><?php echo e($soc->name); ?></option>  
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <option disabled> No Society Found </option>
                                        <?php endif; ?>
                                    </select>

                                    <?php $__errorArgs = ['society_id'];
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

                                <div class="form-group col-sm-3 pt-2">
                                    &nbsp;&nbsp; <label class="pb-2"> <b> Department For </b></label> <br>

                                   &nbsp;&nbsp; <label class="kt-checkbox kt-checkbox--bold kt-checkbox--primary">
                                        <input type="checkbox" <?php if($department->is_complaint): ?> checked <?php endif; ?>  value="1" name="is_complaint"> Complaint
                                            <span></span>
                                    </label> &nbsp; 
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--primary">
                                        <input type="checkbox" <?php if($department->is_service): ?> checked <?php endif; ?>  value="1" name="is_service"> Service
                                            <span></span>
                                    </label> &nbsp; 
                                </div>

	                            <div class="form-group validated col-sm-5">
									<label class="form-control-label"><b><?php echo e(__('Name*')); ?></b></label>
									<input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="name" value="<?php echo e($department->name ?? old('name')); ?>" required autocomplete="name" autofocus placeholder="<?php echo e(__('Enter Department Name')); ?>">

                                    <?php $__errorArgs = ['name'];
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
<?php $__env->startSection('modal-popup'); ?>
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title"><?php echo e(__('Create New Society')); ?></h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				</button>
    			</div>
                <form class="kt-form loader" method="POST" action="<?php echo e(route('departments.store')); ?>">
                    <?php echo csrf_field(); ?>
    				<div class="modal-body">
    					<div class="row">
                            <div class="form-group validated col-sm-12">
    							<label class="form-control-label"><?php echo e(__('Name*')); ?></label>
    							<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" required autofocus>

    							<?php $__errorArgs = ['name'];
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

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/societymanagement/departments/create.blade.php ENDPATH**/ ?>