<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use App\Model\Apitoken;

class ApiController extends Controller
{
    public function getbalance(Request $post, $token)
    {
        try {
            $user = Apitoken::where('ip',$post->ip())->where('token',$token)->first(['user_id']);
            if(!$user){
                return response()->json(['statuscode'=>'ERR','status'=>'error','message'=> 'Invalid api token']);
            }else{
                return response()->json(['statuscode'=>'TXN','balance'=>  User::where('id', $user->user_id)->first()->mainwallet]);
            }
        } catch (\Exception $e) {
            return response()->json(['statuscode' => 'ERR','status'=>'error', 'message'=> 'Invalid api token']);
        }
    }

    public function getip(Request $post)
    {
        return response()->json(['statuscode'=>'TXN', 'ip'=> $post->ip()]);
    }
}