<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Iserveuagent;
use App\Models\Cosmosmerchant;
use App\Models\Aepsreport;
use App\Models\Api;
use App\Models\Report;
use App\Models\Circle;
use App\Models\Help_box;
use App\Models\Role;
use App\Models\Company;
use App\Models\Merchantkey;
use App\Models\Currancy;
use Dompdf\Dompdf;
use Carbon\Carbon;
use App\Helpers\Permission ;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return redirect('dashboard');
    }
    public function dashboard(){
     if(!session('parentData')){
     session(['parentData' => Permission::getParents(\Auth::id())]);
      }
     $data['user'] = \Auth::user();
     $help_box = Help_box::where("type","dashboard")->select("description", "slug", "type")->get();
     foreach($help_box as $hb){
        $data[$hb->slug]['description'] = $hb->description;
        $data[$hb->slug]['type'] = $hb->type;
     }
     $data['currencyDetails'] = Permission::getCurrency(\Auth::id());
     $data['currencies'] = Currancy::all();
        return view('dashboard')->with($data);
    }
    // test
    public function test(){
        $data['state'] = Circle::all();
        $data['user'] = \Auth::user();
        return view('test-form')->with($data);
    }
    public function testData(Request $post){
        return response()->json(['status' => "success"], 200);
    }
    // test
// Latest transaction ajax call function.
public function latesttransaction(){
if(!session('latesttransaction')){
     $table = "reports.";
     $query = \DB::table('reports');
     $query->Join('users as apiuser', 'apiuser.id', '=', 'reports.user_id');
       if(Permission::hasRole(['admin'])){
          $query->whereNotIn($table.'user_id',array(1))->whereIn($table.'product',array('payout','upi', 'openacquiring','Stripe','GTW'))->whereIn($table.'status',['success', 'complete', 'partial capture']);
        }else{
          $query->where($table.'user_id', \Auth::id())->whereIn($table.'product',array('payout','upi', 'openacquiring','Stripe','GTW'))->whereIn($table.'status',['success', 'complete', 'partial capture']);
        }
     $query->orderBy($table.'id','desc')->take(10);
     $latesttxn = $query->get(['reports.txnid','reports.mytxnid','reports.id','reports.amount','reports.status','reports.product','reports.created_at','apiuser.name','apiuser.mobile']);
     echo json_encode($latesttxn);
     session(['latesttransaction'=> $latesttxn]);
    }else{
        echo json_encode(session('latesttransaction'));
    }
}
// End.
// dashboard datacount starts.
public function datacount(){
    if(!session('datacount')){
$data['totalcollection'] = round(Report::whereIn('user_id', session('parentData'))->where('aepstype', 'card')->whereIn('status', ['success', 'complete', 'partial capture'])->sum('amount'),2);
$data['todaycollection'] = round(Report::whereIn('user_id', session('parentData'))->where('aepstype','card')->whereIn('status', ['success', 'complete', 'partial capture'])->whereDate('created_at', date('Y-m-d'))->sum('amount'),2);
$data['totalpayout'] = round(Report::whereIn('user_id', session('parentData'))->where(['product'=>'payout','status'=>'success'])->sum('amount'),2);
$data['totalusers']= User::whereNotIn('id',array(1))->where(['status'=>'active','role_id'=>'6'])->count();
echo json_encode($data);
session(['datacount'=> $data]);
    }else{
        echo json_encode(session('datacount'));
        // echo "session created";
    }
}
//End.
// top user.
public function topuser(){
    if(!session('topuser')){
    $activeusers= User::whereNotIn('id',array(1))->where(['status'=>'active','role_id'=>'6'])->orderBy('id','desc') ->offset(0)->limit(5)->get();
    echo $activeusers;
    session(['topuser'=>$activeusers]);
    }else{
        echo json_encode(session('topuser'));
        // echo "session created";
    }

}
// End.
public function getbalance()
{
        $data['apibalance'] = 0;
        if(is_array(session('parentData'))){
            $sessionParent = session('parentData');
        }else{
            $sessionParent = json_decode(session('parentData'),true);
        }
        $data['downlinebalance'] = round(User::whereIn('id', array_diff($sessionParent, array(\Auth::id())))->sum('mainwallet'), 2);
        $data['mainwallet']      = \Auth::user()->mainwallet;
        $data['microatmbalance'] = \Auth::user()->microatmbalance;
        $data['lockedamount']    = \Auth::user()->lockedamount;
        //dd($data);

        return response()->json($data);
}
public function DataSession(REQUEST $post){
    $data = $post['data'];
    if($data=="refresh"){
     session()->forget('latesttransaction');
     session()->forget('datacount');
     session()->forget('topuser');
     session()->forget('parentData');
     return redirect('/dashboard')->with('success','Data refreshed succesfully');
    }else{
     return redirect('/dashboard')->with('error','Somthing went wrong ! Please try again');
    }
 }
}