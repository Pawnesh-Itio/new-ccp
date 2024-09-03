<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahastate;
use Illuminate\Validation\Rule;
use App\Models\Api;
use App\Models\Report;
use App\Models\Commission;
use App\Models\Aepsreport;
use App\Models\Aepsfundreport;
use App\Models\Aepsfundrequest;
use App\Models\Initiateqr;
use App\Models\Iserveuagent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use App\Models\Apitoken;
use App\Helpers\Permission;

class UpiController extends Controller
{
    protected $xettleupi;
    public function __construct(){
        $this->iserveuwallet  = Api::where('code', 'loadwallet')->first();
        $this->xettleupi = Api::where('code', 'xettleupi')->first();
    }
    
    public function generateauthtoken(Request $post)
    {
        
        $rules = array(
            'token' => 'required'
        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }
        $post['user_id'] = $token->user_id;
        
        $header = array("Content-Type: application/json");
        $refid                 = 'TokenGen';
        $parameter['userName'] = $this->iserveuwallet->username;
        $parameter['password'] = $this->iserveuwallet->password;
        $url = $this->iserveuwallet->url.'token/create';

        $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes', 'Payintoken',$refid);

        $response = json_decode($result['response']);
        if(isset($response->success) && $response->success == true){
            if(isset($response->data->token)){
                if(isset($response->success) && $response->success == true){
                     $array = [];
                     $array['authtoken'] = $response->data->token;
                     return response()->json(['statuscode'=> "TXN",'data'=>$array]);
                 }
                
            }
        }else{
             return response()->json(['statuscode'=> "ERR",'message'=>isset($response->message) ? $response->message : "Something went wrong"]);
         } 
        
    }

    public function verifyupisdk(Request $post)
    {
        
        $rules = array(
            'token' => 'required',
            'secretKey' => 'required',
            'mid' => 'required'
        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }

        $checksecret = User::where('sdktoken', $post->secretKey)->first(['id']);
        if(!$checksecret){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }

        $checkmid = Iserveuagent::where('requestingUserName', $post->mid)->first(['id']);
        if(!$checkmid){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Merchant Id","extmsg"=>"forwarding"]);
        }

        if($checkmid){
            return response()->json(['statuscode'=> "TXN",'message'=>"Authentication Succcess"]);
        }else{
             return response()->json(['statuscode'=> "ERR",'message'=> "Authentication Failed!"]);
         } 
        
    }

    public function iserveuonboard(Request $post)
    {
        
        $rules = array(
            "token"=>"required",
            "firstName"=>"required",
            "lastName"=>"required",
            "mobilenumber"=>"required|numeric|digits:10|unique:iserveuagents,merchantMobileNumber",
            "address"=>"required",
            "area"=>"required",
            "pincode"=>"required|numeric|digits:6",
            "shopname"=>"required",
            "shopstate"=>"required",
            "shopcity"=>"required",
            "shopdistrict"=>"required",
            "shoparea"=>"required",
            "shopaddress"=>"required",
            "shoppincode"=>"required|numeric|digits:6",
            "pancard"=>"required"
        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token or ip address".$post->ip(),"extmsg"=>"forwarding"]);
        }
        $post['user_id'] = $token->user_id;
        $user = User::where('id', $post->user_id)->first();
        
        $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first();
        
        if($isesxistagent){
            return response()->json(['statuscode'=> "ERR",'message'=> "User already onboarded, contact to administrator"]);
        }
       
        $header = array(
            "content-type: application/json",
            "client_id:".(!empty($this->iserveuwallet->username) ? $this->iserveuwallet->username:''),
            "client_secret:".(!empty($this->iserveuwallet->password) ? $this->iserveuwallet->password:'')
        );
        
        $parameter = [ 
                    "productType"=> "upi",
                    "bcagentname"=> $post->firstName,
                    "lastname"=> $post->lastName,
                    "companyname"=> $user->company->companyname,
                    "mobilenumber"=> $post->mobilenumber,
                    "shopname"=> $post->shopname,
                    "vpa"=> trim(strtolower($post->firstName)).$post->mobilenumber,
                    "bcagentid"=> trim(strtolower($post->firstName)).'00'.(substr($post->mobilenumber, -4)),
                    "address"=> $post->address,
                    "area"=> $post->area,
                    "pincode"=> $post->pincode,
                    "shopaddress"=> $post->shopaddress,
                    "shopstate"=> $post->shopstate,
                    "shopcity"=> $post->shopcity,
                    "shopdistrict"=> $post->shopdistrict,
                    "shoparea"=> $post->shoparea,
                    "shoppincode"=> $post->shoppincode,
                    "pancard"=> $post->pancard
                ];
                    
                
        //$url = $this->iserveuwallet->url.'api/upi/composer/selfonboarding';
        $url = 'https://apiprod.iserveu.tech/productionV2/apiAgentOnboarding/externalonboard';

        $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
        
        if($result['response'] == ''){
            return response()->json(['statuscode'=> "ERR",'message'=> "Something went wrong"]);
        }
    
         $response = json_decode($result['response']);
         
         if(isset($response->status) && $response->status == 'SUCCESS'){
             $array = [];
             $array = [ 
                        "firstName"=> $post->firstName,
                        "lastname"=> $post->lastName,
                        "companyname"=> $user->company->companyname,
                        "merchantMobileNumber"=> $post->mobilenumber,
                        "shopname"=> $post->shopname,
                        "merchantVirtualAddress"=> $response->Data->result->VPA ?? $parameter['vpa'],
                        "requestingUserName"=> $parameter['bcagentid'],
                        "address"=> $post->bc_address,
                        "merchant_id"=> $response->Data->result->merchant_id ?? '',
                        "area"=> $post->area,
                        "pincode"=> $post->pincode,
                        "shopaddress"=> $post->shopaddress,
                        "shopstate"=> $post->shopstate,
                        "shopcity"=> $post->shopcity,
                        "shopdistrict"=> $post->shopdistrict,
                        "shoparea"=> $post->shoparea,
                        "shoppincode"=> $post->shoppincode,
                        "user_id"=> $user->id,
                        "pan"=> $post->pancard,
                        "status"=> "success"
                     ];
             
          
             Iserveuagent::insert($array);
             return response()->json(['statuscode'=> "TXN",'message'=>"User successfully Onboarded.",'status'=>"User successfully Onboarded."]);
         }else{
              return response()->json(['statuscode'=> "ERR",'message'=>isset($response->statusDesc) ? $response->statusDesc : "Something went wrong",'status'=>isset($response->error) ? $response->error : "Something went wrong"]);
         }

        
    }

    public function initiatedynamicqr(Request $post)
    {
        
        $rules = array(
            'token' => 'required',
            'amount' => 'required|numeric|min:1',
            "clientTxnId"=> "required|alpha_num|min:12|max:20|unique:reports,mytxnid"
        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token".$post->ip(),"extmsg"=>"forwarding"]);
        }

        $post['user_id'] = $token->user_id;
        
        $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first(['requestingUserName','firstName','lastName','merchantVirtualAddress']);
        if(!$isesxistagent){
            return response()->json(['statuscode' => 'ERR','message'=>"User not onboarded yet","extmsg"=>"forwarding"]);
        }
        $user = User::where('id', $post->user_id)->first();

                
                $header = array(
                    "content-type: application/json",
                    "client_id:".$this->iserveuwallet->username,
                    "client_secret:".$this->iserveuwallet->password
                );

                do {
                    $post['txnid'] = $this->transcode().rand(1111111111111111, 9999999999999999);
                } while (Initiateqr::where("refid", "=", $post->txnid)->first() instanceof Initiateqr);
                
                $parameter['virtualAddress'] =  rand(1111111111, 9999999999).'@paytm';//Str::random(12);
                $parameter['amount'] = $post->amount;
                $parameter['merchantType'] = "AGGREGATE";
                $parameter['paymentMode'] = "INTENT";
                $parameter['channelId'] = "WEBUSER";
                $parameter['clientRefId'] = $post->txnid;
                $parameter['isWalletTopUp'] = false;
                $parameter['remarks'] = $user->company->name." Payment";
                $parameter['requestingUserName'] = $isesxistagent->requestingUserName;
                $url = $this->iserveuwallet->url.'api/upi/initiate-dynamic-transaction';
        
                $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
               // dd([$url, json_encode($parameter),$header,$result]);
                if($result['response'] == ''){
                    return response()->json(['statuscode'=> "ERR",'message'=> "Something went wrong"]);
                }
            
                 $response = json_decode($result['response']);
                 
                 if(isset($response->statusCode) && $response->statusCode == '0'){
                     $array = [];
                     $array['refid'] = $post->txnid;
                     $array['upiid'] = $parameter['virtualAddress'];
                     $array['amount'] = $post->amount; 
                     $array['user_id'] = $post->user_id;
                     $array['status'] = 'pending';
                     Initiateqr::insert($array);

                    $hashAlgorithm   = 'sha256';
                    $hash = hash($hashAlgorithm, $post->txnid.'|'.$post->amount);

                    $array['orderId']     = $post->txnid;

                    $array['hashToken']   = $hash;
                    $array['amount'] = $post->amount; 
                     $array['clientTxnId'] = $post->clientTxnId;
                     $array['merchantVirtualAddress'] = $isesxistagent->merchantVirtualAddress;
                     $array['merchantName'] = $isesxistagent->firstName.' '.$isesxistagent->lastName;
                     return response()->json(['statuscode'=> "TXN","message"=>"Record successfully fetched",'data'=>$array]);
                 }else{
                     return response()->json(['statuscode'=> "ERR",'message'=>isset($response->error) ? $response->error : "Something went wrong"]);
                 }
        
    }
 
    public function generatedynamicqr(Request $post)
    {
        
        $rules = array(
            'token' => 'required',
            "orderId"  => "required",
            "hashToken"  => "required",
            'amount' => 'required|numeric|min:1'

        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }



        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }

        $post['orderId'] = $post->orderId;
        $hashAlgorithm   = 'sha256';


        $hash = hash($hashAlgorithm, $post->orderId.'|'.$post->amount);
        if ($hash != $post->hashToken) {
          return response()->json(['statuscode'=>"ERR","message"=>"Hash Token mismatch"]);
        }


        $post['user_id'] = $token->user_id;
        
        $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first(['requestingUserName','firstName','lastName','merchantVirtualAddress']);
        if(!$isesxistagent){
            return response()->json(['statuscode' => 'ERR','message'=>"User not onboarded yet","extmsg"=>"forwarding"]);
        }
        $user = User::where('id', $post->user_id)->first();

                
        $header = array(
            "content-type: application/json",
            "client_id:".$this->iserveuwallet->username,
            "client_secret:".$this->iserveuwallet->password
        );

        do {
            $post['txnid'] = $this->transcode().rand(1111111111111111, 9999999999999999);
        } while (Initiateqr::where("refid", "=", $post->txnid)->first() instanceof Initiateqr);
        
        $parameter['virtualAddress'] =  rand(1111111111, 9999999999).'@paytm';//Str::random(12);
        $parameter['amount'] = $post->amount;
        $parameter['merchantType'] = "AGGREGATE";
        $parameter['paymentMode'] = "INTENT";
        $parameter['channelId'] = "WEBUSER";
        $parameter['clientRefId'] = $post->txnid;
        $parameter['isWalletTopUp'] = false;
        $parameter['remarks'] = $user->company->name." Payment";
        $parameter['requestingUserName'] = $isesxistagent->requestingUserName;
        $url = $this->iserveuwallet->url.'api/upi/initiate-dynamic-transaction';

        $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
       // dd([$url, json_encode($parameter),$header,$result]);
        if($result['response'] == ''){
            return response()->json(['statuscode'=> "ERR",'message'=> "Something went wrong"]);
        }
    
         $response = json_decode($result['response']);
         
         if(isset($response->statusCode) && $response->statusCode == '0'){
             $array = [];
             $array['refid'] = $post->txnid;
             $array['upiid'] = $parameter['virtualAddress'];
             $array['amount'] = $post->amount; 
             $array['user_id'] = $post->user_id;
             $array['status'] = 'pending';
             Initiateqr::insert($array);
             $array['companyLogo'] = "https://".$user->company->website."/public/logos/".$user->company->logo;
             $array['intentData'] = $response->intentData;
             $array['merchantVirtualAddress'] = $isesxistagent->merchantVirtualAddress;
             $array['merchantName'] = $isesxistagent->firstName.' '.$isesxistagent->lastName;
             return response()->json(['statuscode'=> "TXN","message"=>"Record successfully fetched",'data'=>$array]);
         }else{
             return response()->json(['statuscode'=> "ERR",'message'=>isset($response->error) ? $response->error : "Something went wrong"]);
         }
        
    }   
    

    public function generatestaticqr(Request $post){
        $rules = array(
            'token' => 'required'
        );

        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token".$post->ip(),"extmsg"=>"forwarding"]);
        }

        $post['user_id'] = $token->user_id;

        $isesxistagent = Iserveuagent::where(['user_id'=>$post->user_id,'status'=>'success'])->first();
        $user = User::where('id', $post->user_id)->first();
        if($isesxistagent){
            if($user->qrdata == ""){
                $header = array(
                    "content-type: application/json",
                    "client_id:".$this->iserveuwallet->username,
                    "client_secret:".$this->iserveuwallet->password
                );
                 $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first(['requestingUserName','firstName','lastName','merchantVirtualAddress']);
                
                $parameter = ["requestingUserName"=> $isesxistagent->requestingUserName ?? ""];
              
                        
                $url = $this->iserveuwallet->url.'api/upi/composer/generate-qr-code';

                $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
                //dd([$url, 'POST', json_encode($parameter), $header,$result]);
                
                
                if($result['response'] == ''){
                    return response()->json(['statuscode'=> "ERR",'message'=> "Something went wrong"]);
                }
                $response = json_decode($result['response']);
                
                if(isset($response->status) && strtolower($response->status) == 'success'){
                    User::where('id',$post->user_id)->update(['qrdata'=>$response->qrData]);
                    $user = User::where('id', $post->user_id)->first();
                    return response()->json(['statuscode'=> "TXN","message"=>"QR successfully generated",'data'=>array('qrdata'=>$response->qrData)]);
                }else{
                     return response()->json(['statuscode'=> "ERR",'message'=>isset($response->error) ? $response->error : "Something went wrong"]);
                 }
            }
        }

        if($user->qrdata != ""){
            return response()->json(['statuscode'=> "TXN","message"=>"QR successfully generated",'data'=>array('qrdata'=>$user->qrdata)]);
        }
    }
    
    public function checktxnstatus(Request $post){
        $rules = array(
            'token' => 'required',
            'txnid' => 'required'
        );

        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            
            return response()->json(array(
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ));
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }
        $post['user_id'] = $token->user_id;
        $report = Report::where('txnid', $post->txnid)->first();
        
        if($report){
            $field = [];
            $field['amount'] = $report->amount;
            $field['status'] = $report->status;
            $field['refno'] = $report->refno;
            $field['txnid'] = $report->txnid;
            $field['created_at'] = $report->created_at;
            
            if($report->status == 'success'){
                return response()->json([
                    'statuscode' => "TXN",
                    'message'    => "Transaction fetch Successfull",
                    'data'    => $field
                ]);
            }else if($report->status == 'pending'){
                return response()->json([
                    'statuscode' => "TUP",
                    'message'    => "Transaction fetch Successfull",
                    'data'    => $field
                ]);
            }else{
                return response()->json([
                    'statuscode' => "ERR",
                    'message'    => "Transaction found",
                    'data'    => $field
                ]);  
            }
            
        }else{
             return response()->json(['statuscode' => 'ERR','message'=>"Transaction not found","extmsg"=>"forwarding"]);
        }
        
    }
    
    public function vpaRegister(Request $post)
    {
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        $post['user_id'] = $token->user_id;
        
        $rules = array(
            'merchantBusinessName'   => 'required',
            'merchantVirtualAddress' => 'required',
            'bankAccountNo' => 'required',
            'bankIfsc' => 'required',
            "contactEmail"           => "required",
            "panNo"      => "required",
            "merchantBusinessType"   => "required",
            "gstn"       => "required",
            "mobile"     => "required",
            "address"    => "required",
            "state"      => "required",
            "city"       => "required",
            "pinCode"    => "required",
            "requestUrl" => 'required'
        );
        
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'statuscode' => 'BPR',
                'status' => 'Bad Parameter Request.',  
                'message' => $error
            ));
        }
        
        $user = User::where('id', $post->user_id)->first();
        $url="https://api.xettle.io/v1/service/collect/merchant";
        $header = array(
            "authorization: Basic ".base64_encode($this->xettleupi->username.":".$this->xettleupi->password),
            "cache-control: no-cache",
            "content-type: application/json"
        );
        
        $parameter = [
            "businessName"    => $post->merchantBusinessName,
            "vpaAddress"  => $post->merchantVirtualAddress,
            "panNo"    => $post->panNo,
            "contactEmail"  => $post->contactEmail,
            "gstn"     => $post->gstn,
            "merchantBusinessType"    => $post->merchantBusinessType,
            "bankAccountNo"   => $post->bankAccountNo,
            "bankIfsc"        => $post->bankIfsc,
            "perDayTxnAmt"    => "100",
            "mobile"          => $post->mobile,
            "address"         => $post->address,
            "state"           =>$post->state,
            "city"            => $post->city,
            "pinCode"         => $post->pinCode,
            "serviceType"     =>"upi"
        ];
                
        $result = Permission::curl($url, "POST", json_encode($parameter), $header, "no");
        if($result['response'] != ""){
            $response = json_decode($result['response'], true);
            if(isset($response->code) && $response->code == "0x0200"){
                $insert = [
                    'merchantBusinessName'    => $post->merchantBusinessName,
                    'merchantVirtualAddress'  => $response['data']['upi']['icici']['vpa'],
                    'requestUrl' => $post->requestUrl,
                    'panNo'      => $post->panNo,
                    'contactEmail'  => $post->contactEmail,
                    'gstn'          => $post->gstn,
                    'merchantBusinessType'   => $post->merchantBusinessType,
                    'perDayTxnCount'         => "100",
                    'perDayTxnLmt' => "10000",
                    'perDayTxnAmt' => "100",
                    'mobile'     => $post->mobile,
                    'address'    => $post->address,
                    'state'      => $post->state,
                    'city'       => $post->city,
                    'pinCode'    => $post->pinCode,
                    'mcc'        => $post->bankAccountNo,
                    'user_id'    => $user->id,
                    'vpaaddress'    => $response['data']['upi']['yesbank']['vpa'],
                    'subMerchantId' => $post->bankIfsc
                ];
                
                $report = \DB::table('xettlemerchants')->insert($insert);
                return response()->json([
                    'statuscode' => "TXN",
                    'status'    => "Transaction Successfull",
                    'message'    => "Transaction Successfull",
                    'vpaaddress'    => $response['data']['upi']['yesbank']['vpa']
                ]);
            }
        }
        return response()->json(['statuscode' => "ERR", "status" => isset($response->message) ? $response->message : "Something went wrong", "message" => isset($response->message) ? $response->message : "Something went wrong"]);
    }
    
    public function upicallback(Request $post)
    {
        if($post->server()['REMOTE_ADDR']){
        }
        
        if($post->event=="upi.receive.success"){
            $decode = json_decode(json_encode($post->all()));
            $data  = \DB::table('upilogs')->insert(['product' => 'upiapi', 'response' => json_encode($post->all())]);
            $agent = \DB::table('xettlemerchants')->where('vpaaddress', $decode->data->payeeVPA)->first();
            
            if($agent){
                $report = Report::where('refno', $decode->data->customerRefId)->first();
                
                if(!$report){
                    $user  = User::where('id', $agent->user_id)->first();
                    $provider = Provider::where('recharge1', 'upi')->first();
                    $post['provider_id'] = $provider->id;
                    $usercommission = Permission::getCommission($decode->data->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                        
                    $insert = [
                        "mobile"   => $agent->mobile,
                        "payeeVPA" => $agent->vpaaddress,
                        'txnid'    => $decode->data->merchantTxnRefId,
                        "refno"    => $decode->data->customerRefId,
                        "payid"    => $decode->data->bankTxnId,
                        'mytxnid'  => $decode->data->npciTxnId,
                        'number'   => $decode->data->payerAccNo,
                        'authcode' => $decode->data->originalOrderId,
                        'payerMobile'  => $decode->data->payerMobile,
                        'payerAccName' => $decode->data->payerAccName,
                        'payerIFSC'    => $decode->data->payerIFSC,
                        "amount"  => $decode->data->amount,
                        "charge"  => $usercommission,
                        "api_id"  => $provider->api->id,
                        "user_id" => $user->id,
                        'aepstype'=> "UPI",
                        'status'  => 'success',
                        'credited_by' => $user->id,
                        'type'    => 'credit',
                        'balance'     => $user->mainwallet,
                        'provider_id' => $post->provider_id,
                        'product'    => "upicollect"
                    ];
                    
                    if(isset($decode->code) && ($decode->code) == "0x0200"){
                        Report::create($insert);
                        User::where('id', $user->user_id)->increment('mainwallet', $decode->data->amount - $usercommission);
                    }
                    
                    if($user->role->slug == "apiuser"){
                        $output['status'] = "success";
                        $output['clientid']  = $decode->data->originalOrderId;
                        $output['txnid']     = $decode->data->merchantTxnRefId;
                        $output['vpaadress']   = $agent->vpaaddress;
                        $output['npciTxnId']   = $decode->data->npciTxnId;
                        $output['amount']   = $decode->data->amount;
                        $output['bankTxnId']   = $decode->data->bankTxnId;
                        $output['payerAccNo']  = $decode->data->payerAccNo;
                        $output['payerMobile'] = $decode->data->payerMobile;
                        $output['payerAccName']= $decode->data->payerAccName;
                        $output['payerIFSC']   = $decode->data->payerIFSC;
                        Permission::curl($agent->requestUrl."?".http_build_query($output), "GET", "", [], "yes", "AepsReport", $decode->data->merchantTxnRefId);
                    }
                }
            }
        }
    }
}    