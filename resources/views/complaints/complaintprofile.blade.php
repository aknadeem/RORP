@extends('layouts.base') @section('content')
<style>
    .imageset{
        border: 1px solid #000;
         padding: 5px;
    }
   

</style>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Complaint Profile
                </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
            </div>
        </div>
    </div>

    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet kt-bg-brand kt-portlet--skin-solid kt-portlet--height-fluid">
                 
                    <div class="kt-portlet__body">
                        <!--begin::Widget 7-->
                        <div class="kt-widget7 kt-widget7--skin-light">
                            <div class="kt-widget7__desc">
                                <h3>{{$complaint->complaint_title ?? 'title'}}</h3>
                                <span>{{$$complaint->complaint_description ?? 'desc'}}</span>
                            </div>

                            <div class="kt-widget7__content ">
                                
                                 
                            </div>
                            <div class="kt-widget7__content">
                               
                                <div class="kt-widget7__info">
                                    <h3 class="kt-widget7__username">
                                        {{$complaint->user->name ?? 'user name'}}

                                        {{$complaint->user->unique_id ?? 'uid'}}
                                    </h3>
                                    <span class="kt-widget7__time ml-4">
                                        {{$complaint->created_at->diffForHumans()}}
                                    </span>
                                </div>
                            </div>
                            

                            <div class="kt-widget7__button">
                                <a class="btn btn-success" href="#" role="button">
                                    @if ($complaint->complaint_status == 'in_process') In Process @elseif ($complaint->complaint_status == 'follow_up') Follow Up @elseif ($complaint->complaint_status == 'approval_request') Approval Request @else {{ucfirst($complaint->complaint_status) }}
                                    @endif
                                </a>
                            </div>
                        </div>

                        <!--end::Widget 7-->
                    </div>
                </div>

                <!--end:: Widgets/Announcements 1-->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">
                <!--begin:: Widgets/Support Tickets -->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Ticket Status Logs
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body" style="height: 250px; overflow-y: scroll;">
                        <div class="kt-widget3">
                            @if ($complaint->complaints_logs->count() > 0) 
                            @foreach ($complaint->complaints_logs as $Log) 
                            
                            <div class="kt-widget3__item">
                                <div class="kt-widget3__header">
                                    {{-- <div class="kt-widget3__user-img">
                                        <img class="kt-widget3__img" src="{{ asset('assets/media/users/user1.jpg') }} " alt="" />
                                    </div> --}}
                                    <div class="kt-widget3__info">
                                        <a href="#" class="kt-widget3__username">
                                            nadeem
                                        </a>
                                        <br />
                                        <span class="kt-widget3__time">
                                            {{$Log->created_at->diffForHumans()}}
                                        </span>
                                    </div>
                                    <span class="kt-widget3__status kt-font-success">
                                        in_processss
                                        {{-- {{ucfirst($ticketLog->status)}} --}} {{-- @if ($ticketLog->status == 'in_process') In Process @elseif ($ticketLog->status == 'follow_up') Follow Up @elseif ($ticketLog->status == 'approval_request')
                                        Approval Request @else {{ucfirst($ticketLog->status) }} @endif --}}
                                    </span>
                                </div>
                                <div class="kt-widget3__body">
                                    <p class="kt-widget3__text">
                                        {{-- {{ucfirst($ticketLog->comments)}} --}}

                                        comments
                                    </p>
                                </div>
                            </div>
                            @endforeach @endif
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Support Tickets -->
            </div>
            
        </div>
    </div>
</div>
{{-- @include('includes.ticketapprovalmodal');
@include('includes.approvalrequestmodal'); --}}
@endsection