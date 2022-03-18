<div class="modal fade" id="SocietiesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="ModalTitle"></span> [Societies]</h5> <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                {{-- <input type="hidden" name="department_id" id="dep_id"> --}}
                <div class="row">
                    <div class="kt-notification-v2 col-md-12" id="dropdownSocieties">
                        No Data Found
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
            </div>
        </div>
    </div>
</div>

@section('bottom_scripts')
    <script>
        $(".openSocietiesModal").click(function(event) {
            // alert('hello');
            var modal_title = $(this).attr("modal_title");
            var source_id = parseInt($(this).attr("source_id"));
            // alert
            $('#SocietiesModal').modal('show');
            $('#ModalTitle').html(modal_title);
            var data_array = <?php echo json_encode($data); ?>;
            console.log(data_array);
            var filter_data = data_array.find(d => d.id === source_id);
            var append_data = "";
            var filter_data_societies = filter_data.societies;
            // console.log(two_four_seven_societies);
            if(filter_data_societies.length > 0){
                for (var i = 0; i < filter_data_societies.length; i++) {
                    append_data += '<a href="#" title="View Profile" class="kt-notification-v2__item" > <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">'+filter_data_societies[i].name[0] +'</span> <div class="kt-notification-v2__itek-wrapper"> <div class="kt-notification-v2__item-title"> '+filter_data_societies[i].name +' </div> <div class="kt-notification-v2__item-desc"> '+filter_data_societies[i].slug +' </div></div></a>';
                }
            }
            if(append_data == ''){
                append_data = '<h6 class="text-danger"> No Societies found </h6>';
            }
            $("#dropdownSocieties").html(append_data);
        });
    </script>
@endsection