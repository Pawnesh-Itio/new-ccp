<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mahaagent;
use App\Models\Mahastate;
use App\Models\Report;
use App\Models\Commission;
use App\Models\Aepsreport;
use App\Models\Provider;
use App\Models\Api;
use App\Helpers\Permission;

class CosmosUpiController extends Controller
{
    /**
	 * This method is used to Verify VPA
	 * @param Request $post
	 * @return json it returns json data
	 */
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
    /**
	 * This method is used to check transaction status
	 * @param Request $post
	 * @return json it returns json data
	 */
    public function txnStatus(Request $post)
    {
    //source+channel+terminalId+extTransactionId
      $req = [
              'source'           => 'SPAYFIN001',
              'channel'          => 'api',
              "terminalId"       => "SPAYFIN001-001",
              'extTransactionId' => "SPAYFIN20276739"
             ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string = $checksum.'c0019ec9cb994345a8a180d377ba6f4a';
            $req['checksum']= hash('sha256',$checksum_string);
        
            $key= '58d915f30f0e4421b90ca903c97859e6';
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantprod.timepayonline.com/evok/cm/v2/status',
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
            'cid: e76c6205bc4b46a0a4c3301c94587e9a',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         //dd(['https://merchantuat.timepayonline.com/evok/cm/v2/status',$req,$encrypted_string,$response,$decrypted_string]); 
$res = json_decode($decrypted_string);
    }
    
    public function upiReport(Request $post)
    {
    //source+channel+terminalId+extTransactionId
    
    //source+channel+terminalId+startDate+endDate+pageNo+pageSize
      $req = [
        'source'     => 'SPAYFIN001',
        'channel'    => 'api',
        "terminalId" =>"SPAYFIN001-001",
        'startDate'  => "2015-01-01 01:01:01",
        'endDate'    => "2023-06-17 01:01:01",
        'pageNo'     => "0",
        'pageSize'   => "10"
    ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.'46efbba174d340d791ba66fa8f6606c1';
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key= '2b273ac2cc334f05812b34a04310360a';
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantuat.timepayonline.com/evok/cm/v2/report',
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
            'cid: 40103a8179f140d78867648587655baa',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/cm/v2/report',$req,$encrypted_string,$response,$decrypted_string]); 

    }
    
    
    public function dQr(Request $post)
    {
    //source+channel+extTransactionId+sid+terminalId+amount+type
    //source+channel+extTransactionId+sid+terminalId+amount+type
      $req = [
        'source' => 'SPAYFIN001',
        'channel' => 'api',
        'extTransactionId' =>  "SPAYFIN".rand(11111111,99999999),
        'sid' => 'SPAYFIN001-001',
        "terminalId"=>"SPAYFIN001-001",
        "amount"=>"10.00",
        "type"=>"D",
        "remark"=>"Wallet Load",
        "requestTime"=>date("Y-m-d h:i:sa"),
        "minAmount"=>'5',
        "receipt"=>'http://dashboard.spay.live/',
        
    ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.'46efbba174d340d791ba66fa8f6606c1';
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key= '2b273ac2cc334f05812b34a04310360a';
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantuat.timepayonline.com/evok/qr/v1/dqr',
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
            'cid: 40103a8179f140d78867648587655baa',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/qr/v1/dqr',$req,$encrypted_string,$response,$decrypted_string]); 

    }
    
    
    
    public function callbackDecrypt(Request $post)
    {
        $cipher = "AES-256-CBC";
        $encrypted_data ='yOidBfOUWWUk52YJuM3Nqcx9C2MyIl4z+heSVJcqjM\/uFP0XcYLGutZlJgiYTenV35zhKwyvsxLlPQMDenbwPSPa758P6TmRbjI9xyI8kKnYtmk78o1P9HmzavNrFZf+1sWD+lWfChakzZeJWf0kPa47cNlJjALqCjziqrq5PBvDD8396DMzH4wDQUNrKDU\/zkZh5FSO8BjTba1RMWaUNDXQ3XGlVhU9xZaHm5ew4pnQTUP1T2vfJY6QiqFfgG8CxS1NCw6CrScEZkJloVbldlQr8Bn1u2VWubX1wAiuOCwMFfFxXmOXiSfG9HjO3bCDXUTFTZXQwixRecvbeva954VvVjmQtkHT5dmI71dHTnlVz5\/pRmFqotOUwDh5xPQ5iZf4GMqBf13QneBh3U8usnYrYA+0EIp1aHBKDmhxRe3sP+cuMCaSjz7\/nd\/OsT7q54Uk5zZZ0b\/d9aQf5TV3dUkBlDBYRBkXDbC9ZPcdQLmxgT+5paELsdccDj\/j4O68NbOqrFfNKn5BdWSHYL7k2BRcsdy3+bfU3Az8L66umFs=';
        $encryption_key ="b98b5b508d1a43a89ffc5d2b3df27d45";
        //Decrypt data
        $decrypted_data = openssl_decrypt($encrypted_data, $cipher, $encryption_key);
        //echo $decrypted_data;
         dd(substr($decrypted_data,16));
    }
    
    public function qrStatusRRN(Request $post,$id)
    {
         $data = \DB::table('reports')->where('refno',$id)->first();
        //source+channel+terminalId+extTransactionId
      $req = [
        'source' => 'SPAYFIN001',
        'channel' => 'api',
        "terminalId"=>"SPAYFIN001-001",
        'extTransactionId' =>  $id
        
        
    ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.'46efbba174d340d791ba66fa8f6606c1';
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key= '2b273ac2cc334f05812b34a04310360a';
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantprod.timepayonline.com/evok/qr/v1/qrStatusRRN',
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
            'cid: 40103a8179f140d78867648587655baa',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/qr/v1/qrStatusRRN',$req,$encrypted_string,$response,$decrypted_string]); 
        return response()->json(['status'=>true,'message'=>"Data fetched Successfully","data"=>$decrypted_string,"myReport"=>$data]);
    }
    public function qrStatus(Request $post, $id)
    {
        //source+channel+terminalId+extTransactionId
     /* $req = [
        'source' => 'SPAYFIN001',
        'channel' => 'api',
        "terminalId"=>"SPAYFIN001-001",
        'extTransactionId' =>  "VIRSHAN19797661"
        
        
    ];*/
    
    $data = \DB::table('reports')->where('apitxnid',$id)->first();
    
    $req = [
        'source' => 'SPAYFIN001',
        'channel' => 'api',
        "terminalId"=>"SPAYFIN001-001",
        'extTransactionId' => $data->txnid
    ];
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            $checksum_string=$checksum.'46efbba174d340d791ba66fa8f6606c1';
            $req['checksum']=hash('sha256',$checksum_string);
        
            $key= '2b273ac2cc334f05812b34a04310360a';
            $key=substr((hash('sha256',$key,true)),0,16);
        
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantprod.timepayonline.com/evok/qr/v1/qrStatus',
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
            'cid: 40103a8179f140d78867648587655baa',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/qr/v1/qrStatus',$req,$encrypted_string,$response,$decrypted_string]); 
         
         return response()->json(['status'=>true,'message'=>"Data fetched Successfully","data"=>$decrypted_string]);

    }
    public function qrReport(Request $post)
    {
        //source+channel+terminalId+startDate+endDate+pageNo+pageSize
      $req = [
        'source' => 'SPAYFIN001',
        'channel' => 'api',
        "terminalId"=>"SPAYFIN001-001",
        'startDate' => "2023-04-10 00:00:00",
        'endDate' => "2023-06-17 23:00:00",
        'pageNo' => "0",
        'pageSize' => "20"
        ];
        
        unset($req['sid']);
        
            $checksum='';
            foreach ($req as $val){
                $checksum.=$val;
            }
            //dd($checksum);
            
            //Check Sum string SPAYFIN001apiSPAYFIN001-0012023-04-10 00:00:002023-04-11 23:00:00020
            
            $checksum_string=$checksum.'46efbba174d340d791ba66fa8f6606c1';
            
           // $checksum_string='SPAYFIN001apiSPAYFIN001-0012023-04-10 00:00:002023-04-11 23:00:00020'.'46efbba174d340d791ba66fa8f6606c1';
            $req['checksum']=hash('sha256',$checksum_string);
            $req['sid']='SPAYFIN001-001';
            
            $key= '2b273ac2cc334f05812b34a04310360a';
            $key=substr((hash('sha256',$key,true)),0,16);
            $cipher='AES-128-ECB';
            $encrypted_string=openssl_encrypt(
                json_encode($req),
                $cipher,
                $key
            );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://merchantuat.timepayonline.com/evok/qr/v1/qrreport',
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
            'cid: 40103a8179f140d78867648587655baa',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $decrypted_string = openssl_decrypt(
            $response,
            $cipher,
            $key
        );
         dd(['https://merchantuat.timepayonline.com/evok/qr/v1/qrreport',$req,$encrypted_string,$response,$decrypted_string]); 

    }
    
   
}
