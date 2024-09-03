<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Helpers\Permission;
use App\Models\User;
use App\Models\Pindata;
use App\Models\Circle;
use App\Models\Cosmosmerchant;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
     public function index()
    {
        $data['state'] = Circle::all();
        $data['roles'] = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        return view('welcome-old')->with($data);
    }
    public function registerpage()
    {
        $data['state'] = Circle::all();
        $data['roles'] = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        return view('register')->with($data);
    }
    public function servicepage()
    {
        $data['state'] = Circle::all();
        $data['roles'] = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        return view('services')->with($data);
    }
    /**
     * This method is used for contact us
     * @return mixed
     */
     public function contactpage()
    {
        $data['state'] = Circle::all();
        $data['roles'] = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        return view('contact')->with($data);
    }
    /**
     * This method is used for login user in the system
     * @param Request $post
     * @return mixed
     */
    public function login(Request $post)
    {
        $user = User::where('mobile', $post->mobile)->first();
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

        /*if(!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password])){
            $attempts = \DB::table('attempts')->where('mobile', $post->mobile)->first();
            registration
            if(!$attempts){
                \DB::table('attempts')->insert([
                    'mobile' => $post->mobile,
                    'login'  => 1,
                    'tpin'   => 0
                ]);
            }else{
                if($attempts->login < 2){
                    \DB::table('attempts')->where('mobile', $post->mobile)->increment('login', 1);
                }else{
                    User::where('mobile', $post->mobile)->update(['status' => "block"]);
                    \DB::table('attempts')->where('mobile', $post->mobile)->update(['login' =>  0]);
                    return response()->json(['status' => "Your Account is Blocked Please Use Forgot password Option & Set new password" ], 400);
                }
            }
            
            return response()->json(['status'=> 'Username or password is incorrect, '.(2-$attempts->login)." attempts left."], 400);
        }*/

        if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password,'status'=>'active'])) {
            return response()->json(['status' => 'Your account currently de-activated, please contact administrator'], 400);
        }

        if($otprequired->value == "yes" && $company->senderid){
            if($post->has('otp') && $post->otp == "resend"){
            	return response()->json(['status' => 'OTP login down'], 400);
                /*if($user->otpresend < 3){
                    $otp = rand(111111, 999999);
                    $msg = "Dear Sahaj Money partner, your login otp is ".$otp;
                    $send = \Myhelper::sms($post->mobile, $msg);
                    if($send == 'success'){
                        User::where('mobile', $post->mobile)->update(['otpverify' => $otp, 'otpresend' => $user->otpresend+1]);
                        return response()->json(['status' => 'otpsent'], 200);
                    }else{
                        return response()->json(['status' => 'Please contact your service provider provider'], 400);
                    }
                }else{
                    return response()->json(['status' => 'Otp resend limit exceed, please contact your service provider'], 400);
                }*/
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
            if (\Auth::attempt(['mobile'=>$post->mobile, 'password'=>$post->password, 'status'=>'active'])) {
                return response()->json(['status' => 'Login'], 200);
            }else{
                return response()->json(['status' => 'Something went wrong, please contact administrator'], 400);
            }
        }
    }
    /**
     * This method is used for user logout the system
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        \Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect(url(''));
    }
    /**
     * This method is used for user request to access token for reset password
     * @param Request $post
     * @return json send message & response status like error or success. 
     */
    public function passwordReset(Request $post)
    {
        $rules = array(
            'type' => 'required',
            'mobile'  =>'required|numeric',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if($post->type == "request" ){
            $user = \App\Models\User::where('mobile', $post->mobile)->first();
            if($user){
                $otp     = rand(111111, 999999);
                
                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
                try {
                    // $mail = Permission::mail('mail.password', ["token" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
                    // $mail = Permission::sendRapidMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value,"Reset Password");
                    // $mail = Permission::sendGridMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value,"Reset Password");
                    $mail = Permission::elasticMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value,"Reset Password");
                } catch (\Exception $e) {
                    return response()->json(['status' => 'ERR', 'message' => $otpmailid], 400);
                }
                if($mail == "success"){
                $updateToken = \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => Permission::encrypt($otp, "sdsada7657hgfh$$&7678"),
                    'last_activity' => time()
                ]);
                    return response()->json(['status' => 'TXN', 'email'=>$user->email,'mobile'=>$post->mobile ], 200);
                }else{
                    return response()->json(['status' => $mail, 'message' => "Something went wrong"], 400);
                }
            }else{
                return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"], 400);
            }
        }
    }
    /**
     * This method is used for get otp. This otp user will use set new pin.
     * @param Request $post
     * @return json return message & status
     */
     public function getotp(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric|',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \App\Models\User::where('mobile', $post->mobile)->first();
        if($user){
            $otp = rand(111111, 999999);
                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
                try {
                    $mail = Permission::mail('mail.password', ["token" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Tpin Otp");
                } catch (\Exception $e) {
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
                }
            
            if($mail=="success"){
                $user = \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => Permission::encrypt($otp, "sdsada7657hgfh$$&7678"),
                    'last_activity' => time()
                ]);
            
                return response()->json(['status' => 'TXN', 'message' => "Pin generate token sent successfully"], 200);
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"], 400);
        }  
    }
    /**
     * This method used for set new pin.
     * @param Request $post
     * return json return message & status.
     */
    // Email Confirmation Email.
    public function emailverificationotp(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric|',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \App\Models\User::where('mobile', $post->mobile)->first();
        if($user){
            $otp = rand(111111, 999999);
                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
                try {
                    // $mail = Permission::mail('mail.email-verification', ["token" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Confirm Your Email and Unlock Your Account");
                    // $mail = Permission::sendRapidMail('mail.email-verification',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Confirm Your Email and Unlock Your Account");
                    // $mail = Permission::sendGridMail('mail.email-verification',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Confirm Your Email and Unlock Your Account");
                    $mail = Permission::elasticMail('mail.email-verification',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Confirm Your Email and Unlock Your Account");
                } catch (\Exception $e) {
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
                }
            
            if($mail=="success"){
                $user = \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => Permission::encrypt($otp, "sdsada7657hgfh$$&7678"),
                    'last_activity' => time()
                ]);
            
                return response()->json(['status' => 'TXN', 'message' => "Pin generate token sent successfully"], 200);
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"], 400);
        }  
    }
    // End
    // Forgot Password OTP
    public function forgotPasswordOtp(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric|',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \App\Models\User::where('mobile', $post->mobile)->first();
        if($user){
            $otp = rand(111111, 999999);
                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
                try {
                    // $mail = Permission::mail('mail.email-verification', ["token" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Confirm Your Email and Unlock Your Account");
                    // $mail = Permission::sendRapidMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
                    // $mail = Permission::sendGridMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
                    $mail = Permission::elasticMail('mail.password',["token" => $otp, "name" => $user->name],$user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
                } catch (\Exception $e) {
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
                }
            
            if($mail=="success"){
                $user = \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => Permission::encrypt($otp, "sdsada7657hgfh$$&7678"),
                    'last_activity' => time()
                ]);
            
                return response()->json(['status' => 'TXN', 'message' => "Pin generate token sent successfully"], 200);
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"], 400);
        }  
    }
    // End Forgot Password OTP
    public function setpin(Request $post)
    {
        //dd(\Myhelper::encrypt($post->otp, "a6e028f0c683"));
        $rules = array(
            'id'  =>'required|numeric',
            'otp'  =>'required|numeric',
            'pin'  =>'required|numeric|confirmed',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , Permission::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->first();
        if($user){
            try {
                Pindata::where('user_id', $post->id)->delete();
                $apptoken = Pindata::create([
                    'pin' => Permission::encrypt($post->pin, "sdsada7657hgfh$$&7678"),
                    'user_id'  => $post->id
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Try Again']);
            }
            
            if($apptoken){
                \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , Permission::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->delete();
                return response()->json(['status' => "success"], 200);
            }else{
                return response()->json(['status' => "Something went wrong"], 400);
            }
        }else{
           return response()->json(['status' => 'ERR', 'message' => 'Please Enter valid otp']);
        }  
    }
   


    public function cosmosonboard(Request $post)
    {
        $rules = array(
            'businessName'     => 'required|unique:cosmosmerchants,businessName',
            'mobileNumber'     => 'required|numeric|digits:10|unique:cosmosmerchants,mobileNumber',
            'pancard'          => 'required|unique:cosmosmerchants,panNo',
            'state'            => 'required',
            'city'             => 'required',
            'address'          => 'required',
            'pincode'          => 'required|digits:6|numeric' );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = Cosmosmerchant::where('mobileNumber', $post->mobileNumber)->first();
        if($user){
            return response()->json(['statuscode' => 'ERR', 'message' => "Already Onboarded."]);
        }

        $array = array();
        $array['businessName'] = $post->businessName;
        $array['pgmerchentId'] = $post->businessName;
        $array['merchentLegalName'] = $post->businessName;
        $array['mid'] = 'SID';
        $array['complianceStatus'] = 'Ok';
        $array['locationCountry'] = $post->locationCountry;
        $array['State'] = $post->state;
        $array['City'] = $post->city;
        $array['Address'] = $post->address;
        $array['postalCode'] = $post->pincode;
        $array['panNo'] = $post->pancard;
        $array['mebusinessType'] = 'Pvt. Ltd.';
        $array['settlementType'] = 'As per circular';
        $array['perDaytransactionCnt'] = 'As per circular';
        $array['perdaytransactionlimit'] = 'As per circular';
        $array['pertransactionLimit'] = 'As per circular';
        $array['categoryofMerchant'] = 'Ecommerce';
        $array['WebApp'] = 'WEB';
        $array['gstn']                = $post->gstn;
        $array['mobileNumber']        = $post->mobileNumber;
        $array['WebURL']              = $post->WebURL;
        $array['user_id']              = \Auth::id();

        $qeruy = Cosmosmerchant::insert($array);
        if($qeruy){
            return response()->json(['statuscode' => "TXN", 'message' => "Onboarding successfully"]);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"]);
        }

    }
    
    /**
     * This method is used to register a user in a system 
     * @param Request $post
     * @return json send json with message & status 
     */
    public function registration(Request $post)
    {
        $rules = array(
            'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'country'    => 'required',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required|digits:6|numeric',
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first(['id', 'company_id']);

        $role = Role::where('slug', 'apiuser')->first();

        $post['role_id']    = $role->id;
        $post['id']         = "0";
        $post['parent_id']  = $admin->id;
        $post['status']     = "onboarding";
        $post['kyc']        = "pending";

        $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $role->id)->first();
        if($scheme){
            $post['scheme_id'] = $scheme->permission_id;
        }
        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
        if($response){
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }
            return response()->json(['status' => "success", 'message' => "Success",'data'=>$post->mobile,"email"=>$post->email], 200);
        }else{
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }
    public function ConfirmEmail(Request $post){
        $rules = array(
            'mobile'     => 'required',
            'otp'        => 'required'
        );

        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        // Logic
        $user = \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , Permission::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->first();
        if($user){
            try {
                $userRegistration = User::where('mobile',$post->mobile)->update(['otpverify'=> 'yes' ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Try Again']);
            }
            
            if($userRegistration){
                \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , Permission::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->delete();
                    session(['mobile' => $post->mobile]);
                    return response()->json(['status' => "success"], 200);
                // }
            }else{
                return response()->json(['status' => "Something went wrong"], 400);
            }
        }else{
           return response()->json(['status' => 'ERR', 'message' => 'Please Enter valid otp']);
        }
    }
    public function StorePassword(Request $post){

        $rules = array(
            'password' => ['required','string',Password::min(8)->letters()->mixedCase()->numbers()->symbols(),'confirmed']
        );
        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        if(empty($post->mobile)){
            return response()->json(['status'=>'ERR', 'message' =>'Somthing went wrong, Please try again later.']);
        }
        $password = $post->password;
        $encPassword = bcrypt($password);
        try {
            $userRegistration = User::where('mobile',$post->mobile)->update(['password'=> $encPassword, 'passwordold'=>$password ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERR', 'message' => 'Try Again']);
        }
        
        if($userRegistration){
            $users = User::where('mobile',$post->mobile)->first();
            Session::put('password_set', true);
            if (\Auth::attempt(['mobile'=>$users->mobile, 'password'=>$users->passwordold])) {
                session()->regenerate();
                session()->forget('mobile');
                return response()->json(['status' => "success"], 200);
            }
        }else{
            return response()->json(['status' => "Something went wrong"], 400);
        }

    }
 
    /**
     * This method is used to update user sound box language
     * @param Request $post
     * @return json send json with message & status 
     */
    public function updateSound(Request $post)
    {
         $user = \DB::table("users")->where('id',$post->user_id)->update(["soundBoxLanguage" => $post->soundBoxLanguage,"soundBoxType" => $post->soundBoxType,"soundBoxSerial" => $post->soundBoxSerial]);
 
         return response()->json(['statuscode'=> "TXN",'message'=>"Sound Data Update successfully.",'status'=>"success"]);
           
    }    
   

    /**
     * This method is used Uddate GST Rate
     * @param Request $post
     * @return json send json with message & status
     */
    public function updateGstRate(Request $post)
    {
         $user = \DB::table("users")->where('id',$post->user_id)->update(["gstrate" => $post->gstrate]);
 
         return response()->json(['statuscode'=> "TXN",'message'=>"GST set successfully.",'status'=>"GST set successfully."]);
           
    }  
    /**
     * This method is used send contact mail to admin
     * @param Request $post
     * @return json send json with message & status
     */
    public function contact(Request $post)
    {
        $rules = array(
            'fname'       => 'required',
            'lname'       => 'required',
            'phone'     => 'required|numeric|digits:10',
            'email'      => 'required|email',
            'message'    => 'required'
        );
        $msg =  $post->message;
        $validate = Permission::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        try {
            
            $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
            $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
            $mail = Permission::mail('mail.contact', ["name" => $post->fname.' '. $post->lname, "msg" => $msg, "email" => $post->email, 'phone' => $post->phone],  'rajeshp@itio.in', 'ITIO', $otpmailid->value, $otpmailname->value, "Contact Us");
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
        return response()->json(['status' => "TXN", 'message' => "Success"], 200);
    }
}