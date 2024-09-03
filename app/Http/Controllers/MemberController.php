<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Circle;
use App\Models\Scheme;
use App\Models\Company;
use App\Models\Provider;
use App\Models\Utiid;
use App\Models\Permission;
use App\Models\User;
use App\Models\Commission;
use App\Models\Packagecommission;
use App\Models\Package;
use App\Models\Openacquiring;
use App\Models\Help_box;
use App\Models\Acquirer;
use App\Models\Merchantacquirermapping;
use App\Models\Currancy;
use App\Models\Merchantkey;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index($type , $action="view")
    {
        if($action != 'view' && $action != 'create'){
            abort(404);
        }

        if($action=='create'){
            // get countries 
            $data['countries'] = \App\Helpers\Permission::getCountries();
            $data['currencies'] = Currancy::all();
        }

        $data['role'] = Role::where('slug', $type)->first();
        $data['roles'] = [];
        if(!$data['role'] && !in_array($type, ['other', 'kycpending', 'kycsubmitted', 'kycrejected'])){
            abort(404);
        }
        
        if($action == "view" && !\App\Helpers\Permission::hasRole(['admin','whitelable','reseller','employee'])){
            abort(401);
        }elseif($action == "create" && !\App\Helpers\Permission::hasRole(['admin','whitelable','reseller','employee'])){
            abort(401);
        }

        if(!$data['role']){
            $roles = Role::whereIn('slug', ["whitelable", "md", 'distributor', 'retailer', 'apiuser'])->get();

            foreach ($roles as $role) {
                if(\App\Helpers\Permission::can('create_'.$type)){
                    $data['roles'][] = $role;
                }
            }

            $roless = Role::whereNotIn('slug', ['admin', "whitelable", "md", 'distributor', 'retailer', 'apiuser'])->get();

            foreach ($roless as $role) {
                if(\App\Helpers\Permission::can('create_other')){
                    $data['roles'][] = $role;
                }
            }
        }
        
        if ($action == "create" && (!$data['role'] && sizeOf($data['roles']) == 0)){
            abort(404);
        }
        
        $data['type'] = $type;
        /*if($this->schememanager() != "all"){
            $data['scheme'] = Scheme::where('user_id', \Auth::id())->get();
        }else{
            $data['scheme'] = Package::where('user_id', \Auth::id())->get();
        }*/

        $data['scheme'] = Scheme::where(['status'=>'1'])->get();

       
        $types = array(
            'Resource' => 'resource',
            'Setup Tools' => 'setup',
            'Member'   => 'member',
            'Member Setting'   => 'memberaction',
            'Member Report'    => 'memberreport',

            'Wallet Fund'   => 'fund',
            'Wallet Fund Report'   => 'fundreport',

            'Aeps Fund'   => 'aepsfund',
            'Aeps Fund Report'   => 'aepsfundreport',

            'Agents List'   => 'idreport',

            'Portal Services'   => 'service',
            'Transactions'   => 'report',

            'Transactions Editing'   => 'reportedit',
            'Transactions Status'   => 'reportstatus',

            'User Setting' => 'setting',
            'Api User Setting' => 'apisetting'
        );
        foreach ($types as $key => $value) {
            $data['permissions'][$key] = Permission::where('type', $value)->orderBy('id', 'ASC')->get();
        }

        if($action == "view"){
            $help_box = Help_box::where("type","memberMerchant")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
            return view('member.index')->with($data);
        }else{
            return view('member.create')->with($data);
        }
    }

    public function create(\App\Http\Requests\Member $post)
    {
        $role = Role::where('id', $post->role_id)->first();
        $users = User::where('mobile',$post->mobile)->first();
        $rules = array(
            'name'       => 'required',
            'mobile'     => 'required|numeric|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'pancard'    => 'required|unique:users,pancard',
            'aadharcard' => 'required|unique:users,aadharcard',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required',
            'companyname'=> 'required',
            'website'    => 'required',
            'currancy_id'=> 'required'
                );
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['status' => $error], 400);
                }
                
        if(!in_array($role->slug, ['admin', "whitelable", "md", 'distributor', 'retailer', 'apiuser'])){
            if(!\App\Helpers\Permission::can('create_other')){
                return response()->json(['status' => "Permission not allowed"],200);
            }
        }
        
        if(!\App\Helpers\Permission::can('create_'.$role->slug)){
            return response()->json(['status' => "Permission not allowed"],200);
        }

        if(\App\Helpers\Permission::hasNotRole('admin')){
            $parent = User::where('id', \Auth::id())->first(['id', 'rstock', 'dstock', 'mstock', 'wstock']);
            if($role->slug == "whitelable"){
                if($parent->wstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }
            
            if($role->slug == "md"){
                if($parent->mstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }

            if($role->slug == "distributor"){
                if($parent->dstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }

            if($role->slug == "retailer"){
                if($parent->rstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }
        }

        if($this->schememanager() != "all"){
            if(!$post->has('scheme_id')){
                $post['scheme_id'] = \Auth::user()->scheme_id;
            }
        }

        $post['id'] = "0";
        $post['parent_id'] = \Auth::id();
        $post['kyc'] = "verified";
        $post['passwordold'] = $post->mobile;
        $post['password'] = bcrypt($post->mobile);

        // Company Logo
        if($post->hasFile('file')){
            $current_timestamp = Carbon::now()->timestamp;
            $filename ='logo'.$current_timestamp.".".$post->file('file')->getClientOriginalExtension();
            $post->file('file')->move(public_path('assets/img/logos/'), $filename);
            $post['logo'] = $filename;
        }
        
            $company = Company::create($post->all());
            $post['company_id'] = $company->id;
        if($post->hasFile('aadharcardpics')){
            $filename ='addhar'.\Auth::id().date('ymdhis').".".$post->file('aadharcardpics')->guessExtension();
            $post->file('aadharcardpics')->move(public_path('kyc/'), $filename);
            $post['aadharcardpic'] = $filename;
        }

        if($post->hasFile('pancardpics')){
            $filename ='pan'.\Auth::id().date('ymdhis').".".$post->file('pancardpics')->guessExtension();
            $post->file('pancardpics')->move(public_path('kyc/'), $filename);
            $post['pancardpic'] = $filename;
        }

        if (!$post->has('scheme_id')) {
            $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $post->role_id)->first();
            if($scheme){
                $post['scheme_id'] = $scheme->permission_id;
            }
        }

        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
    	if($response){

            session(['parentData' => $response]);
            
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }
            
            $data['user_id'] = $response->id;
            $data['status'] = 1;
            $data['mobile'] = $post->mobile;
            $data['email'] = $post->email;
            $data['created_at'] =  $data['updated_at'] =  date('Y-m-d H:i:s');

            // PublicKey & TerNo Generator
            $keyData = \App\Helpers\Permission::generatePublicKey();
            $postKey['public_key'] = $keyData['public_key'];
            $postKey['terno'] = $keyData['terno'];
            $postKey['user_id'] = $response->id;
            $postKey['company_id'] =  $post['company_id'];
            $postKey['is_active'] = 'yes';
            $postKey['id'] = 0;
            $keyAction = Merchantkey::updateOrCreate(['id'=>$postKey['id']], $postKey);

            // OpenAcquiring Data
            $id = Openacquiring::insert($data);

            if(\App\Helpers\Permission::hasNotRole(['admin'])){
                if($role->slug == "whitelable"){
                    User::where('id', \Auth::user()->id)->decrement('wstock', 1);
                }
                
                if($role->slug == "md"){
                    User::where('id', \Auth::user()->id)->decrement('mstock', 1);
                }

                if($role->slug == "distributor"){
                    User::where('id', \Auth::user()->id)->decrement('dstock', 1);
                }
    
                if($role->slug == "retailer"){
                    User::where('id', \Auth::user()->id)->decrement('rstock', 1);
                }
            }

            $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
            $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();

            // $mail = \App\Helpers\Permission::mail('mail.member', ["username" => $post->mobile, "password" => $post->mobile, "name" => $post->name], $post->email, $post->name, $otpmailid->value, $otpmailname->value, "Member Registration");
            // $mail = \App\Helpers\Permission::sendRapidMail('mail.member',["username" => $post->mobile, "password" => $post->mobile, "name" => $post->name],$post->email, $post->name, $otpmailid->value, $otpmailname->value,"Member Registration");
            // $mail = \App\Helpers\Permission::sendGridMail('mail.member',["username" => $post->mobile, "password" => $post->mobile, "name" => $post->name],$post->email, $post->name, $otpmailid->value, $otpmailname->value,"Member Registration");
            $mail = \App\Helpers\Permission::elasticMail('mail.member',["username" => $post->mobile, "password" => $post->mobile, "name" => $post->name],$post->email, $post->name, $otpmailid->value, $otpmailname->value,"Member Registration");

    		return response()->json(['status'=>'success'], 200);
    	}else{
    		return response()->json(['status'=>'fail'], 400);
    	}
    }

    public function utiidcreation($user)
    {
        $provider = Provider::where('recharge1', 'utipancard')->first();

        if($provider && $provider->status != 0 && $provider->api && $provider->api->status != 0){
            $parameter['token'] = $provider->api->username;
            $parameter['vle_id'] = $user->mobile;
            $parameter['vle_name'] = $user->name;
            $parameter['location'] = $user->city;
            $parameter['contact_person'] = $user->name;
            $parameter['pincode'] = $user->pincode;
            $parameter['state'] = $user->state;
            $parameter['email'] = $user->email;
            $parameter['mobile'] = $user->mobile;
            $url = $provider->api->url."/create";
            $result = \App\Helpers\Permission::curl($url, "POST", json_encode($parameter), ["Content-Type: application/json", "Accept: application/json"], "no");

            if(!$result['error'] || $result['response'] != ''){
                $doc = json_decode($result['response']);
                if($doc->statuscode == "TXN"){
                    $parameter['user_id'] = $user->email;
                    $parameter['type'] = "new";
                    Utiid::create($post->all());
                }
            }
        }
    }

    public function getCommission(Request $post)
    {
        $product = ['payout','upi'];
        foreach ($product as $key) {
            $data['commission'][$key] = Commission::where('scheme_id', $post->scheme_id)->whereHas('provider', function ($q) use($key){
                $q->where('type' , $key);
            })->get();
        }
        return response()->json(view('member.commission')->with($data)->render());
    }

    public function getPackageCommission(Request $post)
    {
        $product = ['payout','upi'];
        foreach ($product as $key) {
            $data['commission'][$key] = Packagecommission::where('scheme_id', $post->scheme_id)->whereHas('provider', function ($q) use($key){
                $q->where('type' , $key);
            })->get();
        }
        return response()->json(view('member.packagecommission')->with($data)->render());
    }

    public function getScheme(Request $post)
    {
        $user = User::where('id', $post->id)->first(['id', 'role_id']);
        $scheme = Scheme::where('user_id', \Auth::id())->orWhere('type', $user->role->slug)->orWhere('id', $post->scheme_id)->get();
        return response()->json(['data' => $scheme]);
    }

    public function getmemberbenelist(Request $post)
    {
        $userbenelist = \App\Models\Beneficiarybank::where(['user_id'=>$post->id])->get();
    
        return response()->json(['data' => $userbenelist]);
    }
    public function getAcquirerList(Request $post){
        $id = $post->id;
        $acquirerData = Acquirer::leftJoin('merchant_acquirer_mapping', function ($join) use($id){
            $join->on('acquirers.acquirer_id','=','merchant_acquirer_mapping.acquirer_id')
            ->where('merchant_acquirer_mapping.merchant_id','=',$id);
        })
        ->whereNull('merchant_acquirer_mapping.acquirer_id')
        ->select('acquirers.acquirer_id','acquirers.acquirer_name')
        ->get();
        return response()->json($acquirerData);
    }
    public function addAcquirer(Request $post){
        $rules = array(
            "merchant_id" => 'required',
            "acquirer_id" => 'required'
        );
        $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['status' => $error], 400);
                }
        $action = Merchantacquirermapping::create($post->all());
        if($action){
            return response()->json(['status'=>'success'], 200);
        }
    }
    public function getAllAcquireres(Request $post){
        $merchant_id = $post->merchant_id;
        $data['merchant_id'] = $merchant_id;
        $data['acquirer'] = Acquirer::leftJoin('merchant_acquirer_mapping', 'acquirers.acquirer_id','=','merchant_acquirer_mapping.acquirer_id')
        ->where('merchant_acquirer_mapping.merchant_id','=',$merchant_id)
        ->select('acquirers.*','merchant_acquirer_mapping.merchant_acquirer_mapping_id')
        ->get();
        $s2s_agent= User::where('id',$merchant_id)->first('s2s_agent');
        $data['s2s_agent_id'] = $s2s_agent->s2s_agent;
        return response()->json(view('member.acquirer')->with($data)->render());

    }
    public function acquirerMemberDelete(Request $post){
        $delete = Merchantacquirermapping::where('merchant_acquirer_mapping_id', $post->id)->delete();
        return response()->json(['status'=>$delete], 200);
    }
    public function s2s_agent_update(Request $post){
        $UserId = $post['id'];
        $s2s_agent = $post['s2s_agent'];
        $action = User::find($UserId)->update(['s2s_agent'=> $s2s_agent]);
        if($action){
            return response()->json(['status'=>1], 200);
        }else{
            return response()->json(['status'=>0], 200);
        }
    }

}