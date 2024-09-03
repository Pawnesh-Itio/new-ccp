<?php
// Api Controller
use App\Http\Controllers\Api\CosmosUpiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UpiController;
use App\Http\Controllers\Api\PayoutController;
use App\Http\Controllers\Api\OpenacquiringController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\RechargeController;
// Android Controller
use App\Http\Controllers\Android\TransactionController;
use App\Http\Controllers\Android\FundController;
use App\Http\Controllers\Android\BillpayController;
use App\Http\Controllers\Android\PancardController;
use App\Http\Controllers\Android\MoneyController;
use App\Http\Controllers\Android\UserController;
use Illuminate\Http\Request;

// S2S Integration of acquirer
Route::any('Directapi',[PaymentController::class,'direct_api']);

Route::group(['prefix' => 'upi'], function() {
    Route::any('verify/sdk', [UpiController::Class,'verifyupisdk']);
    Route::any('generateauthtoken', [UpiController::class,'generateauthtoken']);
    Route::any('iserveuonboard', [UpiController::class,'iserveuonboardg']);
    Route::any('checktxnstatus', [UpiController::Class,'checktxnstatus']);
    Route::any('generatestaticqr', [UpiController::class,'generatestaticqr']);
    Route::any('generateupi', [UpiController::class,'generatedynamicqr']);
    Route::any('initiatedynamicqr', [UpiController::Class,'initiatedynamicqr']);
    Route::post('vpa/register', [UpiController::class,'vpaRegister']);
});

Route::group(['prefix' => 'ipayout'], function() {
    Route::any('transaction', [PayoutController::Class,'transaction']);
    Route::any('initiate', [PayoutController::Class,'initiatepayout']);
    Route::any('verify', [PayoutController::class,'verifypayout']);
});


//Route::group(['prefix' => 'openacquiring'], function() {
    Route::any('payment', [OpenacquiringController::class,'initiatepayment']);
    Route::any('refund', [OpenacquiringController::Class,'refund']);
    Route::any('retrieve', [OpenacquiringController::class,'retrieve']);
    Route::any('capture', [OpenacquiringController::Class,'capture']);
//});

Route::group(['prefix'=> 'callback/update'], function() {
    Route::any('playsoundbox', [App\Http\Controllers\CallbackController::Class,'playsoundbox']);
    Route::any('pinwalletcallbkp', [App\Http\Controllers\CallbackController::class,'pinwalletcallbkp']);
    Route::any('kwikpaisacallbkp', [App\Http\Controllers\CallbackController::class,'kwikpaisacallbkp']);
    Route::any('iserveuPayinCallbkp', [App\Http\Controllers\CallbackController::Class,'iserveuPayinCallbkp']);
    Route::any('iserveuPayoutCallbkp', [App\Http\Controllers\CallbackController::Class,'iserveuPayoutCallbkp']);
    Route::any('prod/evokCallbkp', [App\Http\Controllers\CallbackController::Class,'evokCallbkp']);
    Route::any('{api}', [App\Http\Controllers\CallbackController::Class,'callback']);
    
});

Route::any('statusCheck', [CosmosUpiController::Class,'statusCheck']);

Route::group(['prefix' => 'upi2'], function() {
    Route::any('verifyVPA', [CosmosUpiController::class,'verifyVPA']);
    Route::any('initiateRequest', [CosmosUpiController::Class,'initiateRequest']);
    Route::any('sdkVerify', [CosmosUpiController::Class,'sdkVerify']);
    Route::any('checkStatus', [CosmosUpiController::Class,'checkStatus']);
    Route::any('transfer', [CosmosUpiController::class,'upiTransfer']);
    Route::any('generateStaticQr', [CosmosUpiController::Class,'staticQrIntent']);
    Route::any('generateQr', [CosmosUpiController::class,'QrIntent']);
    Route::any('callbackDecryptmoneytransfer', [CosmosUpiController::class,'callbackDecrypt']);
    
/*    Route::any('upi/transferUpi', 'CosmosUpiController@upiTransfer');
    Route::any('upi/upiReport', 'CosmosUpiController@upiReport');
    Route::any('upi/checkCron', 'CronController@upiUpdate');

    Route::any('upi/qrStatusRRN', 'CosmosUpiController@qrStatusRRN');
    Route::any('upi/qrStatus', 'CosmosUpiController@qrStatus');
    Route::any('upi/qrReport', 'CosmosUpiController@qrReport');*/
});

Route::group(['prefix' => 'checkaeps'], function() {
    Route::any('icici/initiate', [App\Http\Controllers\AepsController::Class,'iciciaepslog'])->middleware('transactionlog:aeps');
    Route::any('icici/update', [App\Http\Controllers\AepsController::Class,'iciciaepslogupdate'])->middleware('transactionlog:aepsupdate');
});

Route::any('upi/callback', [App\Http\Controllers\UpiController::Class,]);
Route::any('getbal/{token}', [ApiController::class,'getbalance']);
Route::any('getip', [ApiController::class,'getip']);

/*Recharge Api*/
Route::any('getprovider', [RechargeController::Class,'getProvider']);
Route::any('recharge/pay', [RechargeController::Class,'payment'])->middleware('transactionlog:recharge');
Route::any('recharge/status', [RechargeController::Class,'status']);

/*Android App Apis*/
Route::any('android/auth/user/register', [UserController::class,'registration']);
Route::any('android/auth', [UserController::Class,'login']);
Route::any('android/auth/logout', [UserController::Class,'logout']);
Route::any('android/auth/reset/request', [UserController::class,'passwordResetRequest']);
Route::any('android/auth/reset', [UserController::Class,'passwordReset']);
Route::any('android/auth/password/change', [UserController::Class,'changepassword']);
Route::any('android/auth/user/getactive', [UserController::Class,'getactive']);

// Profile Android 
Route::any('android/getstate', [UserController::Class,'getState']);
Route::any('android/auth/profile/change', [UserController::Class,'changeProfile']);

Route::any('android/getbalance', [UserController::Class,'getbalance']);
Route::any('android/aeps/initiate', [UserController::Class,'aepsInitiate'])->middleware('transactionlog:aeps');
Route::any('android/aeps/status', [UserController::Class,'aepsStatus']);
Route::any('android/secure/microatm/initiate', [UserController::Class,'microatmInitiate'])->middleware('transactionlog:microatm');
Route::any('android/secure/microatm/update', [UserController::Class,'microatmUpdate']);

Route::any('android/transaction', [TransactionController::Class,'transaction']);
Route::any('android/fundrequest', [FundController::class,'transaction'])->middleware('transactionlog:fund');
Route::any('android/tpin/getotp', [UserController::Class,'getotp']);
Route::any('android/tpin/generate', [UserController::Class,'setpin']);

/*Recharge Android Api*/

Route::any('android/recharge/providers', [\App\Controllers\Android\RechargeController::class,'providersList']);
Route::any('android/recharge/pay', [\App\Controllers\Android\RechargeController::class,'transaction'])->middleware('transactionlog:recharge');
Route::any('android/recharge/status', [\App\Controllers\Android\RechargeController::class,'status']);
Route::any('android/transaction/status', [\App\Controllers\Android\TransactionController::class,'transactionStatus'])->middleware('transactionlog:transtatus');
Route::any('android/recharge/getplan', [\App\Controllers\Android\RechargeController::class,'getplan']);

/*Bill Android Api*/

Route::any('android/billpay/providers', [BillpayController::class,'providersList']);
Route::any('android/billpay/getprovider', [BillpayController::Class,'getprovider']);
Route::any('android/billpay/transaction', [BillpayController::Class,'transaction'])->middleware('transactionlog:billpay');
Route::any('android/billpay/status', [BillpayController::class,'status']);

/*Bill Android Api*/

Route::any('android/pancard/transaction', [PancardController::Class,'transaction'])->middleware('transactionlog:pancard');
Route::any('android/pancard/status', [PancardController::Class,'status']);

/*Bill Android Api*/

Route::any('android/dmt/transaction', [MoneyController::Class,'transaction'])->middleware('transactionlog:dmt');

/*Member Create Android Api*/
Route::any('android/member/create', [UserController::class,'addMember']);
Route::any('android/member/idstock', [UserController::Class,'idStock']);
Route::any('android/member/list', [TransactionController::class,'transaction']);


Route::any('android/aepsregistration', [UserController::class,'aepskyc']);
Route::any('android/GetState', [UserController::Class,'GetState']);
Route::any('android/GetDistrictByState', [UserController::Class,'GetDistrictByState']);

