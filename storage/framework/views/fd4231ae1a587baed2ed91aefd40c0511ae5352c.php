<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
        data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav">
            <li class="kt-menu__item <?php echo e(Request::segment(1) == '' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true">
                <a href="<?php echo e(url('/')); ?>" class="kt-menu__link">
                    <span class="kt-menu__link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path
                                    d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                    fill="#000000" fill-rule="nonzero" />
                                <path
                                    d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                    <span class="kt-menu__link-text"><?php echo e(__('Dashboard')); ?></span>
                </a>
            </li>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-society-management')): ?>
            <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'society-management' ? 'kt-menu__item--open' : null); ?>"
                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fa fa-building"></i>
                    </span>
                    <span class="kt-menu__link-text">SocietyManagement</span><i
                        class="kt-menu__ver-arrow la la-angle-right"></i></a>
                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'societies' ? 'kt-menu__item--active' : null); ?>"
                            aria-haspopup="true"><a href="<?php echo e(route('societies.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('Societies')); ?></span></a></li>
                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'modules' ? 'kt-menu__item--active' : null); ?>"
                            aria-haspopup="true"><a href="<?php echo e(route('modules.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('Modules')); ?></span></a></li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-departments')): ?>
            <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'department' ? 'kt-menu__item--open' : null); ?>"
                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fa fa-network-wired"></i>
                    </span>

                    <span class="kt-menu__link-text"><?php echo e(__('Departments')); ?> </span><i
                        class="kt-menu__ver-arrow la la-angle-right"></i></a>
                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">

                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'departments' ? 'kt-menu__item--active' : null); ?> "
                            aria-haspopup="true"><a href="<?php echo e(route('departments.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('Departments')); ?></span></a></li>

                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'subdepartments' ? 'kt-menu__item--active' : null); ?> "
                            aria-haspopup="true"><a href="<?php echo e(route('subdepartments.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('SubDepartments')); ?></span></a></li>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add-quick-complaint-departments')): ?>
                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'quick-complaints' ? 'kt-menu__item--active' : null); ?> "
                            aria-haspopup="true"><a href="<?php echo e(route('qkcomplaints.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text">Quick Complaints</span></a></li>
                        <?php endif; ?>

                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-user-management')): ?>
            <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'user-management' ? 'kt-menu__item--open' : null); ?>"
                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fa fa-users-cog"></i>
                    </span>

                    <span class="kt-menu__link-text">UserManagement</span><i
                        class="kt-menu__ver-arrow la la-angle-right"></i></a>
                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">

                        <?php if(auth()->user()->user_level_id == 1): ?>
                        <li class="kt-menu__item <?php echo e(Request::segment(2) == 'permissions' ? 'kt-menu__item--active' : null); ?>"
                            aria-haspopup="true"><a href="<?php echo e(route('permissions.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('Permissions')); ?></span></a></li>
                        <?php endif; ?>

                        <?php if(auth()->user()->user_level_id < 3): ?> <li
                            class="kt-menu__item <?php echo e(Request::segment(2) == 'userlevels' ? 'kt-menu__item--active' : null); ?>"
                            aria-haspopup="true"><a href="<?php echo e(route('userlevels.index')); ?>" class="kt-menu__link "><i
                                    class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                    class="kt-menu__link-text"><?php echo e(__('UserLevels')); ?></span></a>
            </li>
            <?php endif; ?>

            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'users' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('users.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Users')); ?></span></a></li>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-pending-account-user-management')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(3) == 'pendingaccounts' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('pending.accounts')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Pending Accounts')); ?></span></a></li>
            <?php endif; ?>
        </ul>
    </div>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-management')): ?>
    <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'resident-management' ? 'kt-menu__item--open' : null); ?>"
        aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
            <span class="kt-menu__link-icon">
                <i class="fa fa-users"></i>
            </span>
            <span class="kt-menu__link-text"><?php echo e(__('ResidentManagement')); ?> </span><i
                class="kt-menu__ver-arrow la la-angle-right"></i></a>
        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
            <ul class="kt-menu__subnav">
                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residentdata' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residentdata.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Resident Data')); ?></span></a></li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-tenants')): ?>
                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residenttenant' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residenttenant.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__("Resident Tenants")); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-family')): ?>
                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residentfamily' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residentfamily.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Resident Family')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-resident-servent')): ?>
                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residentservent' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residentservent.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Resident Servent')); ?></span></a></li>
                <?php endif; ?>

                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residenthandymen' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residenthandymen.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Resident Handy Men')); ?></span></a></li>

                <li class="kt-menu__item <?php echo e(Request::segment(2) == 'residentvehicle' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('residentvehicle.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Resident Vehicle')); ?></span></a></li>

            </ul>
        </div>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-complaint-management')): ?>
    <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'complaint' ? 'kt-menu__item--open' : null); ?>"
        aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
            <span class="kt-menu__link-icon">
                <i class="fa fa-question-circle"></i>
            </span>
            <span class="kt-menu__link-text"><?php echo e(__('ComplaintManagement')); ?> </span><i
                class="kt-menu__ver-arrow la la-angle-right"></i></a>
        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
            <ul class="kt-menu__subnav">
                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span
                            class="kt-menu__link-text"><?php echo e(__('complaints')); ?></span></span></li>
                 <li
                    class="kt-menu__item <?php echo e(Request::segment(2) == 'complaints' ? 'kt-menu__item--active' : null); ?> "
                    aria-haspopup="true"><a href="<?php echo e(route('complaints.index')); ?>" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text"><?php echo e(__('Complaints')); ?></span></a>
    </li>
    
    <li class="kt-menu__item <?php echo e(Request::segment(2) == 'complaintrefers' ? 'kt-menu__item--active' : null); ?> "
        aria-haspopup="true"><a href="<?php echo e(route('complaintrefers.index')); ?>" class="kt-menu__link "><i
                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                class="kt-menu__link-text"><?php echo e(__('Complaints Refers')); ?></span></a></li>
    </ul>
</div>
</li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-service-management')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'ssm' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-hands-helping"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('ServiceManagement')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-services')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'services' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('services.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Services')); ?></span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-service-devices')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'servicedevices' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('servicedevices.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Service Devices')); ?></span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-service-packages')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'servicepackages' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('servicepackages.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Service Packages')); ?></span></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-service-request')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'requestservice' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('requestservice.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Service Requests')); ?></span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-smart-services-request')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'smart-service-request' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('smr.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Smart Service Requests')); ?></span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-smart-services')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'userservices' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('userservices.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Smart Services')); ?></span></a></li>
            <?php endif; ?>
        </ul>
    </div>
</li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-departmental-services')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'departmental-services' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-list"></i>
        </span>
        <span class="kt-menu__link-text">Departmental Services</span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'depart_services' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('depart_services.index')); ?>" class="kt-menu__link"><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"> Services </span></a></li>

            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'services' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('request_depart_service.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"> Service Requests </span></a></li>

            <li class="kt-menu__item <?php echo e(Request::segment(3) == 'get-sent-requests' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('getSentRequests')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"> Sent Requests </span></a></li>

            <li class="kt-menu__item <?php echo e(Request::segment(3) == 'get-received-requests' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('getReceivedRequests')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"> Received Requests </span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-invoices')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'invoices' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-file"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Invoices')); ?> </span><i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'invoice' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('invoice.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Invoices')); ?></span></a></li>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'custominvoice' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('custominvoice.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Custom Invoice')); ?></span></a></li>
            <!--<li class="kt-menu__item <?php echo e(Request::segment(2) == 'imposedfine' ? 'kt-menu__item--active' : null); ?> " aria-haspopup="true"><a href="<?php echo e(route('imposedfine.index')); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php echo e(__('Imposed Fines')); ?></span></a></li>-->
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'imposedfine' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('imposedfine.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Imposed Fines')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-fine-penalties')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'fines&planties' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true">
    <a href="<?php echo e(route('fines.index')); ?>" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-hammer"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Fines&Planties')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
</li>
<?php endif; ?>

<!-- Deal Discounts -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-deals-and-discounts')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'deal-discounts' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-tags"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Deals & Discounts')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <!-- Deal Discounts -->
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'deals' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('deals.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Deals')); ?></span></a></li>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'vendors' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('vendors.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Vendors')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>

<!-- Notifications -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-notifications')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'notifications' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-bell"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Notifications')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'customnotifications' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('customNotifications')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Custom Notification')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<!-- End Notifications -->

<!-- Start Events -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-events-management')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'event-management' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-calendar-alt"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Events Management')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'event' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('event.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Events')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<!-- End Events -->

<!-- Start Social Media -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-social-management')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'media' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-paper-plane"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Social Media')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'socialmedia' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('socialmedia.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Social Links')); ?></span></a></li>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'news' ? 'kt-menu__item--active' : null); ?> "
                aria-haspopup="true"><a href="<?php echo e(route('news.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('News')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<!-- End Social Media -->

<!-- Start Incident Reporting  -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-incident-reporting')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'incident' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-file"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Incident Reports')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'reports' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('reports.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Incident Reports')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<!-- End incident-->
<!-- Start SOS  -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-sos')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'sos' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-building"></i>
        </span>
        <span class="kt-menu__link-text"><?php echo e(__('Society SOS')); ?> </span><i
            class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'society_sos' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('society_sos.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('SOS')); ?></span></a></li>
        </ul>
    </div>
</li>
<?php endif; ?>
<!-- End SOS-->

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-pages')): ?>
<li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'pages' ? 'kt-menu__item--open' : null); ?>"
    aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-pager"></i>
        </span>
        <span class="kt-menu__link-text">Pages</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-24-7-Page')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'twofour' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('twofour.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('24/7')); ?></span></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-sops-page')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'sops' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('sops.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('Sops')); ?></span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-bylaws-page')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'bylaws' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('bylaws.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('byLaws')); ?></span></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-about-us')): ?>
            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'aboutus' ? 'kt-menu__item--active' : null); ?>"
                aria-haspopup="true"><a href="<?php echo e(route('aboutus.index')); ?>" class="kt-menu__link "><i
                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                        class="kt-menu__link-text"><?php echo e(__('About Us')); ?></span></a></li>
            <?php endif; ?>
        </ul>
    </div>
</li>
<?php endif; ?>

            <?php if(Auth::user()->user_level_id == 1): ?>
            <li class="kt-menu__item  kt-menu__item--submenu <?php echo e(Request::segment(1) == 'user-invoice' ? 'kt-menu__item--open' : null); ?>"
                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-icon">
                    <i class="fa fa-pager"></i>
                </span>
                    <span class="kt-menu__link-text">User Custom Invoice</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                            <li class="kt-menu__item <?php echo e(Request::segment(2) == 'add-user-custom-invoice' ? 'kt-menu__item--active' : null); ?>"
                                aria-haspopup="true"><a href="#" class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Add user custom Invoice</span></a></li>
                    </ul>
                </div>
            </li>
                <?php endif; ?>

</ul>
</div>
</div>
<?php /**PATH /var/www/royal-app/resources/views/_partial/side-menu.blade.php ENDPATH**/ ?>