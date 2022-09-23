<?php

use App\Http\Controllers\CustomUserForInvoiceController;
use App\Models\Society;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\Invoice\TaxController;
use App\Http\Controllers\Pages\ByLawController;
use App\Http\Controllers\Pages\SopLawController;
use App\Http\Controllers\Pages\AboutUsController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Pages\ContactUsController;
use App\Http\Controllers\SocialMedia\NewsController;
use App\Http\Controllers\Fineplanties\FineController;
use App\Http\Controllers\Pages\TwoFourSevenController;
use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Notification\SendNotification;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\DealsDiscounts\VendorController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\EventManagement\EventController;
use App\Http\Controllers\Invoice\CustomInvoiceController;
use App\Http\Controllers\UserManagement\ModuleController;
use App\Http\Controllers\SocialMedia\SocialMediaController;
use App\Http\Controllers\Complaint\ComplaintReferController;
use App\Http\Controllers\Department\SubDepartmentController;
use App\Http\Controllers\Fineplanties\ImposedFineController;
use App\Http\Controllers\UserManagement\SubModuleController;
use App\Http\Controllers\UserManagement\UserLevelController;
use App\Http\Controllers\Department\QuickComplaintController;
use App\Http\Controllers\ServiceManagement\ServiceController;
use App\Http\Controllers\SocietyManagement\SocietyController;
use App\Http\Controllers\UserManagement\PermissionController;
use App\Http\Controllers\UserManagement\DesginationController;
use App\Http\Controllers\UserManagement\UserProfileController;
use App\Http\Controllers\SocietyManagement\SocietySosController;
use App\Http\Controllers\DealsDiscounts\DealsDiscountsController;
use App\Http\Controllers\ServiceManagement\ServiceTypeController;
use App\Http\Controllers\ServiceManagement\UserServiceController;
use App\Http\Controllers\Complaint\FiltersDashboardDataController;
use App\Http\Controllers\SocietyManagement\SocietyBlockController;
use App\Http\Controllers\AccountCreation\AccountCreationController;
use App\Http\Controllers\Notification\CustomNotificationController;
use App\Http\Controllers\ResidentManagement\ResidentDataController;
use App\Http\Controllers\ServiceManagement\ServiceDeviceController;
use App\Http\Controllers\ServiceManagement\RequestServiceController;
use App\Http\Controllers\ServiceManagement\ServicePackageController;
use App\Http\Controllers\ServiceManagement\ServiceSubTypeController;
use App\Http\Controllers\SocietyManagement\IncidentReportController;
use App\Http\Controllers\ResidentManagement\ResidentFamilyController;
use App\Http\Controllers\ResidentManagement\ResidentTenantController;
use App\Http\Controllers\ResidentManagement\ResidentServentController;
use App\Http\Controllers\ResidentManagement\ResidentVehicleController;
use App\Http\Controllers\ResidentManagement\ResidentHandyMenController;
use App\Http\Controllers\ServiceManagement\SmartServiceRequestController;
use App\Http\Controllers\DepartmentalServices\DepartmentalServicesController;
use App\Http\Controllers\DepartmentalServices\RequestDepartServiceController;

Auth::routes();



 Route::get('custom-user/create-invoice', function () {
     $societies = Society::get();
 	return view('custom-user-data.create', compact('societies'));
 });

Route::group(['prefix' => '/customer-user-invoice', 'middleware' => 'auth'], function(){
    Route::get('user-data',[CustomUserForInvoiceController::class, 'getCustomUser'])->name('getCustomUser');
    Route::get('custom-user-form',[CustomUserForInvoiceController::class, 'customUserForm'])->name('customUserForm');
    Route::post('store-user-data',[CustomUserForInvoiceController::class, 'storeCustomUser'])->name('storeCustomUser');
});

Route::post('customlogin',[UserLoginController::class, 'Loginuser'])->name('customlogin');
Route::post('customlogin',[UserLoginController::class, 'Loginuser'])->name('customlogin');
Route::post('designation',[DesginationController::class, 'store'])->name('designation.store');
Route::get('/send-signup-otp/{to}/sms/{pin}', [HomeController::class, 'smsApi'])->name('smsApi');
Route::group(['prefix' => '/', 'middleware' => 'auth'], function(){
	Route::get('/', [HomeController::class, 'index'])->name('home.index');
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::group(['prefix' => '/user-management'], function(){
		Route::post('/admin/set-societies', [UserProfileController::class, 'addAdminSocities'])->name('admin.addsocieties');
		Route::get('/user-info/{id}', [UserProfileController::class, 'getAdminSocities'])->name('admin.getsocieties');
		Route::put('/user/update-profile/{id}', [UserProfileController::class, 'updateUserProfile'])->name('updateUserProfile');
		Route::put('/admin/deattach-society/{id}', [UserProfileController::class, 'deAttachAdminSociety'])->name('deattach.society');
		Route::get('/hod/deattach-department/{depid}/user/{userid}', [UserProfileController::class, 'deAttachDepartment'])->name('deattach.department');
		Route::get('/deattach-supervisor/{depid}/user/{userid}', [UserProfileController::class, 'deAttachSubDepartmentSupervisor'])->name('deattach.supervisor');
		Route::put('/manger/deattach-subdepartment/{id}', [UserProfileController::class, 'deAttachSubDepartment'])->name('deattach.subdepartment');
		Route::get('/user-permissions/{id}', [UserController::class, 'UserPermissions'])->name('user-permissions');
		Route::resource('users', UserController::class);
		Route::get('/user/set-permissions/{id}', [UserController::class, 'editPermissions'])->name('edituserpermissions');
		Route::put('/user/setpermission/{id}', [UserController::class, 'setPermissions'])->name('setuserpermissions');
		Route::resource('userlevels', UserLevelController::class);
		Route::resource('permissions', PermissionController::class);
		Route::get('/account/pendingaccounts', [AccountCreationController::class, 'pendingAccounts'])->name('pending.accounts');
		Route::get('/account/create/{id}', [AccountCreationController::class, 'createAccount'])->name('create_resident.account');
		Route::get('/account/residents-accounts', [AccountCreationController::class, 'viewResidentsAccount'])->name('residentaccounts');
		Route::post('/account/verify', [AccountCreationController::class, 'verifyAccount'])->name('verify.account');
		Route::put('user/status/{id}', [UserController::class, 'toggleStatus'])->name('user.toggleStatus');
		Route::get('user/changepassword/{id}', [UserController::class, 'changePasswordView'])->name('changePasswordView');
		Route::post('user/password/store', [UserController::class, 'changePasswordUpdate'])->name('changePasswordUpdate');
	});

	Route::group(['prefix' => '/resident-management', 'middleware' => 'can:view-resident-management'], function(){
		Route::post('/residentdata/store-update-image/{id}', [ResidentDataController::class, 'storeUpdateImage'])->name('UpdateResidentImage');
		Route::resource('residentdata', ResidentDataController::class);
		Route::resource('residenttenant', ResidentTenantController::class)->only('index','create','update','edit');
		Route::post('/resident-tenant/store', [ResidentTenantController::class, 'storeTenant'])->name('storeTenant');
		Route::resource('residentfamily', ResidentFamilyController::class);
		Route::resource('residentservent', ResidentServentController::class);
		Route::resource('residenthandymen', ResidentHandyMenController::class);
		Route::resource('residentvehicle', ResidentVehicleController::class);
		Route::post('/residentdata/storeImage', [ResidentTenantController::class, 'storeUpdateImageWeb'])->name('storeUpdateImageWeb');
	});
	Route::group(['prefix' => '/department', 'middleware' => 'can:view-departments'], function(){
		Route::resource('departments', DepartmentController::class);
		Route::post('/department/set-hod', [DepartmentController::class, 'setdepartment_hod'])->name('department.addhod');
		Route::get('/get-hods/{id}', [DepartmentController::class, 'getdepartment_hods'])->name('department.gethods');
		Route::resource('subdepartments', SubDepartmentController::class);
		Route::post('/sub/set-supervisor', [SubDepartmentController::class, 'setSupervisor_sub'])->name('sub.addsupervisor');
		Route::post('/sub/addmanager', [SubDepartmentController::class, 'addAssManager'])->name('sub.addmanager');
		Route::get('/sub/get-supervisors/{id}', [SubDepartmentController::class, 'subdepartmentSupervisors'])->name('sub.getsupervisors');
	});
	Route::group(['prefix' => '/society-management', 'middleware' => 'can:view-society-management'], function(){
		Route::resource('societies', SocietyController::class);
		Route::post('/add-sector', [SocietyController::class, 'addSocietySector'])->name('society.addsector');
		Route::get('/get-sectors/{id}', [SocietyController::class, 'getSocietySectors'])->name('society.sectors');
		Route::resource('societyblocks', SocietyBlockController::class);
		Route::resource('modules', ModuleController::class);
		Route::resource('submodules', SubModuleController::class);
		Route::post('/sub/addsubmodule', [ModuleController::class, 'addSubModule'])->name('sub.addsubmodule');
		Route::get('/sub/get-submodules/{id}', [ModuleController::class, 'getSubmodules'])->name('sub.getsubmodules');
	});


	Route::group(['prefix' => '/media', 'middleware' => 'can:view-social-management'], function(){
		Route::resource('socialmedia', SocialMediaController::class);
		Route::resource('news', NewsController::class);
	});

	Route::group(['prefix' => '/event-management', 'middleware' => 'can:view-events-management'], function(){
		Route::resource('event', EventController::class);
	});
	Route::group(['prefix' => '/pages', 'middleware' => 'can:view-pages'], function(){

	    Route::post('/upload-sop-attachment',[SopLawController::class, 'uploadAttachment'])->name('uploadsop.attachment');
		Route::post('/upload-bylaw-attachment',[ByLawController::class, 'uploadAttachment'])->name('uploadbylaw.attachment');

		Route::delete('/delete-bylaw-attachment/{id}',[ByLawController::class, 'deleteAttachment'])->name('deletebylaw_attachment');
		Route::delete('/delete-sop-attachment/{id}',[SopLawController::class, 'deleteAttachment'])->name('deletesop_attachment');
		Route::delete('/delete-twofour-attachment/{id}',[TwoFourSevenController::class, 'deleteAttachment'])->name('deletetwofour_attachment');

		Route::post('/upload-twofour-attachment',[TwoFourSevenController::class, 'uploadAttachment'])->name('uploadtwofour.attachment');

		Route::get('/filter/bylaw',[ByLawController::class, 'filterWithSociety'])->name('bylaw.filter');
		Route::get('/sop/filter',[SopLawController::class, 'filterWithSociety'])->name('sop.filter');
		Route::get('/twofour/filter',[TwoFourSevenController::class, 'filterWithSociety'])->name('twofours_filter');

		Route::resource('twofour', TwoFourSevenController::class);
		Route::resource('contactus', ContactUsController::class);
		Route::resource('sops', SopLawController::class);
		Route::resource('bylaws', ByLawController::class);
	});

	Route::group(['prefix' => '/deal-discounts', 'middleware' => 'can:view-deals-and-discounts'], function(){
		Route::resource('deals', DealsDiscountsController::class);
		// Route::resource('category', DealCategoryController::class);
		Route::resource('vendors', VendorController::class);
	});

	Route::group(['prefix' => '/'], function(){
		Route::resource('aboutus', AboutUsController::class);
	});

	Route::group(['prefix' => '/notifications', 'middleware' => 'can:view-notifications'], function(){
		Route::get('/customnotifications',[CustomNotificationController::class, 'getNotifications'])->name('customNotifications');
		Route::get('/customnotifications/create',[CustomNotificationController::class, 'createadminNotification'])->name('create.notification');
		Route::get('/customnotifications/edit/{id}',[CustomNotificationController::class, 'edit'])->name('edit.notification');
		Route::delete('/customnotifications/destroy/{id}',[CustomNotificationController::class, 'destroy'])->name('delete.notification');
		Route::get('/customnotifications/show/{id}',[CustomNotificationController::class, 'show'])->name('notification.detail');
		Route::get('/markasread',[SendNotification::class, 'MarkAsRead'])->name('markAsRead');
		Route::post('/mark/read',[SendNotification::class, 'markRead'])->name('markRead');
		Route::post('/store',[SendNotification::class, 'storeadminNotification'])->name('notification.store');
		Route::get('/list/{id}',[SendNotification::class, 'notificationList'])->name('notification.list');
	});

	// complaint management module
	Route::group(['prefix' => '/complaint', 'middleware' => 'can:view-complaint-management'], function(){
	    Route::post('tat/update/{id}',[ComplaintController::class, 'updateTAT'])->name('complaint.updatetat');

		Route::get('getComplaintsWithRefresh',[ComplaintController::class, 'getComplaintsWithRefresh'])->name('getComplaintsWithRefresh');

		//Update complaint Status By Supervisor
		Route::post('complaints/supervisor/status',[ComplaintController::class, 'WorkingInComplaint'])->name('sup_complaint_update');
		Route::post('complaints/status/update',[ComplaintController::class, 'complaintStatusChange'])->name('complaintStatusChange');
		Route::post('complaints/feedback',[ComplaintController::class, 'complaintfeedback'])->name('complaintfeedback');
		Route::get('complaint/edit/{id}',[ComplaintController::class, 'complaintedit'])->name('complaintedit');
		// Complaint Refer Controller Routes//

		Route::resource('complaints', ComplaintController::class);

		Route::post('complaint-internal-log',[ComplaintReferController::class, 'complaintInternalLog'])->name('store.complaint_internal_log');

		Route::resource('complaintrefers', ComplaintReferController::class);

		Route::get('filter/{society_id}/status/{complaint_type}',[FiltersDashboardDataController::class, 'getComplaints'])->name('filter.complaints');
	});

	// Service management module
	Route::group(['prefix' => '/ssm', 'middleware' => 'can:view-service-management'], function(){

	    Route::post('request-service-internal-log',[RequestServiceController::class, 'storeInternalLog'])->name('store.service_internal_log');

		Route::resource('servicetypes', ServiceTypeController::class);
		Route::resource('subtypes', ServiceSubTypeController::class);
		Route::resource('services', ServiceController::class);
		Route::resource('servicepackages', ServicePackageController::class);
		Route::post('/requestservice/addremarks',[RequestServiceController::class, 'requestServiceRemarks'])->name('request.addremarks');
		Route::post('/requestservice/updatestatus',[RequestServiceController::class, 'updateStatus'])->name('request.updatestatus');
		Route::post('/requestservice/packgServiceRequest',[RequestServiceController::class, 'approveCancelRequest'])->name('request.pckgservice');

		Route::get('getRequestServicesList',[RequestServiceController::class, 'getRequestServicesList'])->name('getRequestServicesList');

		Route::resource('requestservice', RequestServiceController::class);
		Route::post('/userservices/update',[UserServiceController::class, 'update'])->name('userservices.update');
		Route::resource('userservices', UserServiceController::class)->only([
			'index', 'show', 'edit'
		]);
		Route::resource('servicedevices', ServiceDeviceController::class);
		Route::get('/smart-service-request',[SmartServiceRequestController::class, 'index'])->name('smr.index');

		Route::get('filter/{society_id}/status/{service_type}',[FiltersDashboardDataController::class, 'getServices'])->name('filter.services');
	});

	// invoice module
	Route::group(['prefix' => '/invoices',  'middleware' => 'can:view-invoices'], function(){
	    Route::get('/all-userinvoices',[CustomInvoiceController::class, 'getUserAllInvoices']);

	    Route::get('/filter-invoice/{filter}',[InvoiceController::class, 'getWithFilters'])->name('filter.invoices');
		Route::resource('invoicepayment', InvoiceController::class);
		Route::post('/invoicepayment',[InvoiceController::class, 'invoicePayment'])->name('invoice.payment');
		Route::get('/invoice/monthly/{id}',[InvoiceController::class, 'MonthlyInvoice'])->name('minvoice.create');
		Route::post('/invoice/storemonthly',[InvoiceController::class, 'storeMonthlyInvoice'])->name('minvoice.store');
		Route::get('/invoice-detail/{id}',[InvoiceController::class, 'InvoiceDetail'])->name('invoice.detail');
		Route::resource('invoice', InvoiceController::class);
		Route::post('/custom-invoice/post',[CustomInvoiceController::class, 'addPayment'])->name('custominvoice.payment');
		Route::resource('custominvoice', CustomInvoiceController::class);

		Route::resource('imposedfine', ImposedFineController::class);
	});

	// Fine & Planties
	Route::group(['prefix' => '/fines&planties', 'middleware' => 'can:view-fine-penalties'], function(){
		// addPayment
		Route::post('/fine-payment',[FineController::class, 'addPayment'])->name('fine.payment');
		Route::resource('fines', FineController::class);
	});

	// invoice module
	Route::group(['prefix' => '/taxes'], function(){
		Route::post('/store-tax',[TaxController::class, 'store'])->name('taxes.store');;
	});

	Route::group(['prefix' => '/quick-complaints'], function(){
		Route::get('/subdepartment/{id}',[QuickComplaintController::class, 'getWithSubdepartment'])->name('department.quick');
		Route::resource('qkcomplaints', QuickComplaintController::class);
	});

	Route::group(['prefix' => '/incident', 'middleware' => 'can:view-incident-reporting'], function(){
		Route::resource('reports', IncidentReportController::class);
	});
	Route::group(['prefix' => '/sos', 'middleware' => 'can:view-sos'], function(){
		Route::resource('society_sos', SocietySosController::class);
	});

	Route::group(['prefix' => '/departmental-services'], function(){
		Route::get('/departments',[DepartmentalServicesController::class, 'getDeparments'])->name('getDeparments');
		Route::get('/subdepartments/{id}',[DepartmentalServicesController::class, 'getDepartmentSubdepartments'])->name('getDepartmentSubdepartments');

		Route::put('/update-service/{id}',[DepartmentalServicesController::class, 'update'])->name('service-update');

		Route::get('/department/{depart_id}/subdepart/{subdepart_id}',[DepartmentalServicesController::class, 'getServiceWithFilter'])->name('departservice-filter');

		Route::resource('depart_services', DepartmentalServicesController::class)->except([
            'update'
        ]);


		Route::post('/request-depart-service/update-status',[RequestDepartServiceController::class, 'UpdateRequestStatus'])->name('request_depart_service_status');

		Route::post('/depart-service/update-status/{id}',[RequestDepartServiceController::class, 'storeinternalLogs'])->name('depart_service.internalLogs');

		Route::get('/get-sent-requests',[RequestDepartServiceController::class, 'getSentRequests'])->name('getSentRequests');

		Route::get('/get-received-requests',[RequestDepartServiceController::class, 'getReceivedRequests'])->name('getReceivedRequests');

		Route::resource('request_depart_service', RequestDepartServiceController::class);
	});

});

Route::get('/list/{id}',[SendNotification::class, 'notificationList'])->name('notification.list');
