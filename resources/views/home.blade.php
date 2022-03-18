@extends('layouts.base')
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __('Dashboard') }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">Summary</span>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    @can('create-society-management')
                    <a href="{{ route('societies.create') }}" data-toggle="kt-tooltip" data-placement="bottom"
                        data-skin="brand" title="Click to create Society"
                        class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
                        {{ __('Add New Society') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Start:: Society Data-->
        @forelse ($societies as $society)
        <div class="kt-portlet ">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--user-profile-3">
                    <div class="kt-widget__top">
                        <div
                            class="kt-widget__pic kt-widget__pic--success kt-font-success kt-font-boldest kt-font-light text-uppercase">
                            {{$society->code ?? ''}}
                        </div>
                        <div class="kt-widget__content">
                            <div class="kt-widget__head">
                                <a href="#" class="kt-widget__title"> {{$society->name ?? ''}} </a>
                                <div class="kt-widget__action">
                                    <div class="kt-widget__stats d-flex align-items-center flex-right">
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__label">
                                                <span
                                                    class="btn btn-label-success  btn-sm btn-font-lg btn-bold ">Sectors:
                                                    {{$society->sectors->count()}}</span>
                                            </div>
                                        </div>
                                        &nbsp;
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__label">
                                                <span
                                                    class="btn btn-label-brand btn-sm btn-font-lg btn-bold ">Residents:
                                                    {{$society->residents->count()}} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__subhead">
                                <a href="#"><i class="fa fa-flag"></i>Pakistan</a>
                                <a href="#"><i class="fa fa-warehouse"></i>{{$society->province->name ??
                                    'Province'}}</a>
                                <a href="#"><i class="fa fa-city"></i> {{$society->city->name ?? 'City'}} </a>
                            </div>
                            <div class="kt-widget__info">
                                <div class="kt-widget__desc">
                                    {{-- <h5> Punjab[Multan] </h5> --}}
                                    <span><i class="flaticon2-placeholder"> </i> {{$society->address ?? 'Address'}}
                                    </span>

                                </div>

                                <div class="kt-widget__progress kt-hidden">
                                    <div class="kt-widget__text">
                                        Progress
                                    </div>
                                    <div class="progress" style="height: 5px;width: 100%;">
                                        <div class="progress-bar kt-bg-success" role="progressbar" style="width: 65%;"
                                            aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="kt-widget__stats">
                                        78%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget__bottom mt-2">
                        <div class="kt-widget__item pt-2 mr-2 pr-2">
                            <div class="kt-widget__icon">
                                <i class="fa fa-question-circle fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Complaints</span>
                            </div>
                        </div>

                        @can('view-complaint-management')
                        @php
                        $url_total = route('filter.complaints', [$society->id, 'total_complaint']);

                        $url_process = route('filter.complaints', [$society->id, 'inprocess_complaint']);

                        $url_pending = route('filter.complaints', [$society->id, 'pending_complaint']);

                        $url_resolved = route('filter.complaints', [$society->id, 'resolved_complaint']);
                        $title= 'Click to View';
                        @endphp
                        @else
                        @php
                        $title= 'Unauthorized to View';
                        $url_total = '#';
                        $url_process = '#';
                        $url_pending = '#';
                        $url_resolved = '#';
                        @endphp
                        @endcan

                        <a href="{{ $url_total }}" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                            title="{{ $title }}" class="kt-widget__item pt-2 pr-0 pl-5">
                            <div class="kt-widget__icon">
                                <i class="flaticon-squares-2 fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Total </span>
                                <span class="kt-widget__value text-center"> <span> </span>
                                    {{$society->complaints->count()}} </span>
                            </div>
                        </a>
                        <a href="{{ $url_process }}" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                            title="{{ $title }}" class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="flaticon-interface-4 fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title"> in Process </span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->complaints->whereNotIn('complaint_status',['open','closed','completed'])->count()}}
                                </span>
                            </div>
                        </a>
                        <a href="{{ $url_pending }}" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                            title="{{ $title }}" class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="flaticon-warning fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title"> Pending </span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->complaints->where('complaint_status','open')->count()}} </span>
                            </div>
                        </a>
                        <a href="{{ $url_resolved }}" data-toggle="kt-tooltip" data-placement="bottom" data-skin="brand"
                            title="{{ $title }}" class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="fa fa-check-square fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Resolved</span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->complaints->whereIn('complaint_status',
                                    ['closed','completed'])->count()}} </span>
                            </div>
                        </a>
                    </div>

                    <div class="kt-widget__bottom mt-2 pr-0">
                        <div class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="fa fa-handshake"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title"> Services Request </span>
                                {{-- <a href="#" class="kt-widget__value kt-font-lg">5</a> --}}
                            </div>
                        </div>
                        <a href="{{route('filter.services', [$society->id, 'total_service'])}}"
                            class="kt-widget__item pt-2 pr-0 text-center">
                            <div class="kt-widget__icon">
                                <i class="flaticon-squares-1 fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Total </span>
                                <span class="kt-widget__value text-center"> <span></span>
                                    {{$society->request_services->where('service_type','!=','monthly')->count()}}
                                </span>
                            </div>
                        </a>
                        <a href="{{route('filter.services', [$society->id, 'inprocess_service'])}}"
                            class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="flaticon-interface-4 fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title"> in Process </span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->request_services->where('service_type','!=','monthly')->whereNotIn('status',['open','closed','completed'])->count()}}
                                </span>
                            </div>
                        </a>
                        <a href="{{route('filter.services', [$society->id, 'pending_service'])}}"
                            class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="flaticon-warning fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title"> Pending </span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->request_services->where('service_type','!=','monthly')->where('status',
                                    'open')->count()}} </span>
                            </div>
                        </a>
                        <a href="{{route('filter.services', [$society->id, 'resolved_service'])}}"
                            class="kt-widget__item pt-2 pr-0">
                            <div class="kt-widget__icon">
                                <i class="fa fa-check-square fa-2x"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Resolved</span>
                                <span class="kt-widget__value text-center"><span></span>
                                    {{$society->request_services->where('service_type','!=','monthly')->whereIn('status',
                                    ['closed','completed'])->count()}} </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <h5 class="text-danger text-center mt-5"> No Societies Available </h5>
        @endforelse
        <!--End:: Society Data-->

        @if(auth()->user()->user_level_id > 2)
        <div class="row">
            <div class="col-lg-6">
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Complaint Notifications
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_widget4_tab1_content"
                                        role="tab" aria-selected="true">
                                        Today
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget4_tab2_content" role="tab"
                                        aria-selected="false">
                                        Month
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget4_tab1_content">
                                <div class="kt-widget4">
                                    @forelse(auth()->user()->Notifications->where('type','App\Notifications\ComplaintNotification')->where('created_at','=',today())
                                    as $notification)

                                    @if ($notification->created_at->format('Y-m-d') == today()->format('Y-m-d'))
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__pic kt-widget4__pic--pic">

                                        </div>
                                        <div class="kt-widget4__info">
                                            <a href="#" class="kt-widget4__username">
                                                {{$notification->data['title']}}
                                            </a>
                                            <p class="kt-widget4__text">
                                                {{$notification->data['sender_name']}}
                                            </p>
                                        </div>
                                        <span
                                            class="btn btn-sm btn-label-brand btn-bold">{{$notification->created_at->diffForHumans()}}</span>
                                    </div>
                                    @endif

                                    @empty
                                    <div class="kt-grid kt-grid--ver pt-5">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br> <strong> No new notifications. </strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse

                                </div>
                            </div>
                            <div class="tab-pane" id="kt_widget4_tab2_content">
                                <div class="kt-widget4">
                                    @forelse(auth()->user()->Notifications->where('type','App\Notifications\ComplaintNotification')
                                    as $notification)

                                    @if ($notification->created_at->format('m') == today()->format('m'))
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__pic kt-widget4__pic--pic">

                                        </div>
                                        <div class="kt-widget4__info">
                                            <a href="#" class="kt-widget4__username">
                                                {{$notification->data['title'] ?? ''}}
                                            </a>
                                            <p class="kt-widget4__text">
                                                {{$notification->data['sender_name'] ?? ''}}
                                            </p>
                                        </div>
                                        <span
                                            class="btn btn-sm btn-label-brand btn-bold">{{$notification->created_at->format('M
                                            d, Y')}}</span>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="kt-grid kt-grid--ver pt-4">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br> <strong> No new notifications. </strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Service Notifications
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_widget2_tab1_content"
                                        role="tab" aria-selected="false">
                                        Today
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget2_tab2_content" role="tab"
                                        aria-selected="false">
                                        Month
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget2_tab3_content" role="tab"
                                        aria-selected="true">
                                        Year
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                <div class="kt-widget2">
                                    @forelse(auth()->user()->Notifications->where('type',
                                    'App\Notifications\ServiceNotification') as $notification)
                                    @if ($notification->created_at->format('Y-m-d') == today()->format('Y-m-d'))

                                    <div class="kt-widget2__item kt-widget2__item--{{ $notification->read_at != "" ? "
                                        success" : "brand" }}">
                                        <div class="kt-widget2__checkbox">

                                        </div>
                                        <div class="kt-widget2__info">
                                            <a href="#" class="kt-widget2__title">
                                                {{$notification->data['title']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                {{$notification->data['sender_name']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                <b> {{$notification->created_at->format('M d, Y')}} </b>
                                            </a>
                                        </div>
                                        <div class="kt-widget2__actions">
                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="kt-grid kt-grid--ver pt-4">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br> <strong> No new notifications. </strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_widget2_tab2_content">
                                <div class="kt-widget2">
                                    @forelse(auth()->user()->Notifications->where('type',
                                    'App\Notifications\ServiceNotification') as $notification)
                                    @if ($notification->created_at->format('m') == today()->format('m'))
                                    <div class="kt-widget2__item kt-widget2__item--{{ $notification->read_at != "" ? "
                                        success" : "brand" }}">
                                        <div class="kt-widget2__checkbox">

                                        </div>
                                        <div class="kt-widget2__info">
                                            <a href="#" class="kt-widget2__title">
                                                {{$notification->data['title']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                {{$notification->data['sender_name']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                <b> {{$notification->created_at->format('M d, Y')}} </b>
                                            </a>
                                        </div>
                                        <div class="kt-widget2__actions">
                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="kt-grid kt-grid--ver pt-4">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br> <strong> No new notifications. </strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_widget2_tab3_content">
                                <div class="kt-widget2">
                                    @forelse(auth()->user()->readNotifications->where('type',
                                    'App\Notifications\ServiceNotification') as $notification)
                                    @if ($notification->created_at->format('Y') == today()->format('Y'))
                                    <div class="kt-widget2__item kt-widget2__item--success">
                                        <div class="kt-widget2__checkbox">

                                        </div>
                                        <div class="kt-widget2__info">
                                            <a href="#" class="kt-widget2__title">
                                                {{$notification->data['title']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                {{$notification->data['sender_name']}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                <b> {{$notification->created_at->format('M d, Y')}} </b>
                                            </a>
                                        </div>
                                        <div class="kt-widget2__actions">
                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="kt-grid kt-grid--ver pt-4">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br> <strong> No new notifications. </strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- begin:: End Content  -->
</div>
@endsection