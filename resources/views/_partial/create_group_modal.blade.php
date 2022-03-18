<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{ __('Create User Level')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form class="kt-form loader" action="{{ route('userlevels.store') }}" method="post">
                @csrf
				<div class="modal-body">
					<div class="row">

						<input type="hidden" name="from_user" value="from_user">

						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b>{{ __('Name*') }}</b></label>

							<input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" value="{{ $usergroup->name ?? old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Enter User Level') }}">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary btn-sm">{{ __('Submit') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>