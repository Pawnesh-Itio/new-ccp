<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Api\Api;
use App\Models\Api\Apitoken;
use App\Models\Api\Provider;
use App\Models\Api\Mahabank;
use App\Models\Api\Report;
use App\Models\Api\Commission;
use App\Models\Aepsreport;
use App\Models\Api\Aepsfundrequest;
use App\Models\Api\Beneficiarybank;
use App\Models\Api\Userdata;
use Carbon\Carbon;
use App\Helpers\Permission;

class PayoutController extends Controller
{
    protected $api;
    public function __construct()
    {
        //$this->api = Api::where('code', 'xettlepayout')->first();
        $this->api = Api::where('code', 'ipayout')->first();
    }

    public function initiatepayout(Request $post)
    {

        $rules = array(
            'token'  => 'required',
            "companycode" => "required|min:4",
            "apitxnid"=> "required|alpha_num|min:12|max:20|unique:reports,mytxnid"
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
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();

        if($user->mainwallet<=$post->amount){
           return response()->json(['statuscode'=>'ERR', 'message'=> "Low balance to initiate the transction"]); 
        }

        $post['orderId'] = 'CCP'.date('YmdHis').rand(11111111,99999999);
        $hashAlgorithm   = 'sha256';

        $hash = hash($hashAlgorithm, $post->orderId.'|'.$post->companycode);

        $data = [];
          
          $data['companycode'] = $post->companycode;
          $data['orderId']     = $post->orderId;
          $data['clientTxnId'] = $post->apitxnid;
          $data['hashToken']   = $hash;
         
        if($hash){
            return response()->json(['statuscode'=>"TXN","message"=>"request Initiated successfully","data"=>$data]);
        }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again"]); 
        }


    }

    public function verifypayout(Request $post)
    {

        $rules = array(
            "token"=>"required",
            "orderId"  => "required",
            "hashToken"  => "required",
            "companycode"=>"required|min:4"
        );
        
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'statuscode' => 'ERR',
                'message' => $error
            ));
        }
        
          $hashAlgorithm   = 'sha256';
          $hash = hash($hashAlgorithm, $post->orderId.'|'.$post->companycode);
          if ($hash != $post->hashToken) {
            return response()->json(['statuscode'=>"ERR","message"=>"Hash Token mismatch"]);
              
          }


        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);

        if(!$token){
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is "]);   
        }
        
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();

        $data = [];
          
          $data['companyLogo'] = "https://".$user->company->website."/public/logos/".$user->company->logo;;
          $data['companycode'] = $post->companycode;
          $data['orderId']     = $post->orderId;
          $data['clientTxnId'] = $post->apitxnid;
          $data['hashToken']   = $hash;
         
        if($hash){
            return response()->json(['statuscode'=>"TXN","message"=>"request verified successfully","data"=>$data]);
        }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Verification failed"]); 
        }


    }

    public function transaction(Request $post)
    {
       
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode' => 'ERR','message'=>"Invalid Api token","extmsg"=>"forwarding"]);
        }

        $post['user_id'] = $token->user_id;
        
        if(!$this->api || $this->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Payout Service Currently Down."]);
        }
        
        $user = Userdata::where('user_id', $post->user_id)->first();
        // return response()->json(['statuscode' =>"success","message"=>"We are In", "transactionType"=>$post->transactionType]);
        switch ($post->transactionType) {
            case 'addcontact':
                $rules = array(
                    'firstName'   => 'required',
                    'lastName'    => 'required',
                    "email"       => "required|email",
                    "mobile"      => "required|numeric|digits:10",
                    "accountNumber"   => "required",
                    "ifsc"        => "required",
                    "referenceId" => "required|unique:contacts,referenceId",
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'BPR',
                        'message' => $error
                    ));
                }
                
                $url = "https://api.xettle.io/v1/service/payout/contacts";
                
                $parameter["firstName"] = $post->firstName;
                $parameter["lastName"]  = $post->lastName;
                $parameter["email"]     = $post->email;
                $parameter["mobile"]    = $post->mobile;
                $parameter["type"]      = 'employee';
                $parameter["accountType"]   = 'bank_account';
                $parameter["accountNumber"] = $post->accountNumber;
                $parameter["ifsc"]          = $post->ifsc;
                $parameter["referenceId"]   = $post->referenceId;
                $payload=json_encode($parameter);
                
               $string    = (base64_encode($payload)."/v1/service/payout/contacts".$this->api->username."####".$this->api->optional1);
               $signature = hash("sha256",$string);
               
                $header = array(
                    "authorization: Basic ".base64_encode($this->api->username.":".$this->api->password),
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "Signature:".$signature
                );
                $result = Permission::curl($url, "POST", json_encode($parameter), $header, "no");
                $exitingcontact=\DB::table('contacts')->where('accountNumber',$post->accountNumber)->first();
                if($result['response'] != ""){
                    $response = json_decode($result['response']);
                    if(isset($response->code) && $response->code == "0x0200"){
                        $parameter['user_id']   = $post->user_id;
                        $parameter['contactId'] = $response->data->contactId;
                        $parameter['created_at'] = date('Y-m-d H:i:s');
                        $parameter['updated_at'] = date('Y-m-d H:i:s');
                        $report = \DB::table('contacts')->insert($parameter);
                        return response()->json(['statuscode' => "TXN", 'message' => "Transaction Successfull", "contactid" => $response->data->contactId]);
                    }
                }
                if(isset($exitingcontact->contactId)){
                return response()->json(['statuscode' => "ERR", "message" => isset($response->message) ? $response->message : "Something went wrong","contactid" => $exitingcontact->contactId]);
                }else{
                  return response()->json(['statuscode' => "ERR", "message" => isset($response->message) ? $response->message : "Something went wrong"]);
   
                }
                break;
                
            case 'getbank':
                $banks = Mahabank::get();
                return response()->json(['statuscode' => "TXN", "message" => "Bank details fetched", 'data' => $banks]);
            break;
            
            case 'addbeneficiary':
                 $rules = array(
                    "token"=>"required",
                    "bank"=>"required|numeric",
                    "account"=> "required|numeric",
                    "ifsc"=> "required",
                    "mobile"=> "required|numeric",
                    "name"=> "required"
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'ERR',
                        'message' => $error
                    ));
                }
                
                $isexist = \App\Models\Api\Beneficiarybank::where(['user_id'=>$post->user_id,'beneaccno'=>$post->account])->first();
                if($isexist){
                    return response()->json([
                            'statuscode' => 'ERR', 
                            'message'    => "Account is already exist"
                        ]);
                }
                
                $mbank = Mahabank::where('id',$post->bank)->first(['id','bankname']);
                $beni['bankname']   = $mbank->bankname;
                $beni['beneaccno']  = $post->account;
                $beni['benemobile'] = $post->mobile;
                $beni['benename']   = $post->name;
                $beni['user_id']    = $post->user_id;
                $beni['ifsc']       = $post->ifsc;
                
                $addbank = \App\Models\Api\Beneficiarybank::create($beni);
                if($addbank){
                    return response()->json([
                            'statuscode' => 'TXN', 
                            'status'     => 'Success', 
                            'message'    => "Beneficiary details added"
                        ]);
                }else{
                    return response()->json([
                            'statuscode' => 'ERR',  
                            'message'    => "Something went wrong"
                        ]);
                }
            break;
            
           case 'beneficiarylist':
                 $rules = array(
                    "token"=>"required"
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'ERR',
                        'message' => $error
                    ));
                }
                $benbanks = \App\Models\Api\Beneficiarybank::where('user_id',$post->user_id)->get();
                $data=[];
            
                foreach($benbanks as $benbank){
                    $data[] = [
                        "id"=>$benbank->id,
                        "benebank"=> $benbank->bankname,
                        "beneaccount"=> $benbank->beneaccno,
                        "benemobile"=> $benbank->benemobile,
                        "benename"=> $benbank->benename,
                        "beneifsc"=> $benbank->ifsc,
                        "benestatus"=> $benbank->verified?"V":"NV",
                        "url"=> null
                        ];
                }
                
                if(count($data) > 0){
                    return [
                            "statuscode"=>"TXN",
                            "totallimit"=>"0",
                            "usedlimit"=>"0",
                            "message"=>"Beneficiary fetched Successfull",
                            "beneficiary"=>$data
                        ];
                }else{
                    return [
                        "statuscode"=>"TXN",
                        "totallimit"=>"0",
                        "usedlimit"=>"0",
                        "message"=>"Data not found",
                        "beneficiary"=>$data
                    ];
                }
                break;
            
            
              case 'payout':
              
                //   $user = user_id, name,mobile,mainwallet, role_slug,scheme_id
                $rules = array(
                    "token"=>"required",
                    "amount"=>"required|numeric|min:100",
                    "transactionType"=>"required",
                    "beneName"=> "required",
                    "beneAccountNo"=> "required",
                    "beneifsc"=> "required",
                    "benePhoneNo"=> "required",
                    "beneBankName"=> "required",
                    "mode"=>"required"
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'ERR',
                        'message' => $error
                    ));
                }

                
                 $previousrecharge = Report::where('user_id', $user->id)->where('amount', $post->amount)->where('number', $post->beneAccountNo)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                if($previousrecharge > 0){
                    return response()->json(['statuscode' => "ERR", "message" => "Same Transaction allowed after 2 min."]);
                }
                
       
                if($post->mode == "IMPS"){
                    if($post->amount > 0 && $post->amount <= 1000){
                        $provider = Provider::where('recharge1', 'payout1k')->first();
                    }elseif($post->amount > 1000 && $post->amount <= 25000){
                        $provider = Provider::where('recharge1', 'payout25k')->first();
                    }elseif($post->amount > 25000 && $post->amount <= 200000){
                        $provider = Provider::where('recharge1', 'payout2l')->first();
                    }
                }elseif($post->mode == "NEFT"){
                    $provider = Provider::where('recharge1', 'payoutneft')->first();
                }elseif($post->mode == "RTGS"){
                    $provider = Provider::where('recharge1', 'payoutrtgs')->first();
                }else{
                    $provider = Provider::where('recharge1', 'payout2l')->first();
                }
                
                $post['provider_id'] = $provider->id; 
                $post['charge'] = Permission::getCommissionApidb($post->amount, $user->scheme_id, $post->provider_id, $user->slug);
                
                if($user->mainwallet < $post->amount + $post->charge){
                    return response()->json(['statuscode' => "ERR", "message" => "Low balance to make this request."]);
                }
                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report); 

                
                $aepsreports['api_id']       = $this->api->id;
                $aepsreports['number']       = $post->account;
                $aepsreports['apitxnid']     = $post->apitxnid;
                $aepsreports['provider_id']  = $provider->id;
                $aepsreports['mobile']       = $user->mobile;
                $aepsreports['number']       = $post->beneAccountNo;
                $aepsreports['amount']       = $post->amount;
                $aepsreports['charge']       = $post->charge;
                $aepsreports['option3']      = $post->beneBankName;
                $aepsreports['option4']      = $post->beneifsc;
                $aepsreports['via']         = "api";
                $aepsreports['mode']         = $post->mode;
                $aepsreports['txnid']        = $post->txnid;
                $aepsreports['user_id']      = $user->user_id;
                $aepsreports['credited_by']  = '1';
                $aepsreports['balance']      = $user->mainwallet;
                $aepsreports['trans_type']   = "debit";
                $aepsreports['transtype']    = 'fund';
                $aepsreports['status']       = 'pending';
                $aepsreports['product']      = 'payout';
                $aepsreports['remark']       = "Bank Settlement";
                

                Userdata::where('id', $aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                // Change query for observing 
                $userdata = Userdata::where('id', $aepsreports['user_id'])->first();
                if($userdata){
                    $userdata->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                }
                $myaepsreport = Report::create($aepsreports);
                
                $header = array(
                    "content-type: application/json",
                    "client_id:".$this->api->username,
                    "client_secret:".$this->api->password
                );
                
                $parameter = [ 
                                "beneName"=> $post->beneName,
                                "beneAccountNo"=> $post->beneAccountNo,
                                "beneifsc"=> $post->beneifsc,
                                "benePhoneNo"=> $post->benePhoneNo,
                                "beneBankName"=> $post->beneBankName,
                                "clientReferenceNo"=> $post->txnid,
                                "amount"=> $post->amount,
                                "fundTransferType"=>$post->mode,
                                "pincode"=>751024,
                                "custName"=>$user->name,
                                "custMobNo"=>$user->mobile,    
                                "latlong"=> "22.8031731,88.7874172",
                                "paramA"=> "",
                                "paramB"=> ""
                    
                            ];
                            
                        
                $url = $this->api->url.'prod-apiusercashout/cashtransfer';
        
                $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
  
        
                if($result['response'] == ""){
                    return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Accepted", "refno" => $post->txnid]);
                }
                
                $response = json_decode($result['response']);
                
                if(isset($response->status) && $response->status == "SUCCESS"){
                    Report::find($myaepsreport->id)->update(['refno' => $response->rrn,'payid' => $response->transactionId, "status" => 'success']); 
                    return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Successfull", "refno" => isset($response->rrn) ? $response->rrn : $post->txnid]);
                }elseif(isset($response->status) && in_array($response->status, ['FAILED','FAILURE','-2'])){
                    Report::find($myaepsreport->id)->update(['remark'=>$response->statusDesc,'refno' => isset($response->rrn) ? $response->rrn : "Failed", "status" => 'failed']); 
                    // Update Query for observer
                    $userdata = Userdata::where('user_id', $aepsreports['user_id'])->first();
                    if($userdata){
                        $userdata->increment('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    }
                    // User::where('id', $aepsreports['user_id'])->increment('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    return response()->json([ 'statuscode' => "ERR", 'message' => isset($response->statusDesc) ? $response->statusDesc : "Something went wrong", "refno" => isset($response->data->orderRefId) ? $response->data->orderRefId : $post->txnid]);
                }else{
                    Report::find($myaepsreport->id)->update(['remark'=>$response->statusDesc??'','refno' => isset($response->rrn) ? $response->rrn : "pending", 'payid' => 'pending', "status" => 'pending']); 
                    return response()->json([ 'statuscode' => "TUP", 'message' => "Transaction Pending", "refno" => isset($response->rrn) ? $response->rrn : $post->txnid]);
                }
                break;
            
            case 'payout2':
                $rules = array(
                    'contactId' => 'required',
                    'amount'    => 'required|numeric|min:100',
                    "apitxnid"  => "required|unique:aepsreports,apitxnid",
                    "mode" => 'required',
                    "callbackurl" => "required"
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'BPR',
                        'message' => $error
                    ));
                }
                
                $contact = \DB::table('contacts')->where('contactId', $post->contactId)->first();
                
                if(!$contact){
                    return response()->json(['statuscode' => "ERR", "message" => "Invalid Contact Details"]);
                }
        
                if($post->mode == "IMPS"){
                    if($post->amount > 0 && $post->amount <= 1000){
                        $provider = Provider::where('recharge1', 'payout1k')->first();
                    }elseif($post->amount > 1000 && $post->amount <= 25000){
                        $provider = Provider::where('recharge1', 'payout25k')->first();
                    }elseif($post->amount > 25000 && $post->amount <= 200000){
                        $provider = Provider::where('recharge1', 'payout2l')->first();
                    }
                }elseif($post->mode == "NEFT"){
                    $provider = Provider::where('recharge1', 'payoutneft')->first();
                }elseif($post->mode == "RTGS"){
                    $provider = Provider::where('recharge1', 'payoutrtgs')->first();
                }else{
                    $provider = Provider::where('recharge1', 'payout2l')->first();
                }
                
                $post['provider_id'] = $provider->id;
                $post['charge'] = Permission::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                
                if($user->mainwallet < $post->amount + $post->charge){
                    return response()->json(['statuscode' => "ERR", "message" => "Low aeps balance to make this request."]);
                }
                do {
                    $post['txnid'] = "PAY".$this->transcode().rand(1111111111, 9999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport); 

                $aepsreports['api_id']      = $provider->api_id;
                $aepsreports['provider_id'] = $provider->id;
                $aepsreports['apitxnid']    = $post->apitxnid;
                $aepsreports['payid']       = $contact->id;
                $aepsreports['mobile'] = $user->mobile;
                $aepsreports['refno']  = "pending";
                $aepsreports['number'] = $contact->accountNumber;
                $aepsreports['bank']   = $contact->ifsc;
                $aepsreports['amount'] = $post->amount;
                $aepsreports['charge'] = $post->charge;
                $aepsreports['txnid']  = $post->txnid;
                $aepsreports['user_id']= $user->id;
                $aepsreports['credited_by'] = $user->parent_id;
                $aepsreports['balance']     = $user->mainwallet;
                $aepsreports['trans_type']        = "debit";
                $aepsreports['transtype']   = 'fund';
                $aepsreports['aepstype']    = 'payout';
                $aepsreports['product']    = 'payout';
                $aepsreports['status']   = 'pending';
                $aepsreports['remark']   = "Api Payout";
                $aepsreports['via']      = "api";
                $aepsreports['mytxnid']  = $post->mode;
                $aepsreports['authcode'] = $post->callbackurl;

                User::where('id', $aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                $myaepsreport = Report::create($aepsreports);
                $url = "https://api.xettle.io/v1/service/payout/orders";
                
                $parameter["contactId"] = $contact->contactId;
                $parameter["amount"]    = $post->amount;
                $parameter["purpose"]   = "refund";
                $parameter["mode"]      = $post->mode;
                $parameter["narration"] = 'settle';
                $parameter["remark"]    = 'settle';
                $parameter["clientRefId"] = $post->txnid;
                $payload=json_encode($parameter);
                
                $string=(base64_encode($payload)."/v1/service/payout/orders".$this->api->username."####".$this->api->optional1);
                $signature=  hash("sha256",$string);
                $header = array(
                    "authorization: Basic ".base64_encode($this->api->username.":".$this->api->password),
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "Signature:".$signature
                );
                
                $result = Permission::curl($url, "POST", json_encode($parameter), $header, "yes", 'SmartPay', $post->txnid);
                
                if($result['response'] == ""){
                    return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Successfull", "refno" => $post->txnid]);
                }
                
                $response = json_decode($result['response']);
                if(isset($response->code) && $response->code == "0x0200"){
                    Report::where('id', $myaepsreport->id)->update(['refno' => $response->data->orderRefId,'payid' => $response->data->orderRefId, "status" => 'success']); 
                    return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Successfull", "refno" => isset($response->data->orderRefId) ? $response->data->orderRefId : $post->txnid]);
                }elseif(isset($response->code) && in_array($response->code, ['0x0201', "0x0202", '0x0207', '0x0203'])){
                    Report::where('id', $myaepsreport->id)->update(['refno' => isset($response->message) ? $response->message : "Failed", "status" => 'failed']); 
                    User::where('id', $aepsreports['user_id'])->increment('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    return response()->json([ 'statuscode' => "ERR", 'message' => isset($response->message) ? $response->message : "Something went wrong", "refno" => isset($response->data->orderRefId) ? $response->data->orderRefId : $post->txnid]);
                }else{
                    Report::where('id', $myaepsreport->id)->update(['refno' => $response->data->orderRefId, 'payid' => $response->data->orderRefId, "status" => 'pending']); 
                    return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Successfull", "refno" => isset($response->data->orderRefId) ? $response->data->orderRefId : $post->txnid]);
                }
                break;
                
            case 'status':
                $rules = array(
                    "apitxnid"  => "required"
                );
                
                $validator = \Validator::make($post->all(), array_reverse($rules));
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(array(
                        'statuscode' => 'BPR',
                        'message' => $error
                    ));
                }
                
                $myaepsreport = Report::where('apitxnid', $post->apitxnid)->first();
               // dd($myaepsreport);
                if(!$myaepsreport){
                    return response()->json([ 'statuscode' => "ERR", 'message' => "Transaction Not Found"]);
                }
                
                $url = "https://api.xettle.io/v1/service/payout/orders/".$myaepsreport->payid;
                        $method = "GET";
                        $string=("/v1/service/payout/orders/".$myaepsreport->payid.$this->api->username."####".$this->api->optional1);
                $signature=  hash("sha256",$string);
                $parameter="";
                //dd($string,$signature);
                $header = array(
                    "authorization: Basic ".base64_encode($this->api->username.":".$this->api->password),
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "Signature: ".$signature
                );
                
                $result = Permission::curl($url, $method, $parameter, $header);;
                //dd([$url,$header,$result]);
                if($result['response'] != ""){
                    $response = json_decode($result['response']);
                   // dd($response);
                    if(isset($response->status) && in_array(strtolower($response->status), ['failed', 'failure'])|| in_array(strtolower($response->data->status), ['reversed', 'failure'])){
                        Report::where('id', $myaepsreport->id)->update(['refno' => isset($response->data->message) ? $response->data->message : $response->message, "status" => 'reversed']);
                        Permission::transactionRefund($myaepsreport->id);
                    }else{
                        Report::where('id', $myaepsreport->id)->update(['refno' => isset($response->data->bankReference) ? $response->data->bankReference : "success", "status" => 'success']); 
                    }
                }
                
                $myaepsreport = Report::where('id', $myaepsreport->id)->first();
                return response()->json([ 'statuscode' => "TXN", 'message' => "Transaction Successfull", 'status' => $myaepsreport->status, "refno" => $myaepsreport->refno]);
                break;
        }        
        
    }
}
