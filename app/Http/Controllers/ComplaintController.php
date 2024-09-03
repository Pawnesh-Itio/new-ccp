<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Complaint;
use App\Models\Help_box;
class ComplaintController extends Controller
{
    /**
	 * This method is used to diplay complain raised by user
	 * @return mixed
	 */
    public function index()
    {
        $data =[];
        $help_box = Help_box::where("type","statementComplaint")->select("description", "slug", "type")->get();
        foreach($help_box as $hb){
           $data[$hb->slug]['description'] = $hb->description;
           $data[$hb->slug]['type'] = $hb->type;
        }
        return view('complaint')->with($data);
    }
    /**
	 * This method is used to save complian
	 * @param Request $request
	 * @return json it returns json data
	 */
    public function store(Request $post)
    {
        $rules = array(
            'query'    => 'sometimes|required',
            'subject'    => 'sometimes|required'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        if($post->id == "new"){
            $post['user_id'] = \Auth::id();
        }else{
            $post['resolve_id'] = \Auth::id();
        }

        $action = Complaint::updateOrCreate(['id'=> $post->id], $post->all());
        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }
}
