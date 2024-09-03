<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Utiid;
use App\Models\Aepsfundrequest;
use App\Models\Aepsreport;
use App\Models\Provider;
use App\Models\Api;
use App\Models\Initiateqr;
use App\Models\User;

use App\Helpers\Permission;

class CallbackController extends Controller
{
    
  public function iserveuPayinCallbkp(Request $post)
  {
     \DB::table('microlog')->insert(['response' => json_encode($post->all()), 'product' => 'iserveUPayin']);
     
        if(isset($post->clientRefId)){
            $gettxn = Initiateqr::where("refid", $post->clientRefId)->first();
            $isexistreport = Report::where(["refno"=> $post->rrn,"txnid"=> $post->clientRefId])->first();
     
            if($isexistreport){
                return response()->json(['status' => 1,"statusDesc"=>"already reflected"]);
            }
            
            if(!$gettxn){
                 return response()->json(['status' => 1,"statusDesc"=>"Failure"]);
            }
    
            if($gettxn->status == 'pending' && strtolower($post->status) == 'success'){

                  $user = User::where('id',$gettxn->user_id)->first();
                  $userwalletincrment = User::find($gettxn->user_id)->increment('mainwallet',$post->txnAmt);
                  $provide = Provider::where('recharge1', 'upi1')->first();

              $post['charge'] = Permission::getCommission($post->txnAmt, $user->scheme_id, $provide->id, $user->role->slug);
              $post['gst'] = $this->getGst($post->charge,$user->gstrate);

              $userwalletincrment = User::find($gettxn->user_id)->increment('mainwallet',$post->txnAmt-$post->charge-$post->gst);

                  $insert = 
                  [
                    'number' => $post->payee_vpa,
                    'mobile' => $user->mobile,
                    'provider_id' =>$provide->id,
                    'api_id' => $provide->api_id,
                    'amount' => $post->txnAmt,
                    'charge' => $post->charge,
                    'profit' => '0.00',
                    'gst' => $post->gst,
                    'tds' => '0.00',
                    'apitxnid' => NULL,
                    'txnid' => $gettxn->refid,
                    'payid' => $post->rrn,
                    'refno' => $post->rrn,
                    'description' => null,
                    'remark' => 'Fund Load Success',
                    'option1' => $post->customerName ?? '',
                    'option2' =>$post->transaction_id ??'',
                    'option3' =>"",
                    "payer_vpa"=>$post->payer_vpa,
                    'payeeVPA' =>$post->payee_vpa,
                    'option4' => NULL,
                    'status' => 'success',
                    'aepstype' => 'UPI',
                    'user_id' => $user->id,
                    'credit_by' =>1,
                    'rtype' => 'main',
                    'via' => 'portal',
                    'adminprofit' => '0.00',
                    'balance' => $user->mainwallet,
                    'trans_type' => "credit",
                    'product' => 'UPI'
                  ];       
                  $usercredit = Report::create($insert); 
                  Initiateqr::where("refid", $post->clientRefId)->update(['status'=>'success']);
                  $this->playsoundbox($post->txnAmt,$user);

                  //callback
                  if($user->role->slug == "apiuser"){
                      Permission::callback($usercredit,'upi');
                  }
                   return response()->json(['status' => 0,"statusDesc"=>"success"]);
            }
                   
        }

      return response()->json(['status' => 1,"statusDesc"=>"Failure"]);
  }
    
  public function playsoundbox($amount,$user)
  {
           
      $this->iserveu  = Api::where('code', 'loadwallet')->first();

      $header = array(
          "content-type: application/json",
          "client_id:".$this->iserveu->username,
          "client_secret:".$this->iserveu->password
      );

      $parameter['amount']        = $amount;
      $parameter['language']      = $user->soundBoxLanguage;
      $parameter['product_name']  = "UPI";
      $parameter['device_type']   = $user->soundBoxType;
      $parameter['device_sl_no']  = $user->soundBoxSerial;
      //$url = 'https://apidev.iserveu.online/staging/trigger/playSoundbox';
      $url = 'https://apiprod.iserveu.tech/productionV2/trigger/playSoundbox';

      $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
      //dd([$url, json_encode($parameter), $header,$result]);
      return true;

  }

  public function iserveuPayoutCallbkp(Request $doc)
  {
   \DB::table('microlog')->insert(['response' => json_encode($doc->all()), 'product' => 'iserveUPayout']);
       $report = Report::where(['txnid'=>$doc->merchant_payout_order_id,'product'=>'payout'])->first();
       if($report->status == 'pending'){
           if(isset($doc->Status)){
              if(strtolower($doc->Status) == "success"){
                  $update['status'] = "success";
                 
                  $update['refno'] = isset($doc->rrn) ? $doc->rrn : '';
                  $update['description'] = "Transaction success";
                  return response()->json(['status' =>0,"statusDesc"=>"SUCCESS"]);
              }else if(strtolower($doc->Status) == "pending"){
                  $update['status'] = "pending";
             
                  $update['refno'] = isset($doc->rrn) ? $doc->rrn : '';
                  $update['description'] = "Transaction Pending";
                  return response()->json(['status' =>-5,"statusDesc"=>"INPROGRESS"]);
              }else if(strtolower($doc->Status) == "failure" || strtolower($doc->Status) == "failed"){
                  $update['status'] = "failed";
         
                  $update['refno'] = isset($doc->rrn) ? $doc->rrn : '';
                  $update['description'] = (isset($doc->statusDesc)) ? $doc->statusDesc : "failed";
                  return response()->json(['status' => '-5',"statusDesc"=>"FAILED"]);
              }else if(strtolower($doc->Status) == "refund" || strtolower($doc->Status) == "refunded"){
                //\Myhelper::transactionRefund($report->id);
                 // $update['status'] = "reversed";
                 
                  $update['refno'] = isset($doc->rrn) ? $doc->rrn : '';
                  $update['description'] = (isset($doc->statusDesc)) ? $doc->statusDesc : "failed";
              }

              $update = Report::find([$report->id])->update($update);
           }
       }
   
    return response()->json(['status' =>-5,"statusDesc"=>"FAILED"]);
  }
  
  public function evokCallbkp(Request $post)
  {
 
    \DB::table('microlog')->insert(['response' => json_encode($post->all()), 'product' => 'evokCallback']);
      //$company = Company::whereWebsite($resLog->callbackurl)->first();
    //for static qr callback
    
        $api = Api::whereCode('cosmosupi')->first();

        $cipher = "AES-256-CBC";
        $encrypted_data = $post->message;
        $encryption_key =$api->username;
        $decrypted_data = openssl_decrypt($encrypted_data, $cipher, $encryption_key);

        $newres = substr($decrypted_data,16);
        $json_start_pos = strpos($decrypted_data, '{');

        if ($json_start_pos !== false) {
            $json_string = substr($decrypted_data, $json_start_pos);
            $json_data = json_decode($json_string);
            if(!isset($json_data->status)){
               return response()->json(['status'=>false]);
            }

            $report = Report::where('txnid',$json_data->extTransactionId)->first();

            // for static and else part for dynamic
            if(isset($json_data->remark) && $json_data->remark == 'cstatic'){
               //dd($json_data->extTransactionId);
                $report = Report::where('txnid',$json_data->extTransactionId)->first();
                $iscosmosagent = \DB::table('cosmosmerchants')->where(['vpa'=>$json_data->merchant_vpa])->first();
                 $user  = User::where('id', $iscosmosagent->user_id)->first();

                  $provider = Provider::where('recharge1', 'upi2')->first();

                        $post['provider_id'] = $provider->id;
                        $post['parent_id'] = $user->parent_id;
                        $post['charge'] = Permission::getCommission($json_data->amount, $user->scheme_id, $provider->id, $user->role->slug);
                        $post['gst'] = $this->getGst($post->charge,$user->gstrate);

                          $insert = [
                                    "mobile"   => $user->mobile,
                                    'txnid'    => $json_data->extTransactionId,
                                    "api_id"  => $provider->api->id,
                                    "gst"  => $post->gst,
                                    "charge"  => $post->charge,
                                    "user_id" => $user->id,
                                    "balance" => $user->mainwallet,
                                    'aepstype'=> "UPI",
                                    "trans_type"=>"credit",
                                    'credited_by' => $user->id,
                                    'provider_id' => $post->provider_id,
                                    'product'    => "UPI",
                                    "status"=>"success",
                                    "refno"=>$json_data->rrn,
                                    "amount"=>$json_data->amount,
                                    "payid"=>$json_data->txnId,
                                    "payer_vpa"=>$json_data->customer_vpa,
                                    'payeeVPA' =>$json_data->merchant_vpa,
                                    "payerAccName"=>$json_data->customerName,
                                    "option1"=>$json_data->customerName,
                                    "authcode"=>$json_data->responseTime,
                                    "number"=>$json_data->merchant_vpa
                                  ];

                          $report2 = Report::create($insert);
                        User::find($user->id)->increment('mainwallet', $json_data->amount-$post->charge-$post->gst);
                         if($user->role->slug == "apiuser"){
                              $output['status'] = "success";
                              $output['clientid']  = null;
                              $output['txnid']     = $json_data->extTransactionId;
                              $output['vpaadress']   = $json_data->merchant_vpa;
                              $output['npciTxnId']   = $json_data->rrn;
                              $output['amount']   = $json_data->amount;
                              $output['bankTxnId']   = $json_data->txnId;
                              $output['payerVpa']  = $json_data->customer_vpa;
                              $output['payerAccName']= $json_data->customerName;

                              Permission::curl($user->callbackurl."?".http_build_query($output), "GET", "", [], "yes", "UpiCallback", $report->txnid);
                                
                            } 

        }
    }  

  }  
    
  public function callback(Request $post, $api)
  {
      switch ($api) {
          case 'payout':
              \DB::table('paytmlogs')->insert(['response' => json_encode($post->all()), 'txnid' => $post->result['orderId']]);
              $fundreport = Aepsfundrequest::where('payoutid', $post->txnid)->first();
              if($fundreport && in_array($fundreport->status , ['pending', 'approved'])){
                  if(strtolower($post->status) == "success"){
                      $update['status'] = "approved";
                      $update['payoutref'] = $post->refno;
                  }elseif (strtolower($post->status) == "reversed") {
                      $update['status'] = "rejected";
                      $update['payoutref'] = $post->refno;
                  }else{
                      $update['status'] = "pending";
                  }
                  
                  if($update['status'] != "pending"){
                      $action = Aepsfundrequest::find($fundreport->id)->update($update);
                      if ($action) {
                          if($update['status'] == "rejected"){
                              $report = Report::where('txnid', $fundreport->payoutid)->first();
                              if($report){
                              $report->update(['status' => 'reversed']);
                            //   $report = Report::where('txnid', $fundreport->payoutid)->update(['status' => "reversed"]);
                              }
                              $aepsreports['api_id'] = $report->api_id;
                              $aepsreports['payid']  = $report->payid;
                              $aepsreports['mobile'] = $report->mobile;
                              $aepsreports['refno']  = $report->refno;
                              $aepsreports['aadhar'] = $report->aadhar;
                              $aepsreports['amount'] = $report->amount;
                              $aepsreports['charge'] = $report->charge;
                              $aepsreports['bank']   = $report->bank;
                              $aepsreports['txnid']  = $report->id;
                              $aepsreports['user_id']= $report->user_id;
                              $aepsreports['credited_by'] = $report->credited_by;
                              $aepsreports['balance']     = $report->user->mainwallet;
                              $aepsreports['type']        = "credit";
                              $aepsreports['transtype']   = 'fund';
                              $aepsreports['status'] = 'refunded';
                              $aepsreports['remark'] = "Bank Settlement";
                              Report::create($aepsreports);
                              User::find($aepsreports['user_id'])->increment('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                          }
                      }
                  }
              }
              break;
              
          case 'ppayout':
              \DB::table('paytmlogs')->insert(['response' => json_encode($post->all()), 'txnid' => $post->result['orderId']]);
              $report = Aepsfundrequest::where('payoutid', $post->result['orderId'])->first();
              if($report && in_array($report->status , ['success','pending'])){
              if($report){
                  if(strtolower($post->status) == "success"){
                      Report::where('txnid', $report->payoutid)->update([
                           'status' => 'success',
                           'refno'  => $post->result['rrn']
                      ]);
      
                    //   Aepsfundrequest::where('payoutid', $post->result['orderId'])->update([
                    //       'payoutref' => $post->result['rrn'],
                    //       'status'    => 'approved'
                    //   ]);
                    //   Changed update query for observing.
                    $aepsfundrequest = Aepsfundrequest::where('payoutid', $post->result['orderId'])->first();
                    if($aepsfundrequest){
                       $aepsfundrequest->update([
                        'payoutref' => $post->result['rrn'],
                        'status' => 'approved'
                         ]);
                        } 
                  }elseif (strtolower($post->status) == "failure") {
                    // Changed updated query
                    $reports = Report::where('txnid', $report->payoutid)->get();
                    foreach ($reports as $rep) {
                        $rep->update([
                            'status' => 'reversed',
                            'refno' => $post->result['rrn']
                        ]);
                    }
                    //   Changed update query for observing.
                      Aepsfundrequest::find($report->id)->update(['status' => "rejected", 'payoutref' => $post->result['rrn']]);
                      $aepsreport = Report::where('txnid', $report->payoutid)->first();
                      $aepsreports['api_id'] = $aepsreport->api_id;
                      $aepsreports['payid']  = $aepsreport->payid;
                      $aepsreports['mobile'] = $aepsreport->mobile;
                      $aepsreports['refno']  = $aepsreport->refno;
                      $aepsreports['aadhar'] = $aepsreport->aadhar;
                      $aepsreports['amount'] = $aepsreport->amount;
                      $aepsreports['charge'] = $aepsreport->charge;
                      $aepsreports['bank']   = $aepsreport->bank;
                      $aepsreports['txnid']  = $aepsreport->id;
                      $aepsreports['user_id']= $aepsreport->user_id;
                      $aepsreports['credited_by'] = $aepsreport->credited_by;
                      $aepsreports['balance']     = $aepsreport->user->mainwallet;
                      $aepsreports['type']        = "credit";
                      $aepsreports['transtype']   = 'fund';
                      $aepsreports['status'] = 'refunded';
                      $aepsreports['remark'] = "Bank Settlement Refunded";
      
                      User::find($aepsreports['user_id'])->increment('mainwallet', $aepsreports['amount'] + $aepsreports['charge']);
                      Report::create($aepsreports);
                  }
              }
              }
              break;     
          
          default:
              return response('');
              break;
      }
  } 
    
  public function pinwalletcallbkp(Request $post)
  {
     \DB::table('microlog')->insert(['response' => json_encode($post->all()), 'product' => 'pinwallet']);
     //$requestall = json_encode($post->all());
     if(isset($post->Data['upiid'])){
          $gettxn = Initiateqr::where("refid", $post->Data['upiid'])->first();
          $isexistreport = Report::where("refno", $post->Data['BankRRN'])->first();
          if($isexistreport){
              return response()->json(['status' => true,"message"=>"already reflected"]);
          }
     
          if($gettxn->status == 'pending' && strtolower($post->Data['TxnStatus']) == 'success'){
             $user = User::where('id',$gettxn->user_id)->first();
                $userwalletincrment = User::find($gettxn->user_id)->increment('mainwallet',$post->Data['PayerAmount']);
                $provide = Provider::where('recharge1', 'dynamicqr')->first();
                $insert = 
                [
                  'number' => $post->Data['PayerVA'],
                  'mobile' => $user->mobile,
                  'provider_id' =>$provide->id,
                  'api_id' => $provide->api_id,
                  'amount' => $post->Data['PayerAmount'],
                  'charge' => '0.00',
                  'profit' => '0.00',
                  'gst' => '0.00',
                  'tds' => '0.00',
                  'apitxnid' => NULL,
                  'txnid' => $gettxn->refid,
                  'payid' => $post->Data['BankRRN'],
                  'refno' => $post->Data['BankRRN'],
                  'description' => null,
                  'remark' => "Fund Load Success",
                  'option1' => $post->Data['PayerName'],
                  'option2' =>$post->Data['PinWalletTransactionId'],
                  'option3' =>$post->Data['PayerVA'],
                  'option4' => NULL,
                  'status' => 'success',
                  'aepstype' => 'UPI',
                  'user_id' => $user->id,
                  'credit_by' =>1,
                  'rtype' => 'main',
                   'via' => 'portal',
                   'adminprofit' => '0.00',
                   'balance' => $user->mainwallet,
                   'trans_type' => "credit",
                   'product' => "fund request"
                ];       
                $usercredit = Report::create($insert); 
                Initiateqr::where("refid", $post->Data['upiid'])->update(['status'=>'success']);
                if($user->role->slug == "apiuser"){
                    Permission::callback($usercredit,'upi');
                }
          }
                 
      }
     
      return response()->json(['status' => true,"message"=>"Ready to work"]);
  }
    
  public function kwikpaisacallbkp(Request $doc)
  {
   \DB::table('microlog')->insert(['response' => json_encode($doc->all()), 'product' => 'kwikpaisa']);
       $report = Report::where(['txnid'=>$doc->merchant_payout_order_id,'product'=>'payout'])->first();
       if($report->status == 'pending'){
           if(isset($doc->txn_status)){
              if(strtolower($doc->txn_status) == "success"){
                  $update['status'] = "success";
                 
                  $update['refno'] = isset($doc->tranfer_rrn_number) ? $doc->tranfer_rrn_number : '';
                  $update['description'] = "Transaction success";
              }else if(strtolower($doc->txn_status) == "pending"){
                  $update['status'] = "pending";
             
                  $update['refno'] = isset($doc->tranfer_rrn_number) ? $doc->tranfer_rrn_number : '';
                  $update['description'] = "Transaction Pending";}else if(strtolower($doc->txn_status) == "failure" || strtolower($doc->txn_status) == "failed"){
                  $update['status'] = "failed";
         
                  $update['refno'] = isset($doc->tranfer_rrn_number) ? $doc->tranfer_rrn_number : '';
                  $update['description'] = (isset($doc->description)) ? $doc->description : "failed";
              }else if(strtolower($doc->txn_status) == "refund" || strtolower($doc->txn_status) == "refunded"){
                //\Myhelper::transactionRefund($report->id);
                 // $update['status'] = "reversed";
                 
                  $update['refno'] = isset($doc->tranfer_rrn_number) ? $doc->tranfer_rrn_number : '';
                  $update['description'] = (isset($doc->description)) ? $doc->description : "failed";
              }

              $update = Report::find([$report->id])->update($update);
           }
       }
   
    return response()->json(['status' => true,"message"=>"Ready to work"]);
  }

    public function getGst($amount,$gstrate)
    {
        return ($gstrate/100)*$amount;
    }

}