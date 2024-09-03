<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Permission;
use App\Models\User;
use App\Models\Fundreport;
use App\Models\Aepsfundrequest;
use App\Models\Aepsreport;
use App\Models\Microatmfundrequest;
use App\Models\Microatmreport;
use App\Models\Report;
use App\Models\Fundbank;
use App\Models\Iserveuagent;
use App\Models\Mahaagent;
use App\Models\Mahastate;
use App\Models\Mahabank;
use App\Models\Paymode;
use App\Models\Api;
use App\Models\Provider;
use App\Models\Help_box;
use App\Models\PortalSetting;
use Illuminate\Validation\Rule;
use App\Classes\PaytmChecksum;
use App\Model\Initiateqr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class FundController extends Controller
{
    public $fundapi, $admin;

    public function __construct()
    {
        $api = Api::where('code', 'demotest')->first();
        $this->fundapi = Api::where('code', 'fund')->first();
        $this->iserveu  = Api::where('code', 'loadwallet')->first();
        $this->paytmsettlement  = Api::where('code', 'paytmsettlement')->first();
        $this->psettlement = Api::where('code', 'psettlement')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();

    }

    public function index($type, $action="none")
    {

        $data = [];
        switch ($type) {
            case 'tr':
                $help_box = Help_box::where("type","fund")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                $permission = ['fund_transfer', 'fund_return'];
                break;
            
            case 'request':

                $data['agent'] = Iserveuagent::where('user_id', \Auth::id())->first();
                $data['mahastate'] = Mahastate::get();
                if(!$data['agent']){
                    $data['mahastate'] = Mahastate::get();
                }
                $permission = 'fund_request';
                break;
            
            case 'requestview':
                $permission = 'setup_bank';
                break;
            
            case 'statement':
            case 'requestviewall':
                $permission = 'fund_report';
                break;

            case 'payout':
            case 'aeps':
                $data['neftcharge']        = $this->neftcharge();
                $data['impschargeupto25']  = $this->impschargeupto25();
                $data['impschargeabove25'] = $this->impschargeabove25();
                $data['banks'] = Mahabank::get(); 
                
                $permission = 'fund_request';
                break;
            
            case 'aepsrequest':
            case 'payoutrequest':
                $permission = 'aeps_fund_view';
                break;

            case 'aepsfund':
            case 'aepsrequestall':
                $permission = 'aeps_fund_report';
                break;

            case 'microatm':
                $permission = 'microatm_fund_request';
                break;
            
            case 'microatmrequest':
                $permission = 'microatm_fund_view';
                break;

            case 'microatmfund':
            case 'microatmrequestall':
                $permission = 'microatm_fund_report';
                break;

            default:
                abort(404);
                break;
        }

        if (!Permission::can($permission)) {
            abort(403);
        }

        if (isset($this->fundapi->status) && $this->fundapi->status == "0") {
            abort(503);
        }

        switch ($type) {
            case 'request':
                $data['banks'] = Fundbank::where('user_id', \Auth::user()->parent_id)->where('status', '1')->get();

                if(!Permission::can('setup_bank', \Auth::user()->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', \Auth::user()->company_id)->first(['id']);

                    if($admin && Permission::can('setup_bank', $admin->id)){
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }
                }
                $data['paymodes'] = Paymode::where('status', '1')->get();
                break;
        }

        return view('fund/'.$type)->with($data); 
    }

    public function transaction(Request $post)
    {
        
        if (isset($this->fundapi->status) && $this->fundapi->status == "0") {
            return response()->json(['status' => "This function is down."],400);
        }
       
        $provide = Provider::where('recharge1', 'fund')->first();
        $post['provider_id'] = !empty($provide->id) ? $provide->id:0;
        switch ($post->type) {
            case 'iservuseronboard':
                $post['user_id'] = \Auth::id();
                $user = User::where('id', $post->user_id)->first();
                $rules = array(
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'bc_pan' => 'required',
                    'phone1' => 'required',
                    'shopname' => 'required'
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
                
                $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first();
                if($isesxistagent){
                    return response()->json(['statuscode'=> "ERR",'message'=> "User already onboarded"]);
                }
               
                $header = array(
                    "content-type: application/json",
                    "client_id:".(!empty($this->iserveu->username)? $this->iserveu->username:''),
                    "client_secret:".(!empty($this->iserveu->password) ? $this->iserveu->password :'')
                );
                
            
                
                $parameter = [ 
                                "productType"=> "upi",
                                "bcagentname"=> $post->firstName,
                                "lastname"=> $post->lastName,
                                "companyname"=> $user->company->companyname,
                                "mobilenumber"=> $user->mobile,
                                "shopname"=> $user->shopname,
                                "vpa"=> trim(strtolower($post->firstName)).$user->id.rand(1111,9999),
                                "bcagentid"=> trim(strtolower($post->firstName)).'00'.$user->id,
                                "address"=> $post->bc_address,
                                "area"=> $post->area,
                                "pincode"=> 232101,
                                "shopaddress"=> $post->shopaddress,
                                "shopstate"=> $post->shopstate,
                                "shopcity"=> $post->shopcity,
                                "shopdistrict"=> $post->shopdistrict,
                                "shoparea"=> $post->shoparea,
                                "shoppincode"=> '232101',
                                "pancard"=> $user->pancard
                            ];
                            
                /*$Virtuaa = trim(strtolower($post->firstName)).rand(11111,99999);
                $payload = '{"productType":"upi","bcagentname":"Manish","lastname":"dubey","companyname":"Manish Mobile Ascessories","mobilenumber":"7011037322","shopname":"Manish Mobile Ascessories","vpa":"'.$Virtuaa.'","bcagentid":"manish00223","address":"Lucknow up","area":"Krishna nagar","pincode": 226012,"shopaddress":"krishanagar","shopstate":"Uttar Pradesh","shopcity":"Lucknow","shopdistrict":"Lucknow","shoparea":"Lucknow","shoppincode":"226012","pancard":"FXEPD9423E"}';
                //$url = $this->iserveu->url.'api/upi/composer/selfonboarding';*/
                
                $url = 'https://apiprod.iserveu.tech/productionV2/apiAgentOnboarding/externalonboard';
        
                $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
                //dd([$url, 'POST', json_encode($parameter), $header,$result]);
                
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
                                "merchantMobileNumber"=> $user->mobile,
                                "shopname"=> $user->shopname,
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
                                "pan"=> $user->pancard,
                                "status"=> "success"
                            ];
                     
                  
                     Iserveuagent::insert($array);
                     return response()->json(['statuscode'=> "TXN",'message'=>"User successfully Onboarded.",'status'=>"User successfully Onboarded."]);
                 }else{
                      return response()->json(['statuscode'=> "ERR",'message'=>isset($response->statusDesc) ? $response->statusDesc : "Something went wrong",'status'=>isset($response->error) ? $response->error : "Something went wrong"]);
                 }
                
            
            break;
            
            case 'loadonlinewallet':
                $post['user_id'] = \Auth::id();
                $user = User::where('id', $post->user_id)->first();
                $isesxistagent = Iserveuagent::where('user_id',$post->user_id)->first();

                $header = array(
                    "content-type: application/json",
                    "client_id:".$this->iserveu->username,
                    "client_secret:".$this->iserveu->password
                );

                do {
                    $post['txnid'] = $this->transcode().rand(1111111111111111, 9999999999999999);
                } while (Initiateqr::where("refid", "=", $post->txnid)->first() instanceof Initiateqr);
                
                $parameter['virtualAddress'] =  rand(1111111111, 9999999999).'@paytm';//'9826098007@paytm';
                $parameter['amount'] = $post->amount;
                $parameter['merchantType'] = "AGGREGATE";
                $parameter['paymentMode']  = "INTENT";
                $parameter['channelId']    = "WEBUSER";
                $parameter['clientRefId']  = $post->txnid;
                $parameter['isWalletTopUp'] = false;
                $parameter['remarks'] = $user->company->name." Payment";
                $parameter['requestingUserName'] = $isesxistagent->requestingUserName;
                $url = $this->iserveu->url.'api/upi/initiate-dynamic-transaction';
        
                $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes');
                //dd([$url, 'POST', json_encode($parameter),$result]);
       
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
                     $array['intentData'] = $response->intentData;
                     return response()->json(['statuscode'=> "TXN",'data'=>$array]);
                 }else{
                     return response()->json(['statuscode'=> "ERR",'message'=>isset($response->statusDesc) ? $response->statusDesc : "Something went wrong"]);
                 }

                break;
            
            case 'transfer':
            case 'return':
                if($post->type == "transfer" && !Permission::can('fund_transfer')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                if($post->type == "return" && !Permission::can('fund_return')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                if($post->type == "transfer"){
                    if(\Auth::user()->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient wallet balance."],400);
                    }
                }else{
                    $user = User::where('id', $post->user_id)->first();
                    if($user->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient balance in user wallet."],400);
                    }
                }
                $post['txnid'] = 0;
                $post['option1'] = 0;
                $post['option2'] = 0;
                $post['option3'] = 0;
                $post['refno'] = date('ymdhis');
                return $this->paymentAction($post);

                break;

            case 'requestview':
                if(!Permission::can('setup_bank')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $fundreport = Fundreport::where('id', $post->id)->first();
                
                if($fundreport->status != "pending"){
                    return response()->json(['status' => "Request already approved"],400);
                }

                $post['amount'] = $fundreport->amount;
                $post['type'] = "request";
                $post['user_id'] = $fundreport->user_id;
                if ($post->status == "approved") {
                    if(\Auth::user()->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient wallet balance."],200);
                    }
                    $action = Fundreport::updateOrCreate(['id'=> $post->id], [
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);

                    $post['txnid'] = $fundreport->id;
                    $post['option1'] = $fundreport->fundbank_id; 
                    $post['option2'] = $fundreport->paymode;
                    $post['option3'] = $fundreport->paydate;
                    $post['refno'] = $fundreport->ref_no;
                    return $this->paymentAction($post);
                }else{
                    $action = Fundreport::updateOrCreate(['id'=> $post->id], [
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);

                    if($action){
                        return response()->json(['status' => "success"],200);
                    }else{
                        return response()->json(['status' => "Something went wrong, please try again."],200);
                    }
                }
                
                return $this->paymentAction($post);
                break;

            case 'request':
                if(!Permission::can('fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $rules = array(
                    'fundbank_id'    => 'required|numeric',
                    'paymode'    => 'required',
                    'amount'    => 'required|numeric|min:100',
                    'ref_no'    => 'required|unique:fundreports,ref_no',
                    'paydate'    => 'required'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $post['user_id'] = \Auth::id();
                $post['credited_by'] = \Auth::user()->parent_id;
                if(!Permission::can('setup_bank', \Auth::user()->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', \Auth::user()->company_id)->first(['id']);

                    if($admin && \Permission::can('setup_bank', $admin->id)){
                        $post['credited_by'] = $admin->id;
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $post['credited_by'] = $admin->id;
                    }
                }
                
                $post['status'] = "pending";
                if($post->hasFile('payslips')){
                    $filename ='payslip'.\Auth::id().date('ymdhis').".".$post->file('payslips')->guessExtension();
                    $post->file('payslips')->move(public_path('deposit_slip/'), $filename);
                    $post['payslip'] = $filename;
                }
                $action = Fundreport::create($post->all());
                if($action){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "Something went wrong, please try again."],200);
                }
                break;

            case 'bank':
                $post['user_id'] = \Auth::id();
                $user = User::where('id', $post->user_id)->first();
                if ($this->pinCheck($post) == "fail") {
                    return redirect('/fund/payout')->with('error','Transaction Pin is incorrect');
                }
                
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25   = $this->impschargeupto25();
                $impschargeabove25  = $this->impschargeabove25();
                $neftcharge = $this->neftcharge(); 
                $bankpayoutapi      = $this->bankpayoutapi();

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $post['account'] = $post->inpbeneaccount;
                $post['bank']    = $post->inpbenebank;
                $post['ifsc']    = $post->inpbeneifsc;
                $post['benename']    = $post->inpbenename;
             
                $post['user_id'] = \Auth::id();

                $rules = array(
                    'amount'    => 'required|numeric|min:100',
                    'account'   => 'sometimes|required',
                    'bank'      => 'sometimes|required',
                    'ifsc'      => 'sometimes|required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    // return response()->json($validator->errors(), 422);
                    return response()->json(['errors'=>$validator->errors()], 422);
                }


           
                if($post->amount > 0 && $post->amount <= 1000){
                    $provider = Provider::where('recharge1', 'payout1k')->first();
                }elseif($post->amount>1001 && $post->amount<=25000){
                    $provider = Provider::where('recharge1', 'payout25k')->first();
                }else{
                    $provider = Provider::where('recharge1', 'payout2l')->first();
                }

                $post['charge'] = Permission::getCommission($post->amount, $user->scheme_id, (!empty($provider->id) ? $provider->id : 0), $user->role->slug);



                if($user->mainwallet < $post->amount + $post->charge){
                    return redirect('/fund/payout')->with('error','Low aeps balance to make this request.');
                }

                if($banksettlementtype == "auto"){

                   /* $previousrecharge = Aepsfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } */
                    
                    $this->api = Api::where('code', 'ipayout')->first();

                    
                    if(!$this->api){
                        return redirect('/fund/payout')->with('error','Api down for some time');
                    }


                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Report::where("txnid", "=", $post->payoutid)->first() instanceof Report);
      
                  
                    
                    
                    
                    $aepsreports['api_id'] = $this->api->id;
                    $aepsreports['number'] = $post->account;
                
                    $aepsreports['provider_id']  = !empty($provider->id)?$provider->id:0;
                    $aepsreports['mobile']       = $user->mobile;
                    $aepsreports['refno']        = "success";
                    $aepsreports['aadhar']       = $post->account;
                    $aepsreports['amount']       = $post->amount;
                    $aepsreports['charge']       = $post->charge;
                    $aepsreports['option1']      = $post->benename;
                    $aepsreports['option3']      = $post->bank;
                    $aepsreports['option4']      = $post->ifsc;
                    $aepsreports['mode']         = 'IMPS';
                    $aepsreports['txnid']        = $post->payoutid;
                    $aepsreports['user_id']      = $user->id;
                    $aepsreports['credited_by']  = $this->admin->id;
                    $aepsreports['balance']      = $user->mainwallet;
                    $aepsreports['trans_type']   = "debit";
                    $aepsreports['transtype']    = 'fund';
                    $aepsreports['status']       = 'pending';
                    $aepsreports['product']      = 'payout';
                    $aepsreports['remark']       = "Bank Settlement";
                    //dd($aepsreports);
                    User::find($aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Report::create($aepsreports);

                    if($myaepsreport){
                        return redirect('/fund/payout')->with('success','Success');
                    }else{
                        return redirect('/fund/payout')->with('error','Something went wrong, try again');
                    }

                 
                }else{
                    $post['pay_type'] = "manual";
                    $request = Aepsfundrequest::create($post->all());
                }

                if($request){
                    return response()->json(['status'=>"success", 'message' => "Fund request successfully submitted"], 200);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."], 400);
                }
                break;


             case 'settleamount':
                
               $rules = array(
                    'amount'    => 'required|numeric|min:10',
                    'beneid'   => 'required',
                    'user_id'   => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    // return response()->json($validator->errors(), 422);
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $getbeneficiary = \App\Models\Beneficiarybank::where(['id'=>$post->beneid,'user_id'=>$post->user_id])->first();


               $user = User::where('id',$post->user_id)->first();

                $post['account']     = $getbeneficiary->beneaccno;
                $post['bank']        = $getbeneficiary->bankname;
                $post['ifsc']        = $getbeneficiary->ifsc;
                $post['benename']    = $getbeneficiary->benename;
             
                $post['user_id'] = \Auth::id();

                $rules = array(
                    'amount'    => 'required|numeric|min:100',
                    'account'   => 'sometimes|required',
                    'bank'      => 'sometimes|required',
                    'ifsc'      => 'sometimes|required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    // return response()->json($validator->errors(), 422);
                    return response()->json(['errors'=>$validator->errors()], 422);
                }


           
                if($post->amount > 0 && $post->amount <= 1000){
                    $provider = Provider::where('recharge1', 'payout1k')->first();
                }elseif($post->amount>1001 && $post->amount<=25000){
                    $provider = Provider::where('recharge1', 'payout25k')->first();
                }else{
                    $provider = Provider::where('recharge1', 'payout2l')->first();
                }

                $post['charge'] = Permission::getCommission($post->amount, $user->scheme_id, (!empty($provider->id) ? $provider->id:0), $user->role->slug);



                if($user->mainwallet < $post->amount + $post->charge){
                    return response()->json(['status'=>  "Low aeps balance to make this request."], 400);
                }

                   /* $previousrecharge = Aepsfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } */
                    
                    $this->api = Api::where('code', 'ipayout')->first();

                    
                    if(!$this->api){
                        return response()->json(['status'=> "Api down for some time"]);
                    }


                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Report::where("txnid", "=", $post->payoutid)->first() instanceof Report);
       

                    $aepsreports['api_id'] = $this->api->id;
                    $aepsreports['number'] = $post->account;
                
                    $aepsreports['provider_id']  = $provider->id;
                    $aepsreports['mobile']       = $user->mobile;
                    $aepsreports['refno']        = "success";
                    $aepsreports['aadhar']       = $post->account;
                    $aepsreports['amount']       = $post->amount;
                    $aepsreports['charge']       = $post->charge;
                    $aepsreports['option1']      = $post->benename;
                    $aepsreports['option3']      = $post->bank;
                    $aepsreports['option4']      = $post->ifsc;
                    $aepsreports['mode']         = 'IMPS';
                    $aepsreports['txnid']        = $post->payoutid;
                    $aepsreports['user_id']      = $user->id;
                    $aepsreports['credited_by']  = $this->admin->id;
                    $aepsreports['balance']      = $user->mainwallet;
                    $aepsreports['trans_type']   = "debit";
                    $aepsreports['transtype']    = 'fund';
                    $aepsreports['status']       = 'pending';
                    $aepsreports['product']      = 'payout';
                    $aepsreports['remark']       = "Bank Settlement";
                    //dd($aepsreports);
                    User::find($aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Report::create($aepsreports);


                    $header = array(
                        "content-type: application/json",
                        "client_id:".$this->api->username,
                        "client_secret:".$this->api->password
                    );
                        
                    $parameter = [ 
                                    "beneName"      => $post->benename,
                                    "beneAccountNo" => $post->account,
                                    "beneifsc"     => $post->ifsc,
                                    "benePhoneNo"=> $user->mobile,
                                    "beneBankName"=> $post->bank,
                                    "clientReferenceNo"=> $post->payoutid,
                                    "amount"=> $post->amount,
                                    "fundTransferType"=>'IMPS',
                                    "pincode"=>751024,
                                    "custName"=>$user->name,
                                    "custMobNo"=>$user->mobile,    
                                    "latlong"=> "22.8031731,88.7874172",
                                    "paramA"=> "",
                                    "paramB"=> ""
                                ];
                                    
                                
                    $url = $this->api->url.'prod-apiusercashout/cashtransfer';
                        
                    $response1 = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes', 'Payout', $post->payoutid);
               

                    $response=json_decode($response1['response']);
                    //dd([$url, 'POST', json_encode($parameter),$response1]);

                    if($response1['response'] == ''){
                        return response()->json(['status'=> "success"]);
                    }


                    if(isset($response->status) && $response->status == "SUCCESS"){
                        Report::find($myaepsreport->id)->update(['refno' => $response->rrn,'payid' => $response->transactionId, "status" => 'success']); 
                        return response()->json(['status' => "success"], 200);
                    }elseif(isset($response->status) && in_array($response->status, ['FAILED','FAILURE','-2'])){
                        Report::find($myaepsreport->id)->update(['remark'=>$response->statusDesc,'refno' => isset($response->rrn) ? $response->rrn : "Failed", "status" => 'failed']); 
                        User::find($post->user_id)->increment('mainwallet',$post->amount+$post->charge);
                        return response()->json(['status' => isset($response->statusDesc) ? $response->statusDesc : "Task Failed, please try again"], 200);
                    }else{
                        Report::find($myaepsreport->id)->update(['remark'=>$response->statusDesc??'','refno' => isset($response->rrn) ? $response->rrn : "pending", 'payid' => 'pending', "status" => 'pending']); 
                        return response()->json(['status' => isset($response->statusDesc) ? $response->statusDesc : "Task Failed, please try again"], 200);
                    }
                    if($myaepsreport){
                        return response()->json(['status'=>"success"], 200);
                    }else{
                        return response()->json(['status'=>"Something went wrong, try again"], 400);
                    }


             break;   
            case 'addbeneficiary':
                $post['user_id'] = \Auth::id();
                $user = User::where('id', $post->user_id)->first();
                $attributes = request()->validate([
                        'benebank' => 'required',
                        'beneifsc' => "required",
                        'beneaccount' => "required|numeric|digits_between:6,20",
                        "benemobile" => 'required|numeric|digits:10',
                        "benename" => "required|regex:/^[\pL\s\-]+$/u"
                            ]);
                
                $isexist = \App\Models\Beneficiarybank::where(['user_id'=>$user->id,'beneaccno'=>$post->beneaccount])->first();
                if($isexist){
                    return redirect('/fund/payout')->with('error','This Account is alredy Exist');
                }
                                
                $mbank = Mahabank::where('id',$post->benebank)->first(['id','bankname']);
                $beni['bankname']   = $mbank->bankname;
                $beni['beneaccno']  = $post->beneaccount;
                $beni['benemobile'] = $post->benemobile;
                $beni['benename']   = $post->benename;
                $beni['user_id']    = $user->id;
                $beni['ifsc']       = $post->beneifsc;
                
                $addbank = \App\Models\Beneficiarybank::create($beni);
                if($addbank){
                    return redirect('/fund/payout')->with('success','Beneficiary Added Succesfully ');
                }else{
                    return redirect('/fund/payout')->with('error','Somthing Went Wrong');
                }

            break;    

            case 'deletebeneficiary':
                $post['user_id'] = \Auth::id();
                $user = User::where('id', $post->user_id)->first();
                $rules = array(
                            'id' => 'required'
                            );
                $validate = Permission::FormValidator($rules, $post);
                if($validate != "no"){
                    return response()->json($validate);
                }
                $isdelete = \App\Models\Beneficiarybank::where(['id'=>$post->id,'user_id'=>$user->id])->get()->each->delete();
                if($isdelete){
                    return response()->json([
                            'statuscode' => 'TXN', 
                            'status'     => 'Success', 
                            'message'    => "Beneficiary deleted"
                        ]);
                }else{
                    return response()->json([
                            'statuscode' => 'ERR',  
                            'message'    => "Something went wrong"
                        ]);
                }

            break;                 

            case 'wallet':
                //  if ($this->pinCheck($post) == "fail") {
                //     return response()->json(['status' => "Transaction Pin is incorrect"]);
                // }
                if(!Permission::can('aeps_fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $settlementtype = $this->settlementtype();

                if($settlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $request = Aepsfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($request > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                if(\Auth::user()->mainwallet < $post->amount){
                    return response()->json(['status'=>  "Low aeps balance to make this request"], 400);
                }

                $post['user_id'] = \Auth::id();

                if($settlementtype == "auto"){
                    $previousrecharge = Aepsfundrequest::where('type', $post->type)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge > 0){
                        return response()->json(['status'=> "Transaction Allowed After 5 Min."]);
                    }

                    $post['status'] = "approved";
                    $load = Aepsfundrequest::create($post->all());
                    $payee = User::where('id', \Auth::id())->first();
                    User::find($payee->id)->decrement('mainwallet', $post->amount);
                    $inserts = [
                        "mobile"  => $payee->mobile,
                        "amount"  => $post->amount,
                        "bank"    => $payee->bank,
                        'txnid'   => date('ymdhis'),
                        'refno'   => $post->refno,
                        "user_id" => $payee->id,
                        "credited_by" => $user->id,
                        "balance"     => $payee->mainwallet,
                        'type'        => "debit",
                        'transtype'   => 'fund',
                        'status'      => 'success',
                        'remark'      => "Move To Wallet Request",
                        'payid'       => "Wallet Transfer Request",
                        'aadhar'      => $payee->account
                    ];

                    Report::create($inserts);

                    if($post->type == "wallet"){
                        $provide = Provider::where('recharge1', 'aepsfund')->first();
                        User::find($payee->id)->increment('mainwallet', $post->amount);
                        $insert = [
                            'number' => $payee->account,
                            'mobile' => $payee->mobile,
                            'provider_id' => $provide->id,
                            'api_id' => $this->fundapi->id,
                            'amount' => $post->amount,
                            'charge' => '0.00',
                            'profit' => '0.00',
                            'gst' => '0.00',
                            'tds' => '0.00',
                            'txnid' => $load->id,
                            'payid' => $load->id,
                            'refno' => $post->refno,
                            'description' =>  "Aeps Fund Recieved",
                            'remark' => $post->remark,
                            'option1' => $payee->name,
                            'status' => 'success',
                            'user_id' => $payee->id,
                            'credit_by' => $payee->id,
                            'rtype' => 'main',
                            'via' => 'portal',
                            'balance' => $payee->mainwallet,
                            'trans_type' => 'credit',
                            'product' => "fund request"
                        ];

                        Report::create($insert);
                    }
                }else{
                    $load = Aepsfundrequest::create($post->all());
                }

                if($load){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "fail"],200);
                }
                break;

            case 'matmbank':
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25 = $this->impschargeupto25();
                $impschargeabove25 = $this->impschargeabove25();
                $neftcharge = $this->neftcharge(); 

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $post['user_id'] = \Auth::id();

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    $rules = array(
                        'amount'    => 'required|numeric|min:10',
                        'account'   => 'sometimes|required',
                        'bank'   => 'sometimes|required',
                        'ifsc'   => 'sometimes|required'
                    );
                }else{
                    $rules = array(
                        'amount'    => 'required|numeric|min:10'
                    );

                    $post['account'] = $user->account;
                    $post['bank']    = $user->bank;
                    $post['ifsc']    = $user->ifsc;
                }

                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    // return response()->json($validator->errors(), 422);
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    User::updateOrCreate(['id' => \Auth::user()->id], ['account' => $post->account, 'bank' => $post->bank, 'ifsc'=>$post->ifsc]);
                }

                $settlerequest = Microatmfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($settlerequest > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                $post['charge'] = 0;
                if($post->amount <= 25000){
                    $post['charge'] = $impschargeupto25;
                }

                if($post->amount > 25000){
                    $post['charge'] = $impschargeabove25;
                }
                
                if($user->mainwallet < $post->amount + $post->charge){
                    return response()->json(['status'=>  "Low aeps balance to make this request."], 400);
                }

                if($banksettlementtype == "auto"){

                    $previousrecharge = Microatmfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } 
                    
                    $api = Api::where('code', 'psettlement')->first();

                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Microatmfundrequest::where("payoutid", "=", $post->payoutid)->first() instanceof Microatmfundrequest);

                    $post['status']   = "pending";
                    $post['pay_type'] = "payout";
                    $post['payoutid'] = $post->payoutid;
                    $post['payoutref']= $post->payoutid;
                    $post['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Microatmfundrequest::create($post->all());
                    } catch (\Exception $e) {
                        return response()->json(['status'=> "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }

                    $aepsreports['api_id'] = $api->id;
                    $aepsreports['payid']  = $aepsrequest->id;
                    $aepsreports['mobile'] = $user->mobile;
                    $aepsreports['refno']  = "success";
                    $aepsreports['aadhar'] = $post->account;
                    $aepsreports['amount'] = $post->amount;
                    $aepsreports['charge'] = $post->charge;
                    $aepsreports['bank']   = $post->bank."(".$post->ifsc.")";
                    $aepsreports['txnid']  = $post->payoutid;
                    $aepsreports['user_id']= $user->id;
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->mainwallet;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::find($aepsreports['user_id'])->decrement('mainwallet',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Microatmreport::create($aepsreports);

                    
                    $url = $api->url; 

                    $parameter = [
                        "apitxnid" => $post->payoutid,
                        "amount"   => $post->amount, 
                        "account"  => $post->account,
                        "name"     => $user->name,
                        "bank"     => $post->bank,
                        "ifsc"     => $post->ifsc,
                        "token"    => $api->username,
                        'ip'       => $post->ip(),
                        'callback' => url('api/callback/update/payout')
                    ];
                    $header = array("Content-Type: application/json");

                    if(env('APP_ENV') != "local"){
                        $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'yes',$post->payoutid);
                    }else{
                        $result = [
                            'error'    => true,
                            'response' => ''
                        ];
                    }

                    if($result['response'] == ''){
                        return response()->json(['status'=> "success"]);
                    }

                    $response = json_decode($result['response']);

                    if(isset($response->status) && in_array($response->status, ['TXN', 'TUP'])){
                        Microatmfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "approved", "payoutref" => $response->rrn]);
                        return response()->json(['status'=>"success"], 200);
                    }else{
                        User::find($aepsreports['user_id'])->increment('mainwallet', $aepsreports['amount']+$aepsreports['charge']);
                        Microatmreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->rrn) ? $response->rrn : $response->message]);
                        Microatmfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['status'=>'ERR', 'message' => $response->message], 400);
                    }
                }else{
                    $post['pay_type'] = "manual";
                    $request = Microatmfundrequest::create($post->all());
                }

                if($request){
                    return response()->json(['status'=>"success", 'message' => "Fund request successfully submitted"], 200);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."], 400);
                }
                break;

            case 'matmwallet':
                if(!Permission::can('aeps_fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $settlementtype = $this->settlementtype();

                if($settlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $request = Microatmfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($request > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                if(\Auth::user()->mainwallet < $post->amount){
                    return response()->json(['status'=>  "Low aeps balance to make this request"], 400);
                }

                $post['user_id'] = \Auth::id();

                if($settlementtype == "auto"){
                    $previousrecharge = Microatmfundrequest::where('type', $post->type)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge > 0){
                        return response()->json(['status'=> "Transaction Allowed After 5 Min."]);
                    }

                    $post['status'] = "approved";
                    $load  = Microatmfundrequest::create($post->all());
                    $payee = User::where('id', \Auth::id())->first();
                    User::find($payee->id)->decrement('mainwallet', $post->amount);
                    $inserts = [
                        "mobile"  => $payee->mobile,
                        "amount"  => $post->amount,
                        "bank"    => $payee->bank,
                        'txnid'   => date('ymdhis'),
                        'refno'   => $post->refno,
                        "user_id" => $payee->id,
                        "credited_by" => $user->id,
                        "balance"     => $payee->mainwallet,
                        'type'        => "debit",
                        'transtype'   => 'fund',
                        'status'      => 'success',
                        'remark'      => "Move To Wallet Request",
                        'payid'       => "Wallet Transfer Request",
                        'aadhar'      => $payee->account
                    ];

                    Microatmreport::create($inserts);

                    if($post->type == "wallet"){
                        $provide = Provider::where('recharge1', 'aepsfund')->first();
                        User::find($payee->id)->increment('mainwallet', $post->amount);
                        $insert = [
                            'number' => $payee->account,
                            'mobile' => $payee->mobile,
                            'provider_id' => $provide->id,
                            'api_id' => $this->fundapi->id,
                            'amount' => $post->amount,
                            'charge' => '0.00',
                            'profit' => '0.00',
                            'gst' => '0.00',
                            'tds' => '0.00',
                            'txnid' => $load->id,
                            'payid' => $load->id,
                            'refno' => $post->refno,
                            'description' =>  "MicroAtm Fund Recieved",
                            'remark' => $post->remark,
                            'option1' => $payee->name,
                            'status' => 'success',
                            'user_id' => $payee->id,
                            'credit_by' => $payee->id,
                            'rtype' => 'main',
                            'via' => 'portal',
                            'balance' => $payee->mainwallet,
                            'trans_type' => 'credit',
                            'product' => "fund request"
                        ];

                        Report::create($insert);
                    }
                }else{
                    $load = Microatmfundrequest::create($post->all());
                }

                if($load){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "fail"],200);
                }
                break;
                
            case 'aepstransfer':
                if(Permission::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                if($user->mainwallet < $post->amount){
                    return response()->json(['status' => "Insufficient Aeps Wallet Balance"],400);
                }

                // Changed update query for observing.
                $action  = Aepsfundrequest::find($post->id)->update(['status'=>$post->status, 'remark'=> $post->remark]);
                $payee   = User::where('id', $request->user_id)->first();

                if($action){
                    if($post->status == "approved" && $request->status == "pending"){
                        User::find($payee->id)->decrement('mainwallet', $request->amount);

                        $inserts = [
                            "mobile"  => $payee->mobile,
                            "amount"  => $request->amount,
                            "bank"    => $payee->bank,
                            'txnid'   => $request->id,
                            'refno'   => $post->refno,
                            "user_id" => $payee->id,
                            "credited_by" => $user->id,
                            "balance"     => $payee->mainwallet,
                            'type'        => "debit",
                            'transtype'   => 'fund',
                            'status'      => 'success',
                            'remark'      => "Move To ".ucfirst($request->type)." Request",
                        ];

                        if($request->type == "wallet"){
                            $inserts['payid'] = "Wallet Transfer Request";
                            $inserts["aadhar"]= $payee->aadhar;
                        }else{
                            $inserts['payid'] = $payee->bank." ( ".$payee->ifsc." )";
                            $inserts['aadhar'] = $payee->account;
                        }

                        Report::create($inserts);

                        if($request->type == "wallet"){
                            $provide = Provider::where('recharge1', 'aepsfund')->first();
                            User::find($payee->id)->increment('mainwallet', $request->amount);
                            $insert = [
                                'number' => $payee->mobile,
                                'mobile' => $payee->mobile,
                                'provider_id' => $provide->id,
                                'api_id' => $this->fundapi->id,
                                'amount' => $request->amount,
                                'charge' => '0.00',
                                'profit' => '0.00',
                                'gst' => '0.00',
                                'tds' => '0.00',
                                'txnid' => $request->id,
                                'payid' => $request->id,
                                'refno' => $post->refno,
                                'description' =>  "Aeps Fund Recieved",
                                'remark' => $post->remark,
                                'option1' => $payee->name,
                                'status' => 'success',
                                'user_id' => $payee->id,
                                'credit_by' => $user->id,
                                'rtype' => 'main',
                                'via' => 'portal',
                                'balance' => $payee->mainwallet,
                                'trans_type' => 'credit',
                                'product' => "fund request"
                            ];

                            Report::create($insert);
                        }
                    }
                    return response()->json(['status'=> "success"], 200);
                }else{
                    return response()->json(['status'=> "fail"], 400);
                }

                break;

            case 'microatmtransfer':
                if(Permission::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                if($user->mainwallet < $post->amount){
                    return response()->json(['status' => "Insufficient Aeps Wallet Balance"],400);
                }

                $request = Microatmfundrequest::find($post->id);
                $action  = Microatmfundrequest::where('id', $post->id)->update(['status'=>$post->status, 'remark'=> $post->remark]);
                $payee   = User::where('id', $request->user_id)->first();

                if($action){
                    if($post->status == "approved" && $request->status == "pending"){
                        User::find($payee->id)->decrement('mainwallet', $request->amount);

                        $inserts = [
                            "mobile"  => $payee->mobile,
                            "amount"  => $request->amount,
                            "bank"    => $payee->bank,
                            'txnid'   => $request->id,
                            'refno'   => $post->refno,
                            "user_id" => $payee->id,
                            "credited_by" => $user->id,
                            "balance"     => $payee->mainwallet,
                            'type'        => "debit",
                            'transtype'   => 'fund',
                            'status'      => 'success',
                            'remark'      => "Move To ".ucfirst($request->type)." Request",
                        ];

                        if($request->type == "wallet"){
                            $inserts['payid'] = "Wallet Transfer Request";
                            $inserts["aadhar"]= $payee->aadhar;
                        }else{
                            $inserts['payid'] = $payee->bank." ( ".$payee->ifsc." )";
                            $inserts['aadhar'] = $payee->account;
                        }

                        Microatmreport::create($inserts);

                        if($request->type == "wallet"){
                            $provide = Provider::where('recharge1', 'aepsfund')->first();
                            User::find($payee->id)->increment('mainwallet', $request->amount);
                            $insert = [
                                'number' => $payee->mobile,
                                'mobile' => $payee->mobile,
                                'provider_id' => $provide->id,
                                'api_id' => $this->fundapi->id,
                                'amount' => $request->amount,
                                'charge' => '0.00',
                                'profit' => '0.00',
                                'gst' => '0.00',
                                'tds' => '0.00',
                                'txnid' => $request->id,
                                'payid' => $request->id,
                                'refno' => $post->refno,
                                'description' =>  "MicroAtm Fund Recieved",
                                'remark' => $post->remark,
                                'option1' => $payee->name,
                                'status' => 'success',
                                'user_id' => $payee->id,
                                'credit_by' => $user->id,
                                'rtype' => 'main',
                                'via' => 'portal',
                                'balance' => $payee->mainwallet,
                                'trans_type' => 'credit',
                                'product' => "fund request"
                            ];

                            Report::create($insert);
                        }
                    }
                    return response()->json(['status'=> "success"], 200);
                }else{
                    return response()->json(['status'=> "fail"], 400);
                }

                break;
            
            case 'loadwallet':
                if(Permission::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $action = User::find(\Auth::id())->increment('mainwallet', $post->amount);
                
                if($action){
                    $insert = [
                        'number' => \Auth::user()->mobile,
                        'mobile' => \Auth::user()->mobile,
                        'provider_id' => $post->provider_id,
                        'api_id' => !empty($this->fundapi->id)?$this->fundapi->id :0,
                        'amount' => $post->amount,
                        'charge' => '0.00',
                        'profit' => '0.00',
                        'gst' => '0.00',
                        'tds' => '0.00',
                        'apitxnid' => NULL,
                        'txnid' => date('ymdhis'),
                        'payid' => NULL,
                        'refno' => NULL,
                        'description' => NULL,
                        'remark' => $post->remark,
                        'option1' => NULL,
                        'option2' => NULL,
                        'option3' => NULL,
                        'option4' => NULL,
                        'status' => 'success',
                        'user_id' => \Auth::id(),
                        'credit_by' => \Auth::id(),
                        'rtype' => 'main',
                        'via' => 'portal',
                        'adminprofit' => '0.00',
                        'balance' => \Auth::user()->mainwallet,
                        'trans_type' => 'credit',
                        'product' => "fund ".$post->type
                    ];
                    $action = Report::create($insert);
                    if($action){
                        return response()->json(['status' => "success"], 200);
                    }else{
                        return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
                    }
                }else{
                    return response()->json(['status' => "Fund transfer failed, please try again."],400);
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    public function paymentAction($post)
    {
        $user = User::where('id', $post->user_id)->first();
        //dd($post->all());
        if($post->type == "transfer" || $post->type == "request"){
            $action = User::find($post->user_id)->increment('mainwallet', $post->amount);
        }else{
            $action = User::find($post->user_id)->decrement('mainwallet', $post->amount);
        }

        if($action){
            if($post->type == "transfer" || $post->type == "request"){
                $post['trans_type'] = "credit";
            }else{
                $post['trans_type'] = "debit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => $post->provider_id,
                'api_id' => !empty($this->fundapi->id)?$this->fundapi->id:0,
                'amount' => $post->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $post->txnid,
                'payid' => NULL,
                'refno' => $post->refno,
                'description' => NULL,
                'remark' => $post->remark,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => \Auth::id(),
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $post->trans_type,
                'product' => "fund ".$post->type
            ];
            $action = Report::create($insert);
            if($action){
                return $this->paymentActionCreditor($post);
            }else{
                return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
            }
        }else{
            return response()->json(['status' => "Fund transfer failed, please try again."],400);
        }
    }

    public function paymentActionCreditor($post)
    {
        $payee = $post->user_id;
        $user = User::where('id', \Auth::id())->first();
        if($post->type == "transfer" || $post->type == "request"){
            $action = User::find($user->id)->decrement('mainwallet', $post->amount);
        }else{
            $action = User::find($user->id)->increment('mainwallet', $post->amount);
        }

        if($action){
            if($post->type == "transfer" || $post->type == "request"){
                $post['trans_type'] = "debit";
            }else{
                $post['trans_type'] = "credit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => !empty($post->provider_id)?$post->provider_id:0,
                'api_id' => !empty($this->fundapi->id)? $this->fundapi->id:0,
                'amount' => $post->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $post->txnid,
                'payid' => NULL,
                'refno' => $post->refno,
                'description' => NULL,
                'remark' => $post->remark,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => $payee,
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $post->trans_type,
                'product' => "fund ".$post->type
            ];

            $action = Report::create($insert);
            if($action){
                return response()->json(['status' => "success"], 200);
            }else{
                return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
            }
        }else{
            return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
        }
    }
    
    public function genratepinwallet()
    {
        $header = array("Content-Type: application/json");
        $refid                 = 'TokenGen';
        $parameter['userName'] = $this->iserveu->username;
        $parameter['password'] = $this->iserveu->password;
        $url = $this->iserveu->url.'token/create';

        $result = Permission::curl($url, 'POST', json_encode($parameter), $header, 'no', 'Payintoken',$refid);

        $response = json_decode($result['response']);
        if(isset($response->success) && $response->success == true){
            if(isset($response->data->token)){
                $api = Api::where('code', 'loadwallet')->first();
                if($api){
                 $api->update(['optional1' => $response->data->token]);
                } 
            }
        }
    } 

    public function getGst($amount)
    {
        return (18/100)*$amount;
    }

    
}