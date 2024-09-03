<?php

namespace App\Http\Controllers;
use App\Helpers\Permission;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Models\Company;
use App\Models\Provider;
use App\Models\Commission;
use App\Models\Companydata;
use App\Models\Packagecommission;
use App\Models\Package;
use App\Models\User;
use App\Models\Help_box;
use App\Models\Merchantkey;
use Carbon\Carbon;

class ResourceController extends Controller
{
    public function index($type,$id=0)
    {
        switch ($type) {
            case 'scheme':
                $permission = "scheme_manager";
                $data['payoutOperator']    = Provider::where('type', 'payout')->where('status', "1")->get();
                $data['upiOperator']    = Provider::where('type', 'upi')->where('status', "1")->get();
                $help_box = Help_box::where("type","resourceScheme")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                break;

            case 'package':
                if($this->schememanager() != "all"){
                    abort(403);
                }
                $data['mobileOperator'] = Provider::where('type', 'mobile')->where('status', "1")->get();
                $data['dthOperator'] = Provider::where('type', 'dth')->where('status', "1")->get();
                $data['ebillOperator'] = Provider::where('type', 'electricity')->where('status', "1")->get();
                $data['pancardOperator'] = Provider::where('type', 'pancard')->where('status', "1")->get();
                $data['nsdlpanOperator'] = Provider::where('type', 'nsdlpan')->where('status', "1")->get();
                $data['dmtOperator'] = Provider::where('type', 'dmt')->where('status', "1")->get();
                $data['aepsOperator'] = Provider::where('type', 'aeps')->where('status', "1")->get();
                $data['upiOperator']    = Provider::where('type', 'upi')->where('status', "1")->get();
                break;

            case 'company': 
                $help_box = Help_box::where("type","resourcecompany")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                $permission = "company_manager";
                break;
                
                
            case 'companydata':
                $help_box = Help_box::where("type","resourceCompanydata")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                $permission = "company_manager";
                $data['company'] = Company::all();
                break;

            case 'companyprofile':
                $help_box = Help_box::where("type","companyprofile")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                if($id != 0){
                    $permission = "company_manager";
                    $companyId = $id;
                }else{
                    $permission = "change_company_profile";
                    $companyId = \Auth::user()->company_id;
                }
                
                $data['company'] = Company::where('id', $companyId)->first();
                $companydata = Companydata::query()->select('companydatas.slug','companydatas.title','companydatas.description');
                $companydata->where('company_id',$companyId);
                $companydata->orWhere('type','E_ALL');
                $companydata->orderBy('companydatas.id','desc');
                $companydata->groupBy('companydatas.id');
                // Merchant Key 
                $data['businessdata'] = Merchantkey::where('company_id',$companyId)->first();
                $data['companydata'] = $companydata->get();

                break;
            
            case 'commission':
                $permission = "view_commission";
                $product = ['upi', 'payout'];

                if($this->schememanager() != "all"){
                    foreach ($product as $key) {
                        $data['commission'][$key] = Commission::where('scheme_id', \Auth::user()->scheme_id)->whereHas('provider', function ($q) use($key){
                            $q->where('type' , $key);
                        })->get();
                    }
                }else{
                    foreach ($product as $key) {
                        $data['commission'][$key] = Packagecommission::where('scheme_id', \Auth::user()->scheme_id)->whereHas('provider', function ($q) use($key){
                            $q->where('type' , $key);
                        })->get();
                    }
                }
                
                break;
            
            default:
                # code...
                break;
        }
        if ($type != "package" && !Permission::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;

        return view("resource.".$type)->with($data);
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'scheme':
            case 'commission':
                $permission = "scheme_manager";
                break;
            
            case 'company':
                
                $permission = ["company_manager", "change_company_profile"];
                break;

            case 'companydata':
                $permission = "change_company_profile";
                break;
        }

        if (isset($permission) && !Permission::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed here"], 400); 
        }

        switch ($post->actiontype) {
            case 'scheme':
                $rules = array(
                    'name'    => 'sometimes|required|unique:schemes,name' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Scheme::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'package':
                $rules = array(
                    'name'    => 'sometimes|required|unique:packages,name' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Package::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'company': 
                // validation
                $rules = array(
                    'companyname'    => 'sometimes|required|unique:companies,companyname,'.$post->id,
                    'website'    => 'sometimes|required|unique:companies,website,'.$post->id
                );

                if($post->file('file')){
                    $rules['file'] = 'sometimes|required|mimes:jpg,JPG,jpeg,png|max:500';
                }
                    $validate = Permission::FormValidator($rules, $post);
                    if($validate != "no"){
                        return $validate;
                    }
                if($post->id != '0'){
                    $company = Company::find($post->id);
                }
                
                if($post->hasFile('file')){
                    try {
                        unlink(public_path('assets/img/logos/').$company->logo);
                    } catch (\Exception $e) {
                    }

                    if($post->id=='0'){
                    $current_timestamp = Carbon::now()->timestamp;
                    $filename ='logo'.$current_timestamp.".".$post->file('file')->getClientOriginalExtension();
                    }else{
                    $filename ='logo'.$post->id.".".$post->file('file')->getClientOriginalExtension();
                    }
                    $post->file('file')->move(public_path('assets/img/logos/'), $filename);
                    $post['logo'] = $filename;
                   //dd($post->all());
                }                
                $action = Company::updateOrCreate(['id'=>$post->id], $post->all());
                $company_id = $action->id;
                // Getting Last Inserted Id
                if($post->id =='0'){
                     // If new company created get Public Key for merchant
                    $keyData = Permission::generatePublicKey();
                    $postKey['public_key'] = $keyData['public_key'];
                    $postKey['terno'] = $keyData['terno'];
                    $postKey['user_id'] = \Auth::user()->id;
                    $postKey['company_id'] = $company_id;
                    $postKey['is_active'] = 'yes';
                    $postKey['id'] = 0;
                    $keyAction = Merchantkey::updateOrCreate(['id'=>$postKey['id']], $postKey);
                // Linking User with created company.
                $userAction = User::where('id', \Auth::user()->id)->update(['company_id' => $company_id]);
                if ($action && $userAction) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
            }else{
                if ($action ) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
            }
                break;

            case 'companydata':
                $rules = array(
                    'id'    => 'required',
                    'type'  => 'sometimes|required',
                    'company_id' => 'sometimes|required',
                    'title' => 'sometimes|required',
                    'slug' => 'sometimes|required',
                    'description' => 'sometimes|required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                if($post->company_id){
                    $company_id = $post->company_id;
                }else{
                    $company_id ="";
                }
                $data =array(
                    "title"=>$post->title,
                    "slug"=>$post->slug,
                    "description"=>"$post->description",
                    "type"=>$post->type,
                    "company_id"=>$company_id
                );
                $action = Companydata::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'commission':
                $rules = array(
                    'scheme_id'    => 'sometimes|required|numeric' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                foreach ($post->slab as $key => $value) {
                    $update[$value] = Commission::updateOrCreate([
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key]
                    ],[
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key],
                        'type'      => $post->type[$key],
                        'apiuser'=> $post->apiuser[$key],
                    ]);
                }
                return response()->json(['status'=>$update], 200);
                break;

            case 'packagecommission':
                $rules = array(
                    'scheme_id'    => 'sometimes|required|numeric' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                foreach ($post->slab as $key => $value) {
                    $data     = Packagecommission::where('scheme_id',\Auth::user()->scheme_id)->where('slab', $value)->first();
                    $provider = Provider::where('id', $value)->first();
                    $pass = true;

                    if(\Permission::hasNotRole('admin') && $data){
                        if($data->provider->type == "dmt"){
                            if($post->type[$key] == "flat" && $post->value[$key] > 50 ){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than 50";
                            }

                            if($post->type[$key] == "percent" && $post->value[$key] > 1 ){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than 1";
                            }
                        }
                    }

                    if($post->value[$key] < 0 ){
                        $pass = false;
                        $update[$post->slab[$key]] = "value should be greater than 0";
                    }

                    if(\Permission::hasNotRole('admin') && !$data){
                        $pass = false;
                        $update[$post->slab[$key]] = "Your commission not set by parent";
                    }

                    if(\Permission::hasNotRole('admin') && $data){
                        if(
                            $provider->type == "mobile" || 
                            $provider->type == "electricity"|| 
                            $provider->type == "dth"  || 
                            $provider->type == "pancard" || 
                            $provider->type == "aeps" ||
                            $provider->type == "upi"
                        ){
                            if($data->value < $post->value[$key]){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than ".$data->value;
                            }
                        }

                        if(($provider->type == "dmt" && $provider->recharge1 != "dmt1accverify") || $provider->type == "nsdlpan"){
                            if($data->value > $post->value[$key]){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be less than ".$data->value;
                            }
                        }
                    }

                    if(\Permission::hasNotRole('admin') && $data){
                        $slabtype = $data->type;
                    }else{
                        $slabtype = $post->type[$key];
                    }
                    if($pass){
                        $update[$value] = Packagecommission::updateOrCreate(
                            [
                                'scheme_id' => $post->scheme_id,
                                'slab'      => $post->slab[$key],
                            ],
                            [
                                'scheme_id' => $post->scheme_id,
                                'slab'      => $post->slab[$key],
                                'type'      => $slabtype,
                                'value'     => $post->value[$key]
                            ]
                        );
                    }
                }
                return response()->json(['status'=>$update], 200);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function getCommission(Request $post , $type)
    {
        return Commission::where('scheme_id', $post->scheme_id)->get()->toJson();
    }

    public function getPackageCommission(Request $post , $type)
    {
        return Packagecommission::where('scheme_id', $post->scheme_id)->get()->toJson();
    }

    public function mycommission(Type $var = null)
    {
        # code...
    }
    public function companydata(Request $post){
        $data = Companydata::query()->leftJoin('companies','companies.id','=','companydatas.company_id')->select('companydatas.id','companydatas.title','companydatas.slug','companydatas.description','companydatas.type','companies.companyname');
        $data->orderBy('companydatas.id','desc');
        $data->groupBy('companydatas.id','companies.companyname');
        $result['data'] = $data->get();
        return response()->json($result);
    }
}