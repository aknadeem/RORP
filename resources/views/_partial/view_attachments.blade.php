<div class="modal fade" id="viewAttachmentList" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="ModalTitle"></span> {{$modal_title ?? ''}} Attachments </h5> <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                        <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_widget4_tab1_content">
                                	@forelse ($attachments->groupBy('title') as $key=>$attach)
                                	
                                	<div class="kt-widget4 mb-4">
                                        	<div class="kt-widget4__item">
                                        		<span class="kt-badge kt-badge--username kt-badge--unified-brand kt-badge--lg kt-badge--rounded kt-badge--bold">  <i class="fa fa-file-pdf"> </i> </span>
						                        <div class="kt-widget4__info ml-4">
						                            <h6 class="kt-widget4__text">
						                                {{$key ?? ''}}
						                            </h6>

						                            <a href="{{ asset('uploads/attachments/'.$attach[0]['attachment']) }}" download class="kt-widget4__username mt-2" title="Click to Download">
						                                {{$attach[0]['attachment']}}
						                            </a>
						                        </div>
						                        <a href="{{ route($delete_url,$attach[0]['attachment']) }}" title="Delete This Attachment" class="btn btn-bold btn-label-danger btn-sm delete-confirm" del_title="byLaw {{$key ?? ''}}"><i class="fa fa-trash-alt"></i> Delete  </a>
						                    </div>
                                        </div>
                                    @empty
                                    	<h6 class="text-center text-danger"> No Attachment Found</h6>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
            </div>
        </div>
    </div>
</div>