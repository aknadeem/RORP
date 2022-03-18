<div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile kt-header-menu--layout-default">
            <ul class="kt-menu__nav">
                

                <li class="kt-menu__item kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open kt-menu__item--here kt-menu__item--active"
                    data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                    
                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item kt-menu__item--active" aria-haspopup="true">
                                <a href="index.html" class="kt-menu__link">
                                    <span class="kt-menu__link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M5.84026576,8 L18.1597342,8 C19.1999115,8 20.0664437,8.79732479 20.1528258,9.83390904 L20.8194924,17.833909 C20.9112219,18.9346631 20.0932459,19.901362 18.9924919,19.9930915 C18.9372479,19.9976952 18.8818364,20 18.8264009,20 L5.1735991,20 C4.0690296,20 3.1735991,19.1045695 3.1735991,18 C3.1735991,17.9445645 3.17590391,17.889153 3.18050758,17.833909 L3.84717425,9.83390904 C3.93355627,8.79732479 4.80008849,8 5.84026576,8 Z M10.5,10 C10.2238576,10 10,10.2238576 10,10.5 L10,11.5 C10,11.7761424 10.2238576,12 10.5,12 L13.5,12 C13.7761424,12 14,11.7761424 14,11.5 L14,10.5 C14,10.2238576 13.7761424,10 13.5,10 L10.5,10 Z"
                                                    fill="#000000" />
                                                <path
                                                    d="M10,8 L8,8 L8,7 C8,5.34314575 9.34314575,4 11,4 L13,4 C14.6568542,4 16,5.34314575 16,7 L16,8 L14,8 L14,7 C14,6.44771525 13.5522847,6 13,6 L11,6 C10.4477153,6 10,6.44771525 10,7 L10,8 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="kt-menu__link-text">My Account</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- end:: Header Menu -->

    

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
        <!--begin: User Bar -->
        <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
                <span class="kt-header__topbar-icon kt-pulse kt-pulse--brand">
                    <i class="fa fa-bell"></i>
                    <span class="kt-pulse__ring"></span>
                </span>
                <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                <span class="kt-badge kt-badge--danger" style="margin-top: 10px; margin-left: -15px;"><?php echo e(auth()->user()->unreadNotifications->count()); ?></span>
                <?php endif; ?>

            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                <form>
                    <!--begin: Head -->
                    <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b pt-0 pb-0"
                        style="background-color: #1c5b90 !important;">
                        <h5 class="kt-head__title text-left pl-3 pt-4 pr-3 pb-4">
                            Notifications
                            
                            <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                            <a class="btn btn-success btn-sm mark_all" style="float: right; margin-top: -5px;">Read
                                All</a>
                            <?php else: ?>
                            <span class="btn btn-success btn-sm" style="float: right;margin-top: -5px;">Read All</span>
                            <?php endif; ?>
                        </h5>
                    </div>

                    <!--end: Head -->
                    <div class="tab-content">
                        <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                            <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true"
                                data-height="300" data-mobile-height="200">

                                <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                if($notification->type == 'App\Notifications\ServiceNotification'){
                                $url = route('requestservice.show', $notification->data['service_request_id']);
                                }else if($notification->type == 'App\Notifications\ComplaintNotification'){
                                $url = route('complaints.show', $notification->data['complaint_id']);
                                }else if($notification->type ==
                                'App\Notifications\DepartmentalServiceNotification'){
                                $url = route('request_depart_service.show', $notification->data['request_id']);
                                }
                                else{
                                $url = '#';
                                }
                                ?>
                                <a href="<?php echo e($url); ?>" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                                <path
                                                    d="M12,16 C12.5522847,16 13,16.4477153 13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 C11,16.4477153 11.4477153,16 12,16 Z M10.591,14.868 L10.591,13.209 L11.851,13.209 C13.447,13.209 14.602,11.991 14.602,10.395 C14.602,8.799 13.447,7.581 11.851,7.581 C10.234,7.581 9.121,8.799 9.121,10.395 L7.336,10.395 C7.336,7.875 9.31,5.922 11.851,5.922 C14.392,5.922 16.387,7.875 16.387,10.395 C16.387,12.915 14.392,14.868 11.851,14.868 L10.591,14.868 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            <?php if($notification->type == 'App\Notifications\AdminNotification'): ?>
                                            Custom Notitfications
                                            <?php elseif($notification->type == 'App\Notifications\ServiceNotification'): ?>
                                            Service Notifications
                                            <?php elseif($notification->type ==
                                            'App\Notifications\DepartmentalServiceNotification'): ?>
                                            Departmental Service Notification
                                            <?php else: ?>
                                            Complaint Notitfications
                                            <?php endif; ?>
                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo e($notification->data['title'] ?? ''); ?>

                                        </div>
                                        <div class="kt-notification__item-time">
                                            By: <span class="text-info"><?php echo e($notification->data['by'] ??
                                                $notification->data['sender_name']); ?></span> <br>
                                            <span class="text-right" style="float: right;">
                                                <?php echo e($notification->created_at->diffForHumans()); ?>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <!--    <div class="kt-grid kt-grid--ver pt-4">-->
                                <!--    <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">-->
                                <!--        <div class="kt-grid__item kt-grid__item--middle kt-align-center">-->
                                <!--            All caught up!-->
                                <!--            <br> <strong> No new notifications. </strong> -->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <?php endif; ?>

                                <?php $__empty_1 = true; $__currentLoopData = auth()->user()->readNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                if($notification->type == 'App\Notifications\ServiceNotification'){
                                $url = route('requestservice.show', $notification->data['service_request_id']);
                                }else if($notification->type == 'App\Notifications\ComplaintNotification'){
                                $url = route('complaints.show', $notification->data['complaint_id']);
                                }else{
                                $url = '#';
                                }
                                ?>
                                <a href="javascript:void(0);" class="kt-notification__item bg-secondary text-light">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                                <path
                                                    d="M12,16 C12.5522847,16 13,16.4477153 13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 C11,16.4477153 11.4477153,16 12,16 Z M10.591,14.868 L10.591,13.209 L11.851,13.209 C13.447,13.209 14.602,11.991 14.602,10.395 C14.602,8.799 13.447,7.581 11.851,7.581 C10.234,7.581 9.121,8.799 9.121,10.395 L7.336,10.395 C7.336,7.875 9.31,5.922 11.851,5.922 C14.392,5.922 16.387,7.875 16.387,10.395 C16.387,12.915 14.392,14.868 11.851,14.868 L10.591,14.868 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            <?php if($notification->type == 'App\Notifications\AdminNotification'): ?>
                                            Custom Notitfications
                                            <?php elseif($notification->type == 'App\Notifications\ServiceNotification'): ?>
                                            Service Notifications
                                            <?php else: ?>
                                            Complaint Notitfications
                                            <?php endif; ?>
                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo e($notification->data['title'] ?? ''); ?>

                                        </div>
                                        <div class="kt-notification__item-time">
                                            By: <span class="text-info"><?php echo e($notification->data['by'] ??
                                                $notification->data['sender_name']); ?></span> <br>
                                            <span class="text-right" style="float: right;">
                                                <?php echo e($notification->created_at->diffForHumans()); ?>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <!--<div class="kt-grid kt-grid--ver pt-4">-->
                                <!--    <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">-->
                                <!--        <div class="kt-grid__item kt-grid__item--middle kt-align-center">-->
                                <!--            All caught up!-->
                                <!--            <br> <strong> No new notifications. </strong> -->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Welcome,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">
                        <?php echo e(Auth::user()->name); ?></span>
                    <img class="kt-hidden" alt="Pic" src="" />
                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span
                        class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?php echo e(Auth::user()->name[0]); ?></span>
                </div>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                <!--begin: Head -->
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                    style="background-color: #1c5b90 !important;">
                    <div class="kt-user-card__avatar">
                        <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo e(Auth::user()->name[0]); ?></span>
                    </div>
                    <div class="kt-user-card__name">
                        <?php echo e(Auth::user()->name); ?>

                    </div>

                </div>
                <!--end: Head -->
                <!--begin: Navigation -->
                <div class="kt-notification">
                    <a href="<?php echo e(route('users.show', Auth::user()->id )); ?>" class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                My Profile
                            </div>
                            <div class="kt-notification__item-time">
                                Account settings and more
                            </div>
                        </div>
                    </a>
                    <div class="kt-notification__custom kt-space-between">

                        <a href="<?php echo e(route('logout')); ?>" target="_blank"
                            class="btn btn-label btn-label-brand btn-sm btn-bold" onclick="event.preventDefault(); 
                        document.getElementById('logout-form').submit();"><?php echo e(__('Logout')); ?>

                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>

                    </div>
                </div>
                <!--end: Navigation -->
            </div>
        </div>
        <!--end: User Bar -->
    </div>
    <!-- end:: Header Topbar -->
</div><?php /**PATH /var/www/royal-app/resources/views/_partial/top-header.blade.php ENDPATH**/ ?>