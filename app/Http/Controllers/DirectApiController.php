<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;  

use App\Models\Merchantkey;
use App\Models\Report;
use App\Models\Acquirer;
use App\Models\User;
use App\Models\Currancy;
use App\Models\Merchantacquirermapping;
use App\Helpers\Permission ;

class DirectApiController extends Controller
{
    
    public function direct_api(Request $post){
        // Validate Fields
        $rules = array(
            'public_key'       => 'required',
            'success_url'      => 'required',
            'failur_url'       => 'required',
            'product_info'     => 'required',
            'firstname'        => 'required',
            'email'            => 'required',
            'phone'            => 'required',
            'amount'           => 'required',
            'address_1'        => 'required',
            'address_2'        => 'required',
            'city'             => 'required',
            'state'            => 'required',
            'country'          => 'required',
            'zip'              => 'required'
        );
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['Errors'=>"Required Peremeters are missing"], 422);
        }
        $postPublicKey = $post['public_key']; //Our panel Merchant public key 
        // Get User Data
        $userData = Merchantkey::where("public_key",$postPublicKey)
                                ->where("is_active","yes")
                                ->first();
        if($userData){
            // Get userdata->scheme_id,kyc,currency_id
            $fetchUserDetail = User::where('id',$userData->user_id)->first(['id','currancy_id','scheme_id','kyc','status','s2s_agent']);
            $fetchUserCurrency = Currancy::where('id',$fetchUserDetail->currancy_id)->first('shortname');
            // Status, Acquirer, KYC, Scheme and Currancy  validation
            if($fetchUserDetail->status!='active'){
                return response()->json(['Errors'=>'Your account is not active, Please contact technical support'], 422);
                exit;
            }
            if($fetchUserDetail->kyc!='verified'){
                return response()->json(['Errors'=>'Your KYC is not verified, Please contact technical support'], 422);
                exit;
            }
            if(empty($fetchUserDetail->scheme_id)){
                return response()->json(['Errors'=>"Scheme is not set, Please contact technical support"], 422);
                exit;
            }
            if(empty($fetchUserDetail->s2s_agent)){
                return response()->json(['Errors'=>"Acquirer is not set, Please contact technical support"], 422);
                exit;
            }

            // Get Acquirer
            $acquirerData = Acquirer::Join('users', 'users.s2s_agent','=','acquirers.acquirer_id')
            ->where('users.id','=',$userData->user_id)
            ->select('acquirers.*')
            ->first();
            if(!$acquirerData){
                return response()->json(['Errors'=>$fetchUserDetail->scheme_id], 422);
                exit;
            }
            $slug = $acquirerData['acquirer_slug'];
            $acquirer_id = $acquirerData['acquirer_id'];
            
        switch ($slug) {

            case 'GTW':
                // Data Initializing
                $api_endpoint = $acquirerData['api_endpoint'];
                $apiFields = json_decode($acquirerData['fields'],true);
                $api_public_key = $apiFields['public_key'];
                $api_terno = $apiFields['terno'];
                $currDate = date('y-m-d h:i:s');
                $currTimestamp = strtotime($currDate);
                $gateway_url= $api_endpoint;
                $curlPost=array();
                $protocol = isset($_SERVER["HTTPS"])?'https://':'http://';
                $source_url=$protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
                // cURL data initializing
                $curlPost['public_key']=$api_public_key;// GTW integeration public key
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
                $curlPost['bill_ip']= "126.201.411.8";
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
                    print_r($results); exit;
                }
                elseif(!isset($results)){
                    // return response()->json(['error'=>"Somthing went wrong please try again later"], 422);
                    // exit;
                    echo $response; exit;
                }
                if(isset($results['order_status']) && !empty($results['order_status'])){
                    $order_status = (int)($results["order_status"]);
                }else{
                    $order_status = 0;
                }
                // Insert transaction in DB on Initiation.
                $reportArr['acquirer_id'] = $acquirer_id;
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
                $reportArr['billing_response'] = json_encode($curlPost);
                $reportArr['response'] = json_encode($results);
                $reportArr['acquirer_slug'] = $slug;
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
                        $return_url = $return_url.'&'.$sub_query;
                    }else{
                        $return_url = $return_url.'?'.$sub_query;
                    }
                    return response()->json(['response' =>$response,'redirect' => $redirecturl,'return_url'=>$return_url,'transID' =>$transID,'reportId'=>$reportId], 200);
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

            break;

            case 'Stripe':
                $apiFields = json_decode($acquirerData['fields'],true);

                echo "Stripe";
                

            break;

            case 'easy_buzz':
                $apiFields = json_decode($acquirerData['fields'],true);
                $txnID = 'TRP-'.date('YmdHis').rand(11111111,99999999);
                // Post DATA
                $postData = [
                    'key' => $apiFields['merchant_key'],
                    'txnid' => $txnID,
                    'amount' => $post['amount'],
                    'productinfo' => $post['product_info'],
                    'firstname' => $post['firstname'],
                    'phone' => $post['phone'],
                    'email' => $post['email'],
                    'surl' => $apiFields['success_url'],
                    'furl' => $apiFields['failur_url'],
                    'address1' => $post['address_1'],
                    'address2' => $post['address_2'],
                    'city' => $post['city'],
                    'state' => $post['state'],
                    'country' => $post['country'],
                    'zipcode' => $post['zip'],
                    'show_payment_mode'=>'NB,CC,DC,MW,UPI,OM,EMI,PL,CBT,BT',
                    'udf1'=>$postPublicKey,
                    'udf2'=>$userData->user_id
                    
                ];
                // End Post Data
                // Generate Report
                $reportArr['acquirer_id'] = $acquirer_id;
                $reportArr['acquirer_slug'] = $slug;
                $reportArr['user_id'] = $userData->user_id;
                $reportArr['amount'] = $post['amount'];
                $reportArr['mobile'] = $post['phone'];
                $reportArr['provider_id'] =0;
                $reportArr['api_id'] =1;
                $reportArr['trans_type'] = 'credit';
                $reportArr['mytxnid'] = $txnID;
                $reportArr['txnid'] = $txnID;
                $reportArr['product'] ="EaseBuzz";                                                                           
                $reportArr['aepstype'] ='card';
                $reportArr['status'] ='pending';
                $reportArr['refno'] = $txnID;
                $reportArr['number'] = $txnID;
                $reportArr['billing_response'] = json_encode($postData);

                $insertedReport = Report::create($reportArr);
                $reportId = $insertedReport->id;
                // End Generate Report
                // Hash
                $hashFields = $apiFields['merchant_key'] . '|' . $txnID . '|' . $post['amount'] . '|' . $post['product_info'] . '|' . $post['firstname'] . '|' . $post['email'] . '|'. $postPublicKey .'|'. $userData->user_id .'|' . $reportId . '||||||||' . $apiFields['salt'];
                $hash = hash('sha512', $hashFields);
                $postData['hash']=$hash;
                $postData['udf3']=$reportId;
                    // Initiation
                    $response = Permission::eassBuzz($postData);
                    $response_data = json_decode($response, true);
                    $status = $response_data['status'];
                    if($status==1){
                    $access_key = $response_data['data'];
                    // Parse the URL
                    $parsedUrl = parse_url($apiFields['test_api_endpoint']);
                    $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                    $baseUrl = trim($baseUrl);
                    $access_key = trim($access_key);
                    $url = $baseUrl."/v2/pay/".$access_key;
                    return response()->json(['redirect' => $url,], 200);
                    }else{
                    return response()->json(['Errors'=>"Somthing went wrong, please try again!"], 422);
                    }
            break;
        }
    }else{
        return response()->json(['Errors'=>"Public key is not registered with us"], 422);
    }
}
}
