@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Complaints')}}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <a href="{{ route('complaints.index') }}"><span class="kt-subheader__desc">{{
                        __('Complaints')}}</span></a>
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
                                {{ __(($complaint->id > 0 ? "Edit" : "Create").' Complaint') }}
                            </h3>
                        </div>
                        <div class="kt-subheader__toolbar">
                            <div class="kt-subheader__wrapper">
                                <a href="{{ route('complaints.index') }}"
                                    class="btn btn-brand btn-bold btn-sm kt-margin-l-10 mt-3">
                                    {{ __('Complaint')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form loader"
                        action="{{ ($complaint->id) ? route('complaints.update', $complaint->id ) : route('complaints.store') }}"
                        method="post">
                        @csrf
                        @if($complaint->id)
                        @method('PUT')
                        @endif
                        <div class="kt-portlet__body pb-0">
                            <div class="kt-section kt-section--first mb-0">
                                <div class="row">
                                    <div class="form-group validated col-sm-2">
                                        <label class="form-control-label"><b>{{ __('Type*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('user_type') is-invalid @enderror"
                                            name="user_type" required>
                                            <option value="self"> Self </option>
                                            <option value="on_behalf"> On Behaf </option>
                                        </select>
                                        @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-5">
                                        <label class="form-control-label"><b>{{ __('POC Name*') }}</b></label>

                                        <input type="text" class="form-control @error('poc_name') is-invalid @enderror"
                                            name="poc_name" required value="{{ old('poc_name') }}"
                                            placeholder="Enter POC name" />

                                        @error('poc_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group validated col-sm-5">
                                        <label class="form-control-label"><b>{{ __('POC Contact Number*') }}</b></label>
                                        <input type="text"
                                            class="form-control kt_inputmask_8_1 @error('poc_number') is-invalid @enderror"
                                            name="poc_number" required value="{{ old('poc_number') }}"
                                            placeholder="Enter POC Contat Number" />
                                        @error('poc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Department Name*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('department_id') is-invalid @enderror"
                                            name="department_id" data-live-search="true" required id="DepartmentSelect">
                                            <option value="" selected disabled> Select Department </option>
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Sub Department*') }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('sub_department_id') is-invalid @enderror"
                                            data-live-search="true" name="sub_department_id" required
                                            id="SubDepartmentSelect">
                                            <option value="" selected disabled> Select SubDepartment </option>
                                        </select>
                                        @error('sub_department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __('Select Quick Complaint')
                                                }}</b></label>
                                        <select
                                            class="form-control kt-selectpicker @error('reference_id') is-invalid @enderror"
                                            name="reference_id" id="QuickComplaintSelect" data-live-search="true">
                                            <option value="" selected disabled> Select Quick Complaint </option>
                                        </select>
                                        @error('reference_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-sm-6">
                                        <label class="form-control-label"><b>{{ __(' Complaint Title*') }}</b></label>
                                        <input type="text"
                                            class="form-control @error('complaint_title') is-invalid @enderror"
                                            name="complaint_title" value="{{ old('complaint_title') }}"
                                            id="ComplaintTitle" required placeholder="Enter Complaint Title" />
                                        @error('complaint_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group validated col-12">
                                        <label class="form-control-label"><b>{{ __(' Complaint Description*')
                                                }}</b></label>
                                        <input type="text"
                                            class="form-control @error('complaint_description') is-invalid @enderror"
                                            name="complaint_description" id="ComplaintDescription" required
                                            value="{{ old('complaint_description') }}"
                                            placeholder="Enter Complaint Description" />

                                        @error('complaint_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit')}}</button>

                                <a href="{{URL::previous()}}" type="reset" class="btn btn-secondary btn-sm">{{
                                    __('Cancel')}}</a>
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
@endsection
@section('scripts')
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
            url:'{{ url("/quick-complaints/subdepartment/") }}/'+SubDepartmentid,
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
<script src="{{ asset('assets/js/pages/crud/forms/validation/form-widgets.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js?v=1') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js?v=1') }}" type="text/javascript"></script>
@endsection