<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Api\Api;
use App\Models\Api\Apitoken;
use App\Models\Provider;
use App\Models\Mahabank;
use App\Models\Api\Report;
use App\Models\Commission;
use App\Models\Aepsreport;
use App\Models\Aepsfundrequest;
use App\Models\Beneficiarybank;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Api\Openacquiring;
use App\Models\Api\Refund;
use Illuminate\Support\Facades\DB;
use App\Helpers\Permission;

class OpenacquiringController extends Controller
{ 
    
    /**
    * This method is used for Openaquiring creadit card payment initiat
    * @param Request $post
    * @return json
    */
    public function initiatepayment(Request $post)
    {

       $rules = [
            'token'  => 'required',
            "credit_card_number" => "required|numeric|min:16",
            "credit_card_name"=> "required",
            "credit_card_expiry_month" => "required|numeric",
            "credit_card_expiry_year" => "required|numeric",
            "credit_card_cvv" => "required|numeric",
            "email" => 'required|email',
            "amount" => 'required',
            "currency" => 'required',
            "address" => 'required',
            'country_code' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required'
        ];
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json([
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ]);
        }

        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);

        if(!$token){
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        
        $post['user_id'] = $token->user_id;
        $openacquiring = Openacquiring::where('user_id', $token->user_id)->first();
        $post['orderId'] = ''.date('YmdHis').rand(11111111,99999999);
        $url = env('OPENACQUIRING_API_URL', 'https://api.openacquiring.com/').'v1/merchants/'.$openacquiring->merchant_id.'/payment';
        $parameter = [
        'intent' => 'auth',
        'payer' => ['payment_type' => 'cc', 'funding_instrument' => ['credit_card' => ['number' => $post->credit_card_number, 'expire_month' => $post->credit_card_expiry_month, 'expire_year' => $post->credit_card_expiry_year, 'cvv2' => $post->credit_card_cvv, 'name' => $post->credit_card_name]],  
        "payer_info" =>["email"=> $post->email,
       // "name"=> "Tom Hanks",
        "billing_address"=> [
            "line1"=> $post->address,
            "line2"=> $post->address2,
            "city"=> $post->city,
            "country_code"=> $post->country_code,
            "postal_code"=> $post->zip_code,
            "state"=> $post->state,
            "phone"=> [
                "country_code"=> $post->phone_code,
                "number"=> $post->phone_number
            ]
        ]
    ],
    "browser_info"=> [
        "ip"=> $post->ip(),
    ]],
    "payee"=> [
    "email"=> $openacquiring->email,
    "merchant_id"=> $openacquiring->merchant_id
    ],
    "transaction"=> [
    "type"=> "1",
    "amount"=> [
        "currency"=> $post->currency,
        "total"=> $post->amount
    ],
    "invoice_number"=> $post['orderId'],
    "return_url" => env('OPENACQUIRING_RETURN_URL'),]]; 
    $header = ['Content-Type:application/json','Authorization:Basic '.base64_encode($openacquiring->client_id.':'.$openacquiring->client_secret)];
    // return response()->json(["client_id"=>base64_encode($openacquiring->client_id.':'.$openacquiring->client_secret)]);
    // return response()->json(["Data"=>$header]);
    $result = Permission::curl($url, "POST", json_encode($parameter), $header, "no");
    if($result['code']==200){
        $response = json_decode($result['response'], true);
        $redirect_url = '';
        if(!empty($response['result']['redirect_url'])){
            $redirect_url = $response['result']['redirect_url'];
            $redirect_url_arr = explode('/', trim($redirect_url, '/'));
            $insert['apitxnid'] = end($redirect_url_arr);
        }
        $app  = Api::where('code', 'open_cquiring')->first();
        $insert['user_id'] = $token->user_id;
        $insert['amount'] = $post->amount;
        $insert['mobile'] = $post->phone_number;
        $insert['refno'] = $response['reference_id'];
        $insert['provider_id'] = 0;
        $insert['api_id'] = $app->id;
        $insert['number'] = $response['id'];
        $insert['trans_type'] = 'credit';
        $insert['mytxnid'] = $post['orderId'];
        $insert['status'] = !empty($response['state']) ?$response['state'] : "pending";
        $insert['product'] = 'openacquiring';
        $insert['aepstype'] ='card';
        $insertedReport =  Report::create($insert);
        $reportId = $insertedReport->id;
        
        return response()->json(['statuscode'=>"TXN","message"=>"Request Initiated successfully","data"=>['reference_id' => $response['id'], 'redirect_url' => $redirect_url]]);
    }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again","result"=>$result]); 
    }
}
    /**
    * This method is used for Openaquiring Refund payment
    * @param Request $post
    * @return json
    */
    public function refund(Request $post){
         $rules = [
            'token'  => 'required',
            "reference_number"=> "required",
            "amount" => 'required',
            "invoice_number" => 'required'
        ];
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json([
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ]);
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $report_result = Report::where('refno', $post->reference_number)->select(DB::raw('reports.amount'), DB::raw('reports.id'),
            DB::raw("SUM(open_acquirer_capture_refunds.amount) as total_amount"))
        ->leftJoin('open_acquirer_capture_refunds', function($join){
        $join->on('reports.id', '=', 'open_acquirer_capture_refunds.report_id')
        ->where('open_acquirer_capture_refunds.type', '=', '2');})
        ->groupBy('reports.amount', 'reports.id')
        ->first();
        if($report_result['amount']<$post->amount || (!empty($report_result['total_amount']) && $report_result['amount']< ($report_result['total_amount']+$post->amount))){
            return response()->json(['statuscode'=>'ERR', 'message'=> "Refund amount is greater than initial amount"]);   
        }
        if(!empty($report_result['total_amount']) && $report_result['total_amount']>=$report_result['amount']){
            return response()->json(['statuscode'=>'ERR', 'message'=> "Refund amount is already completed"]);   
        }
        $post['user_id'] = $token->user_id;
        $openacquiring = Openacquiring::where('user_id', $token->user_id)->first();
        $url = env('OPENACQUIRING_API_URL', 'https://api.openacquiring.com/').'v1/merchants/'.$openacquiring->merchant_id.'/payment/'.$post->reference_number.'/refund';
        $header = ['Content-Type:application/json',
            'Authorization:Basic '.base64_encode($openacquiring->client_id.':'.$openacquiring->client_secret)
        ];
        $parameter = [
        'amount' => $post->amount,
        'invoice_number' => $post->invoice_number,
        'custom' => ['field1' => (!empty($post->comment) ? $post->comment: 'Refund for amount:'. $post->amount)]
        ];
        $result = Permission::curl($url, "POST", json_encode($parameter), $header, "no"); 
    if(in_array($result['code'], [200])){
        $response = json_decode($result['response'], true);
        $total_amount =  $post->amount+(!empty($report_result['total_amount'])?$report_result['total_amount']:0);
        $status = ($total_amount == $report_result['total_amount']) ? 'refund': 'partial refund';
        Report::where('id', $report_result['id'])->update(['status' => $status]);
        Refund::create(['report_id' => $report_result['id'], 'reference_id' => $response['id'], 'amount' => $post->amount]);
        return response()->json(['statuscode'=>"TXN","message"=>"Refund successfully","data"=>['reference_id' => $response['reference_id']]]);
    }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again","result"=>$result]); 
    }   
    }
    
    /**
    * This method is used for Openaquiring Retrive payment request
    * @param Request $post
    * @return json
    */
    public function retrieve(Request $post){
         $rules = [
            'token'  => 'required',
            "reference_number" => "required"
        ];
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json([
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ]);
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        
        $post['user_id'] = $token->user_id;
        $openacquiring = Openacquiring::where('user_id', $token->user_id)->first();
        $url = env('OPENACQUIRING_API_URL', 'https://api.openacquiring.com/').'v1/merchants/'.$openacquiring->merchant_id.'/payment/'.$post->reference_number;
        $header = ['Content-Type:application/json',
            'Authorization:Basic '.base64_encode($openacquiring->client_id.':'.$openacquiring->client_secret)
        ];
        $parameter = [];
        $result = Permission::curl($url, "GET", json_encode($parameter), $header, "no"); 
    if($result['code']==200){
        $response = json_decode($result['response'], true);
        return response()->json(['statuscode'=>"TXN","message"=>"request Initiated successfully","data"=>$response]);
    }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again","result"=>$result]); 
    }   
    }
    
    /**
    * This method is used for Openaquiring capture payment request
    * @param Request $post
    * @return json
    */
    public function capture(Request $post){
         $rules = [
            'token'  => 'required',
            "reference_number"=> "required",
            "amount" => 'required',
            "invoice_number" => 'required'
        ];
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json([
                'statuscode'  => 'ERR',
                'message' => $error,
                "extmsg"=>"fwd"
            ]);
        }
        $token = Apitoken::where('ip',$post->ip())->where('token', $post->token)->first(['user_id']);
        if(!$token){
            return response()->json(['statuscode'=>'ERR', 'message'=> "IP or Token mismatch, your current system IP is ".$post->ip()]);   
        }
        $report_result = Report::where('refno', $post->reference_number)->select(DB::raw('reports.amount'), DB::raw('reports.id'),
            DB::raw("SUM(open_acquirer_capture_refunds.amount) as total_amount"))
        ->leftJoin('open_acquirer_capture_refunds', function($join){
        $join->on('reports.id', '=', 'open_acquirer_capture_refunds.report_id')
        ->where('open_acquirer_capture_refunds.type', '=', 1);})
        ->groupBy('reports.amount', 'reports.id') 
        ->first();
        if($report_result['amount']<$post->amount || (!empty($report_result['total_amount']) && $report_result['amount']< ($report_result['total_amount']+$post->amount))){
            return response()->json(['statuscode'=>'ERR', 'message'=> "Refund amount is greater than initial amount"]);   
        }
        if(!empty($report_result['total_amount']) && $report_result['total_amount']>=$report_result['amount']){
            return response()->json(['statuscode'=>'ERR', 'message'=> "Refund amount is already completed"]);   
        }
        $post['user_id'] = $token->user_id;
        $openacquiring = Openacquiring::where('user_id', $token->user_id)->first();

        $url = env('OPENACQUIRING_API_URL', 'https://api.openacquiring.com/').'v1/merchants/'.$openacquiring->merchant_id.'/payment/'.$post->reference_number.'/capture';
        $header = ['Content-Type:application/json',
            'Authorization:Basic '.base64_encode($openacquiring->client_id.':'.$openacquiring->client_secret)
        ];
        $parameter = [
        'amount' => $post->amount,
        'invoice_number' => $post->invoice_number,
        'custom' => ['field1' => 'This is test']
        ];
        $result = Permission::curl($url, "POST", json_encode($parameter), $header, "no"); 
    if($result['code']==200){
        $response = json_decode($result['response'], true);
        $total_amount =  $post->amount+(!empty($report_result['total_amount'])?$report_result['total_amount']:0);
        $status = ($total_amount == $report_result['total_amount']) ? 'complete': 'partial capture';
        Report::where('id', $report_result['id'])->update(['status' => $status]);
        Refund::create(['report_id' => $report_result['id'], 'reference_id' => $response['id'], 'amount' => $post->amount]);
        return response()->json(['statuscode'=>"TXN","message"=>"Payment Captured successfully","data"=>['reference_id' => $response['reference_id']]]);
    }else{
            return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again","result"=>$result]); 
    }   
}
    
}
