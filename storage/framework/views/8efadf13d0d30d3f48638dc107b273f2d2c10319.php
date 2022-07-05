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

	.kt-avatar .kt-avatar__holder {
		width: 70px !important;
		height: 60px !important;
	}
</style>

<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-container kt-container--fluid">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title"><?php echo e(__('ResidentManagement')); ?> </h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<a href="<?php echo e(route('residentdata.index')); ?>"><span class="kt-subheader__desc"><?php echo e(__('Residents')); ?></span></a>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Resident Data')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-resident-management')): ?>
							<a href="<?php echo e(route('residentdata.create')); ?>"
								class="btn btn-brand btn-elevate btn-icon-sm btn-sm" title="Create User">
								<i class="fa fa-plus mb-1"></i>Create </a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="ResidentYajraTable">
					<thead>
						<tr>
							<td class="form-group" colspan="4">
								<label class="form-control-label">Search <b>Society</b></label>
								<select class="form-control filter-select" data-column="3">
									<option selected disabled value=""> Select Society </option>
									<option value="all"> All </option>
									<?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($society->name); ?>"> <?php echo e($society->name); ?> [<?php echo e($society->code); ?>]
									</option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<option value="09"> Pin Verified </option>
								</select>
							</td>
							<td class="form-group" colspan="4">
								<label class="form-control-label">Search <b>Pin Status</b></label>
								<select class="form-control filter-select" data-column="11">
									<option selected disabled> Select Status </option>
									<option value="all"> All </option>
									<option value="1"> Pin Verified </option>
									<option value="0"> Pin Not Verified </option>
							</td>
						</tr>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th> Name </th>
							<th> Society </th>
							<th style="display:none;"> Contact No </th>
							<th style="display:none;"> Email </th>
							<th style="display:none;"> Sector </th>
							<th style="display:none;"> Address </th>
							<th> SMS Pin </th>
							<th> Email Pin </th>
							<th> Otp Status </th>
							<th style="display:none;"> Otp Status </th>
							<th> Membership Id </th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e(++$key); ?></td>
							<td>
								<div class="kt-avatar kt-avatar--outline">
									<a href="<?php echo e(asset('storage/residents/'.$user->image)); ?>" target="_blank">
										<div class="kt-avatar__holder"
											style="<?php if($user->image !=''): ?> background-image: url(<?php echo e(asset('storage/residents/'.$user->image)); ?>); <?php endif; ?>">
										</div>
									</a>
									<label class="kt-avatar__upload OpenResidentImageModal"
										resident_id="<?php echo e($user->id ?? 0); ?>" resident_name="<?php echo e($user->name ?? ''); ?>"
										title="Click to Update Image">
										<i class="fa fa-pen"></i>
									</label>
								</div>
							<td><?php echo e($user->name); ?></td>
							<td><?php echo e($user->society->name); ?></td>
							<td style="display:none;"><?php echo e($user->mobile_number); ?></td>
							<td style="display:none;"><?php echo e($user->email); ?></td>
							<td style="display:none;"><?php echo e($user->sector->sector_name ?? ''); ?></td>
							<td style="display:none;"><?php echo e($user->address); ?></td>
							<td><b><?php echo e($user->m_pin); ?></b></td>
							<td><b class="text-danger"><?php echo e($user->e_pin); ?></b></td>

							<td>
								<?php if($user->pin_verified == 1): ?>
								<a href="#" class="btn btn-brand btn-elevate btn-icon-sm btn-sm"> <i
										class="fa fa-check"></i> Pin Verified </a>
								<?php else: ?>
								<b> Pin is Not Verified </b>
								<?php endif; ?>
							</td>
							<td style="display:none;"><?php echo e($user->pin_verified); ?></td>
							<td><?php echo e($user->membership_id ?? ''); ?></td>
							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-resident-management')): ?>
								<a href="<?php echo e(route('residentdata.edit', $user->id)); ?>" class="text-warning"> <i
										class="fa fa-edit fa-lg" title="Edit Resident"></i> </a> &nbsp;
								<?php endif; ?>

								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-management')): ?>
								<a href="<?php echo e(route('residentdata.show', $user->id)); ?>" class="text-success"> <i
										class="fa fa-eye fa-lg" title="View Detail"></i> </a> &nbsp;
								<?php endif; ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-resident-management')): ?>
								<a href="<?php echo e(route('residentdata.destroy', $user->id)); ?>"
									class="text-danger delete-confirm" del_title="User <?php echo e($user->name); ?>"><i
										class="fa fa-trash-alt fa-lg" title="Delete Resident"></i></a>
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
<?php $__env->startSection('top-styles'); ?>
<?php $__env->startSection('modal-popup'); ?>
<div class="modal fade" id="ResidentImageModal" tabindex="-1" role="dialog" aria-labelledby="ResidentImageModal"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span id="ResidentName"></span>
				</h5> <br>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form method="post" id="ResidentImageForm">
				<?php echo csrf_field(); ?>
				<div class="modal-body pb-0">
					<input type="hidden" name="resident_id" id="ResidentIdModal">
					<div class="row">
						<div class="form-group validated col-8">
							<label class="form-control-label"> <b>Upload Image*:</b></label>
							<input type="file" class="form-control" name="image_file" required>
							
							<div class="invalid-feedback" id="image_file_error"></div>
						</div>

						<div class="col-4 mt-2 img-holder">
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
					<button class="btn btn-brand btn-sm">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<link href="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.css?v=1')); ?>" rel="stylesheet"
	type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script>
	$(".OpenResidentImageModal").click(function(event) {
        var resident_id = parseInt($(this).attr("resident_id"));
        var resident_name = $(this).attr("resident_name") ?? 'Update Image';
		console.log(resident_name);
        $('#ResidentIdModal').val(resident_id);
        $('#ResidentName').html(resident_name);
        $('#ResidentImageModal').modal('show');
    });

	$('#ResidentImageForm').on('submit', function(e) {
		e.preventDefault();
		let residentId = parseInt($('#ResidentIdModal').val());
		let form_type = 'POST'
		let form_url = "<?php echo e(url('/resident-management/residentdata/store-update-image/')); ?>/"+residentId
		$.ajax({
			type: form_type,
			url: form_url,
			data: new FormData(this),
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			beforeSend : function(msg) {
				$('#ResidentImageForm').find('div.invalid-feedback').text('')
			},
			success: function(msg) {
				console.log(msg);
				if(msg?.success == 'no'){
					$.each(msg?.error, function(prefix, val){
						$('#ResidentImageForm').find('div#'+prefix+'_error').text(val[0])
					});
				}else{
					$("#ResidentImageForm").trigger("reset");
					$('#ResidentImageModal').modal('hide');
					// $('#employee-datatable').DataTable().ajax.reload(null, false);
					Swal.fire(
						'Saved',
						msg.message,
						'success'
					)
				}
			}
		});
	});


</script>


<script src="<?php echo e(asset('assets/js/pages/crud/datatables/extensions/buttons.js?v=1')); ?> "></script>
<script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js?v=1')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1.0')); ?>" type="text/javascript">
</script>

<script>
	var DTable =  $('#ResidentYajraTable').DataTable({
    serverside: true,
    processing: true,
    info: true,
    // dom: 'Bfrtip', // not showing length menu
    dom: 'Blfrtip', // with length menu
    "pageLength":10,
    "lengthMenu":[[10,30,50,-1],[10,30,50,"all"]],
    buttons: [
        'copy',
        {
            extend: 'excel',
            title: 'Residents',
            exportOptions: {
                columns: [2,3,4,5,6,7]
            },
        },
        {
            extend: 'pdf',
            title: 'Residents',
            exportOptions: {
                columns: [2,3,4,5,6,7]
            },
        },
        {
            extend: 'print',
            title: 'Residents',
            exportOptions: {
                columns: [2,3,4,5,6,7]
            },
        }
    ],
});

    $(".filter-select").change(function () {
        if($(this).val() == 'all'){
            DTable.column($(this).data('column')).search('').draw();
        }else{
            DTable.column($(this).data('column')).search($(this).val()).draw();
        }
    });
        
        

	//Reset input file in modal
	$('input[type="file"][name="image_file"]').val('');
        //Image preview on upload time
        $('input[type="file"][name="image_file"]').on('change', function(){
            var img_path = $(this)[0].value;
            var img_holder = $('.img-holder');
            var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
            // alert(extension);
            if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                    if(typeof(FileReader) != 'undefined'){
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>', {'src':e.target.result,'class':'','style':'max-width:80%;margin-bottom:1px;'}).appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    }else{
                        $(img_holder).html('This browser does not support FileReader');
                    }
            }else{
                $(img_holder).empty();
            }
        });
        // End Image Preview Code on Modal Form
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/royal-app/resources/views/residentmanagement/residentdata/index.blade.php ENDPATH**/ ?>