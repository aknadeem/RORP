<div id="addDepartServiceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addDepartServiceModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add Service: <span class="h6 text-muted">for Departmental Service Requests</span><span
                        id="DepTitle"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form class="kt-form" method="post" id="addDepartmentServiceForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="HiddenMethod" name="_method" value="POST">
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
                            <label class="form-control-label" for="ServiceTitle"> <b>Title*</b></label>
                            <input type="text" class="form-control" name="service_title" id="ServiceTitle"
                                placeholder="Enter Serivce Title">
                            <div class="invalid-feedback service_title_error"></div>
                        </div>

                        <div class="form-group validated col-md-6 col-sm-6 col-xs-12 mb-2">
                            <label class="form-control-label" for="ServiceCharges"><b>Charges*</b></label>
                            <input type="number" step="any" min="0" class="form-control" name="service_charges"
                                id="ServiceCharges" placeholder="Enter Service Charges">
                            <div class="invalid-feedback service_charges_error" id=""></div>
                        </div>

                        <div class="form-group validated col-md-6 col-sm-6 col-xs-12 mb-2  pl-0">
                            <label class="form-control-label" id="ChargesTypeSelect"> <b> Select Charges Type*
                                </b></label>
                            <select class="form-control kt-selectpicker" name="charges_type" id="ChargesTypeSelect"
                                style="width:100%;" data-live-search="true" required>
                                <option disabled value=""> <b>Select Type </b> </option>
                                @forelse (\App\Helpers\Constant::CHARGES_TYPE as $key=>$value)
                                <option value="{{$value}}">{{$key}}</option>
                                @empty
                                <option value="">No option found</option>
                                @endforelse
                            </select>
                            <div class="invalid-feedback charges_type_error"></div>
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
        $('#addDepartServiceModal').modal({backdrop: 'static', keyboard: false})

        if(Service_id > 0){
            var find_service = services?.find(x => x.id === Service_id)
            console.log(find_service?.service_title)
            $('#dep_service_id').val(Service_id)
            dep_id = find_service?.department?.id
            sub_dep_id = find_service?.subdepartment?.id
            $('#ServiceTitle').val(find_service?.service_title)
            $('#ServiceCharges').val(find_service?.service_charges)
            // $('#ChargesTypeSelect').val(find_service?.charges_type)
            // $('.kt-selectpicker').selectpicker("refresh");
            // $("#ChargesTypeSelect").attr("selected", false);
            $("#ChargesTypeSelect option[value='"+find_service?.charges_type+"']").attr("selected","selected");
            $('#kt_summernote_1').summernote('editor.pasteHTML', find_service?.description);
        }

        getDepartments(dep_id, sub_dep_id)

        $('#addDepartmentServiceForm').on('submit',function(event){
            event.preventDefault();
            let dep_service_id =  parseInt($('#dep_service_id').val()) || 0
            var form_url = ''
            var form_type = ''

            if(dep_service_id > 0){
                form_url = "{{ url('/departmental-services/update-service') }}/"+dep_service_id
                form_type = 'PUT'
            }else{
                form_url = "{{ route('depart_services.store') }}"
                form_type = 'POST'
            }

            $('#HiddenMethod').val(form_type);
            $('#addDepartmentServiceForm').attr('action', form_url);
            // alert(form_url)

            $.ajax({
                url: form_url,
                type: form_type,
                cache: false,
                data: $('#addDepartmentServiceForm').serialize(),
                dataType:'JSON',
                
                beforeSend : function(msg) {
                    $('#addDepartmentServiceForm').find('div.invalid-feedback').text('')
                },
                success:function(response){
                    console.log(response);
                    if(response?.success == 'no'){
                        $.each(msg?.error, function(prefix, val){
                            $('#addDepartmentServiceForm').find('span.'+prefix+'_error').text(val[0]);
                        });
                    }else{
                        $("#addDepartmentServiceForm").trigger("reset");
                        $('#addDepartServiceModal').modal('hide');

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
    }

</script>
<script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js?v=1') }}" type="text/javascript"></script>
@endsection