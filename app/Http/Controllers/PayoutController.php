<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api;

class PayoutController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'xettlepayout')->first();
    }
    public function index(){
        $data['agent']=\DB::table('xettlemerchants')->where('user_id',\Auth::id())->where('contact_id','!=','')->first();
       print_r($data['agent']);
       exit;
        return view('service/payout')->with($data);
    }
}