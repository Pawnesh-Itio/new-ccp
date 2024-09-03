<?php

namespace App\Http\Controllers;
use App\Helpers\Permission;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Apitoken;
use App\Models\User;
use App\Models\Merchantkey;
use App\Models\Help_box;

class ApiController extends Controller
{

    /**
	 * This method is used display list of API according type types are as document, operator & operator
     * URL of this action as  https://lpg.CCP.com/developer/api/setting
	 * @param string $type
	 * @return mixed return view name & data
	 */
    public function index($type)
    {
        $data['type'] = $type;
        $companyId = \Auth::user()->company_id;
        $data['kyc_status'] = \Auth::user()->kyc;
        $data['businessdata'] = Merchantkey::where('company_id',$companyId)->first();
        $help_box = Help_box::where("type","setting")->select("description", "slug", "type")->get();
        foreach($help_box as $hb){
           $data[$hb->slug]['description'] = $hb->description;
           $data[$hb->slug]['type'] = $hb->type;
        }
        return view("apitools.".$type)->with($data);
    }
    /**
	 * This method is used to update data according to type for example API Token data, Callback data etc
	 * @param Request $post
	 * @return json it returns json data
	 */
    public function update(Request $post)
    {
        if (Permission::hasRole(['apiuser','reseller']) && (!Permission::can("apiuser_acc_manager") || !Permission::can("api_document"))) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->type) {
            case 'apitoken':
                $rules = array(
                    'ip'  => 'required|ip'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                do {
                    $post['token'] = str::random(30);
                } while (Apitoken::where("token", "=", $post->token)->first() instanceof Apitoken);

                $post['user_id'] = \Auth::id();
                $action = Apitoken::updateOrCreate(['id'=> $post->id], $post->all());
                break;
            
            case 'callback':
                $rules = array(
                    'id'  => 'required',
                    'callbackurl'  => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $callback['product'] = "test";
                $callback['status']  = "test";
                $callback['refno']   = "test";
                $callback['txnid']   = "test";
                $query = http_build_query($callback);
                $url = $post->callbackurl."?".$query;

                $result = Permission::curl($url, "GET", "", [], "no", "", "");
                if($result['code'] != "200"){
                    return response()->json(['status' => "Callback user is not valid"], 400);
                }
                $action = User::where('id', $post->id)->update(['callbackurl'=> $post->callbackurl]);
                break;

            case 'companycode':
                $rules = array(
                    'id'  => 'required',
                    'companycode'  => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                
                $action = User::where('id', $post->id)->update(['companycode'=> $post->companycode]);
                break;
        }

        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }
    /**
	 * This method is used to delete API token
	 * @param Request $request
	 * @return json it returns json data
	 */
    public function tokenDelete(Request $post)
    {
        $delete = Apitoken::where('id', $post->id)
                           ->where('user_id', \Auth::id())
                           ->get()
                           ->each
                           ->delete();
        if($delete){
        return response()->json(['status'=>1], 200);
        }
    }
}
