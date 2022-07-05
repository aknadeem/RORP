<?php $__env->startSection('content'); ?>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">         
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title"><?php echo e(__('Department')); ?></h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                <a href="<?php echo e(route('departments.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('departments')); ?></span></a>
                
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch" />
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon2-search-1"></i></span>
                    </span>
                </div>
            </div>

            <div class="kt-subheader__toolbar">
                <a href="<?php echo e(URL::previous()); ?>" class="btn btn-default btn-bold">
                    Back </a>  &nbsp;
            </div>
        </div>
    </div>
    <?php
        // filter department from departments array
        $search_society_id = request()->search_society_id;
        if($search_society_id !='all' AND $search_society_id !=''){
            $departments = $departments->where('society_id',$search_society_id);
        }else{
            $departments = $departments;
        }
    ?>

    <!-- end:: Content Head -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <form action="" method="get">
            <div class="alert alert-light alert-elevate" role="alert">
                <div class="col-md-2"></div>
                <div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
                <div class="form-group validated col-sm-6 ">
                    <label class="form-control-label"><b></b></label>
                    <select class="form-control kt-selectpicker" name="search_society_id" data-live-search="true" required>
                        <option selected disabled>  <?php echo e(__('Select Society')); ?></option>
                        <option <?php echo e(($search_society_id == 'all') ? 'selected' : ''); ?> value="all">  <?php echo e(__('All Departments')); ?></option>
                        <?php $__empty_1 = true; $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option <?php echo e(($search_society_id == $soc->id) ? 'selected' : ''); ?> value="<?php echo e($soc->id); ?>"><?php echo e($soc->name); ?></option>  
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option disabled> No Society Found </option>
                        <?php endif; ?>
                    </select>

                    <?php $__errorArgs = ['search_society_id'];
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
                <div class="kt-section__content kt-section__content--solid mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                </div>
            </div>
        </form>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Departments')); ?>

					</h3>

                    
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-departments')): ?>
							<a href="<?php echo e(route('departments.create')); ?>" class="btn btn-brand btn-elevate btn-sm btn-icon-sm" title="Create Department"><i class="fa fa-plus mb-1"></i><?php echo e(__('Create')); ?></a>
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
                            <th><?php echo e(__('ID')); ?> <?php echo e($search_society_id); ?></th>
                            <th><?php echo e(__('Society')); ?></th>
                            <th><?php echo e(__('Department Name')); ?> </th>
                            <th><?php echo e(__('Head Of Department(HOD)')); ?> </th>
                            <th><?php echo e(__('Sub Departments')); ?> </th>
                            <th><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($dep->id); ?></td>
                                <td><?php echo e(__($dep->society->name)); ?></td>
                                <td><?php echo e(__($dep->name)); ?></td>
                                <td>

                                    <?php if(!$dep->hod): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-departments')): ?>
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addHodModal" title="Add Hod" dep_title="<?php echo e(__($dep->name)); ?>" dep_id="<?php echo e($dep->id); ?>"><i class="fa fa-plus mb-1"></i>Add</button>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-bold btn-label-brand btn-sm viewHodModal" title="View Hod's" dep_title="<?php echo e(__($dep->name)); ?>" dep_id="<?php echo e($dep->id); ?>"><i class="fa fa-eye mb-1"></i>view </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-society-management')): ?>
                                        <button type="button" class="btn btn-bold btn-label-success btn-sm addSubdepartmentModal" title="Add Subdepartment" dep_title="<?php echo e(__($dep->name)); ?>" dep_id="<?php echo e($dep->id); ?>"><i class="fa fa-plus mb-1"></i>Add</button>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-society-management')): ?>
                                        <?php if($dep->subdepartments->count() > 0): ?>
                                            <button type="button" class="btn btn-bold btn-label-brand btn-sm viewSubdepartments" title="View Subdepartments" dep_title="<?php echo e(__($dep->name)); ?>" dep_id="<?php echo e($dep->id); ?>"><i class="fa fa-eye mb-1"></i>view </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update-departments')): ?>
                                    <a href="<?php echo e(route('departments.edit', $dep->id)); ?>" class="text-warning"> <i class="fa fa-edit fa-lg" title="Edit department"></i> </a> &nbsp;
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-departments')): ?>
                                    <!--<a href="<?php echo e(route('departments.destroy', $dep->id)); ?>" class="text-danger delete-confirm" del_title="Department <?php echo e($dep->name); ?>"><i class="fa fa-trash-alt fa-lg" title="<?php echo e(__('Delete department')); ?>"></i></a>-->
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
				<!--end: Datatable -->
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
                    <h5 class="modal-title">
                    <?php echo e(__('Add HOD')); ?> <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="<?php echo e(route('department.addhod')); ?>"  method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                    	<input type="hidden" name="department_id" id="dep_id">
                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b><?php echo e(__('Department')); ?> </b></label>
                                <input type="text" class="form-control" name="department" readonly id="dep_name" disabled>
                                <?php $__errorArgs = ['hod_id	'];
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
                                <label class="form-control-label"> <b><?php echo e(__('Select HOD*')); ?> </b></label>
                                <select class="form-control kt-select2 <?php $__errorArgs = ['module_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="kt_select2" name="hod_id" style="width:100%;">
                                    <option selected disabled> <b> <?php echo e(__('Select HOD')); ?> </b> </option>
                                    <?php $__empty_1 = true; $__currentLoopData = $hods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    	<option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option disabled> No HOD Found </option>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['hod_id	'];
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
    <div class="modal fade" id="kt_modal_loadData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="DepTitleHod"></span> HOD'S </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_widget4_tab1_content">
                                        <div class="kt-widget4" id="HodNewDesign">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSubdepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    <?php echo e(__('Add Subdepartment')); ?> <span id="DepTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form class="kt-form" action="<?php echo e(route('subdepartments.store')); ?>"  method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b><?php echo e(__('Select Department*')); ?> </b></label>
                                <select class="form-control kt-selectpicker <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="kt_select2_1" data-live-search="true" required name="department_id">
                                    <option selected disabled> <b> <?php echo e(__('Select Department')); ?> </b> </option>
                                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option disabled> No department Found </option>
                                    <?php endif; ?>
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
                        </div>

                        <div class="row">
                            <div class="form-group validated col-sm-12">
                                <label class="form-control-label"> <b><?php echo e(__('SubDepartment Name*')); ?> </b></label>
                                <input type="text" placeholder="<?php echo e(__('Enter Sub Department Name')); ?>" class="form-control" name="name" required>
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
    <div class="modal fade" id="SubdepartmentData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="MainDepTitleHod"></span> Sub Departments </h5> <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="kt-notification-v2 col-md-12" id="dropdownSubDepartments">
                            <a href="/userprofile" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> t</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> test title </div> <div class="kt-notification-v2__item-desc"> level title</div></div></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('top-styles'); ?>
    <link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('js/ssm_datatable.js?v=1')); ?>" type="text/javascript"></script>

<script>
	$(".addHodModal").click(function(event) {
        var dep_title = $(this).attr("dep_title");
        var dep_id = $(this).attr("dep_id");
        $('#kt_modal_1').modal('show');
        $('#dep_name').val(dep_title);
        $('#dep_id').val(dep_id);
    });

    $(".addSubdepartmentModal").click(function(event) {
        var dep_title = $(this).attr("dep_title");
        var dep_id = $(this).attr("dep_id");
        $('#addSubdepartmentModal').modal('show');
        // $('#dep_name').val(dep_title);
        // $('#dep_id').val(dep_id);
    });

    $(".viewHodModal").click(function(event) {
    var vdep_title = $(this).attr("dep_title");
    var dep_id = $(this).attr("dep_id");
    // alert
    $('#kt_modal_loadData').modal('show');
    $('#MainDepTitleHod ').html(vdep_title);
    // $('#dep_id').val(dep_id);
        $.ajax({
            type: "GET",
            contentType: "application/json",
            url: "<?php echo e(url('department/get-hods/')); ?>/"+dep_id,
            // data: JSON.stringify(obj1),
            dataType: "json",
            success: function (data) {
                var append_data = "";
                var new_data = "";
                console.log(data);
                //alert(data1.d);
                var jsonData = data['hods'];
                // console.log(jsonData);
                for (var i = 0; i < jsonData.length; i++) {
                    append_data +='<div class="kt-widget4__item">'+  
                        '<div class="kt-widget4__info">'+
                            '<a class="kt-widget4__username">'+
                                jsonData[i].hod.name +
                            '</a>'+
                            '<p class="kt-widget4__text">'+
                                jsonData[i].hod.userlevel.title+
                            '</p>'+
                        '</div>'+
                        '<a href="/user-management/hod/deattach-department/'+jsonData[i].department_id+'/user/'+jsonData[i].hod_id+'" title="Remove Hod From This Department" class="btn btn-bold btn-label-danger btn-sm"><i class="fa fa-trash"></i> Remove </a>'+
                    '</div>';
                }
                if(append_data == ''){
                    append_data = '<h6 class="text-danger"> No HOD Found </h6>';
                }
                // $("#dropdownHods").append(append_data);
                $("#HodNewDesign").html(append_data);
            }
        });
   });

    $(".viewSubdepartments").click(function(event) {
        // alert('hello');
    var vdep_title = $(this).attr("dep_title");
    var dep_id = parseInt($(this).attr("dep_id"));
    // alert
    $('#SubdepartmentData').modal('show');
    $('#DepTitleHod ').html(vdep_title);
    // $('#dep_id').val(dep_id);
    // alert(dep_id);
    var departments = <?php echo json_encode($departments); ?>;
    var single_department = departments.find(d => d.id === dep_id);
    var append_data = "";
    var subdeparments = single_department.subdepartments;
    // console.log(single_department);
        if(subdeparments.length > 0){
            for (var i = 0; i < subdeparments.length; i++) {

                append_data += '<a href="/userprofile" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">'+subdeparments[i].name[0] +'</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> '+subdeparments[i].name +' </div> <div class="kt-notification-v2__item-desc"> '+subdeparments[i].slug +' </div></div></a>';
            }
            
            if(append_data == ''){
                append_data = '<h6 class="text-danger"> No Subdeparments found </h6>';
            }
        }
        $("#dropdownSubDepartments").html(append_data);
    });
</script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/societymanagement/departments/index.blade.php ENDPATH**/ ?>