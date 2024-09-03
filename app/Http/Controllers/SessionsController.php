<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Permission ;
use App\Models\User;
use App\Models\Pindata;
use App\Models\Circle;
use App\Models\Cosmosmerchant;
use App\Models\Role;
use Illuminate\Validation\Rule;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store(Request $post)
    {
        $rules = array(
            'password' => 'required',
            'mobile'  =>'required|numeric',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        $user = User::where('mobile', $post->mobile)->first();
        if($user->status == 'block'){
            return response()->json(['status' => "Your account is blocked please contact admin" ], 400);
        }
        if(!$user){
            return response()->json(['status' => "Your aren't registred with us." ], 400);
        }

		$geodata = geoip($post->ip());
		$log['ip']           = $post->ip();
		$log['user_agent']   = $post->server('HTTP_USER_AGENT');
		$log['user_id']      = $user->id;
		$log['geo_location'] = $geodata->lat."/".$geodata->lon;
		$log['url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$log['parameters']   = 'portal';
		\DB::table('login_activitylogs')->insert($log);  

        $company = \App\Models\Company::where('id', $user->company_id)->first();
        $otprequired = \App\Models\PortalSetting::where('code', 'otplogin')->first();
        if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password])) {
            return response()->json(['status' => 'Your Username or Password is Incorrect!'], 400);
        }
        if(!empty($otprequired) &&$otprequired->value == "yes" && $company->senderid){
            if($post->has('otp') && $post->otp == "resend"){
            	return response()->json(['status' => 'OTP login down'], 400);
            }

            if($user->otpverify == "yes"){
                $otp  = rand(111111, 999999);
                $msg  = "Dear Sahaj Money partner, your login otp is ".$otp;
                
                $send = Permission::sms($post->mobile, $msg);
                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
                $mail = Permission::mail('mail.otp', ["otp" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Login Otp");
                if($send == 'success'){
                    User::where('mobile', $post->mobile)->update(['otpverify' => $otp]);
                    return response()->json(['status' => 'otpsent'], 200);
                }else{
                    return response()->json(['status' => 'Please contact your service provider provider'], 400);
                }
            }else{
                if(!$post->has('otp')){
                    return response()->json(['status' => 'preotp'], 200);
                }
            }

            if (\Auth::attempt(['mobile' =>$post->mobile, 'password' =>$post->password, 'otpverify' =>$post->otp, 'status'=>"active"])){
                return response()->json(['status' => 'Login'], 200);
            }else{
                return response()->json(['status' => 'Please provide correct otp'], 400);
            }

        }else{
            if (\Auth::attempt(['mobile'=>$post->mobile, 'password'=>$post->password])) {
                session()->regenerate();
                return response()->json(['status' => "success"], 200);
            }else{
                return response()->json(['status' => 'Something went wrong, please contact administrator'], 400);
            }
        }
    }
    
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }
}
