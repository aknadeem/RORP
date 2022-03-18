<div class="modal fade" id="AddAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Upload Attachment </h5> <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{$upload_url}}" method="post" enctype="multipart/form-data" class="loader">
            	@csrf
            <div class="modal-body">
                <div class="row">
                    
                    <div class="form-group validated col-sm-12">
                        <label class="form-control-label"><b>{{ __('Title*') }}</b></label>
                        <input type="text" name="title" class="form-control" required autofocus placeholder="Enter Title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                	<div class="form-group validated col-sm-12">
                        <label class="form-control-label"><b>{{ __('Select Societies*') }}</b></label>
                        <select class="form-control kt-selectpicker" name="societies[]" multiple data-live-search="true">
                            <option disabled> <b> Select Societies </b></option>
                            @forelse ($societies as $society)
                            	<option value="{{$society->id}}"> <b> {{$society->name}} </b></option>
                            @empty
                            	<option> <b> No Society Found </b></option>
                            @endforelse
                        </select>
                        @error('social_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-control-label"><b>{{ __('Upload Attachment') }}</b></label>
                            <input type="file" name="attachment" class="form-control" accept="application/pdf" required>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                <button type="submit" class="btn btn-brand btn-sm">{{ __('save')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>

@section('bottom_scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>

    <script>
        $("#addSopAttachment").click(function() {
            $('#AddAttachmentModal').modal('show');
        });
    </script>
@endsection