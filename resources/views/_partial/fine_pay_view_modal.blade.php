<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Add Payment </h5>
                <button type="button" class="close paymentModel" aria-label="Close"></button>
            </div>
            <form lass="loader" action="{{ route('fine.payment') }}" method="POST" id="PckgServiceModelForm">
                @csrf
                <input name="user_id" type="hidden" id="user_id" />
                <input name="fine_id" type="hidden" id="invoice_id" />
                <input name="imposed_id" type="hidden" id="imposedId" />
                <input name="invoice_price" type="hidden" id="invoice_price" />
                <div class="modal-body">
                    <div class="form-group row">
                    	<div class="col-lg-12">
                            <label class="form-control-label"><b>{{ __('Resident:') }}</b></label>
                            <div class="input-group date">
                                <input type="text" id="resident_name" readonly disabled class="form-control">
                            </div>
                        </div>
                        <span id="AmtError" class="text-danger"></span><br>
                    </div>
                    
                    <div class="row">
	                    <div class="col-lg-6">
                            <label class="form-control-label"><b>{{ __('Payment Date:*') }}</b></label>
                            <div class="input-group date">

								@php
									$now_date = Carbon\Carbon::now()->format('Y-m-d');
								@endphp
                                <input type="text" name="paid_date" required class=" kt_datepicker_validate form-control @error('payed_date') is-invalid @enderror" value="{{$now_date}}" placeholder="Select date" style="border-radius: 3px;">
                                
                                @error('payed_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                        
						<div class="col-lg-6">
                            <label class="form-control-label"><b>{{ __('Amount:*') }}</b></label>
                            <div class="input-group date">
                                <input type="number" step="any" min="0" max="" required id="payed_amount" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" placeholder="Enter Amount">
                                
                                @error('paid_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 mt-2">
                        <label for="message-text" class="form-control-label"> <b> Remarks:* </b></label>
                        <textarea class="form-control" name="remarks" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm paymentModel">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
	
<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                Fine Detail </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
                </button>
            </div>
			<div class="modal-body kt-portlet mb-0 pb-0">
				<div class="kt-scroll ps ps--active-y kt-portlet__body" data-scroll="true" data-height="320" style="height: 320px; overflow: hidden;">
					<!--begin::Widget -->
					<div class="kt-widget kt-widget--user-profile-2">
						<div class="kt-widget__head pt-4">
							<div class="kt-widget__media">
								{{-- <img class="kt-widget__img" src="assets/media/users/300_19.jpg" alt="image"> --}}
								<img class="kt-widget__img" src="{{url('assets/media/users/default.jpg')}}" alt="image" style="border-radius: 20%;">

								{{-- <div class="kt-widget__pic kt-widget__pic--success kt-font-success kt-font-boldest">
									MP
								</div> --}}
							</div>
							<div class="kt-widget__info">
								<span class="kt-widget__username" id="ResidentName_D">
								</span>
								<span class="kt-widget__desc" id="ResidentUid_D">
								</span>
							</div>
						</div>
						<div class="kt-widget__body">
							<div class="kt-widget__item">
								<hr>
								<div class="kt-widget__contact">
									<span class="kt-widget__label">Fine Amount:</span>
									<h5 class="kt-widget__data" id="FineAmount_D">Rs: {{ number_format($fine->fine_amount ?? 0, 0)}}</h5>
								</div>
								<div class="kt-widget__contact">
									<span class="kt-widget__label">Fine Date:</span>
									<b class="kt-widget__data text-success" id="FineDate_D"></b>
								</div>
								<div class="kt-widget__contact">
									<span class="kt-widget__label">Due Date:</span>
									<b  class="kt-widget__data text-danger" id="DueDate_D"></b>
								</div>
								<div class="kt-widget__contact">
									<span class="kt-widget__label">Fine By:</span>
									<span class="kt-widget__data" id="FineBy_D"></span>
								</div>
								<div class="kt-widget__contact">
									<span class="kt-widget__label">Status:</span>
									<span><span class="btn btn-bold btn-sm btn-label-success" id="FineStatus_D"></span></span>

								</div>
							</div>
							<hr/>
							<div class="kt-widget__section pt-0">
								<p id="FineDescription_D">{!! $fine->description ?? ''!!}</p>
							</div>
						</div>
					</div>
					<!--end::Widget -->
				</div>
			</div>

			<div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                {{-- <button type="submit" class="btn btn-primary btn-sm">Save</button> --}}
            </div>
        </div>
    </div>
</div>

@section('scripts')

<script>
    $(document).ready(function () {
   		//close Add Payment Model
   		$('.paymentModel').click(function () {
			$('#PckgServiceModelForm').trigger('reset');
			$('#AddPaymentModel').modal('toggle');
			$('#AmtError').html('');
			$('#FinalAmt').html('');
		});
   		// open Add Payment Modal
   		$('.openPaymentModel').click(function () {
			$('#AddPaymentModel').modal('show');
			var fineId = $(this).attr('fine-id');
			var user_id = $(this).attr('user_id');
			var imposed_id = $(this).attr('imposed-id');
	        var fine_amount = parseFloat($(this).attr('invoice-price'));
	        // alert(imposed_id);
	        $('#user_id').val(user_id);
	        $('#resident_name').val($(this).attr('UserName'));
	        $('#invoice_id').val(fineId);
	        $('#imposedId').val(imposed_id);
	        $('#invoice_price').val(fine_amount);
	        $('#invoiceNumber').html('<b class="text-danger"> '+fineId+'</b>');
	        $('#pckgPrice').html(fine_amount);
	        $('#payed_amount').val(fine_amount);
	        $('#payed_amount').attr({"max" : fine_amount,"min" : fine_amount,});
	        $('#payed_amount').on('input', function() {
	    		var final_amount = fine_amount; // remaing amount
	    		var payAmount = parseFloat($(this).val()); // get payment Amount
	    		if(payAmount > final_amount || payAmount < final_amount){
	    			$('#AmtError').html('<b> Amount Cannot be greater Than: '+fine_amount+' OR Less Then '+fine_amount+' 0 </b>');
	    			$('#FinalAmt').html('');
	    		}else{
	    			var new_amount = final_amount - payAmount;
	    			$('#FinalAmt').html('Remaining Amount: <b> '+new_amount+'</b>');
	    			$('#AmtError').html('');
	    		}
	    	});
		});

		$(".ViewFineDetail").click(function(){
	    	var fine_id = parseInt($(this).attr('FineImposeID'));
	        var fine_list = <?php  echo json_encode($impose_fines ?? $fine->imposed); ?>;

	        // console.log(fine_list);

	        var fine_detail = fine_list.find(x => x.id === fine_id);
	        // console.log(fine_detail);
	        $('#kt_modal_1').modal('show');
	        $('#FineAmount_D').html(fine_detail.fine.fine_amount);
	        $('#ResidentName_D').html(fine_detail.user.name);
	        $('#ResidentUid_D').html(fine_detail.user.unique_id);
	        $('#FineDate_D').html(fine_detail.fine_date_format);
	        $('#DueDate_D').html(fine_detail.due_date_format);
	        $('#FineBy_D').html(fine_detail.fineby.name);
	        $('#FineDescription_D').html(fine_detail.fine.description);
	        $('#FineStatus_D').html("<i class='fa fa-credit-card'></i> "+ fine_detail.fine_status[0].toUpperCase() + fine_detail.fine_status.slice(1));
	    });
	});
    </script>
@endsection