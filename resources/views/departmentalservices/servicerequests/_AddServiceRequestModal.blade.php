<div id="DepartServiceRequestModal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="DepartServiceRequestModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Request a Service </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form class="kt-form" method="post" id="ServiceRequestForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="dep_service_id" id="dep_service_id">
                    <div class="row">
                        <div class="form-group validated col-md-6 col-sm-6 col-xs-12 mb-2">
                            <label class="form-control-label" for="DepartmentSelect"> <b> Select Department*
                                </b></label>
                            <select class="form-control kt-selectpicker" name="department_id" id="DepartmentSelect"
                                style="width:100%;" data-live-search="true" required>
                                <option selected disabled value=""> <b>Select Department </b> </option>
                            </select>
                            <div class="invalid-feedback department_id_error" id="quick_subdepE"></div>
                        </div>
                        <div class="form-group validated col-md-6 col-sm-6 col-xs-12 mb-2 pl-0">
                            <label class="form-control-label" for="SubDepartmentSelect"> <b> Select Subdepartment*
                                </b></label>
                            <select class="form-control kt-selectpicker" name="sub_department_id"
                                id="SubDepartmentSelect" style="width:100%;" data-live-search="true">
                                <option selected disabled value=""> <b>Select Subdepartment </b> </option>
                            </select>
                            <div class="invalid-feedback sub_department_id_error" id="quick_subdepE"></div>
                        </div>

                        <div class="form-group validated col-md-12 col-sm-12 col-xs-12 mb-2">
                            <label class="form-control-label" for="ServiceSelect"> <b> Select Service*
                                </b></label>
                            <select class="form-control kt-selectpicker" name="departmental_service_id"
                                id="ServiceSelect" style="width:100%;" data-live-search="true">
                                <option selected disabled value=""> <b>Select Service </b> </option>
                            </select>
                            <div class="invalid-feedback departmental_service_id_error" id="quick_subdepE"></div>
                        </div>

                        <div class="form-group validated col-sm-12 mb-0">
                            <label class="form-control-label"> <b>Description</b></label>

                            <textarea class="form-control summernote" name="description" id="kt_summernote_1"
                                placeholder="Enter Description here... "></textarea>
                            <div class="invalid-feedback description_error" id=""></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{
                        __('Close')}}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@section('bottom_scripts')
<script>
    var services = <?php echo json_encode($services); ?>;
    let dep_id = null
    let sub_dep_id = null
    $(".addQuickComplint").click(function(event) {
        var Service_id = parseInt($(this).attr("Service_id") || 0)
        // $('#addQucikComplaintModal').modal('show')
        $('#DepartServiceRequestModal').modal({backdrop: 'static', keyboard: false})

        getDepartments(dep_id, sub_dep_id)

        $('#ServiceRequestForm').on('submit',function(event){
            event.preventDefault();
            let dep_service_id =  parseInt($('#dep_service_id').val()) || 0

            let form_url = "{{ route('request_depart_service.store') }}"
            let form_type = 'POST'

            $.ajax({
                url: form_url,
                type: form_type,
                cache: false,
                data: $('#ServiceRequestForm').serialize(),
                dataType:'JSON',
                
                beforeSend : function(msg) {
                    $('#ServiceRequestForm').find('div.invalid-feedback').text('')
                },
                success:function(response){
                    console.log(response);
                    if(response?.success == 'no'){
                        $.each(msg?.error, function(prefix, val){
                            $('#ServiceRequestForm').find('span.'+prefix+'_error').text(val[0]);
                        });
                    }else{
                        $("#ServiceRequestForm").trigger("reset");
                        $('#DepartServiceRequestModal').modal('hide');

                        swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "Ok",
                            // closeOnConfirm: true,
                        }).then (() => {
                            location.reload();
                        });

                        // swal({
                        //     title: "Success",
                        //     text: response.message,
                        //     type: "success",
                        //     confirmButtonText: "Ok",
                        //     closeOnConfirm: true
                        // }, function () {
                        //     $('#addQucikComplaintModal').modal('hide');
                        //     location.reload();
                        // });
                    }
                },
                error:function(e){
                    console.log(e);
                },
            });
        });
    });

    function getDepartments(dep_id, sub_dep_id){
        $.get("{{ route('getDeparments')}}", function(res, status){
            var html_code = '';
            var selected = '';
            console.log(res)
            if(res.success == 'yes'){
                html_code ='<option value="">Select department</option>'; 
                for (var i = 0; i < res.departments.length; i++) {
                    if(dep_id == res.departments[i].id){
                        selected = 'selected'
                    }else{
                        selected = ''
                    }
                    html_code+='<option '+selected+' value='+res.departments[i].id+'>'+res.departments[i].name+'</option>'; 
                }
            }else{
                html_code+='<option value="">No Data Found</option>'; 
            }
            $('#DepartmentSelect').html(html_code);
            $('.kt-selectpicker').selectpicker("refresh");
        });

        $("#DepartmentSelect").change(function(event) {
            let departmentId = parseInt($(this).val())
            let uri = "{{ url('/departmental-services/subdepartments')}}/"+departmentId
            $.get(uri, function(res, status){
                var html_code = '';
                console.log(res)
                if(res.success == 'yes'){
                    html_code ='<option value="">Select Subdepartment</option>'; 
                    for (var i = 0; i < res.subdepartments.length; i++) {

                        if(sub_dep_id == res.subdepartments[i].id){
                            selected = 'selected'
                        }else{
                            selected = ''
                        }

                        html_code+='<option '+selected+' value='+res.subdepartments[i].id+'>'+res.subdepartments[i].name+'</option>'; 
                    }
                }else{
                    html_code+='<option value="">No Data Found</option>'; 
                }
                $('#SubDepartmentSelect').html(html_code);
                $('.kt-selectpicker').selectpicker("refresh");
            });

        });

        $("#SubDepartmentSelect").change(function(event) {
            let DepartId = parseInt($('#DepartmentSelect').val())
            let SubdepartId = parseInt($(this).val())

            if(DepartId > 0 && SubdepartId){
                let uri = "{{ url('/departmental-services/') }}"
                uri = uri+'/department/'+DepartId+'/subdepart/'+SubdepartId
                // alert(uri)
                $.get(uri, function(res, status){
                    var html_code = '';
                    console.log(res)
                    if(res.success == 'yes'){
                        html_code ='<option value="">Select Service</option>'; 
                        for (var i = 0; i < res.services.length; i++) {
                            html_code+='<option style="color:#000; font-size:14px;" value='+res.services[i].id+'>Title: '+res.services[i].service_title+' | <span> Charges: </span>'+res.services[i].service_charges+' | <b>Type: </b>'+res.services[i].type_value+'</option>'; 
                        }
                    }else{
                        html_code+='<option value="">No Data Found</option>'; 
                    }
                    $('#ServiceSelect').html(html_code);
                    $('.kt-selectpicker').selectpicker("refresh");
                });
            } 
        });

    }

</script>
<script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
@endsection