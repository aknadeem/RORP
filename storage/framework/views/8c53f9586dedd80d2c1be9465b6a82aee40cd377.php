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
                                <a href="<?php echo e(route('complaints.index')); ?>"
                                    class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                                    <?php echo e(__('Complaint')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader"
                        action="<?php echo e(($complaint->id) ? route('complaints.update', $complaint->id ) : route('complaints.store')); ?>"
                        method="post">
                        <?php echo csrf_field(); ?>
                        <?php if($complaint->id): ?>
                        <?php echo method_field('PUT'); ?>
                        <?php endif; ?>
                        <div class="kt-portlet__body pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <div class="form-group validated col-sm-2">
                                        <label class="form-control-label"><b><?php echo e(__('Type*')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['user_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="user_type" required>
                                            <option value="self"> Self </option>
                                            <option value="on_behalf"> On Behaf </option>
                                        </select>
                                        <?php $__errorArgs = ['user_type'];
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

                                    <div class="form-group validated col-sm-5">
                                        <label class="form-control-label"><b><?php echo e(__('POC Name*')); ?></b></label>

                                        <input type="text" class="form-control <?php $__errorArgs = ['poc_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="poc_name" required value="<?php echo e(old('poc_name')); ?>"
                                            placeholder="Enter POC name" />

                                        <?php $__errorArgs = ['poc_name'];
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

                                    <div class="form-group validated col-sm-5">
                                        <label class="form-control-label"><b><?php echo e(__('POC Contact Number*')); ?></b></label>
                                        <input type="text"
                                            class="form-control kt_inputmask_8_1 <?php $__errorArgs = ['poc_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="poc_number" required value="<?php echo e(old('poc_number')); ?>"
                                            placeholder="Enter POC Contat Number" />
                                        <?php $__errorArgs = ['poc_number'];
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
                                        <label class="form-control-label"><b><?php echo e(__('Department Name*')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="department_id" data-live-search="true" required id="DepartmentSelect">
                                            <option value="" selected disabled> Select Department </option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['department_id'];
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
                                        <label class="form-control-label"><b><?php echo e(__('Sub Department*')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['sub_department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            data-live-search="true" name="sub_department_id" required
                                            id="SubDepartmentSelect">
                                            <option value="" selected disabled> Select SubDepartment </option>
                                        </select>
                                        <?php $__errorArgs = ['sub_department_id'];
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
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b><?php echo e(__('Select Quick Complaint')); ?></b></label>
                                        <select
                                            class="form-control kt-selectpicker <?php $__errorArgs = ['reference_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="reference_id" id="QuickComplaintSelect" data-live-search="true">
                                            <option value="" selected disabled> Select Quick Complaint </option>
                                        </select>
                                        <?php $__errorArgs = ['reference_id'];
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
                                        <label class="form-control-label"><b><?php echo e(__(' Complaint Title*')); ?></b></label>
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['complaint_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="complaint_title" value="<?php echo e(old('complaint_title')); ?>"
                                            id="ComplaintTitle" required placeholder="Enter Complaint Title" />
                                        <?php $__errorArgs = ['complaint_title'];
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
                                    <div class="form-group validated col-12">
                                        <label class="form-control-label"><b><?php echo e(__(' Complaint Description*')); ?></b></label>
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['complaint_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="complaint_description" id="ComplaintDescription" required
                                            value="<?php echo e(old('complaint_description')); ?>"
                                            placeholder="Enter Complaint Description" />

                                        <?php $__errorArgs = ['complaint_description'];
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
<?php $__env->startSection('scripts'); ?>
<script>
    var departments = <?php echo json_encode($departments); ?>;

$( "#DepartmentSelect" ).change(function() {
    let Departmentid = parseInt($(this).val())
    var department = departments.find(x => x.id === Departmentid);
    var subdepart_html = '';
    if(department.subdepartments.length > 0){
        subdepart_html='<option value="" Selected disabled> Select Subdepartmentt </option>';
        for (var i = 0; i < department.subdepartments.length; i++) {
                subdepart_html+='<option value='+department.subdepartments[i].id+'>'+department.subdepartments[i].name+'</option>'; 
            }
    }
    $('#SubDepartmentSelect').html(subdepart_html);
    $('.kt-selectpicker').selectpicker("refresh");
    
});
    var QuickComplaints = {}
    $( "#SubDepartmentSelect" ).change(function() {
        let SubDepartmentid = parseInt($(this).val())
        $.ajax({
            type:'GET',
            url:'<?php echo e(url("/quick-complaints/subdepartment/")); ?>/'+SubDepartmentid,
            success:function(data) {
                console.log(data.quick_complaints)
                var html_code = '';
                QuickComplaints = data?.quick_complaints 
                if(data.quick_complaints.length > 0){
                    html_code='<option value="" Selected disabled> Select Quick Complaint </option>';
                    for (var i = 0; i < data.quick_complaints.length; i++) {
                        html_code+='<option value='+data.quick_complaints[i].id+'>'+data.quick_complaints[i].title+'</option>'; 
                    }
                    html_code+='<option value="0">Other</option>'; 
                }
                $('#QuickComplaintSelect').html(html_code);
                $('.kt-selectpicker').selectpicker("refresh");
            }
        }); 
    });

    $( "#QuickComplaintSelect" ).change(function() {
        let quickId = parseInt($(this).val())
        if(quickId > 0){
            let singleComplaint = QuickComplaints.find(x => x.id === quickId)
            $('#ComplaintTitle').val(singleComplaint.title)
            $('#ComplaintTitle').attr('readonly', true);
            $('#ComplaintDescription').val(singleComplaint.detail)
            $('#ComplaintDescription').attr('readonly', true);
        }else{
            $('#ComplaintTitle').val('')
            $('#ComplaintDescription').val('')
            $('#ComplaintTitle').attr('readonly', false);
            $('#ComplaintDescription').attr('readonly', false);
        }
        
    });

</script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/complaints/create.blade.php ENDPATH**/ ?>