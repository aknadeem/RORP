<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Complaint\ComplaintReferController;
use App\Http\Controllers\DealsDiscounts\DealsDiscountsController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Department\SubDepartmentController;
use App\Http\Controllers\EventManagement\EventController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Notification\SendNotification;
use App\Http\Controllers\Pages\ByLawController;
use App\Http\Controllers\ResidentManagement\ResidentDataController;
use App\Http\Controllers\ResidentManagement\ResidentFamilyController;
use App\Http\Controllers\ResidentManagement\ResidentHandyMenController;
use App\Http\Controllers\ResidentManagement\ResidentServentController;
use App\Http\Controllers\ResidentManagement\ResidentTenantController;
use App\Http\Controllers\ResidentManagement\ResidentVehicleController;
use App\Http\Controllers\Department\QuickComplaintController;
use App\Http\Controllers\RestApi\InvoiceApiController;
use App\Http\Controllers\ServiceManagement\RequestServiceController;
use App\Http\Controllers\ServiceManagement\ServiceApiController;
use App\Http\Controllers\ServiceManagement\ServiceController;
use App\Http\Controllers\SocialMedia\NewsController;
use App\Http\Controllers\SocialMedia\SocialMediaController;
use App\Http\Controllers\SocietyManagement\IncidentReportController;
use App\Http\Controllers\SocietyManagement\SocietySosController;
use App\Http\Controllers\SocietyManagement\SocietyController;
use App\Http\Controllers\Pages\SopLawController;
use App\Http\Controllers\Pages\TwoFourSevenController;
use App\Http\Controllers\Fineplanties\FineController;
use App\Http\Controllers\Fineplanties\ImposedFineController;
use App\Http\Controllers\Invoice\CustomInvoiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Complaint\FiltersDashboardDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/send-signup-otp/{to}/sms/{pin}', [HomeController::class, 'smsApi']);
Route::post('forgot-password', [UserLoginController::class, 'forgotPassword']);
Route::post('reset-password', [UserLoginController::class, 'reset']);
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'dashboard'

], function ($router) {
    Route::get('/summary', [HomeController::class, 'dashboardApi']); 
    // Route::get('/filtercomplaints/{type}', [FiltersDashboardDataController::class, 'getComplaintsForApi']);
    Route::get('filterservices/{society_id}/{service_type}',[FiltersDashboardDataController::class, 'getServices']);
    Route::get('filtercomplaints/{society_id}/{complaint_type}',[FiltersDashboardDataController::class, 'getComplaints']);
    // Route::get('/filterservices/{type}', [FiltersDashboardDataController::class, 'getServicesForApi']);   
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'complaints'
], function ($router) {
    Route::get('/department/{depid}/subdepartment/{subid}', [ComplaintReferController::class, 'complaintWithDepartments']);
    Route::post('/addcomplaint', [ComplaintController::class, 'apiComplaint']);
    Route::put('/update', [ComplaintController::class, 'update']);
    Route::get('/destroy', [ComplaintController::class, 'destroy']);
    Route::get('/complaint/{id}', [ComplaintController::class, 'show']);
    Route::get('/refercomplaints', [ComplaintReferController::class, 'index']);
    Route::get('/filterComplaints/{id}', [ComplaintReferController::class, 'filterComplaint']);
    Route::get('/departmentshods', [ComplaintReferController::class, 'HodsSupervisors']);
    Route::post('/supervisorfeedback',[ComplaintController::class, 'WorkingInComplaint']);
    Route::post('/managerfeedback',[ComplaintController::class, 'complaintStatusChange']);
    Route::post('/feedback',[ComplaintController::class, 'complaintfeedback']);
    Route::post('/complaint-internal-log',[ComplaintReferController::class, 'complaintInternalLog']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'residentvehicle'

], function ($router) {
    Route::get('/vehicles', [ResidentVehicleController::class, 'index']);
    Route::get('/vehicle-types', [ResidentVehicleController::class, 'vehicleTypes']);
	Route::get('/vehicle/{id}', [ResidentVehicleController::class, 'show']);
	Route::post('/store', [ResidentVehicleController::class, 'store']);
	Route::get('/myvehicle/{id}', [ResidentVehicleController::class, 'myvehicle']);
	Route::put('/update/{id}', [ResidentVehicleController::class, 'update']);
	Route::get('/destroy/{id}', [ResidentVehicleController::class, 'destroy']);   
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'residenthandymen'
], function ($router) {
    Route::get('/handymen', [ResidentHandyMenController::class, 'index']);
	Route::get('/handyman/{id}', [ResidentHandyMenController::class, 'show']);
	Route::post('/store', [ResidentHandyMenController::class, 'store']);
	Route::get('/myhandymen/{id}', [ResidentHandyMenController::class, 'myhandymen']);
	Route::put('/update/{id}', [ResidentHandyMenController::class, 'update']);
	Route::get('/destroy/{id}', [ResidentHandyMenController::class, 'destroy']); 
	Route::get('/types', [ResidentHandyMenController::class, 'handyServiceTypes']); 
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'residentservent'
], function ($router) {
    Route::get('/servents', [ResidentServentController::class, 'index']);
	Route::get('/servent/{id}', [ResidentServentController::class, 'show']);
	Route::post('/store', [ResidentServentController::class, 'store']);
	Route::get('/myservent/{id}', [ResidentServentController::class, 'myservent']);
	Route::put('/update/{id}', [ResidentServentController::class, 'update']);
	Route::get('/destroy/{id}', [ResidentServentController::class, 'destroy']);
	Route::get('/types', [ResidentServentController::class, 'serventTypes']);   
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'resident'
], function ($router) {
    Route::get('/tenants', [ResidentTenantController::class, 'index']);
    Route::post('/tenant/store', [ResidentTenantController::class, 'storeTenant']);
    Route::get('/tenant/update/{id}', [ResidentTenantController::class, 'update']);
    Route::get('/tenant/{id}', [ResidentTenantController::class, 'show']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'residentfamily'
], function ($router) {
    Route::get('/families', [ResidentFamilyController::class, 'index']);
	Route::get('/family/{id}', [ResidentFamilyController::class, 'show']);
	Route::get('/myfamily/{id}', [ResidentFamilyController::class, 'myfamily']);
	Route::post('/store', [ResidentFamilyController::class, 'store']);
	Route::put('/update/{id}', [ResidentFamilyController::class, 'update']);
	Route::get('/destroy/{id}', [ResidentFamilyController::class, 'destroy']);   
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'residentdata'

], function ($router) {
    Route::get('/residents', [ResidentDataController::class, 'index'])->middleware('auth:api');
	Route::get('/resident/{id}', [ResidentDataController::class, 'show'])->middleware('auth:api');
	Route::post('/store', [ResidentDataController::class, 'store']);
	Route::get('/tenant/{id}', [ResidentTenantController::class, 'show']);
	Route::post('/verifypins', [ResidentDataController::class, 'verifyEpinMpin']);
	Route::put('/update/{id}', [ResidentDataController::class, 'update'])->middleware('auth:api');
	Route::get('/destroy/{id}', [ResidentDataController::class, 'destroy'])->middleware('auth:api');
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'societymanagement'

], function ($router) {
	Route::get('/societies', [SocietyController::class, 'index']); 
	Route::get('/sectors/{id}', [SocietyController::class, 'getSocietySectors']); 
      
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'department'

], function ($router) {
	Route::get('/departments', [DepartmentController::class, 'forApi'])->middleware('auth:api');
	Route::get('/sub-departments', [SubDepartmentController::class, 'index'])->middleware('auth:api');
	Route::get('/subdepartments', [SubDepartmentController::class, 'getSocietySectors'])->middleware('auth:api');
	Route::get('/subdepartment/{id}', [SubDepartmentController::class, 'getSocietySectors'])->middleware('auth:api');
	Route::get('/departmentsubdepartments', [SubDepartmentController::class, 'subdepartmentWithManagers']);
});


Route::put('/residentdata/storeImage/{id}', [ResidentDataController::class, 'storeUpdateImage']);
Route::put('/residentfamily/storeImage/{id}', [ResidentFamilyController::class, 'storeFamilyImage']);
Route::put('/residentservent/storeImage/{id}', [ResidentServentController::class, 'storeServentImage']);
Route::put('/residenthandymen/storeImage/{id}', [ResidentHandyMenController::class, 'storeHandyImage']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/is-valid-token', [AuthController::class, 'isValidToken']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/update-password', [UserLoginController::class, 'changePasswordApi'])->middleware('auth:api');
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'pages'

], function ($router) {
	Route::get('/aboutus', [App\Http\Controllers\Pages\AboutUsController::class, 'index']);
	Route::get('/aboutus/{id}', [App\Http\Controllers\Pages\AboutUsController::class, 'show']);
	
	Route::get('/flashnews', [App\Http\Controllers\SocialMedia\NewsController::class, 'getFlashNews']);
	
	Route::get('/news', [NewsController::class, 'index'])->middleware('auth:api');
	Route::get('/news/{id}', [NewsController::class, 'show'])->middleware('auth:api');
	Route::get('/socialmedias', [SocialMediaController::class, 'index'])->middleware('auth:api');
	Route::get('/socialmedia/{id}', [SocialMediaController::class, 'show'])->middleware('auth:api');
	Route::get('/events', [EventController::class, 'index']); 
	Route::get('/event/{id}', [EventController::class, 'show']); 
	Route::get('/twofoursevens', [TwoFourSevenController::class, 'index'])->middleware('auth:api');
	Route::get('/twofourseven/{id}', [TwoFourSevenController::class, 'show'])->middleware('auth:api');
	Route::get('/sopslaws', [SopLawController::class, 'index'])->middleware('auth:api');
	Route::get('/sopslaw/{id}', [SopLawController::class, 'show'])->middleware('auth:api');
	Route::get('/bylaws', [ByLawController::class, 'index'])->middleware('auth:api');
	Route::get('/bylaw/{id}', [ByLawController::class, 'show'])->middleware('auth:api');  
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'deals'

], function ($router) {
	Route::get('/deals', [DealsDiscountsController::class, 'index']);
	Route::get('/deal/{id}', [DealsDiscountsController::class, 'show']);
	Route::get('/societydeal/{id}', [DealsDiscountsController::class, 'getsocietydeal']);
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'notification'

], function ($router) {
	Route::get('/news', [NewsController::class, 'index']);
	Route::get('/news/{id}', [NewsController::class, 'show']);
});


Route::group([
	'middleware' => 'api',
    'prefix' => 'customnotification'

], function ($router) {
	Route::get('/markasread',[SendNotification::class, 'MarkAsRead']);
	Route::post('/mark/read',[SendNotification::class, 'markRead']);
	Route::get('/create',[SendNotification::class, 'createadminNotification']);
	Route::post('/store',[SendNotification::class, 'storeadminNotification']);
	Route::get('/list/{id}',[SendNotification::class, 'notificationList']);   
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'services'

], function ($router) {
	Route::get('/services',[ServiceController::class, 'index'])->middleware('auth:api');
	Route::get('/servicetypes',[ServiceController::class, 'serviceTypesAPi']);
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'ssm'
], function ($router) {
	Route::get('/services',[ServiceApiController::class, 'services']);
	Route::get('/smart-services',[ServiceApiController::class, 'smartServices']);
	Route::get('/service-packages/{id}',[ServiceApiController::class, 'servicePackages']);
	Route::get('/service-devices/{id}',[ServiceApiController::class, 'serviceDevices']);
	Route::get('/service-requests',[ServiceApiController::class, 'serviceRequests'])->middleware('auth:api');
	Route::get('/simple-service-requests',[ServiceApiController::class, 'getSimpleServiceRequests'])->middleware('auth:api');
	Route::get('/smart-service-requests',[ServiceApiController::class, 'getSmartServiceRequests'])->middleware('auth:api');
	Route::post('/request-smart-service',[ServiceApiController::class, 'requestSmartService'])->middleware('auth:api');
	Route::post('/request-service',[RequestServiceController::class, 'store'])->middleware('auth:api');
	Route::get('/request-service-detail/{id}',[RequestServiceController::class, 'show']);
	
	Route::get('/simple-service-requests/department/{depid}/subdepartment/{subid}',[ServiceApiController::class, 'getSimpleServiceRequestsWithDepartments']);
	
	Route::get('/smart-service-requests/department/{depid}/subdepartment/{subid}',[ServiceApiController::class, 'getSmartServiceRequestsWithDepartments']);
	
	
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'invoices'

], function ($router) {
	Route::get('/user-invoices/{id}',[InvoiceApiController::class, 'index']);
	Route::get('/user-invoice-detail/{id}',[InvoiceApiController::class, 'show']);
});


Route::group([
	'middleware' => 'api',
    'prefix' => 'fines'

], function ($router) {
	Route::get('/user-fines/{id}',[FineController::class, 'getUserFineApi']);
	Route::get('/user-fine-detail/{id}',[ImposedFineController::class, 'show']);
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'custom'
], function ($router) {
	Route::get('/user-invoice/{id}',[CustomInvoiceController::class, 'getUserInvoices']);
	Route::get('/invoice-detail/{id}',[CustomInvoiceController::class, 'show']);
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'verify'
], function ($router) {
	Route::get('/email/{email}',[HomeController::class, 'checkEmailExists']);
});

// incidents reporting api
Route::group([
	'middleware' => 'auth:api',
    'prefix' => 'incident'
], function ($router) {
	Route::get('/reports',[IncidentReportController::class, 'index']);
	Route::get('/report/show/{id}',[IncidentReportController::class, 'show']);
	Route::post('/report/store',[IncidentReportController::class, 'store']);
	Route::put('/report/update/{id}',[IncidentReportController::class, 'update']);
});

Route::group([
	'middleware' => 'auth:api',
    'prefix' => 'sos'
], function ($router) {
	Route::get('/list',[SocietySosController::class, 'index']);
	Route::get('/show/{id}',[SocietySosController::class, 'show']);
	Route::post('/store',[SocietySosController::class, 'store']);
	Route::put('/update/{id}',[SocietySosController::class, 'update']);
});

Route::group([
	'middleware' => 'auth:api',
    'prefix' => 'quick-complaint'
], function ($router) {
	Route::get('/list',[QuickComplaintController::class, 'index']);
	Route::get('/show/{id}',[QuickComplaintController::class, 'show']);
	Route::get('/subdepartment/{id}',[QuickComplaintController::class, 'getWithSubdepartment']);
});

