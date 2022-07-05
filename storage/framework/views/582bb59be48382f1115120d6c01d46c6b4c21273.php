<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('Create User Level')); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form class="kt-form loader" action="<?php echo e(route('userlevels.store')); ?>" method="post">
                <?php echo csrf_field(); ?>
				<div class="modal-body">
					<div class="row">

						<input type="hidden" name="from_user" value="from_user">

						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b><?php echo e(__('Name*')); ?></b></label>

							<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="name" value="<?php echo e($usergroup->name ?? old('name')); ?>" required autocomplete="name" autofocus placeholder="<?php echo e(__('Enter User Level')); ?>">

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
</div><?php /**PATH /var/www/royal-app/resources/views/_partial/create_group_modal.blade.php ENDPATH**/ ?>