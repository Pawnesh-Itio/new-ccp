<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mahaagent;
use App\Models\Company;
use App\Models\Mahastate;
use App\Models\Report;
use App\Models\Commission;
use App\Models\Aepsreport;
use App\Models\Provider;
use App\Models\Api;
use App\Models\Cosmosmerchant;
use App\Models\Apitoken;
use App\Helpers\Permission;

class CosmosUpiController extends Controller
{
 
    public function verifyVPA(Request $post)
    {
        $rules = array(
           'token' => 'required',
           'upiId' => 'required'
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

        $post['company_id'] = $user->company_id;
        $api = Api::whereCode('cosmosupi')->first();
        if(!$api){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Api server down']);
        }
        $post['transction_id'] = $api->optional2.date('YmdHis').rand(11111111,99999999);
        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();

        if(!$cosmosAgent){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Onboarding pending yet, contact to administrator']);
        }
        
        $req = [
                'source' => $api->optional3,
                'channel' => 'api',
                'extTransactionId' =>$post->transction_id,
                'upiId' => $post->upiId,
                'terminalId' => $cosmosAgent->sid,
                'sid' => $cosmosAgent->sid,
              ];
            
        $checksum='';
        foreach ($req as $val){
            $checksum.=$val;
        }
        $checksum_string=$checksum.$api->optional1;
        $req['checksum']=hash('sha256',$checksum_string);
    
        $key= $api->username;
        $key=substr((hash('sha256',$key,true)),0,16);
    
        $cipher='AES-128-ECB';
        $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);
        
        $url = $api->url.'cm/v2/verifyVPA';
        $header = array(
                    "Content-Type: text/plain",
                    "cid: ".$api->password
                 );
        $result = Permission::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosVerifyVpa', $post->transction_id);
           $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
      
          //dd([$url, "POST",json_encode($req), $header,$result,$decrypted_string]); 
        if($result['response'] != ''){
            $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
          
            $doc = json_decode($decrypted_string);
            if($doc->status =="SUCCESS"){
                $deatils = [
                      "upiId"=>$doc->upiId,
                      "extTransactionId"=>$doc->extTransactionId,
                      "txnType"=>$doc->txnType,
                      "customerName"=>$doc->data['0']->customerName
                    ];
                return response()->json(['statuscode'=>'TXN', 'message'=> "UPI id verified successfully","data" =>$deatils ]);
            }else{
               return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Verification failed at bank end"]); 
            }
        }else{
             return response()->json(['statuscode'=>'TXF', 'message'=> "Verification failed at bank end"]);
        }
        dd($api);
        exit();
        
    }
    
    public function initiateRequest(Request $post)
    {
        $rules = array(
            'token'  => 'required',
            "upiId"  => "required",
            "amount" => "required|numeric",
            "customerName" => "required",
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
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();

        $provider = Provider::where('recharge1', 'upi1')->first();

        $post['provider_id'] = !empty($provider->id) ? $provider->id :0;
    

       /* $usercommission = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);

        if($user->mainwallet<=$usercommission){
           return response()->json(['statuscode'=>'ERR', 'message'=> "Please Contact Admin to initiate the transction"]); 
        }
*/
        $api = Api::whereCode('cosmosupi')->first();
        
        if(!$api){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Api server down']);
        }

        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();

        if(!$cosmosAgent){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Onboarding pending yet, contact to administrator']);
        }

       $post['orderId'] = 'SPAY'.date('YmdHis').rand(11111111,99999999);

      $req = [
              'source' => $api->optional3,
              'channel' => 'api',
              'extTransactionId' => $post->orderId,
              'upiId' => $post->upiId,
              "amount"=> $post->amount.'.00',
              "customerName"=> $post->customerName,
              "statusKYC"=>"Y",
              "requestTime"=>date('Y-m-d H:i:s'),
              "remark"=>"Wallet Load",
              "terminalId"=> $cosmosAgent->sid,
              'sid' => $cosmosAgent->sid
            ];


        $checksum='';
        foreach ($req as $val){
            $checksum.=$val;
        }
        $checksum_string=$checksum.$api->optional1;
        $req['checksum']=hash('sha256',$checksum_string);

        $key= $api->username;
        $key=substr((hash('sha256',$key,true)),0,16);

        $cipher='AES-128-ECB';
        $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);

        $url = $api->url.'cm/v2/transfer';
        $header = array(
                    "Content-Type: text/plain",
                    "cid: ".$api->password
                  );

        $result = Permission::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosInitiate', $post->orderId);
        if($result['response'] != ''){

        $response = $result['response'];
        $decrypted_string = openssl_decrypt($response,$cipher,$key);
        $doc = json_decode($decrypted_string);

       //dd([$decrypted_string,json_encode($req)]);

        if($doc->status == "SUCCESS" && $doc->customerName !=''){    
        
            $hashAlgorithm   = 'sha256';

            $hash = hash($hashAlgorithm, $post->orderId.'|'.$post->amount);

            $insert = [
                        "mobile"   => $user->mobile,
                        'txnid'    => $post->orderId,
                        'mytxnid'  => $post->clientRefId,
                        "amount"  => $post->amount,
                        "api_id"  => $provider->api->id,
                        "user_id" => $post->user_id,
                        "balance" => $user->mainwallet,
                        'aepstype'=> "UPI",
                        "trans_type"=>"credit",
                        'status'  => 'success',
                        'credited_by' => $post->user_id,
                        'balance'     => $user->mainwallet,
                        'provider_id' => $post->provider_id,
                        'product'    => "upi"
                      ];
               User::where('id', $post->user_id)->increment('mainwallet', $post->amount);

            Report::create($insert);

          $data = [];
          
          $data['orderAmount'] = $post->amount;
          $data['orderId']     = $post->orderId;
          $data['clientTxnId'] = $post->clientTxnId;
          $data['hashToken']   = $hash;
          
          return response()->json(['statuscode'=>"TXN","message"=>"request Initiated successfully","data"=>$data]);
        }else{
            return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Verification failed at bank end"]); 
        }
    }else{
        return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong"]);
    }
    }


    public function sdkVerify(Request $post)
    {

        $rules = array(
            'token'  => 'required',
            "orderId"  => "required",
            "upiId"  => "required",
            "amount" => "required|numeric"
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

       $post['orderId'] = $post->orderId;
       $hashAlgorithm   = 'sha256';

      
      $hash = hash($hashAlgorithm, $post->orderId.'|'.$post->amount);
      if ($hash != $post->hashToken) {
        return response()->json(['statuscode'=>"ERR","message"=>"Hash Token mismatch"]);
          
      }

      $report = Report::where('txnid',$post->orderId)->first();

      if(!$report){
         return response()->json(['statuscode'=>"ERR","message"=>"Invalid orderId"]);
      }

        
        $blockHandle=['PhonePe','HDFC-Qa','MakeMyTrip','UltraCash'];
        $user = User::where('id',$report->user_id)->first();
        $data =[];
        $data['companyName'] = $user->company->companyname;
        $data['companyLogo'] = "https://".$user->company->website."/public/logos/".$user->company->logo;
        $data['orderAmount'] = $post->amount;
        $data['orderId']     = $post->orderId;
        $data['clientTxnId'] = $report->mytxnid;
        $data['upiId']       = $post->upiId;

        //Report::where('id', $report->id)->update(['status'=> "pending"]);

        $data['upiIntentString']="upi://pay?pa=".$post->upiId."&pn=".$user->company->companyname."&am=".$post->amount."&tn=".$post->orderId."&tr=".$post->orderId."&ti=".$post->orderId;
        return response()->json(['statuscode'=>"TXN","message"=>"verified successfully","data"=>$data,'upiblockHandle'=>$blockHandle]);
    }

    public function checkStatus(Request $post)
    {
        $rules = array(
            'token'  => 'required',
            "clientTxnId"  => "required"
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

        $api = Api::whereCode('cosmosupi')->first();
        if(!$api){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Api server down']);
        }

        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();

        if(!$cosmosAgent){
            return response()->json(['statuscode'=>'ERR', 'message'=> 'Onboarding pending yet, contact to administrator']);
        }

        $order = Report::where(['mytxnid'=>$post->clientTxnId])->first();
        if(!$order){
          return response()->json(['statuscode'=>'ERR', 'message'=> "Order Id Not Found"]);  
        }


        $req = [
                  'source'           => $api->optional3,
                  'channel'          => 'api',
                  "terminalId"       => $cosmosAgent->sid,
                  'extTransactionId' => $order->txnid
               ];

        $checksum = '';
        foreach ($req as $val){
            $checksum.=$val;
        }
        $checksum_string=$checksum.$api->optional1;
        $req['checksum']=hash('sha256',$checksum_string);

        $key= $api->username;
        $key=substr((hash('sha256',$key,true)),0,16);

        $cipher='AES-128-ECB';
        $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);

        //$url = $api->url.'cm/v2/status';
        $url = $api->url.'qr/v1/qrStatus';
        $header = array(
                    "Content-Type: text/plain",
                    "cid: ".$api->password
                  );

        $result = Permission::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosStatus', $post->orderId);
        if($result['response'] != ''){

        $response = $result['response'];
        $decrypted_string = openssl_decrypt($response,$cipher,$key);

        $doc = json_decode($decrypted_string);
       //dd([  $url, "POST",json_encode($req),$decrypted_string, $header]);

        if($doc->status == "SUCCESS" && count($doc->data) > 0){ 
           $update['refno']  = $doc->data[0]->custRefNo ?? "success";
           $update['status']  = "success";
           Report::where('txnid',$order->txnid)->update($update);


          $data = [];
          
          $data['amount'] = $order->amount;
          $data['clientTxnId'] = $post->clientTxnId;
          $data['refno'] = $update['refno'];
          
          return response()->json(['statuscode'=>"TXN","message"=>"Transaction successfully","data"=>$data]);
        }else if(($doc->status == "FAILURE" || $doc->status == "FAILED") && count($doc->data) > 0){ 
           $update['refno']  = $doc->data[0]->respMessge ?? "failed";
           $update['status']  = "failed";
           Report::where('txnid',$order->txnid)->update($update);

          $data = [];
          
          $data['amount'] = $order->amount;
          $data['clientTxnId'] = $post->clientTxnId;
          
          return response()->json(['statuscode'=>"TXF","message"=>$doc->data[0]->respMessge ?? "Transaction failed","data"=>$data]);
        }else{
            return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Something went wrong"]); 
        }
    }else{
        return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong"]);
    }
    }

    public function QrIntent(Request $post)
    {

      
        $rules = array(
            'token'   => 'required',
            'clientOrderId' => 'required|unique:reports,mytxnid',
            'amount' => 'required',
            'returnUrl' => 'required|url'
        );
        
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'statuscode' => 'BPR',
                'status'=> 'Bad Parameter Request.',  
                'message' => $error
            ));
        }
        
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
         return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();
        $post['company_id'] = $user->company_id;
        $company=Company::whereId($user->company_id)->first();
        $api = Api::whereCode('cosmosupi')->first();
        $post['transction_id'] = !empty($api->optional2) ? $api->optional2.date('YmdHis').rand(11111111,99999999) :'';
        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();
        if(!$cosmosAgent){
           return response()->json(['statuscode'=>'ERR', 'message'=>"Merchant Not Registed with SID"]);    
        }
        $provider = Provider::where('recharge1', 'upi2')->first();
        $post['provider_id'] = $provider->id;

        /*if($user->mainwallet < $post->amount){
           return response()->json(['statuscode'=>'ERR', 'message'=> "Low balance to initiate this transaction"]); 
        }*/
        
        $req = [
                'source' => $api->optional3,
                'channel' => 'api',
                'extTransactionId' =>  $post->transction_id,
                'sid' => $cosmosAgent->sid,
                "terminalId"=>$cosmosAgent->sid,
                "amount"=>$post->amount,
                "type"=>"D",
                "remark"=>"Wallet Load",
                "requestTime"=>date("Y-m-d h:i:sa"),
                "minAmount"=>'1',
                "receipt"=>$post->returnUrl,
            
           ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.$api->optional1;
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key = $api->username;
            $key = substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);
            $url = $api->url.'qr/v1/dqr';
            $header = array(
                        "Content-Type: text/plain",
                        "cid: ".$api->password
                     );
            $result = Permission::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosVerifyVpa', $post->transction_id);
            if($result['response'] != ''){
            $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
           // dd([$decrypted_string,json_encode($req)]);
            $doc = json_decode($decrypted_string);
            
            if($doc->status == "SUCCESS" && $doc->qrString !=''){
                $insert = [
                        "mobile"       => $cosmosAgent->mobileNumber,
                        "payeeVPA"     => $cosmosAgent->vpa,
                        'txnid'        => $post->transction_id,
                        "payid"        => $doc->extTransactionId,
                        'mytxnid'      => $post->clientOrderId,
                        "amount"       => $post->amount,
                        "api_id"       => $provider->api->id,
                        "user_id"      => $post->user_id,
                        "balance"      => $user->mainwallet,
                        'aepstype'     => "UPI",
                        "trans_type"   => "credit",
                        "option1"      => urldecode($doc->qrString),
                        'status'       => 'initiated',
                        'description'  => $post->returnUrl,
                        'credited_by'  => $post->user_id,
                        'balance'      => $user->mainwallet,
                        'provider_id'  => $post->provider_id,
                        'product'      => "upi"
                    ];
                    //dd($insert);
                    Report::create($insert);
                $deatils = [
                      "extTransactionId"=>$doc->extTransactionId,
                      "qrString"=>urldecode($doc->qrString),
                      "clientOrderId"=>$post->clientOrderId,
                      "url"=> 'http://' . $company->website.'/order/'.$doc->extTransactionId
                    ];
                return response()->json(['statuscode'=>'TXN', 'message'=> "QR generated Successfully","data" =>$deatils ]);
            }else{
               return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Verification failed at bank end"]); 
            }
        }else{
             return response()->json(['statuscode'=>'TXF', 'message'=> "Verification failed at bank end"]);
        }

    }
   

    public function staticQrIntent(Request $post)
    {

        $rules = array(
            'token'   => 'required',
            'clientOrderId' => 'required|unique:reports,mytxnid'
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
        
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
         return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();
        $post['company_id'] = $user->company_id;
        $company=Company::whereId($user->company_id)->first();
        $api = Api::whereCode('cosmosupi')->first();
        $post['transction_id'] = !empty($api->optional2) ? $api->optional2.date('YmdHis').rand(11111111,99999999) : '';
        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();
        if(!$cosmosAgent){
           return response()->json(['statuscode'=>'ERR', 'message'=>"Merchant Not Registed with SID"]);    
        }
        $provider = Provider::where('recharge1', 'upi2')->first();
        $post['provider_id'] = $provider->id;

        /*if($user->mainwallet < $post->amount){
           return response()->json(['statuscode'=>'ERR', 'message'=> "Low balance to initiate this transaction"]); 
        }*/
        //'extTransactionId' =>  $post->transction_id, in req param
        $req = [
                'source' => $api->optional3,
                'channel' => 'api',
                'extTransactionId' => $post->transction_id,
                'sid' => $cosmosAgent->sid,
                "terminalId"=>$cosmosAgent->sid,
                "type"=>"S",
                "remark"=>"cstatic",
                "requestTime"=>date("Y-m-d h:i:sa"),
                "minAmount"=>'5',
                "receipt"=>$post->returnUrl,
            
           ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.$api->optional1;
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key= $api->username;
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);
            $url = $api->url.'qr/v1/dqr';
            $header = array(
                        "Content-Type: text/plain",
                        "cid: ".$api->password
                     );
            $result = Permission::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosVerifyVpa', $post->transction_id);
            if($result['response'] != ''){
            $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
            $doc = json_decode($decrypted_string);
            
            if($doc->status == "SUCCESS" && $doc->qrString !=''){
                $insert = [
                        "mobile"       => $cosmosAgent->mobileNumber,
                        "payeeVPA"     => $cosmosAgent->vpa,
                        'txnid'        => $post->transction_id,
                        "payid"        => $doc->extTransactionId,
                        'mytxnid'      => $post->clientOrderId,
                        "amount"       => '0',
                        "api_id"       => $provider->api->id,
                        "user_id"      => $post->user_id,
                        "balance"      => $user->mainwallet,
                        'aepstype'     => "UPI",
                        "trans_type"   => "credit",
                        "option1"      => urldecode($doc->qrString),
                        'status'       => 'initiated',
                        'description'  => $post->returnUrl,
                        'credited_by'  => $post->user_id,
                        'balance'      => $user->mainwallet,
                        'provider_id'  => $post->provider_id,
                        'product'      => "upicollect"
                    ];
                    //dd($insert);
                    Report::create($insert);
                $deatils = [
                      "extTransactionId"=>$doc->extTransactionId,
                      "qrString"=>urldecode($doc->qrString),
                      "clientOrderId"=>$post->clientOrderId,
                      "url"=> 'http://' . $company->website.'/order/'.$doc->extTransactionId
                    ];
                return response()->json(['statuscode'=>'TXN', 'message'=> "QR generated Successfully","data" =>$deatils ]);
            }else{
               return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Verification failed at bank end"]); 
            }
        }else{
             return response()->json(['statuscode'=>'TXF', 'message'=> "Verification failed at bank end"]);
        }

    }

    public function orderIdInitiate(Request $post, $orderId)
    {
        $order = Report::where('txnid',$orderId)->whereStatus('initiated')->first();
        if(!$order){
          return response()->json(['statuscode'=>'ERR', 'message'=> "Order Id Not Found"]);  
        }
        $data['option1'] =$order->option1;
        $data['payid'] =$order->payid;
        $data['mytxnid'] =$order->mytxnid;
        $data['amount'] =$order->amount;
        $data['orderId'] =$orderId;
        return view("service.qrPay")->with($data);
    }

/*    public function verifyVPA(Request $post)
    {
        //dd('ok');
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
         return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();
        $post['company_id'] = $user->company_id;
        $api = Api::whereCode('cosmosupi')->first();
        $post['transction_id'] = $api->optional2.date('YmdHis').rand(11111111,99999999);
        $cosmosAgent = Cosmosmerchant::where('user_id',$post->user_id)->first();
        
        $req = [
            'source' => $api->optional3,
            'channel' => 'api',
            'extTransactionId' =>$post->transction_id,
            'upiId' => $post->UpiId,
            'terminalId' => $cosmosAgent->sid,
            'sid' => $cosmosAgent->sid,
        ];
            
        $checksum='';
        foreach ($req as $val){
            $checksum.=$val;
        }
        $checksum_string=$checksum.$api->optional1;
        $req['checksum']=hash('sha256',$checksum_string);
    
        $key= $api->username;
        $key=substr((hash('sha256',$key,true)),0,16);
    
        $cipher='AES-128-ECB';
       // dd([$req,$api->optional1]);
        $encrypted_string=openssl_encrypt(json_encode($req),$cipher,$key);
        
        $url = $api->url.'cm/v2/verifyVPA';
        $header = array(
                    "Content-Type: text/plain",
                    "cid: ".$api->password
                 );
        $result = \Myhelper::curl($url, "POST",$encrypted_string, $header, "yes", 'cosmosVerifyVpa', $post->transction_id);
           $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
      
          //dd([$url, "POST",json_encode($req), $header,$result,$decrypted_string]); 
        if($result['response'] != ''){
            $response = $result['response'];
            $decrypted_string = openssl_decrypt($response,$cipher,$key);
          
            $doc = json_decode($decrypted_string);
            if($doc->status =="SUCCESS"){
                $deatils = [
                      "upiId"=>$doc->upiId,
                      "extTransactionId"=>$doc->extTransactionId,
                      "txnType"=>$doc->txnType,
                      "customerName"=>$doc->data['0']->customerName
                    ];
                return response()->json(['statuscode'=>'TXN', 'message'=> "UPI id verified successfully","data" =>$deatils ]);
            }else{
               return response()->json(['statuscode'=>'TXF', 'message'=> $doc->data[0]->respMessge??"Verification failed at bank end"]); 
            }
        }else{
             return response()->json(['statuscode'=>'TXF', 'message'=> "Verification failed at bank end"]);
        }
        //dd($api);
        
    }*/
    
    public function statusCheck(Request $post)
    {
        $rules = array(
            'extTransactionId'   => 'required'
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
        
        $report = Report::whereTxnid($post->extTransactionId)->first();
        
        $data = [
                "status"=>$report->status,
                "amount"=>$report->amount,
                "utr"=>$report->refno,
                "returnUrl"=>$report->description
               ];
        return response()->json(['statuscode'=>'TXN', 'status'=>$report->status,'data'=> $data]);  
    }
    
    
    public function callbackDecrypt(Request $post)
    {
        
        $cipher = "AES-256-CBC";
        $encrypted_data ='';
        $encryption_key ="b98b5b508d1a43a89ffc5d2b3df27d45";
        //Decrypt data
        $decrypted_data = openssl_decrypt($encrypted_data, $cipher, $encryption_key);
        //echo $decrypted_data;
         dd(substr($decrypted_data,16));
    }

    public function upiTransfer(Request $post)
    {
     

        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
         return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $post['user_id'] = $token->user_id;
        $user = User::whereId($post->user_id)->first();
        $api                   = Api::whereCode('cosmosupi')->first();
        $post['transction_id'] = $api->optional2.date('YmdHis').rand(11111111,99999999);
        $cosmosAgent           = Cosmosmerchant::where('user_id',$post->user_id)->first();

        $req = [
            'source' => $api->username,
            'channel' => 'api',
            'extTransactionId' => $post->transction_id,
            'upiId' => '8726241799@timecosmos',
            'terminalId' => $cosmosAgent->sid,
            'sid' => $cosmosAgent->sid,
            "amount"=>"10.00",
            "customerName"=>"Vikeash kumar",
            "remark"=>"Wallet Load",
        ];
            
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }$checksum_string=$checksum.$api->optional1;
            $req['checksum'] = hash('sha256',$checksum_string);
        
            $key = $api->username;
            $key = substr((hash('sha256',$key,true)),0,16);
        
            $cipher = 'AES-128-ECB';
            $encrypted_string = openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantuat.timepayonline.com/evok/cm/v2/transfer',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$encrypted_string,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain',
            'cid: '.$api->password,
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/cm/v2/transfer',$req,$encrypted_string,$response,$decrypted_string]); 

    }
    //

   
}