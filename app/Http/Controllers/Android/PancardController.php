<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\Utiid;
use App\Models\Provider;
use App\Helpers\Permission;
use Carbon\Carbon;

class PancardController extends Controller
{
    public function transaction (Request $post)
    {
        if(!in_array($post->type, ['utiid', 'token'])){
            return response()->json(['statuscode' => "ERR", "message" => "Type parameter request in invalid"]);
        }

        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        if (!Permission::can('utipancard_service', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }

        $provider = Provider::where('recharge1', 'utipancard')->first();

        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Recharge Service Currently Down."]);
        }
        $post['api_id'] = $provider->api->id;

        switch ($post->type) {
            case 'utiid':
                $vledata = Utiid::where('user_id', $post->user_id)->first();
                if($vledata){
                    return response()->json(['statuscode' => "TXN", "message" => "Utiid creation request submitted"]);
                }
                
                $post['name'] = $user->shopname;
                $post['location'] = $user->address." ".$user->city;
                $post['contact_person'] = $user->name;
                $post['pincode'] = $user->pincode;
                $post['state'] = $user->state;
                $post['email'] = $user->email;
                $post['mobile'] = $user->mobile;
                $post['user_id'] = $user->id;
                $post['adhaar'] = $user->aadharcard;
                $post['pan'] = $user->pancard;
                $post['type'] = "new";
                
                $rules = array(
                    'name'     => 'required',
                    'location' => 'required',
                    'contact_person' => 'required',
                    'pincode'  => 'required|numeric|digits:6',
                    'state'    => 'required',
                    'email'    => 'required',
                    'mobile'   => 'required|numeric|digits_between:10,11',
                );

                $validate = Permission::FormValidator($rules, $post);
                if($validate != "no"){
                    return $validate;
                }
                do {
                    $post['txnid'] = "EB".rand(1111111111, 9999999999);
                } while (Utiid::where("txnid", "=", $post->txnid)->first() instanceof Utiid);

                $parameter['createdby'] = $provider->api->username;
                $parameter['securityKey'] = $provider->api->password;
                $parameter['psaname'] = $post->name;
                $parameter['location'] = $post->location;
                $parameter['contactperson'] = $post->contact_person;
                $parameter['pincode'] = $post->pincode;
                $parameter['state'] = $post->state;
                $parameter['emailid'] = $post->email;
                $parameter['phone1'] = $post->mobile;
                $parameter['phone2'] = $post->mobile;
                $parameter['adhaar'] = $post->adhaar;
                $parameter['pan'] = $post->pan;
                $parameter['dob'] = date('m/d')."/1989";
                $parameter['udf1'] = $post->txnid;
                $parameter['udf2'] = "";
                $parameter['udf3'] = "";
                $parameter['udf4'] = "";
                $parameter['udf5'] = "";
                $url = $provider->api->url."UATInsUTIAgent";

                if (env('APP_ENV') != "local") {
                    $result = Permission::curl($url, "POST", json_encode($parameter), ["Content-Type: application/json", "Accept: application/json"], "yes", 'App\Model\Utiid' , $post->txnid);
                }else{
                    $result = [
                        'error' => true,
                        'response' => json_encode([
                            'statuscode' => 'TXN',
                            'message'  => 'local'
                        ])
                    ];
                }

                if(!$result['error'] || $result['response'] != ''){
                    $doc = json_decode($result['response']);
                    if(isset($doc[0]->StatusCode) && $doc[0]->StatusCode == "000"){
                        $post['payid'] = $doc[0]->Request;
                        $post['vleid'] = $doc[0]->psaid;
                        $action = Utiid::create($post->all());
                        if ($action) {
                            return response()->json(['statuscode' => "TXN", "message" => "Utiid creation request submitted"]);
                        }else{
                            return response()->json(['statuscode' => "TXF", "message" => "Task Failed, please try again"]);
                        }
                    }else{
                        return response()->json(['status' =>(isset($doc[0]->Message))? $doc[0]->Message : "Task Failed, please try again"], 200);
                    }
                }else{
                    return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
                }
                break;

            case 'token':
                if ($this->pinCheck($post) == "fail") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }
                
                $rules = array(
                    'vleid'   => 'required',
                    'tokens'  => 'required|numeric|min:1',
                );
                
                $validate = Permission::FormValidator($rules, $post);
                if($validate != "no"){
                    return $validate;
                }

                if($user->mainwallet - $this->mainlocked() < $post->tokens * 107){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Low Balance, Kindly recharge your wallet.']);
                }

                $vledata = Utiid::where('user_id', $user->id)->first();

                if(!$vledata){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Vle id not found']);
                }

                $post['amount'] = $post->tokens * 107;
                $post['profit'] = $post->tokens * Permission::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

                $previousrecharge = Report::where('number', $vledata->vleid)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                if($previousrecharge > 0){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Same Transaction allowed after 2 min.'], 400);
                }
                
                $action = User::where('id', $post->user_id)->decrement('mainwallet', $post->amount - $post->profit);
                if ($action) {
                    $insert = [
                        'number' => $vledata->vleid,
                        'mobile' => $user->mobile,
                        'provider_id' => $provider->id,
                        'api_id' => $provider->api->id,
                        'amount' => $post->amount,
                        'profit' => $post->profit,
                        'txnid'  => $post->txnid,
                        'option1' => $post->tokens,
                        'status'  => 'pending',
                        'user_id'    => $user->id,
                        'credit_by'  => $user->id,
                        'rtype'      => 'main',
                        'via'        => 'app',
                        'balance'    => $user->mainwallet,
                        'trans_type' => 'debit',
                        'product'    => 'utipancard'
                    ];

                    $report = Report::create($insert);
                    
                    $parameter['createdby'] = $provider->api->username;
                    $parameter['securityKey'] = $provider->api->password;
                    $parameter['totalcoupon_physical'] = $post->tokens;
                    $parameter['psaid'] = $vledata->vleid;
                    $parameter['transactionid'] = $post->txnid;
                    $parameter['transactiondate'] = date('m/d/Y h:i:s A');
                    $parameter['udf1'] = $post->txnid;
                    $parameter['udf2'] = "";
                    $parameter['udf3'] = "";
                    $parameter['udf4'] = "";
                    $parameter['udf5'] = "";

                    if (env('APP_ENV') == "server") {
                        $url = $provider->api->url."UATInsCouponRequest";
                        $result = Permission::curl($url, "POST", json_encode($parameter), ["Content-Type: application/json", "Accept: application/json"], "yes", "App\Model\Report", $post->txnid);
                    }else{
                        $result = [
                            'error' => false,
                            'response' => ''
                        ];
                    }
        
                    if($result['error'] || $result['response'] == ''){
                        $update['status'] = "pending";
                        $update['payid'] = "pending";
                        $update['refno'] = "pending";
                        $update['description'] = "pan token request pending";
                    }else{
                        $doc = json_decode($result['response']);
                        if(isset($doc[0]->StatusCode)){
                            if($doc[0]->StatusCode == "000"){
                                $update['status'] = "success";
                                $update['payid'] = $doc[0]->RequestId;
                                $update['description'] = "Pancard Token Request Accepted";
                            }else{
                                $update['status'] = "failed";
                                if($doc[0]->StatusCode == "008"){
                                    $update['description'] = "Service down for sometime.";
                                }else{
                                    $update['description'] = (isset($doc[0]->message)) ? $doc[0]->message : "failed";
                                }
                            }
                        }else{
                            $update['status'] = "pending";
                            $update['payid'] = "pending";
                            $update['refno'] = "pending";
                            $update['description'] = "pan token request pending";
                        }
                    }
        
                    if($update['status'] == "success" || $update['status'] == "pending"){
                        Report::where('id', $report->id)->update($update);
                        $post['reportid'] = $report->id;
                        Permission::commission($report);
                        $output['statuscode'] = "TXN";
                        $output['message'] = "Uti Pancard Token Request Submitted";
                    }else{
                        User::where('id', $user->id)->increment('mainwallet', $post->amount - $post->profit);
                        Report::where('id', $report->id)->update($update);
                        $output['statuscode'] = "TXF";
                        $output['message'] = $update['description'];  
                    }
                    return response()->json($update, 200);
                }else{
                    return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
                }
                break;
        }
    }

    public function status(Request $post)
    {
        if(!in_array($post->type, ['utiid', 'token'])){
            return response()->json(['statuscode' => "ERR", "message" => "Type parameter request in invalid"]);
        }

        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'txnid'    => 'required|numeric',
            'type'     => 'required'
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->where('apptoken',$post->apptoken)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        if (!Permission::can('utipancard_status', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        switch ($post->type) {
            case 'utiid':
                $report = Utiid::where('id', $post->txnid)->first();
                $url = $report->api->url.'/status?token='.$report->api->username.'&vle_id='.$report->vleid;
                break;
            
            case 'token':
                $report = Report::where('id', $post->txnid)->first();
                if(!$report || !in_array($report->status , ['pending', 'success'])){
                    return response()->json(['status' => "Recharge Status Not Allowed"], 400);
                }
        
                $url = $report->api->url.'UATUTICouponRequestStatus';
                $method = "POST";
                $parameter['securityKey'] = $report->api->password;
                $parameter['createdby']   = $report->api->username;
                $parameter['requestid']   = $report->payid;
                break;
        }

        $method = "GET";
        $parameter = "";
        $header = [];

        if (env('APP_ENV') != "local") {
                $result = Permission::curl($url, $method, $parameter, $header);
            }else{
                $result = [
                    'error' => false,
                    'response' => json_encode([
                        'statuscode' => 'TXN',
                        'message'=> 'local'
                    ]) 
                ];
            }
        if($result['response'] != ''){
            switch ($post->type) {
                case 'utiid':
                    $doc = json_decode($result['response']);
                    if(isset($doc->statuscode) && $doc->statuscode == "TXN"){
                        $update['status'] = "success";
                        $update['remark'] = $doc->message;

                        $output['statuscode'] = "TXN";
                        $output['txn_status'] = "success";
                        $output['message'] = $doc->message;
                    }elseif(isset($doc->statuscode) && $doc->statuscode == "TXF"){
                        $update['status'] = "failed";
                        $update['remark'] = $doc->message;

                        $output['statuscode'] = "TXR";
                        $output['txn_status'] = "failed";
                        $output['message'] = $doc->message;

                    }else{
                        $update['status'] = "Unknown";

                        $output['statuscode'] = "TNF";
                        $output['txn_status'] = "unknown";
                        $output['message'] = $doc->message;
                    }
                    $product = "utiid";
                break;

                case 'token':
                    $doc = json_decode($result['response']);
                    if(isset($doc[0]->StatusCode) && $doc[0]->StatusCode == "000"){
                        $update['status'] = "success";

                        $output['statuscode'] = "TXN";
                        $output['txn_status'] = "success";
                        $output['message']    = $doc->message;

                    }else{
                        $update['status'] = "Unknown";

                        $output['statuscode'] = "TNF";
                        $output['txn_status'] = "unknown";
                        $output['message'] = $doc->message;
                    }
                    $product = "utipancard";
                    break;
            }

            if ($update['status'] != "Unknown") {
                $reportupdate = Report::where('id', $report->id)->update($update);
                if ($reportupdate && $update['status'] == "reversed" && $product == "utipancard") {
                    Permission::transactionRefund($post->id);
                }

                if($report->user->role->slug == "apiuser" && $report->status == "pending" && $post->status != "pending"){
                    Permission::callback($report, $product);
                }
            }
            return response()->json($output);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Something went wrong, contact your service provider"]);
        }
    }
}
