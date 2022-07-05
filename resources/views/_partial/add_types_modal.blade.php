<div id="AddTypeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddTypeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog align-center" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title"> Add Designation </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="post" action="{{ route('designation.store') }}">
                    @csrf
                    <div class="row form-group">
                        <div class="col-sm-12 mb-2">
                            <input type="hidden" name="table_name" id="TableName">
                            <label for="TypeName"> Name * </label>
                            <input type="text" placeholder="Enter name" name="name" class="form-control"
                                id="DesignationName" required>
                            <span class="text-danger name_error"></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4 mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{
                                __('Close')}}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('bottom_scripts')
<script>
    $(function() {  
        $('.OpenaddTypeModal').click(function () {
            let form_title = 'Add new';
            $('#AddTypeModal').modal('show');

            $("#TypeForm").trigger("reset");
            $('#TypeForm').find('span.text-danger').text('')

            $("#ModalTitle").html(form_title);
            $('#TypeForm').on('submit', function(e) {
                e.preventDefault();
                let form_type = 'POST'
                let form_url = "{{ route('designation.store') }}"
                
                let naame = $('#DesignationName').val()
                let csrf_tok = "{{ csrf_token() }}"
                console.log(form_url);
                $.ajax({
                    type: form_type,
                    url:  form_url,
                  
                  data: {name: naame, _token: "{{ csrf_token() }}" },
                   cache: false,
                    // data: $('#CustomerForm').serialize(),
                    beforeSend : function(msg) {
                        $('#TypeForm').find('span.text-danger').text('')
                    },
                    success: function(msg) {
                        console.log(msg);
                        if(msg?.success == 'no'){
                            // console.log(msg.error)
                            $.each(msg?.error, function(prefix, val){
                                // console.log(prefix)
                                $('#TypeForm').find('span.'+prefix+'_error').text(val[0]);
                            });

                            // swal.fire({
                            //     title: "Warning",
                            //     text: msg.message,
                            //     icon: "warning",
                            //     confirmButtonText: "Close",
                            // });
                        }else{
                            $("#TypeForm").trigger("reset");
                            $('#AddTypeModal').modal('hide');

                            swal.fire({
                                title: "Success",
                                text: msg.message,
                                icon: "success",
                                confirmButtonText: "Ok",
                                // closeOnConfirm: true,
                            }).then (() => {
                                var selectBox = $('#desginationSelect');
                                var option = new Option(msg?.data?.name, msg?.data?.id, true, true);
                                selectBox.append(option).trigger('change');
                                selectBox.autofocus();
                                // $('.mySelect').select2();
                                // location.reload();
                            });
                        }
                    }
                });
            });
        });
    });
</script>
@endsection