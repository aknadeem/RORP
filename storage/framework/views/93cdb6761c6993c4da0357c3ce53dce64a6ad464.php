<?php $__env->startSection('content'); ?>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('UserManagement')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('users.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Users')); ?></span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <span class="kt-subheader__desc"><?php echo e(__($user->id > 0 ? "edit" : "create")); ?> </span>

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
                                <?php echo e(__(($user->id > 0 ? "Edit" : "Create").' User')); ?> afd
                                <small> i.e SuperAdmin, Admin , HOD, Assistent Manager, Supervisor</small>
                            </h3>
                            

                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="<?php echo e(route('users.index')); ?>"
                                    class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                                    <?php echo e(__('Users')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form"
                        action="<?php echo e(($user->id) ? route('users.update', $user->id ) : route('users.store')); ?>"
                        method="post">
                        <?php echo csrf_field(); ?>
                        <?php
                        $exLevel = 0;
                        $exSoc = 0; // socety id, for selecetd option
                        $exSector = 0; // socety id, for selecetd option
                        if($user->id){
                        $exLevel = $user->userlevel->id;
                        $exSoc = $user->society_id;
                        $exSector = $user->society_sector_id;
                        }
                        ?>

                        <?php if($user->id): ?>
                        <?php echo method_field('PUT'); ?>
                        <?php endif; ?>
                        <div class="kt-portlet__body mb-0 pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <input type="hidden" name="user_type" value="admin">

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b><?php echo e(__('Name')); ?></b> *</label>
                                        <input type="text" name="name"
                                            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->name ?? old('name')); ?>" autofocus required
                                            placeholder="<?php echo e(__('Enter Name')); ?>">
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

                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b><?php echo e(__('Email*')); ?></b></label>
                                        <input type="email" name="email"
                                            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->email ?? old('email')); ?>" required
                                            placeholder="<?php echo e(__('Enter Email')); ?>">
                                        <?php $__errorArgs = ['email'];
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
                                        <label class="form-control-label"><b><?php echo e(__('CNIC')); ?></b></label>
                                        <input type="text" name="cnic"
                                            class="form-control kt_inputmask_cnic <?php $__errorArgs = ['cnic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->cnic ?? old('cnic')); ?>" placeholder="00000-0000000-0">
                                        <?php $__errorArgs = ['cnic'];
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
                                        <label class="form-control-label"><b><?php echo e(__('Password*')); ?></b></label>
                                        <input type="password" name="password"
                                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="<?php echo e(__('Enter Password')); ?>">
                                        <?php $__errorArgs = ['password'];
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
                                        <label class="form-control-label" for="contact_no"><b><?php echo e(__('Contact Number*')); ?></b></label>
                                        <input type="text" name="contact_no"
                                            value="<?php echo e($user->contact_no ?? old('contact_no')); ?>"
                                            class="form-control kt_inputmask_8_1 <?php $__errorArgs = ['contact_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required placeholder="<?php echo e(__('Enter Contact Number')); ?>">
                                        <?php $__errorArgs = ['contact_no'];
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
                                        <label class="form-control-label"><b><?php echo e(__('Gender')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="gender" style="width:100%;">
                                            <option selected disabled> Select Gender </option>
                                            <option <?php if($user->gender == 'male'): ?>
                                                selected <?php endif; ?> value="male"> Male </option>
                                            <option <?php if($user->gender == 'female'): ?>
                                                selected <?php endif; ?> value="female"> Female </option>
                                            <option <?php if($user->gender == 'other'): ?>
                                                selected <?php endif; ?> value="other"> Other </option>
                                        </select>
                                        <?php $__errorArgs = ['gender'];
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
                                        <label class="form-control-label"><b><?php echo e(__('Select Society*')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['society_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="society_id" data-live-search="true" id="societySelect">
                                            <option selected disabled> <?php echo e(__('Select Society')); ?></option>
                                            <?php $__empty_1 = true; $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option <?php echo e(($exSoc==$soc->id) ? 'selected' : ''); ?> value="<?php echo e($soc->id); ?>">
                                                <?php echo e($soc->name); ?> </option>
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

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> <b>Select Designation</b></label>
                                            <div class="input-group">
                                                <select
                                                    class="form-control kt-selectpicker <?php $__errorArgs = ['desgination_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="desgination_id" data-live-search="true"
                                                    id="desginationSelect">
                                                    <option selected disabled> <?php echo e(__('Select Desgination')); ?></option>
                                                </select>
                                                <a class="btn btn-primary btn-sm OpenaddTypeModal">OPen some </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> <b><?php echo e(__('Select User Level*')); ?> </b></label>
                                            <div class="input-group">
                                                <select
                                                    class="form-control kt-selectpicker <?php $__errorArgs = ['user_level_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="user_level_id" data-live-search="true" required>
                                                    <option selected disabled> <?php echo e(__('Select UserLevel')); ?></option>

                                                    <?php $__empty_1 = true; $__currentLoopData = $user_levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option <?php if($exLevel==$level->id): ?>
                                                        selected
                                                        <?php endif; ?> value="<?php echo e($level->id); ?>"><?php echo e($level->title); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option disabled> No User level Found </option>
                                                    <?php endif; ?>
                                                </select>
                                                <?php $__errorArgs = ['user_level_id'];
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
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('Submit')); ?></button>
                                <a href="<?php echo e(URL::previous()); ?>" type="reset" class="btn btn-secondary btn-sm"><?php echo e(__('Cancel')); ?></a>
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



<?php echo $__env->make('_partial.add_types_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1')); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>


<script>
    $("#societySelect").change(function(){
            var society_id = parseInt($(this).val());
            var sector_id = <?php  echo json_encode($exSector); ?>;
            console.log(sector_id);
            var sector_html = '';
            var selected = '';
            var societies = <?php echo json_encode($societies); ?>;
            var society = societies.find(x => x.id === society_id);

            console.log(society);
            if(society.sectors.length > 0){
                for (var i = 0; i < society.sectors.length; i++) {
                    console.log(society.sectors[i].sector_name);
                    if(sector_id == society.sectors[i].id){
                        selected = 'selected';
                    }

                    sector_html+='<option value='+society.sectors[i].id+'>'+society.sectors[i].sector_name+'</option>'; 
                }
            }else{
                // console.log("No City Found");
                sector_html='<option> No Sector Found </option>';
            }

            $('#sectorSelect').html(sector_html);
            $('.kt-selectpicker').selectpicker("refresh");
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/usermanagement/user/create.blade.php ENDPATH**/ ?>