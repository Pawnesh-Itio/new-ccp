<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CosmosUpiController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DirectApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/  
    // Checkout-Page Integeration
    Route::any('checkout-form', [PaymentController::class, 'checkoutForm'])->name('checkout-form');
    Route::any('checkout', [PaymentController::class, 'checkout'])->name('checkout')->withoutMiddleware(['csrf']);
	Route::any('Strippayinitiate',[PaymentController::class,'Strippayinitiate'])->name('Strippayinitiate');
	Route::any('gtw_checkout', [PaymentController::class, 'gtw_checkout'])->name('gtw_checkout');
	Route::any('gtw_checkout_form',[PaymentController::class,'gtw_checkout_form'])->name('gtw_checkout_form');
	Route::any('gtw_fetch', [PaymentController::class, 'gtw_fetch'])->name('gtw_fetch');
	Route::any('epayCheckout', [PaymentController::class, 'epayCheckout'])->name('epayCheckout');
	Route::any('failur', [PaymentController::class, 'failur'])->name('failur');
	Route::any('webhook_url', [PaymentController::class, 'webhook'])->name('webhook');
	Route::any('return_url', [PaymentController::class, 'return_url'])->name('return_url');
	Route::any('success', [PaymentController::class, 'success'])->name('success');
	Route::any('failur', [PaymentController::class, 'failur'])->name('failur');
	

	Route::any('/state/{country?}', [RegisterController::class, 'state'])->name('state');
	Route::any('/city/{state?}', [RegisterController::class, 'city'])->name('city');

	// S-2-S Integeration.
	Route::any('Directapi', [DirectApiController::class,'direct_api'])->name('direct_api');

    Route::any('cosmosonboard', [UserController::class,'cosmosonboard'])->name('cosmosonboard');
    Route::any('verifyVPA', [CosmosUpiController::class,'verifyVPA']);
    Route::any('upiTransfer', [CosmosUpiController::class,'upiTransfer']);
    Route::any('txnStatus', [CosmosUpiController::class,'txnStatus']);
    Route::any('upiReport', [CosmosUpiController::Class,'upiReport']);
    Route::any('dQr', [CosmosUpiController::Class,'dQr']);
    Route::any('callbackDecrypt', [CosmosUpiController::class,'callbackDecrypt']);
    Route::any('qrStatusRRN/{type}', [CosmosUpiController::class,'qrStatusRRN']);
    Route::any('qrStatus/{type}', [CosmosUpiController::class,'qrStatus']);
    Route::any('qrReport', [CosmosUpiController::class,'qrReport']);
    Route::any('order/{orderId}', [App\Http\Controllers\Api\CosmosUpiController::class,'orderIdInitiate']);
	// OTP
	Route::post('forgot-pass-otp', [UserController::class,'forgotPasswordOtp'])->name('forgotPasswordOtp');
	// Auth Routes
	Route::group(['prefix' => 'auth', 'middleware' => 'auth'], function (){
		Route::post('reset', [UserController::class,'passwordReset'])->name('authReset');
		Route::post('register', [UserController::class,'registration'])->name('register');
		Route::post('getotp', [UserController::class,'getotp'])->name('getotp');
		Route::post('setpin', [UserController::class,'setpin'])->name('setpin');
		Route::post('updateSound', [UserController::class,'updateSound'])->name('updateSound');
		Route::post('updateGstRate', [UserController::class,'updateGstRate'])->name('updateGstRate');
	});

Route::get('/test', [TestController::class,'index'])->name('test');

Route::post('wallet/balance', [HomeController::class,'getbalance'])->name('getbalance');
Route::get('setpermissions', [HomeController::class,'setpermissions']);
Route::get('setscheme', [HomeController::class,'setscheme']);
Route::get('checkcommission', [HomeController::class,'checkcommission']);
Route::get('getmyip', [HomeController::Class,'getmysendip']);
Route::get('balance', [HomeController::class,'getbalance'])->name('getbalance');
Route::get('mydata', [HomeController::class,'mydata']);
Route::get('bulkSms', [HomeController::class,'mydata']);
// Tools Routs
Route::group(['prefix' => 'tools', 'middleware' => ['auth','company','checkrole:admin','user']], function (){
	Route::get('{type}', [RoleController::class,'index'])->name('tools');
	Route::post('{type}/store', [RoleController::class,'store'])->name('toolsstore');
	Route::post('setpermissions',[RoleController::class,'assignPermissions'])->name('toolssetpermission');
	Route::post('get/permission/{id}', [RoleController::Class,'getpermissions'])->name('permissions');
	Route::post('getdefault/permission/{id}', [RoleController::class,'getdefaultpermissions'])->name('defaultpermissions');
});
// Statement Routes
Route::group(['prefix' => 'statement', 'middleware' => ['auth', 'company','user']], function() {    
	Route::get("export/{type}", [StatementController::class,'export'])->name('export');
	Route::get("openacquiring", [StatementController::class,'openacquiring'])->name('openacquiring');
	Route::post("openacquiring", [StatementController::class,'openacquiring'])->name('store_openacquiring');
	Route::get("openacquiring_payment", [StatementController::class,'openacquiring_payment']);
	Route::get('{type}/{id?}/{status?}', [StatementController::class,'index'])->name('statement'); 
	Route::post('update', [CommonController::class,'update'])->name('statementUpdate')->middleware('activity');
	Route::post('status', [CommonController::class,'status'])->name('statementStatus');
	Route::post('open_acquiring_status',  [CommonController::class,'open_acquiring_status'])->name('open_acquiring_status');
	Route::post('capture_refund', [CommonController::class,'capture_refund'])->name('capture_refund')->middleware('activity');
});
// Members Routes
Route::group(['prefix' => 'member', 'middleware' => ['auth', 'company','user']], function (){
	
	Route::post('getmemberbenelist', [MemberController::class,'getmemberbenelist'])->name('getmemberbenelist');
	Route::get('{type}/{action?}', [MemberController::class,'index'])->name('member');
	Route::post('store', [MemberController::class,'create'])->name('memberstore');
	Route::post('commission/update', [MemberController::class,'commissionUpdate'])->name('commissionUpdate')->middleware('activity');
	Route::post('getcommission', [MemberController::class,'getCommission'])->name('getMemberCommission');
	Route::post('getpackagecommission', [MemberController::class,'getPackageCommission'])->name('getMemberPackageCommission');
	Route::post('getScheme', [MemberController::class,'getScheme'])->name('getScheme');
	Route::post('getAcquirerList', [MemberController::class,'getAcquirerList'])->name('getAcquirerList');
	Route::post('addAcquirer', [MemberController::class,'addAcquirer'])->name('addAcquirer');
	Route::post('getAllAcquirers',[MemberController::class,'getAllAcquireres'])->name('getAllAcquirers');
	Route::post('acquirerMemberDelete', [MemberController::class,'acquirerMemberDelete'])->name('acquirerMemberDelete');
	Route::post('s2s_agent_update',[MemberController::class,'s2s_agent_update'])->name('s2s_agent_update');
});
// Portal Routes
Route::group(['prefix'=> 'portal', 'middleware' => ['auth', 'company','user']], function() {
	Route::get('{type}', [PortalController::class,'index'])->name('portal');
	Route::post('store', [PortalController::class,'create'])->name('portalstore');
});
// Fund Routes
Route::group(['prefix'=> 'fund', 'middleware' => ['auth', 'company','user']], function() {
	Route::get('{type}/{action?}', [FundController::class,'index'])->name('fund');
	Route::post('transaction', [FundController::Class,'transaction'])->name('fundtransaction')->middleware('transactionlog:fund');
});
// Profile Routes
Route::group(['prefix' => 'profile', 'middleware' => ['auth','user']], function() {
	Route::get('/view/{id?}', [SettingController::class,'index'])->name('profile');
	Route::get('certificate', [SettingController::class,'certificate'])->name('certificate');
	Route::post('update', [SettingController::class,'profileUpdate'])->name('profileUpdate')->middleware('activity');
	Route::post('kyc_update', [SettingController::class,'kyc_update'])->name('kyc_update')->middleware('activity');
	Route::post('password_update', [SettingController::class,'password_update'])->name('password_update')->middleware('activity');
});
// Setup Routes
Route::group(['prefix' => 'setup', 'middleware' => ['auth','company','user']], function() {
	Route::get('{type}', [SetupController::class,'index'])->name('setup');
	Route::post('update', [SetupController::class,'update'])->name('setupupdate')->middleware('activity');
	Route::post('getAcquirerFields', [SetupController::class,'getAcquirerFields'])->name('getAcquirerFields');
	Route::post('acquirerDelete',[SetupController::class,'acquirerDelete'])->name('acquirerDelete');
	Route::post('acquirerFieldDelete',[SetupController::class,'acquirerFieldDelete'])->name('acquirerFieldDelete');
});
// Resources Routes
Route::group(['prefix' => 'resources', 'middleware' => ['auth','user','company']], function (){
	Route::get('{type}/{id?}', [ResourceController::class,'index'])->name('resource');
	Route::post('update', [ResourceController::class,'update'])->name('resourceupdate')->middleware('activity');
	Route::post('companydata',[ResourceController::class,'companydata'])->name('companydata');
	Route::post('get/{type}/commission', [ResourceController::class,'getCommission']);
	Route::post('get/{type}/packagecommission', [ResourceController::class,'getPackageCommission']);
});
// Recharge Routes
// Bill Pay Routes
// Pancard Routes
// dmt Routes
// aeps Routes
// upi Routes
// Payout Routes
// Developers API Routes
Route::group(['prefix' => 'developer/api', 'middleware' => ['auth','user']], function (){
	Route::get('{type}', [ApiController::class, 'index'])->name('apisetup');
	Route::post('update',[ApiController::class, 'update'])->name('apitokenstore');
	Route::post('token/delete', [ApiController::class, 'tokenDelete'])->name('tokenDelete1');
});
// Complaint Routes
Route::group(['prefix' => 'complaint', 'middleware' => 'auth'], function() {
	Route::get('/', [ComplaintController::class,'index'])->name('complaint');
	Route::post('store', [ComplaintController::class,'store'])->name('complaintstore');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home'])->name('home');
	Route::get('/test-form', [HomeController::class, 'test']);
	Route::post('/test-update', [HomeController::class, 'testData']);
	Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard')->middleware('user');
	Route::post('/Data-Session',[HomeController::class,'DataSession'])->name('DataSession');
	Route::get('/latest-transaction',[HomeController::class,'latesttransaction'])->name('latesttransaction');
	Route::get('/data-count',[HomeController::class, 'datacount'])->name('datacount');
	Route::get('/top-user',[HomeController::class, 'topuser'])->name('topuser');
    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::post('/fetch/{type}/{id?}/{returntype?}', [CommonController::class, 'fetchData']);
	Route::post('block-user-update', [SettingController::class,'profileUpdate'])->middleware('activity');
	Route::post('block-user-resource-update', [ResourceController::class,'update'])->middleware('activity');
	Route::post('block-user-kyc-update', [SettingController::class,'kyc_update'])->middleware('activity');
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});
 
Route::post('get_state', [CommonController::class,'get_state'])->name('get_state');
Route::get('openacquiring', [StatementController::class,'index'])->name('openacquiring');
Route::get('openacquirings', [StatementController::class,'openacquirings'])->name('openacquirings');
Route::post('openacquiringstatement/{id?}', [CommonController::class,'openacquiringstatement'])->name('openacquiringstatement');
Route::post('openacquiring', [StatementController::class,'store_openacquiring']);
Route::get('qrcode', [UpiController::class,'index1'])->name('qrcode');
Route::get('caputure_payment', [CommonController::class,'caputure_payment'])->name('caputure_payment');

Route::get('artisancmd', function() {
	$output = Artisan::call('migrate:refresh');

    // Get the output of the command
    $status = Artisan::output();

    // Return the status as response
    return "<pre>$status</pre>";
});
Route::get('artisancmd-db2', function() {
	$output = Artisan::call('migrate:refresh',[
		'--path'=>'database/migrations/api',
		'--database'=>'pgsql_second'
	]);

    // Get the output of the command
    $status = Artisan::output();

    // Return the status as response
    return "<pre>$status</pre>";
});
Route::get('artisancmd-db-seed', function() {
	$output = Artisan::call('db:seed');

    // Get the output of the command
    $status = Artisan::output();

    // Return the status as response
    return "<pre>$status</pre>";
});
Route::get('php', function() {
	echo phpinfo();
});

// Guest
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create'])->middleware('check.password.step');
	Route::get('/countries', [RegisterController::class, 'countries'])->name('countries');
    Route::post('/register', [UserController::class, 'registration'])->name('registration');
	Route::get('/register/set-password', [RegisterController::class, 'SetPassword'])->name('SetPassword');
	Route::post('/register/set-password', [UserController::class, 'StorePassword'])->name('StorePassword');
	Route::post('getotp', [UserController::class,'emailverificationotp'])->name('registrationotp');
	Route::post('ConfirmEmail',[UserController::class,'ConfirmEmail'])->name('ConfirmEmail')->middleware('activity');
    Route::get('/login', [SessionsController::class, 'create'])->middleware('check.password.step');
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('reset', [UserController::class, 'passwordReset'])->name('authReset');
	Route::get('/login', function () {
		return view('session/login-session');
	})->name('login')->middleware('check.password.step');
});