<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('ResidentManagement')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="<?php echo e(route('users.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Resident')); ?></span></a>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"><?php echo e(__('detail')); ?></span>

            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Begin::App-->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <!--Begin:: App Aside Mobile Toggle-->
            <button class="kt-app__aside-close" id="kt_user_profile_aside_close">
                <i class="la la-close"></i>
            </button>
            <!--End:: App Aside Mobile Toggle-->
            <!--Begin:: App Aside-->
            <div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">
                <!--begin:: Widgets/Applications/User/Profile1-->
                <div class="kt-portlet ">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit-y">
                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-1">
                            <div class="kt-widget__head">
                                <div class="kt-widget__media">
                                    <?php if($residentdata->image !=''): ?>
                                    <img src="<?php echo e(asset('storage/residents/'.$residentdata->image)); ?>" alt="image"
                                        style="border-radius: 10%;">
                                    <?php else: ?>
                                    <img src="<?php echo e(url('assets/media/users/default.jpg?v=1')); ?>" alt="image"
                                        style="border-radius: 10%;">
                                    <?php endif; ?>
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__section">
                                        <a href="#" class="kt-widget__username">
                                            <?php echo e($residentdata->name ?? ''); ?>


                                            <?php if($residentdata->is_account): ?>
                                            <i title="Account is created"
                                                class="flaticon2-correct kt-font-success "></i>
                                            <?php else: ?>
                                            <i title="Account not created yet!"
                                                class="flaticon2-warning kt-font-danger "></i>
                                            <?php endif; ?>

                                        </a>
                                        <span class="kt-widget__subtitle">
                                            <?php echo e($residentdata->type ?? ''); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__body">
                                <div class="kt-widget__content" style="text-align: left !important;">
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Father Name:</span>
                                        <a href="#" class="kt-widget__data "><?php echo e($residentdata->father_name ?? ''); ?></a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">CNIC Number: </span>
                                        <a href="#" class="kt-widget__data "><?php echo e($residentdata->cnic ?? ''); ?></a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Email:</span>
                                        <a href="#" class="kt-widget__data "><?php echo e($residentdata->email ?? ''); ?></a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Phone:</span>
                                        <a href="#" class="kt-widget__data text-left"><?php echo e($residentdata->mobile_number ??
                                            '-'); ?></a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Society:</span>
                                        <span class="kt-widget__data"> <?php echo e($residentdata->society->name ?? ''); ?> </span>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Membership id:</span>
                                        <span class="kt-widget__data"><b><?php echo e($residentdata->membership_id ??
                                                ''); ?></b></span>
                                    </div>
                                </div>
                                <div class="kt-widget__items">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-management')): ?>
                                    <a href="#" class="kt-widget__item kt-widget__item" id="viewTenantsModal"
                                        style="background:#eee !important;">
                                        <span class="kt-widget__section">
                                            <span class="kt-widget__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path
                                                            d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z"
                                                            fill="#000000" opacity="0.3" />
                                                        <path
                                                            d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z"
                                                            fill="#000000" opacity="0.3" />
                                                        <path
                                                            d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z"
                                                            fill="#000000" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="kt-widget__desc">
                                                Resident Detail
                                            </span>
                                        </span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Widget -->
                    </div>
                </div>
                <!--end:: Widgets/Applications/User/Profile1-->
            </div>
            <!--End:: App Aside-->
            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">Personal Information </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <div class="kt-portlet__head-wrapper">
                                    </div>
                                </div>
                            </div>
                            <form class="kt-form kt-form--label-left">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <!--<form class="kt-form kt-form--label-left" enctype="multipart/form-data"  method="POST" action="#">-->
                                <div class="kt-portlet__body pb-0">
                                    <div class="kt-section kt-section--first">
                                        <div class="kt-section__body pb-0">
                                            <!--<div class="form-group row">-->
                                            <!--	<label class="col-xl-3 col-lg-3 col-form-label">Profile Image </label>-->
                                            <!--	<div class="col-lg-9 col-xl-6">-->
                                            <!--		<div class="kt-avatar kt-avatar--outline" id="kt_user_avatar"> -->
                                            <!--			<div class="kt-avatar__holder" style="background-image: url(<?php echo e(asset('assets/media/users/default.jpg?v=1')); ?>)"></div>-->
                                            <!--			<label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">-->
                                            <!--				<i class="fa fa-pen"></i>-->
                                            <!--				<input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">-->
                                            <!--			</label>-->
                                            <!--			<span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">-->
                                            <!--				<i class="fa fa-times"></i>-->
                                            <!--			</span>-->
                                            <!--		</div>-->
                                            <!--	</div>-->
                                            <!--</div>-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input name="name" class="form-control" type="text" readonly
                                                        disabled value="<?php echo e($residentdata->name); ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span
                                                                class="input-group-text"><i
                                                                    class="la la-phone"></i></span></div>
                                                        <input type="text" class="form-control" readonly disabled
                                                            name="contact_no" value="<?php echo e($residentdata->mobile_number); ?>"
                                                            placeholder="Phone" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span
                                                                class="input-group-text"><i class="la la-at"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="email" readonly
                                                            disabled value="<?php echo e($residentdata->email); ?>" readonly
                                                            placeholder="Email" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Address</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input name="address" class="form-control" type="text" readonly
                                                        disabled placeholder="Enter Address"
                                                        value="<?php echo e($residentdata->address); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions kt-hide">
                                        <div class="row">
                                            <div class="col-lg-8 col-xl-8">
                                            </div>
                                            <div class="col-lg-4 col-xl-4">
                                                <button type="reset"
                                                    class="btn btn-primary btn-sm">Update</button>&nbsp;
                                                <button type="reset" class="btn btn-secondary btn-sm">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--End:: App Content-->
        </div>
        <!--End::App-->
    </div>
    <!-- begin:: End Content  -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal-popup'); ?>
<?php if($residentdata !=''): ?>
<?php echo $__env->make('usermanagement.user.profile._viewResidentInfo', ['resident' => $residentdata], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/residentmanagement/residentdata/residentdetail.blade.php ENDPATH**/ ?>