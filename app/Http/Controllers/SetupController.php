<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fundbank;
use App\Models\Api;
use App\Models\Provider;
use App\Models\PortalSetting;
use App\Models\Complaintsubject;
use App\Models\Link;
use App\Models\Acquirer;
use App\Models\Acquirerfield;

use App\Helpers\Permission;
use Illuminate\Validation\Rule;

class SetupController extends Controller
{
    public function index($type)
    {
        switch ($type) {
            case 'api':
                $permission = "setup_api";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                $data['apis'] = Api::whereIn('type', ['recharge', 'bill', 'pancard', 'money','fund'])->where('status', '1')->get(['id', 'product']);
                break;
            
            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
                $data['settlementtype'] = PortalSetting::where('code', 'settlementtype')->first();
                $data['banksettlementtype'] = PortalSetting::where('code', 'banksettlementtype')->first();
                $data['otplogin'] = PortalSetting::where('code', 'otplogin')->first();
                $data['otpsendmailid']   = PortalSetting::where('code', 'otpsendmailid')->first();
                $data['otpsendmailname'] = PortalSetting::where('code', 'otpsendmailname')->first();
                $data['bcid']   = \App\Models\PortalSetting::where('code', 'bcid')->first();
                $data['cpid']   = \App\Models\PortalSetting::where('code', 'cpid')->first();
                $data['transactioncode']   = \App\Models\PortalSetting::where('code', 'transactioncode')->first();
                $data['mainlockedamount']   = \App\Models\PortalSetting::where('code', 'mainlockedamount')->first();
                $data['aepslockedamount']   = \App\Models\PortalSetting::where('code', 'aepslockedamount')->first();
                $data['settlementcharge']   = \App\Models\PortalSetting::where('code', 'settlementcharge')->first();
                $data['impschargeupto25']   = \App\Models\PortalSetting::where('code', 'impschargeupto25')->first();
                $data['impschargeabove25']   = \App\Models\PortalSetting::where('code', 'impschargeabove25')->first();
                $data['aepsslabtime']   = \App\Models\PortalSetting::where('code', 'aepsslabtime')->first();
                $data['bankpayoutapi']  = \App\Models\PortalSetting::where('code', 'bankpayoutapi')->first();
                $permission = "portal_setting";
                break;

            case 'links':
                $permission = "setup_links";
                break;
            case 'acquirer':
                $permission ="";
                break;
            default:
                abort(404);
                break;
        }

        if (!Permission::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;

        return view("setup.".$type)->with($data);
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'api':
                $permission = "setup_api";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                break;

            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
                $permission = "portal_setting";
                break;

            case 'links':
                $permission = "setup_links";
                break;
        }

        if (isset($permission) && !Permission::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'bank':
                $rules = array(
                    'name'    => 'sometimes|required',
                    'account'    => 'sometimes|required|numeric|unique:fundbanks,account'.($post->id != "new" ? ",".$post->id : ''),
                    'ifsc'    => 'sometimes|required',
                    'branch'    => 'sometimes|required'  
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Fundbank::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'api':
                $rules = array(
                    'product'    => 'sometimes|required',
                    'name'    => 'sometimes|required',
                    'code'    => 'sometimes|required|unique:apis,code'.($post->id != "new" ? ",".$post->id : ''),
                    'type' => ['sometimes', 'required', Rule::In(['recharge', 'bill', 'money', 'pancard', 'fund'])],
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Api::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'operator':

                $rules = array(
                    'name'    => 'sometimes|required',
                    'recharge1'    => 'sometimes|required',
                    'recharge2'    => 'sometimes|required',
                    'type' => ['sometimes', 'required', Rule::In(['mobile','dth','electricity','pancard','dmt','aeps','fund','nsdlpan'])],
                    'api_id'    => 'sometimes|required|numeric',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Provider::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'complaintsub':
                $rules = array(
                    'subject'    => 'sometimes|required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Complaintsubject::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'portalsetting':
                $rules = array(
                    'value'    => 'required',
                    'name'     => 'required',
                    'code'     => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = PortalSetting::updateOrCreate(['code'=> $post->code], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'links':
                $rules = array(
                    'name'    => 'required',
                    'value'    => 'required|url',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Link::updateOrCreate(['id'=> $post->id], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'acquirer':
                $rules = array(
                    'acquirer_name' => 'sometimes|required',
                    'api_endpoint' => 'sometimes|required',
                    'acquirer_slug' => 'sometimes|required'
                );
                $validator = \Validator::make($post->all(),$rules);
                if($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()],422);
                }
                // post Data
                $fildArr=array();
                if(!empty($post['test_public_key'])){
                    $fildArr['test_public_key']= $post['test_public_key'];
                }
                if(!empty($post['test_secret_key'])){
                    $fildArr['test_secret_key']= $post['test_secret_key'];
                }
                if(!empty($post['live_public_key'])){
                    $fildArr['live_public_key']= $post['live_public_key'];
                }
                if(!empty($post['live_secret_key'])){
                    $fildArr['live_secret_key']= $post['live_secret_key'];
                }
                if(!empty($post['return_url'])){
                    $fildArr['return_url']= $post['return_url'];
                }
                if(!empty($post['webhook_url'])){
                    $fildArr['webhook_url']= $post['webhook_url'];
                }
                if(!empty($post['success_url'])){
                    $fildArr['success_url']= $post['success_url'];
                }
                if(!empty($post['failur_url'])){
                    $fildArr['failur_url']= $post['failur_url'];
                }
                if(!empty($post['public_key'])){
                    $fildArr['public_key']= $post['public_key'];
                }
                if(!empty($post['terno'])){
                    $fildArr['terno']= $post['terno'];
                }
                if(!empty($post['token'])){
                    $fildArr['token']= $post['token'];
                }
                if(!empty($post['authorization'])){
                    $fildArr['authorization']= $post['authorization'];
                }
                $jsonFildArr = json_encode($fildArr);
                if(!empty($post['acquirer_name'])){
                $acquArr['acquirer_name'] = $post['acquirer_name'];
                }
                if(!empty($post['api_endpoint'])){
                $acquArr['api_endpoint'] =$post['api_endpoint'];
                }
                if(!empty($post['acquirer_slug'])){
                    $acquArr['acquirer_slug'] =$post['acquirer_slug'];
                }
                if(!empty($post['is_active'])){
                $acquArr['is_active'] =$post['is_active'];
                }
                $acquArr['fields'] =$jsonFildArr;
                
                $action = Acquirer::updateOrCreate(['acquirer_id'=> $post->acquirer_id], $acquArr);
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'acquirer_field':
                $rules = array(
                    'field_name' => 'sometimes|required',
                    'field_label' => 'sometimes|required',
                    'field_type' => 'sometimes|required'
                );
    
                $validator = \Validator::make($post->all(),$rules);
                if($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()],422);
                }
                $action = Acquirerfield::updateOrCreate(['field_id'=> $post->field_id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            default:
                # code...
                break;
        }
    }
    public function getAcquirerFields(Request $post){
            $acquirer_id = $post->acquirer_id;
            // $data['acquirer_fields'] =  Acquirerfield::where('acquirer_id', '=',$acquirer_id)
            //                              ->select("field_id", "field_name", "field_label", "field_type","is_active")
            //                              ->get();
            // $data['acquirer_id'] = $acquirer_id;
            // return response()->json(view('member.acquirer_fields')->with($data)->render());

            $acquirerData = Acquirer::where('acquirer_id',$acquirer_id)
                            ->select("fields")
                            ->first();
            $jsonResponse = $acquirerData->fields;
            return response()->json(['data' => $jsonResponse], 200);
    }

    public function acquirerDelete(Request $post){
        $delete = Acquirer::where('acquirer_id', $post->id)->delete();
        return response()->json(['status'=>$delete], 200);
    }
    public function acquirerFieldDelete(Request $post){
        $delete = Acquirerfield::where('field_id', $post->id)->delete();
        return response()->json(['status'=>$delete], 200);
    }
}
