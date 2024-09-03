<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utiid;
use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class TestController extends Controller {

    public function index() {
        $report_result = Report::where('refno', 'Q2JKY9F3IKVK87C05UHW')->select(\DB::raw('reports.amount'), \DB::raw('reports.id'),
            \DB::raw("SUM(open_acquirer_capture_refunds.amount) as total_amount"))
         ->leftJoin('open_acquirer_capture_refunds', function($join){
        $join->on('reports.id', '=', 'open_acquirer_capture_refunds.report_id')
        ->where('open_acquirer_capture_refunds.type', '=', 1);
})->first();
        // echo '<pre>'; print_r($report_result['id']);die;
        
       return view('test.index');
    }
    public function checkout(Request $request){
        $url = "https://api.sandbox.openacquiring.com/v1/merchants/vynh3bui63qbx604/payment";
    // Initialize a CURL session.
    $ch = curl_init();  
    $exp = explode('/', $request->cc_expire);
    $post = [
        'intent' => 'sale',
        'payer' => ['payment_type' => 'cc', 'funding_instrument' => ['credit_card' => ['number' => $request->cc_number, 'expire_month' => $request->cc_month, 'expire_year' => $request->cc_year, 'cvv2' => $request->cc_cvv, 'name' => $request->cc_name]],  
        "payer_info" =>["email"=>'test@gmail.com',
        "name"=> 'test',
        "billing_address"=> [
            "line1"=> "18 Avenue",
            "line2"=> "cassidy",
            "city"=> "Rose-Hill",
            "country_code"=> "mu",
            "postal_code"=> "72101",
            "state"=> "",
            "phone"=> [
                "country_code"=> "230",
                "number"=> "57976041"
            ]
        ]
    ],
    "browser_info"=> [
        "accept_header"=> "text/html,application/xhtml+xml,application/xml;q\u003d0.9,image/avif,image/webp,*/*;q\u003d0.8",
        "color_depth"=> 24,
        "java_enabled"=> false,
        "javascript_enabled"=> true,
        "language"=> "en-US",
        "screen_height"=> "1080",
        "screen_width"=> "1920",
        "timezone_offset"=> -240,
        "user_agent"=> "Mozilla/5.0 \u0026 #40;Windows NT 10.0; Win64; x64; rv=>103.0\u0026#41; Gecko/20100101 Firefox/103.0",
        "ip"=> "12.2.12.0",
        "channel"=> "Web"
    ]],
    "payee"=> [
    "email"=> $request->email,
    "merchant_id"=> "vynh3bui63qbx604"
    ],
    "transaction"=> [
    "type"=> "1",
    "amount"=> [
        "currency"=> "USD",
        "total"=> "800"
    ],
    "invoice_number"=> "123455",
    "return_url" => "https://lpg..com/openacquiring_payment",
    "items"=> [[
            "sku"=> "100299S",
            "name"=> "Ultrawatch",
            "description"=> "Smart watch",
            "quantity"=> "1",
            "price"=> "500",
            "shipping"=> "20",
            "currency"=> "USD",
            "url"=> "",
            "image"=> "",
            "tangible"=> "true"
        ],
        [
            "sku"=> "100269S",
            "name"=> "Drone",
            "description"=> "drone x",
            "quantity"=> "1",
            "price"=> "500",
            "shipping"=> "20",
            "currency"=> "USD",
            "url"=> "",
            "image"=> "",
            "tangible"=> "true"
        ]
    ]
    ]]; 
    $headers = [
        'Content-Type:application/json',
        'Authorization:Basic '.base64_encode('yci092bkrcd2kfrb:8idvbu778b8u0yhz')
    ];
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    $result = curl_exec($ch);
    $response = json_decode($result);
    if( isset($response->result->redirect_url) ) {
        header('Location: ' . $response->result->redirect_url);
        exit;
    }
    echo '<pre>';print_r($response);
    die();
    }
    public function payment($paymentSessionId){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.openacquiring.com/v1/merchants/vynh3bui63qbx604/payment/session/'.$paymentSessionId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.base64_encode('yci092bkrcd2kfrb:8idvbu778b8u0yhz')
        ),
        ));
        $curlResponse = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($curlResponse, true);
        echo '<pre>';
        print_r($response);
    } 

    public function success(Request $request){
        $paymentSessionId = $request->query('payment-session-id');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.openacquiring.com/v1/merchants/vynh3bui63qbx604/payment/session/'.$paymentSessionId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST=> 0,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.base64_encode('yci092bkrcd2kfrb:8idvbu778b8u0yhz')
        ),
        ));
        $curlResponse = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($curlResponse, true);
        echo '<pre>';
        print_r($response);
    }
}