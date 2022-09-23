<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content"> 
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('Fine&Plenty')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('fines.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Fine&Plenties')); ?></span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"><?php echo e(__('Create')); ?></span>
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
                            <?php echo e(__(($fine->id > 0 ? "Edit" : "Create").' Fine&Plenty')); ?>

                        </h3>
                    </div>
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__wrapper">
                            <a href="<?php echo e(route('fines.index')); ?>" class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3" title="Click to View Fine&Planties">
                               <?php echo e(__('Fine&plenties')); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="<?php echo e(($fine->id) ? route('fines.update', $fine->id ) : route('fines.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php if($fine->id > 0): ?>
                        <?php
                            $ex_user = $fine->user_id;
                            $ex_society = $fine->society_id;
                        ?>
                        <?php echo method_field('PUT'); ?>
                    <?php else: ?>
                        <?php
                            $ex_user = 0;
                            $ex_society = 0;
                        ?>
                    <?php endif; ?>
                    <div class="kt-portlet__body pb-0">
                        <div class="kt-section kt-section--first mb-0">
                            <div class="row">
                                 <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b><?php echo e(__('Select Society:*')); ?></b></label>
                                    <select class="form-control kt-selectpicker <?php $__errorArgs = ['society_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="society_id" data-live-search="true"  required>
                                        <option selected disabled value="">  <?php echo e(__('Select Society')); ?></option>
                                       <?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('society_id') || $ex_society ? 'selected' : ''); ?> value="<?php echo e($soc->id); ?>"> <?php echo e($soc->name); ?> [<?php echo e($soc->code); ?>] </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b><?php echo e(__('Title:*')); ?></b></label>
                                   <input type="text" class="form-control" name="title" value="<?php echo e($fine->title ?? old('title')); ?>" placeholder="Enter Fine Title" autofocus="true" required />
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

                                
                                
                                <div class="form-group validated col-sm-6">
                                    <label class="form-control-label"><b><?php echo e(__('Fine Amount:*')); ?></b></label>
                                   <input type="number" step="any" class="form-control" name="fine_amount" value="<?php echo e($fine->fine_amount ?? old('fine_amount')); ?>" placeholder="Enter Fine Amount" />
                                    <?php $__errorArgs = ['fine_amount'];
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

                                

                                

                                <div class="form-group validated col-sm-12">
                                    <label class="form-control-label"><b><?php echo e(__('Desription')); ?></b></label>
                                    <textarea class="form-control summernote" name="description" id="kt_summernote_1"><?php echo $fine->description ?? ''; ?></textarea>

                                    <?php $__errorArgs = ['Description'];
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

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/editors/summernote.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/fines/create.blade.php ENDPATH**/ ?>