<div class="modal fade" id="Tax_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('Create Tax:')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<form class="kt-form loader" action="{{ route('taxes.store') }}" method="post">
                @csrf
				<div class="modal-body">
					<div class="row">
						<input type="hidden" name="from_user" value="from_user">
						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b>{{ __('Tax Title:*') }}</b></label>
							<input type="text" class="form-control @error('name') is-invalid @enderror"  name="tax_title" required autofocus placeholder="{{ __('Enter Tax Title') }}">
                            @error('tax_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
						</div>
						<div class="form-group validated col-sm-12">
							<label class="form-control-label"><b>{{ __('Tax Percentage:*') }}</b></label>
							<input type="number" min="0" max="100" step="any" class="form-control @error('tax_percentage') is-invalid @enderror"  name="tax_percentage" required autofocus placeholder="{{ __('Enter Tax Percentage') }}">
                            @error('tax_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
						</div>

                        <div class="form-group validated col-sm-12">
                            <label class="form-control-label"><b>{{ __('Select Tax Type:') }}</b></label>
                            <select class="form-control kt-selectpicker @error('tax_type') is-invalid @enderror" name="tax_type" data-live-search="true">
                                <option selected disabled>  {{ __('Select Tax Type ')}}</option>
                                <option value="services"> Services </option>
                                <option value="packages"> Packages </option>
                                <option value="devices"> Devices </option>
                                <option value="other"> Other </option>
                            </select>
                            @error('tax_type')
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