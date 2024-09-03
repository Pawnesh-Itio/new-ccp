<?php

namespace App\Http\Controllers;
use App\Helpers\Permission;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Circle;
use App\Models\Role;
use App\Models\Openacquiring;
use App\Models\Help_box;
use App\Models\Merchantkey;
class SettingController extends Controller
{
    /**
     * This method is used for display User profiles 
     * @param Requesy $get
     * @param integer $id default value 0
     * @return mixed 
     */
    public function index(Request $get, $id=0)
    {
        $data = [];
        $data['tab'] = $get->tab;
        $help_box = Help_box::where("type","userprofile")->select("description", "slug", "type")->get();
        foreach($help_box as $hb){
           $data[$hb->slug]['description'] = $hb->description;
           $data[$hb->slug]['type'] = $hb->type;
        }
        if($id != 0 ){
            if(Permission::hasRole('admin')){
                $userid = $id;
                $data['user'] = User::find($id);
            }else{
                abort(403);
            }
        }else{
            $userid = \Auth::user()->id;
            $data['user'] = \Auth::user();
        }
        if(Permission::hasRole('admin')){
            $data['parents'] = User::whereHas('role', function ($q){
                $q->where('slug', '!=', 'retailer');
            })->get(['id', 'name', 'role_id', 'mobile']);
            $data['roles']   = Role::where('slug' , '!=' , 'admin')->get();
        }else{
            $data['parents'] = [];
            $data['roles']   = [];
        }
        // Get Default Currancy 
        $action= Permission::getCurrency($userid);
        if($action){
            $data['default_currancy'] = $action;
        }else{
            $data['default_currency'] ="";
        }
        return view('profile.index')->with($data);
    }
    /**
     * This method is used for display comany certificate for company agent.
     */
    public function certificate()
    {
        return view('certificate');
    }
    /**
     * This method is used for update user profile details only admin can update all iformation other roles user can update only passwrd & pin. 
     * @param object $post
     * @return json return message & status
    */
    public function profileUpdate(\App\Http\Requests\Member $post)
    {
        if(Permission::hasNotRole('admin') && (\Auth::id() != $post->id) && !in_array($post->id, session('parentData'))){

            return response()->json(['status' => "Permission Not Alloweds"], 400);

        }



        switch ($post->actiontype) {

            case 'password':
                if(($post->id != \Auth::id()) && !Permission::can('member_password_reset')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                if(($post->id == \Auth::id()) && !Permission::can('password_reset')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                $rules = array('password' => ['required','string',Password::min(8)->letters()->mixedCase()->numbers()->symbols(),'confirmed']);
                    $validate = Permission::FormValidator($rules, $post);
                    if($validate != "no"){
                        return $validate;
                    }
                    $post['passwordold'] = $post->password;
                    $post['password'] = bcrypt($post->password);
                    $post['resetpwd'] = "changed";
                if(Permission::hasNotRole('admin')){
                    $name = \Auth::user()->name;
                    $email = \Auth::user()->email;
                    $mobile = \Auth::user()->mobile;
                    // SendEmail 
                    $otp = rand(111111, 999999); //Generate OTP
                    $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first(); //Getting Mail ID 
                    $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first(); //Getting Mail Name
                    try {
                        // $mail = Permission::sendGridMail('mail.password',["token" => $otp, "name" => $name],$email, $name, $otpmailid->value, $otpmailname->value, "Reset Password");
                        $mail = Permission::elasticMail('mail.password',["token" => $otp, "name" => $name],$email, $name, $otpmailid->value, $otpmailname->value, "Reset Password");
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
                    }
                    if($mail=="success"){
                        $user = \DB::table('password_resets')->insert([
                            'mobile' => $mobile,
                            'token' => Permission::encrypt($otp, "sdsada7657hgfh$$&7678"),
                            'last_activity' => time()
                        ]);
                        return response()->json(['status' => 'email', 'mobile' => $mobile, 'email' => $email, 'password' => $post->passwordold], 200);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
                    }
                }
                break;

            case 'profile_picture':

                    if(($post->id != \Auth::id()) && !Permission::can('member_profile_edit')){
    
                        return response()->json(['status' => "Permission Not Allowed"], 400);
    
                    }
    
    
    
                    if(($post->id == \Auth::id()) && !Permission::can('profile_edit')){
    
                        return response()->json(['status' => "Permission Not Allowed"], 400);
    
                    }
                    $rules = array(
                        'file' => ['required','max:1000','mimes:jpg,bmp,png,jpeg,JPEG,PNG,JPG,BMP'],
                        'id'   => ['required']
                        );
                
                        $validate = Permission::FormValidator($rules, $post);
                        if($validate != "no"){
                            return $validate;
                        }
                    $userdata = User::find($post['id']);
                    if($post->hasFile('file')){
                    try {
                        unlink(public_path('assets/img/user-profile/').$userdata->profile);
                    } catch (\Exception $e) {
                    }
                    $fileName = 'user'.$post->id.'.'.$post->file->extension(); 
                    $post->file->move(public_path('assets/img/user-profile/'), $fileName);
                    User::where('id',$post->id)
                    ->update([
                        'profile'=>$fileName
                    ]);
                    return response()->json(['status' => "success"], 200);
                }
                // End Logic
    
                    break;

            case 'profile-status':

                if(($post->id != \Auth::id()) && !Permission::can('member_profile_edit')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }

                if(($post->id == \Auth::id()) && !Permission::can('profile_edit')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }

                $rules = array(
                    'id'          => 'required',
                    'status'      => 'required',
                );
                if($post['status']=='active'){
                    $checkStatus = User::where("id",$post->id)
                    ->first(['kyc']);
                    if($checkStatus->kyc == 'pending'){
                            $post['status'] = 'onboarding';
                    }
                }

                break;

            case 'profile':

                if(($post->id != \Auth::id()) && !Permission::can('member_profile_edit')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }



                if(($post->id == \Auth::id()) && !Permission::can('profile_edit')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }
                // Validation
                $rules = array(
                    'name'       => 'sometimes|required',
                    'mobile'     => 'sometimes|required|numeric|digits:10|unique:users,mobile,'.$post->id ,
                    // 'email'      => 'required|email|unique:users,email',
                    'state'      => 'sometimes|required',
                    'city'       => 'sometimes|required',
                    'address'    => 'sometimes|required',
                    'pincode'    => 'sometimes|required|digits:6|numeric',
                    'gender'     => 'sometimes|required',
                );
        
                $validate = Permission::FormValidator($rules, $post);
                if($validate != "no"){
                    return $validate;
                }

                break;

            

            case 'wstock' :

            case 'mstock' :

            case 'dstock' :

            case 'rstock' :

                if(!Permission::can('member_stock_manager')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }



                if(Permission::hasNotRole(['admin'])){

                    if($post->wstock > 0 && \Auth::user()->wstock < $post->wstock){

                        return response()->json(['status'=>'Low id stock'], 400);

                    }

                    

                    if($post->mstock > 0 && \Auth::user()->mstock < $post->mstock){

                        return response()->json(['status'=>'Low id stock'], 400);

                    }



                    if($post->dstock > 0 && \Auth::user()->dstock < $post->dstock){

                        return response()->json(['status'=>'Low id stock'], 400);

                    }

        

                    if($post->rstock > 0 && \Auth::user()->rstock < $post->rstock){

                        return response()->json(['status'=>'Low id stock'], 400);

                    }

                }

                

                if($post->wstock != ''){

                    User::where('id', \Auth::id())->decrement('wstock', $post->wstock);

                    $response = User::where('id', $post->id)->increment('wstock', $post->wstock);

                }

                

                if($post->mstock != ''){

                    User::where('id', \Auth::id())->decrement('mstock', $post->mstock);

                    $response = User::where('id', $post->id)->increment('mstock', $post->mstock);

                }



                if($post->dstock != ''){

                    User::where('id', \Auth::id())->decrement('dstock', $post->dstock);

                    $response = User::where('id', $post->id)->increment('dstock', $post->dstock);

                }



                if($post->rstock != ''){

                    User::where('id', \Auth::id())->decrement('rstock', $post->rstock);

                    $response = User::where('id', $post->id)->increment('rstock', $post->rstock);

                }



                if($response){

                    return response()->json(['status'=>'success'], 200);

                }else{

                    return response()->json(['status'=>'fail'], 400);

                }



                break;



            case 'bankdata':

                if(Permission::hasNotRole('admin')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }

                break;



            case 'mapping':

                if(Permission::hasNotRole('admin')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }

                $user = User::find($post->id);

                $parent = User::find($post->parent_id);



                if($parent->role->slug == "retailer"){

                    return response()->json(['status' => "Invalid mapping member"], 400);

                }

                switch ($user->role->slug) {

                    case 'apiuser':

                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','distributor', 'md', 'whitelable','reseller'])->count();

                        break;



                    case 'distributor':

                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','md', 'whitelable'])->count();

                        break;

                    

                    case 'md':

                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','whitelable'])->count();

                        break;



                    case 'whitelable':

                        return response()->json(['status' => "Invalid mapping member"], 400);

                        break;

                }



                if(empty($roles)){

                    return response()->json(['status' => "Invalid mapping member"], 400);

                }

                break;



            case 'rolemanager':

                if(Permission::hasNotRole('admin')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }



                $roles = Role::where('id', $post->role_id)->where('slug', 'admin')->count();

                if($roles){

                    return response()->json(['status' => "Invalid member role"], 400);

                }



                $user = User::find($post->id);

                switch ($user->role->slug) {

                    case 'retailer':

                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['distributor', 'md', 'whitelable','apiuser'])->count();

                        break;



                    case 'distributor':

                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['md', 'whitelable'])->count();

                        break;

                    

                    case 'md':

                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['whitelable'])->count();

                        break;



                    case 'whitelable':

                        return response()->json(['status' => "Invalid member role"], 400);

                        break;

                }



                if(!$roles){

                    return response()->json(['status' => "Invalid member role"], 400);

                }

                break;



            case 'scheme':

                if($this->schememanager() == "admin" && Permission::hasNotRole('admin')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }



                if($this->schememanager() == "all" && Permission::hasRole('retailer')){

                    return response()->json(['status' => "Permission Not Allowed"], 400);

                }



                if($this->schememanager() == "admin"){

                    $users = Permission::getParents($post->id);

                    // User::whereIn('id', $users)->where('id', '!=', $post->id)->update(['scheme_id' => $post->scheme_id]);
                    // Changed query for observer.

                    $usersToUpdate = User::whereIn('id', $users)
                                         ->where('id', '!=', $post->id)
                                         ->get();
                    foreach ($usersToUpdate as $user){
                                $user->scheme_id = $post->scheme_id;
                                $user->save();
                    }

                }

                break;

        }
        $response = User::updateOrCreate(['id'=> $post->id], $post->all());

        if($response){

            return response()->json(['status'=>'success'], 200);

        }else{

            return response()->json(['status'=>'fail'], 400);

        }

    }
    public function kyc_update(Request $request){
        $rules = array(
            'aadharcard'     => 'required|unique:users,aadharcard,'.$request->id,
            'pancard'        => 'required|unique:users,pancard,'.$request->id,
            'kyc'            => 'sometimes|required',
            'currancy_id'            => 'sometimes|required',
            'id'             => 'required'
        );
        $validate = Permission::FormValidator($rules, $request);
        if($validate != "no"){
            return $validate;
        }
        if($request['usertype']){
            if($request['usertype']=='block'){
              $data['kyc'] ="submitted";
              $data['status'] = "active";
              $openacquiringData["user_id"] =$request['id'];
              $openacquiringData["status"] =0;
              $openacquiringData["mobile"] =$request['mobile'];
              $openacquiringData["email"] =$request['email'];
              $openacquiringData["created_at"] =$openacquiringData['updated_at'] = date('Y-m-d H:i:s');
            }
        }
        $data['aadharcard']  = $request['aadharcard'];
        $data['pancard']     = $request['pancard'];
        if(isset($request['gstin']) && !empty($request['gstin'])){
        $data['gstin']       = $request['gstin'];
        }
        if(isset($request['kyc'])){
        $data['kyc']       = $request['kyc'];
        }
        if(isset($request['currancy_id'])){
            $data['currancy_id']       = $request['currancy_id'];
        }
        $userdata = User::find($request['id']);
        $userAddharPicArr = json_decode($userdata->aadharcardpic);
        if($request->hasFile('aadharcardpics')){
            if(sizeof($request['aadharcardpics']) <= 2){
                $aadharcardpics = $request['aadharcardpics'];
                $count = 1;
                for($i=0; $i<sizeof($aadharcardpics);$i++)
                {
                    try {
                       unlink(public_path('assets/img/kyc/').$userAddharPicArr[$i]);
                        } catch (\Exception $e) { }
                   $fileNameAadhar = 'user-aadhar-'.$request->id.'-'.$count++.'.'.$aadharcardpics[$i]->extension(); 
                   $aadharcardpics[$i]->move(public_path('assets/img/kyc/'), $fileNameAadhar);
                   $aadharcardPicArr[] = $fileNameAadhar;
                }
                $data['aadharcardpic'] = json_encode($aadharcardPicArr);
            }else{
                return response()->json(['status'=>'Only 2 Files can be uploaded'], 200);
            }
        }
        if($request->hasFile('pancardpics')){
            try {
                unlink(public_path('assets/img/kyc/').$userdata->pancard);
            } catch (\Exception $e) {
            }
            $fileNamePan = 'user-pan-'.$request->id.'.'.$request->pancardpics->extension(); 
            $request->pancardpics->move(public_path('assets/img/kyc/'), $fileNamePan);
            $data['pancardpic'] = $fileNamePan;
        }
        User::where('id',$request->id)
        ->update($data);
        // Adding OpenAcquiring Data.
        if($request['usertype']){
        if($request['usertype']=='block'){
        Openacquiring::create($openacquiringData);
            }
        }
        return response()->json(['status'=>'success'], 200);
    }
    public function password_update(Request $request){
        $rules = array(
            'otp'        => 'required',
            'mobile'     => 'required',
            'password'   => 'required'
        );
        $validate = Permission::FormValidator($rules, $request);
        if($validate != "no"){
            return $validate;
        }
        $otp = $request['otp'];
        $mobile = $request['mobile'];
        $password = $request['password'];
        $user = \DB::table('password_resets')->where('mobile', $mobile)->where('token' , Permission::encrypt($otp, "sdsada7657hgfh$$&7678"))->first();
        if($user){
            // Update Password
            $data['password'] = bcrypt($password);
            $data['passwordold'] = $password;
            $response = User::updateOrCreate(['mobile'=> $mobile], $data);
            $delteToken = \DB::table('password_resets')->where('mobile', $mobile)->where('token' , Permission::encrypt($otp, "sdsada7657hgfh$$&7678"))->delete();
            if($response){
            return response()->json(['status' => "success"], 200);
            }else{
            return response()->json(['status' => "failed"], 200);
            }
        }else{
           return response()->json(['status' => 'ERR', 'message' => 'Please Enter valid otp']);
        }

    }

}