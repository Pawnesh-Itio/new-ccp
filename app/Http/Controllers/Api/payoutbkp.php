<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Api;
use App\Models\Apitoken;
use App\Models\Provider;
use App\Models\Mahabank;
use App\Models\Report;
use App\Models\Commission;
use App\Models\Aepsreport;
use App\Models\Aepsfundrequest;
use App\Models\User;
use Carbon\Carbon;
use App\Helpers\Permission;

class PayoutController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'xettlepayout')->first();
    }

    public function transaction(Request $post)
    {
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        $post['user_id'] = $token->user_id;
        
        if(!$this->api || $this->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Payout Service Currently Down."]);
        }
        
        $user = User::where('id', $post->user_id)->first();
              
        switch ($post->transactionType) {

            
            case 'payout':
               $api = Api::where('code', 'kppayout')->first();
                if(!$api){
                    return response()->json(['status'=> "Api down for some time"]);
                }


                do {
                    $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                } while (Report::where("txnid", "=", $post->payoutid)->first() instanceof Report);
  
                $provider = Provider::where('recharge1', 'payout1')->first();
                
                $url = $api->url.'single_payout';
                
                
                $aepsreports['api_id'] = $api->id;
                $aepsreports['number'] = $post->account;
            
                $aepsreports['provider_id']  = $provider->id;
                $aepsreports['mobile']       = $user->mobile;
                $aepsreports['refno']        = "success";
                $aepsreports['aadhar']       = $post->account;
                $aepsreports['amount']       = $post->amount;
                $aepsreports['charge']       = $post->charge;
                $aepsreports['option3']      = $post->bank;
                $aepsreports['option4']      = $post->ifsc;
                $aepsreports['mode']         = 'IMPS';
                $aepsreports['txnid']        = $post->payoutid;
                $aepsreports['user_id']      = $user->id;
                $aepsreports['credited_by']  = $this->admin->id;
                $aepsreports['balance']      = $user->mainwallet;
                $aepsreports['trans_type']         = "debit";
                $aepsreports['transtype']    = 'fund';
                $aepsreports['status']       = 'success';
                $aepsreports['product']      = 'payout';
                $aepsreports['remark']       = "Bank Settlement";
                //dd($aepsreports);
                User::where('id', $aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                $myaepsreport = Report::create($aepsreports);
                
                
                $parameter = [
                        "event_name"=> "fund_transfer",
                        "debit_account_number"=> $api->optional2,
                        "transfer_type"=> "direct",
                        "beneficiary_mode"=> "onetimetransfer",
                        "mobile"=> $user->mobile,
                        "email"=> $user->email,
                        "address"=> null,
                        "country_dialing_code"=> "91",
                        "country_iso_code"=> "IN",
                        "beneficiary_id"=> "null",
                        "debit_account_type"=> "current",
                        "transfer_mode"=> "IMPS",
                        "transfer_amount"=> $post->amount,
                        "account_transfer"=> [
                                                "account_owner_name"=> $user->name,
                                                "account_number"=> $post->account,
                                                "ifsc_code"=> $post->ifsc,
                                                "is_validate"=> "false",
                                                "payment_for"=> "payout request"
                                             ],
                        "payout_order_id"=> $post->payoutid
                        ];
          

                $header = array(
                    "Accept: application/json",
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "KPENVIRONMENT:".$api->optional1,
                    "KPMID:".$api->username,
                    "KPMIDKEY:".$api->password
                );

                    
                $response = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes', 'kwikpaisapayout', $post->payoutid);
                
               
                

                $result=json_decode($response['response']);

                if($response['response'] == ''){
                    return response()->json(['status'=> "success"]);
                }
                
                if(isset($result->status) && $result->status=='success'){
                    Report::where(['id'=> $myaepsreport->id])->update(['status'=>'success','refno'=>$result->tranfer_rrn_number]);
                    return response()->json(['status'=>"success"], 200);
                }else{
                    User::where('id', $aepsreports['user_id'])->increment('mainwallet', $aepsreports['amount']+$aepsreports['charge']);
                    Report::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed" ]);
                    return response()->json(['status'=>isset($result->error_description) ? $result->error_description : "Trasaction failed, try again"], 400);
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
