<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Merchantkey;
use App\Models\Report;
use App\Models\Acquirer;
use App\Models\Merchantacquirermapping;
use Redirect;   
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
 public function checkoutForm(){
    $data['merchantData'] = Merchantkey::where("is_active","=","yes")
                            ->whereNotNull('public_key')
                            ->select("public_key", "terno")
                            ->get();
    return view('checkout.checkoutform')->with($data);
 }
 public function checkout(REQUEST $post){
    $rules = array(
        'public_key'      => 'required',
        'bill_amt'        => 'required',
        'bill_currency'   => 'required',
        'product_name'    => 'required',
        'fullname'        => 'required',
        'bill_email'      => 'required',
        'bill_address'    => 'required',
        'bill_city'       => 'required',
        'bill_country'    => 'required',
        'bill_zip'        => 'required',
        'bill_phone'      => 'required',
    );
    $validator = \Validator::make($post->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['Errors'=>"Required Peremeters are missing"], 422);
    }
    $data['bill_amt'] = $post['bill_amt'];
    $data['bill_currency'] = $post['bill_currency'];
    $data['public_key'] = $post['public_key'];
    $data['terNO'] = $post['terNO'];
    $data['checkout_url'] = $post['checkout_url'];
    $data['return_url'] = $post['return_url'];
    $data['success_url'] = $post['success_url'];
    $data['failur_url'] = $post['failur_url'];
    $data['webhook_url'] = $post['webhook_url'];
    $data['product_name'] = $post['product_name'];
    $data['fullname'] = $post['fullname'];
    $data['bill_email'] = $post['bill_email'];
    $data['bill_address'] = $post['bill_address'];
    $data['bill_city'] = $post['bill_city'];
    $data['bill_state'] = $post['bill_state'];
    $data['bill_country'] = $post['bill_country'];
    $data['bill_zip'] = $post['bill_zip'];
    $data['bill_phone'] = $post['bill_phone'];
    // Fetch Record using pubic_key and terNo 
    $action = Merchantkey::where('public_key',$data['public_key'])
                         ->where('is_active','yes')
                         ->first();
   if($action){
    $merchant_id = $action->user_id;
    // Get all acquirer of the merchant
    $acquirer = Acquirer::Join('merchant_acquirer_mapping', 'acquirers.acquirer_id','=','merchant_acquirer_mapping.acquirer_id')
    ->where('merchant_acquirer_mapping.merchant_id','=',$merchant_id)
    ->select('acquirers.*')
    ->get();
    $data['acquirer_data'] = $acquirer;
    return view('checkout.checkout')->with($data);
   }else{
    return response()->json(['Errors'=>"Public key is not registered with us"], 422);
   }
 }
 public function Strippayinitiate(Request $post){
    $secret_key = $post['test_secret_key'];// Initializing seceret key
    $public_key = $post['public_key'];// Our Panel Merchant public key
    $terno = $post['terno'];// Our Panel Merchant Ter No
    $acquirer_id = $post['acquirer_id']; // Acquirer Id
    $checkout_url = $post['checkout_url'];
    $return_url  = $post['return_url'];
    $webhook_url = $post['webhook_url'];
    $success_url = $post['success_url'];
    $failur_url  = $post['failur_url'];
    $fullname = $post['fullname'];
    $product_name = $post['product_name'];
    $bill_email = $post['bill_email'];
    $bill_address = $post['bill_address'];
    $bill_country = $post['bill_country'];
    $bill_city = $post['bill_city'];
    $bill_state = $post['bill_state'];
    $bill_zip = $post['bill_zip'];
    $bill_phone = $post['bill_phone'];
    $bill_amt = $post['bill_amt'];
    $bill_currency = $post['bill_currency'];
    $bill_ip = $_SERVER['REMOTE_ADDR'];// Ip Address
    $currDate = date('y-m-d h:i:s');
    $currTimestamp = strtotime($currDate);
    $reference=$currTimestamp; //Generate using timestamp
    $stripeToken = $post['stripeToken'];
    $card_holder_name = $post['card_holder_name'];
    
    // Initialize Data
    $orderId = 'ICB-'.date('YmdHis').rand(11111111,99999999);

    // Fetch User data
    $userData = Merchantkey::where("public_key",$public_key)
                           ->where("is_active","yes")
                           ->first();
    if($userData){
    // Get Acquirer
    $acquirerData = Acquirer::where('acquirer_id','=',$acquirer_id)
                  ->first();
    $slug = $acquirerData['acquirer_slug'];
    // Initial Insert data in db.
    $initialInsert['user_id'] = $userData->user_id;
    $initialInsert['amount'] = $bill_amt;
    $initialInsert['mobile'] = $bill_phone;
    $initialInsert['provider_id'] =0;
    $initialInsert['api_id'] =1;
    $initialInsert['trans_type'] = 'credit';
    $initialInsert['mytxnid'] = $orderId;
    $initialInsert['status'] = "pending";
    $initialInsert['product'] ="Stripe";
    $initialInsert['aepstype'] ='card';
    $insertedReport = Report::create($initialInsert);
    $reportId = $insertedReport->id;
    // Meta Data Array
    $customerMetaData = [
        'order_id' => $orderId,
        'registration_source' => 'website',
    ];
    $paymentIntentMetaData =[
      'merchant_key' =>$public_key, 
      'order_id' =>$orderId,
      'ip'=>$bill_ip,
      'invoice_number'=>$orderId
    ];
    // Create a customer
    $customer_data = http_build_query([
    'email' => $bill_email,
    'name' => $fullname,
    'phone'=> $bill_phone,
    'address' => [
        'line1' => $bill_address,
        'city' => $bill_city,
        'state' => $bill_state,
        'postal_code' => $bill_zip,
        'country' => $bill_country
    ],
    'metadata' =>$customerMetaData
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/customers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $customer_data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$secret_key:");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded'
));
$customer_response = curl_exec($ch);
$customer_data = json_decode($customer_response);
if(isset($customer_data->error)){
    echo "Customer creation failed: ".$customer_data->error->message;
    exit;
}
$customer_id = $customer_data->id;
// Create a payment method
$payment_method_data = http_build_query([
    'type' => 'card',
    'card' => [
        'token' => $stripeToken
    ]
]);

curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_methods");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_method_data);
$payment_method_response = curl_exec($ch);
$payment_method_data = json_decode($payment_method_response);
if(isset($payment_method_data->error)){
    echo "Payment method creation failed: ".$payment_method_data->error->message;
    exit;
}
$payment_method_id = $payment_method_data->id;

// Attach the payment method to the customer
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_methods/$payment_method_id/attach");
curl_setopt($ch, CURLOPT_POSTFIELDS, "customer=$customer_id");
$attach_response = curl_exec($ch);
$attach_data = json_decode($attach_response);
if(isset($attach_data->error)){
        $errMsg= $attach_data->error->message;
        $finalUpdate['status'] = "declined";
        $report = Report::find($reportId);
        $report->makeHidden(['fundbank', 'apicode', 'apiname', 'username', 'sendername', 'providername']);
        if($report){
            $report->update($finalUpdate);
        }
        return response()->json(['status' => "error",'error_msg'=>$errMsg,'return_url'=>$return_url,'failur_url'=>$failur_url], 200);
}

// Create a payment intent
$payment_intent_data = http_build_query([
    'amount' => $bill_amt*100, // $20 in cents
    'currency' => $bill_currency,
    'customer' => $customer_id,
    'payment_method' => $payment_method_id,
    'off_session' => 'true',
    'confirm' => 'true',
    'description' => $product_name,
    'shipping[name]' => $fullname,
    'shipping[address]'=> [
      'line1'=>$bill_address,
      'city'=>$bill_city,
      'country'=>$bill_country,
      'postal_code'=>$bill_zip,
    ],
    'return_url'=>$return_url,
    'metadata'=> $paymentIntentMetaData
]);

curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_intents");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_intent_data);
$payment_intent_response = curl_exec($ch);

// Check for errors
if ($payment_intent_response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Decode the response
    $payment_intent_data = json_decode($payment_intent_response);

    // Check if the payment intent was successful
    $sub_query = http_build_query($payment_intent_data).http_build_query($customer_data);
    // Preparing Response for Database
    $arrPayment_intent_data = json_decode($payment_intent_response,true);
    $arrCustomer_data = json_decode($customer_response,true);
    $mergedPayCustArr = array($arrPayment_intent_data,$arrCustomer_data);
    $dbResponse =  json_encode($mergedPayCustArr);
    if (isset($payment_intent_data->error)) {

        $finalUpdate['status'] = "failed";
        $finalUpdate['acquirer_id'] = $acquirer_id;
        $finalUpdate['response'] = $dbResponse;
        $report = Report::find($reportId);
        $report->makeHidden(['fundbank', 'apicode', 'apiname', 'username', 'sendername', 'providername']);
        if($report){
            $report->update($finalUpdate);
        }
        return response()->json(['status' => "failed",'error'=>$payment_intent_data->error->message,'return_url'=>$return_url,'failur_url'=>$failur_url], 200);
    } else {
        // Update Report with status success and payment intetn id.
        // Redirect User on success page 
        $paymentIntentId = $payment_intent_data->id;
        $finalUpdate['status'] = "success";
        $finalUpdate['refno'] = $paymentIntentId;
        $finalUpdate['number'] = $paymentIntentId;
        $finalUpdate['acquirer_id'] = $acquirer_id;
        $finalUpdate['response'] = $dbResponse;
        $finalUpdate['acquirer_slug'] = $slug;
        $report = Report::find($reportId);
        $report->makeHidden(['fundbank', 'apicode', 'apiname', 'username', 'sendername', 'providername']);
        if($report){
            $report->update($finalUpdate);
        }
        if(strpos($return_url,'?')!==false){
            $return_url = $return_url.'&'.$sub_query;
        }else{
            $return_url = $return_url.'?'.$sub_query;
        }
        return response()->json(['status' => "success",'return_url'=>$return_url,'success_url'=>$success_url], 200);
    }
}   
// Close cURL session
curl_close($ch);
    }else{
        return response()->json(['Errors'=>"Merchant is not registered with us"], 422);
    }

 }
 public function gtw_checkout(Request $post){
    $rules = array(
        'public_key'      => 'required',
        'bill_amt'        => 'required',
        'bill_currency'   => 'required',
        'product_name'    => 'required',
        'fullname'        => 'required',
        'bill_email'      => 'required',
        'bill_address'    => 'required',
        'bill_city'       => 'required',
        'bill_state'      => 'required',
        'bill_country'    => 'required',
        'bill_zip'        => 'required',
        'bill_phone'      => 'required',
        'ccno'            => 'required',
        'month'           => 'required',
        'year'            => 'required',
        'ccvv'            => 'required'
    );

    $validator = \Validator::make($post->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['Errors'=>"Required Peremeters are missing"], 422);
    }
    $postPublicKey = $post['public_key']; //Our panel Merchant public key 
    $postTerNo = $post['terno']; // Our panel Merchant TerNo

    // Get User Data
    $userData = Merchantkey::where("public_key",$postPublicKey)
    ->where("is_active","yes")
    ->first();
    if($userData){
    // Get Acquirer
    $acquirer_id = $post['acquirer_id'];
    $acquirerData = Acquirer::where('acquirer_id','=',$acquirer_id)
                  ->first();
    $slug = $acquirerData['acquirer_slug'];
    $status_nm ="";
    // variables for integration
    $api_endpoint = $post['api_endpoint'];
    $gtw_public_key = $post['gtw_public_key'];
    $gtw_terno = $post['gtw_terno'];
    $currDate = date('y-m-d h:i:s');
    $currTimestamp = strtotime($currDate);

    $gateway_url= $api_endpoint;
    $curlPost=array();
    $protocol = isset($_SERVER["HTTPS"])?'https://':'http://';
    $source_url=$protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
    //<!-- Key for integeration -->
    $curlPost['public_key']=$gtw_public_key;// GTW integeration public key
    //<!--Id for integeration -->
    $curlPost['terNO']=$gtw_terno; // GTW integeration terNo

    $orderId = 'ICB-'.date('YmdHis').rand(11111111,99999999); //Generate using timestamp
    $curlPost['bill_amt']= $post['bill_amt'];
    $curlPost['bill_currency']= $post['bill_currency'];
    $curlPost['product_name']= $post['product_name'];
    $curlPost['fullname']= $post['fullname'];
    $curlPost['bill_email']=$post['bill_email'];
    $curlPost['bill_address']= $post['bill_address'];
    $curlPost['bill_city']= $post['bill_city'];
    $curlPost['bill_state']= $post['bill_state'];
    $curlPost['bill_country']= $post['bill_country'];
    $curlPost['bill_zip']= $post['bill_zip'];
    $curlPost['bill_phone']= $post['bill_phone'];
    $curlPost['reference']= $currTimestamp;
    $curlPost['webhook_url']= $post['webhook_url'];
    $curlPost['return_url']= $post['return_url'];
    $curlPost['bill_ip']= $_SERVER['REMOTE_ADDR'];
    $curlPost['source_url']= $source_url;
    $curlPost['integration-type']='s2s';
    $curlPost['source']='Curl-Direct-Card-Payment';
    $curlPost['ccno']= $post['ccno'];
    $curlPost['ccvv']= $post['ccvv'];
    $curlPost['month']= $post['month'];
    $curlPost['year']= $post['year'];
    
     $curl_cookie="";
     $curl = curl_init(); 
     curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
     curl_setopt($curl, CURLOPT_URL, $gateway_url);
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
     curl_setopt($curl, CURLOPT_REFERER, $source_url);
     curl_setopt($curl, CURLOPT_POST, 1);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
     curl_setopt($curl, CURLOPT_TIMEOUT, 200);
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_COOKIE,$curl_cookie);
     $response = curl_exec($curl);
     $results = json_decode($response,true);

if((isset($results["Error"]) && ($results["Error"]))||(isset($results["error"]) && ($results["error"]))){
    return response()->json(['Errors'=>$results["Error"]], 422);
    exit;
}
elseif(!isset($results)){
	// echo $response; exit;
    return response()->json(['Errors'=>"Somthing went wrong please try again later!"], 422);
    exit;
}
if(isset($results['order_status']) && !empty($results['order_status'])){
$order_status = (int)($results["order_status"]);
}else{
    $order_status = 0;
}
// Insert transaction in DB on Initiation.
    $reportArr['acquirer_id'] = $acquirer_id;
    $reportArr['acquirer_slug'] = $slug;
    $reportArr['user_id'] = $userData->user_id;
    $reportArr['amount'] = $post['bill_amt'];
    $reportArr['mobile'] = $post['bill_phone'];
    $reportArr['provider_id'] =0;
    $reportArr['api_id'] =1;
    $reportArr['trans_type'] = 'credit';
    $reportArr['mytxnid'] = $orderId;
    $reportArr['txnid'] = $results["transID"];
    $reportArr['product'] ="GTW";                                                                           
    $reportArr['aepstype'] ='card';
    $reportArr['status'] ='pending';
    $reportArr['refno'] = $curlPost['reference'];
    $reportArr['number'] = $curlPost['reference'];
    $reportArr['number'] = $curlPost['reference'];
    $reportArr['billing_response'] = json_encode($curlPost);
    $reportArr['response'] = json_encode($results);

    if($order_status==1 || $order_status==9){
        $reportArr['status'] ='success';
    }elseif($order_status==2 || $order_status==22 || $order_status==23){
        $reportArr['status']='failed';
    }
    $insertedReport = Report::create($reportArr);
    $reportId = $insertedReport->id;
    $sub_query = http_build_query($results);

if(isset($results["authurl"]) && $results["authurl"]){ 
	//3D Bank URL
	$redirecturl = $results["authurl"];
    $transID = $results["transID"];
    $return_url = $curlPost['return_url'];
    if(strpos($return_url,'?')!==false){
		$return_url = $return_url.'&'.'&'.$sub_query;
	}else{
		$return_url = $return_url.'?'.$sub_query;
	}
    return response()->json(['redirect' => $redirecturl,'return_url'=>$curlPost['return_url'],'transID' =>$transID,'reportId'=>$reportId], 200);
	// header("Location:".$redirecturl);exit;
}elseif($order_status==1 || $order_status==9){ 
	// 1:Approved/Success,9:Test Transaction
	$return_url = $curlPost["return_url"];
	if(strpos($return_url,'?')!==false){
		$return_url = $return_url.'&'.$sub_query;
	}else{
		$return_url = $return_url.'?'.$sub_query;
	}
        return response()->json(['return_url'=>$return_url,'transID' =>$transID,'reportId'=>$reportId], 200);
        exit;

}elseif($order_status==2 || $order_status==22 || $order_status==23) {   
	// 2:Declined/Failed, 22:Expired, 23:Cancelled
	$return_url = $curlPost["return_url"];
	if(strpos($return_url,'?')!==false){
		$return_url = $return_url.'&'.$sub_query;
	}else{
		$return_url = $return_url.'?'.$sub_query;
	}		
	return response()->json(['return_url'=>$return_url,'transID' =>$transID,'reportId'=>$reportId], 200);
    exit;

}else{ 
	// Pending
	$redirecturl = $referer;
	if(strpos($redirecturl,'?')!==false){
		$redirecturl = $redirecturl.'&'.$sub_query;
	}else{
		$redirecturl = $redirecturl.'?'.$sub_query;
	}
	header("Location:$redirecturl");exit;
}
    }
    else{
        return response()->json(['Errors'=>"Wrong Public Key"], 422);
    }
}
// Epay
public function epayCheckout(Request $post){
    // Data Initialization
    $acquirer_id = $post['acquirer_id'];
    $currDate = date('y-m-d h:i:s');
    $currTimestamp = strtotime($currDate);
    $invoiceNumber = $currTimestamp;
    $token = $post['token'];
    $authToken = $post['authorization'];
    // API endpoint
    $url = $post['api_endpoint'];
    // Data to be sent to request
    $data = array(
        'fullName' => $post['fullname'],
        'amount' => $post['bill_amt'],
        'invoiceNumber'=>$invoiceNumber,
        'email'=>$post['bill_email'],
        'currency'=>$post['bill_currency'],
        'phone'=>$post['bill_phone'],
        'city'=>$post['bill_city'],
        'state'=>$post['bill_state'],
        'street1'=>$post['bill_address'],
        'country'=>$post['bill_country'],
        'postal_code'=>$post['bill_zip'],
        'token'=>$token,
        'status_url'=>$post['return_url'],
        'return_url'=>$post['return_url'],
    );

    // Authorization header
    $authorization = $authToken;
    // Headers
    $headers = array(
        'Authorization: ' . $authorization
    );
    // cURL session initialization
    $ch = curl_init();
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
    curl_setopt($ch, CURLOPT_POST, 1); // Set the HTTP method to POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Set the POST data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
    // Execute cURL session
    $response = curl_exec($ch);
    // Check for cURL errors
    if(curl_error($ch)) {
        echo 'Error: ' . curl_error($ch);
    }
    // Close cURL session
    curl_close($ch);
    // Output the response
    echo $response;
}

public function gtw_fetch(Request $post){
    $transID = $post['transID'];
    $reportId = $post['reportId'];
    $return_url = $post['return_url'];
    // FetchAcquirer
    $data = Report::where('id', $reportId)->select('acquirer_id')->first();
    $acquirer_id = $data->acquirer_id;
    $acquirerData = Acquirer::where('acquirer_id','=',$acquirer_id)->select('fields')->first();
    $apiFields = json_decode($acquirerData['fields'],true);
    $api_public_key = $apiFields['public_key'];
    $api_terno = $apiFields['terno'];

    $url="https://gtw.online-epayment.com/fetch_trnsStatus?".$transID."&public_key=".$api_public_key;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
	$response = curl_exec($ch);
	curl_close($ch);
    // Update status in DB
    $responesArr = json_decode($response,true);
    $finalUpdate['status'] ='pending';
    if($responesArr['order_status']!=0){
        if($responesArr['order_status']==1 || $responesArr['order_status']==9){
            $finalUpdate['status'] ='success';
            $finalUpdate['response']=$response;
        }elseif($responesArr['order_status']==2 || $responesArr['order_status']==22 || $responesArr['order_status']==23){
            $finalUpdate['status']='failed';
        }
    $report = Report::find($reportId);
    $report->makeHidden(['fundbank', 'apicode', 'apiname', 'username', 'sendername', 'providername']);
    if($report){
        $report->update($finalUpdate);
    }
    $sub_query = http_build_query($responesArr);
    if(strpos($return_url,'?')!==false){
		$return_url = $return_url.'&'.$sub_query;
	}else{
		$return_url = $return_url.'?'.$sub_query;
	}

    $responseData = [
        "response"=> $response,
        "return"=>$return_url
    ];
    $responseData =json_encode($responseData);
}
	echo $responseData ;
} 
 public function success(){
    Log::debug('Response received success:');
    return view('checkout.success');
}
public function failur(){
    Log::debug('Response received failur:');
    return view('checkout.failur');
}
public function webhook(){
    Log::debug('Response received webhook:');
    exit;
}
public function return_url(){
    Log::debug('Response received return:');
    $data = $_GET;
    $status = "pending";
    if($_GET['reference']){
    $refno = $_GET['reference'];
    $orderStatus = $_GET['order_status'];
    if($orderStatus == 1 || $orderStatus == 9){
        $status = "success";
    }
    $report = Report::where('refno', $refno)->first();
    if($report){
        $report->makeHidden(['fundbank', 'apicode', 'apiname', 'username', 'sendername', 'providername']);
        $report->status = $status;
        $report->response = $data;
        $report->save();
    }
    print_r($report->response);
}
}  
}