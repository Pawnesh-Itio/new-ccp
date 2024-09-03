<?php
namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use App\Helpers\Permission ;
use App\Models\Utireport;
use Carbon\Carbon;
use App\Models\Rechargereport;
use App\Models\Help_box;
use App\Models\Billpayreport;
use App\Models\Moneyreport;
use App\Models\User;
use App\Models\AccountStatement;
use App\Models\Cosmosmerchant;
use App\Models\Fundreport;
use App\Models\Circle;
use App\Models\Country;
use App\Models\Nsdlpan;
use App\Models\Openacquiring;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Style\Table as TableStyle;

class StatementController extends Controller
{
    public function index($type,$id=0,$status="pending")
    {
        if($id != 0){
            $agentfilter = "hide";
        }else{
            $agentfilter = "";
        }
        $data = ['id' => $id, 'agentfilter' => $agentfilter];
        if($id != 0){

            $user = User::where('id', $id)->first();
            if (!$user) {
                abort('404');
            }
        }
        switch ($type) {
            case 'account':
                $help_box = Help_box::where("type","statementAccount")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                if($id == 0){
                    $user_id = \Auth::id();
                    $permission = "account_statement";
                }else{
                    $user_id =$id;
                    $permission = "member_account_statement_view";
                }
                $data['currencyDetails'] = Permission::getCurrency($user_id); 
                break;

            case 'cosmosonboarding':
            $data['iscagent'] = Cosmosmerchant::where('user_id', \Auth::id())->first();
            $data['state'] = Circle::all();
            
                if($id == 0){
                    
                    $permission = "account_statement";
                }else{
                    $permission = "member_account_statement_view";
                }
                break;

            case 'cosmosid':
                $help_box = Help_box::where("type","agentCosmos")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                if($id == 0){
                    $permission = "upiid_statement";
                }else{
                    $permission = "member_upiid_statement_view";
                }
                break;

            case 'openacquiringid':
                $help_box = Help_box::where("type","agentOpenacquiring")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                 if($id == 0){
                    $permission = "upiid_statement";
                }else{
                    $permission = "member_upiid_statement_view";
                }
            break;

            case 'iserveuid':
                if($id == 0){
                    $permission = "upiid_statement";
                }else{
                    $permission = "member_upiid_statement_view";
                }
                break;

            case 'utiid':
                if($id == 0){
                    $permission = "utiid_statement";
                }else{
                    $permission = "member_utiid_statement_view";
                }
                break;

            case 'utipancard':
                if($id == 0){
                    $permission = "utipancard_statement";
                }else{
                    $permission = "member_utipancard_statement_view";
                }
                break;

            case 'billpay':
                if($id == 0){
                    $permission = "billpayment_statement";
                }else{
                    $permission = "member_billpayment_statement_view";
                }
                break;

            case 'payouts':
                $help_box = Help_box::where("type","statementPayout")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                if($id == 0){
                    $permission = "upiid_statement";
                }else{
                    $permission = "upiid_statement";
                }
                break;

            case 'recharge':
                if($id == 0){
                    $permission = "recharge_statement";
                }else{
                    $permission = "member_recharge_statement_view";
                }
                break;

            case 'money':
                if($id == 0){
                    $permission = "money_statement";
                }else{
                    $permission = "member_money_statement_view";
                }
                break;

            case 'aeps':
                if($id == 0){
                    $permission = "aeps_statement";
                }else{
                    $permission = "member_aeps_statement_view";
                }
                break;

             case 'upi':
                $help_box = Help_box::where("type","statementPayment")->select("description", "slug", "type")->get();
                foreach($help_box as $hb){
                   $data[$hb->slug]['description'] = $hb->description;
                   $data[$hb->slug]['type'] = $hb->type;
                }
                if($id == 0){
                    $user_id = \Auth::id();
                    $permission = "upiid_statement";
                }else{
                    $user_id = $id;
                    $permission = "upiid_statement";
                }
                $data['currencyDetails'] = Permission::getCurrency($user_id);
                break; 

            case 'upiold':
                if($id == 0){
                    $permission = "aeps_statement";
                }else{
                    $permission = "member_aeps_statement_view";
                }
                break;   

            case 'aepsid':
                if($id == 0){
                    $permission = "aepsid_statement";
                }else{
                    $permission = "member_aepsid_statement";
                }
                $data['users'] = User::whereIn('id', session('parentData'))->get(['id', 'name', 'mobile']);
                break;

            case 'awallet':
                if($id == 0){
                    $permission = "awallet_statement";
                }else{
                    $permission = "member_awallet_statement_view";
                }
                break;

            case 'upiwallet':
                if($id == 0){
                    $permission = "awallet_statement";
                }else{
                    $permission = "member_awallet_statement_view";
                }
                break;   
            case 'matm':  

            case 'matmall': 
                if($id == 0){   
                    $permission = "matm_statement"; 
                }else{  
                    $permission = "member_matm_statement_view"; 
                }   
                break; 

            case 'matmwallet':
                if($id == 0){
                    $permission = "matmwallet_statement";
                }else{
                    $permission = "member_matmwallet_statement_view";
                }
                break;

            case 'upiid':
                if($id == 0){
                    $permission = "upiid_statement";
                }else{
                    $permission = "member_upiid_statement_view";
                }
                break;

            case 'contact':
                if($id == 0){
                    $permission = "contact_statement";
                }else{
                    $permission = "member_contact_statement_view";
                }
                break;
                
            default:
                abort(404);
                break;
        }
        if (Permission::can($permission)!=true) {
            abort(403);
        }
        return view('statement/'.$type)->with($data);
    }

    public function export(Request $post, $type)

    {

        

        //dd($post->all());

       ini_set('memory_limit', '-1');

        $parentData = session('parentData');

        if(isset($post->status)){

            if($post->status == 'null'){

                $post['status'] = '';

            }

        }

        switch ($type) {

            case 'recharge': 

            case 'billpay':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'rtype', 'main')->where($table.'product', $type);



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;



            case 'pancard':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'rtype', 'main')->where($table.'product', "utipancard");



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;



            case 'money':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'rtype', 'main')->where($table.'product', "dmt");



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;



            case 'aeps':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'aepstype', 'CW')->where($table.'rtype', 'main');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;

                

           
case 'open_acquiring':

                $table = "reports.";

                $query = \DB::table('reports')->whereIn($table.'product', ['openacquiring','Stripe','GTW']);

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{
                    if(Permission::hasNotRole('admin')){
                        $query->whereIn($table.'user_id', $parentData);
                    }
                }

                break;   

            case 'upi':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'aepstype', 'UPI')->where($table.'rtype', 'main');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;   



            case 'payout':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'product', 'payout')->where($table.'rtype', 'main');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'reports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'reports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'reports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'reports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'reports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;  

                

            case 'upiold':

              

                $table = "aepsreports.";

                $query = \DB::table('aepsreports')->where($table.'aepstype', 'UPI');

              

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'aepsreports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'aepsreports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'aepsreports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'aepsreports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'aepsreports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'aepsreports.provider_id');

             

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                    

                }

                break;       



            case 'matm':

                $table = "microatmreports.";

                $query = \DB::table('microatmreports')->where($table.'aepstype', 'CW')->where($table.'rtype', 'main');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'microatmreports.user_id');

                $query->leftJoin('users as distributor', 'distributor.id', '=', 'microatmreports.disid');

                $query->leftJoin('users as md', 'md.id', '=', 'microatmreports.mdid');

                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'microatmreports.wid');

                $query->leftJoin('apis', 'apis.id', '=', 'microatmreports.api_id');

                $query->leftJoin('providers', 'providers.id', '=', 'microatmreports.provider_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

                break;



            case 'whitelable':

            case 'apiuser':

            case 'reseller':

            case 'md':

            case 'distributor':

            case 'retailer':

                if(isset($post->status)){

                    if($post->status == 'null'){

                        $post['status'] = '';

                    }

                }

                $table = "users.";

                $query = \DB::table('users');

                $query->leftJoin('companies', 'companies.id', '=', 'users.company_id');

                $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');

                $query->leftJoin('users as parents', 'parents.id', '=', 'users.parent_id');

                $query->where('roles.slug', '=', $type);

            break;



            case 'fundrequest':

                $table = "fundreports.";

                $query = \DB::table('fundreports');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'fundreports.user_id');

                $query->leftJoin('users as sender', 'sender.id', '=', 'fundreports.credited_by');

                $query->leftJoin('fundbanks', 'fundbanks.id', '=', 'fundreports.fundbank_id');



                $query->where($table.'credited_by', \Auth::id());

                break;



            case 'fund':

                $table = "reports.";

                $query = \DB::table('reports')->where($table.'api_id', '2');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                $query->leftJoin('users as sender', 'sender.id', '=', 'reports.credit_by');



                $query->where($table.'user_id', \Auth::id());

                break;



            case 'aepsfundrequestview':

                if(Permission::hasNotRole('admin')){

                    return redirect()->back();

                }

                $table = "aepsfundrequests.";

                $query = \DB::table('aepsfundrequests')->where($table.'status', 'pending');



                $query->leftJoin('users', 'users.id', '=', 'aepsfundrequests.user_id');

                break;



            case 'aepsfundrequest':

            case 'aepsfundrequestviewall':

                $table = "aepsfundrequests.";

                $query = \DB::table('aepsfundrequests');



                $query->leftJoin('users', 'users.id', '=', 'aepsfundrequests.user_id');

                if($type == "aepsfundrequest"){

                    $query->where($table.'user_id', \Auth::id());

                }else{

                    if(Permission::hasNotRole('admin')){

                        return redirect()->back();

                    }

                }

                break;



            case 'microatmfundrequestview':

                if(Permission::hasNotRole('admin')){

                    return redirect()->back();

                }

                $table = "microatmfundrequests.";

                $query = \DB::table('microatmfundrequests')->where($table.'status', 'pending');



                $query->leftJoin('users', 'users.id', '=', 'microatmfundrequests.user_id');

                break;



            case 'microatmfundrequest':

            case 'microatmfundrequestviewall':

                $table = "microatmfundrequests.";

                $query = \DB::table('microatmfundrequests');



                $query->leftJoin('users', 'users.id', '=', 'microatmfundrequests.user_id');

                if($type == "matmfundrequest"){

                    $query->where($table.'user_id', \Auth::id());

                }else{

                    if(Permission::hasNotRole('admin')){

                        return redirect()->back();

                    }

                }

                break;



            case 'aepsagentstatement':

                $query = \DB::table('mahaagents');

                $table = "mahaagents.";



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

            break;



            

            case 'utiid':

                $query = \DB::table('utiids');

                $table = "utiids.";

                $query->leftJoin('users', 'users.id', '=', 'utiids.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

            break;



            case 'iserveuid':

                $query = \DB::table('iserveuagents');

                $table = "iserveuagents.";

                $query->leftJoin('users', 'users.id', '=', 'iserveuagents.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

            break;



           case 'cosmosid':

                $query = \DB::table('cosmosmerchants');

                $table = "cosmosmerchants.";

                $query->leftJoin('users', 'users.id', '=', 'cosmosmerchants.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->whereIn($table.'user_id', $parentData);

                }

              break;



            case 'wallet':

                $table = "reports.";

                $query = \DB::table('reports');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0 && $post->agent !='undefined'){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->where($table.'user_id', \Auth::id());

                }

                break;



            case 'awallet':

                $table = "reports.";

                $query = \DB::table('reports');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->where($table.'user_id', \Auth::id());

                }

                break;

                

            case 'upiwallet':

                $table = "reports.";

                $query = \DB::table('reports');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->where($table.'user_id', \Auth::id());

                }

                break;    



            case 'matmwallet':

                $table = "microatmreports.";

                $query = \DB::table('microatmreports');



                $query->leftJoin('users as retailer', 'retailer.id', '=', 'microatmreports.user_id');



                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){

                    $query->where($table.'user_id', $post->agent);

                }else{

                    $query->where($table.'user_id', \Auth::id());

                }

                break;

            

            default:

                # code...

                break;

        }



        if((isset($post->fromdate) && !empty($post->fromdate)) 

            && (isset($post->todate) && !empty($post->todate))){

            if($post->fromdate == $post->todate){

                $query->whereDate($table.'created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));

            }else{

                $query->whereBetween($table.'created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);

            }

        }elseif (isset($post->fromdate) && !empty($post->fromdate)) {

            if(!in_array($type, ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'reseller'])){

                $query->whereDate($table.'created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));

            }

        }else{

            if(!in_array($type, ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'reseller'])){

                $query->whereDate($table.'created_at','=', date('Y-m-d'));

            }

        }



            

        if(isset($post->status) && $post->status != '' && $post->status != 'undefined'){

           if($post->status != null){

                switch ($post->type) {

                    default:

                        $query->where($table.'status', $post->status);

                    break;

                }

           }



        }



        switch ($type) {

            case 'recharge':

            case 'billpay':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);

                break;



            case 'pancard':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.option1', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'reports.user_id']);

                break;



            case 'money':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.option1', 'reports.option2', 'reports.option3', 'reports.option4', 'reports.mobile', 'reports.option1', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);

                break;



            case 'aeps':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.mobile', 'reports.aadhar', 'reports.amount', 'reports.refno', 'reports.charge', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);

                break;

                

            case 'upi':
case 'open_acquiring':
            case 'payout':

                $datas = $query->get(['reports.id','reports.created_at', 'reports.option1','reports.option2','reports.option3','reports.option4','reports.number','reports.profit','reports.txnid', 'reports.created_at', 'reports.mobile', 'reports.aadhar', 'reports.amount', 'reports.refno', 'reports.charge','reports.gst', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);

                break;      

                

            case 'upiold':

                $datas = $query->get(['aepsreports.id', 'aepsreports.created_at', 'aepsreports.mobile', 'aepsreports.aadhar', 'aepsreports.amount', 'aepsreports.refno', 'aepsreports.charge', 'aepsreports.status', 'aepsreports.wid', 'aepsreports.wprofit', 'aepsreports.mdid', 'aepsreports.mdprofit', 'aepsreports.disid', 'aepsreports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'aepsreports.user_id']);

                break;      



            case 'matm':

                $datas = $query->get(['microatmreports.id', 'microatmreports.created_at', 'microatmreports.mobile', 'microatmreports.aadhar', 'microatmreports.amount', 'microatmreports.refno', 'microatmreports.charge', 'microatmreports.status', 'microatmreports.wid', 'microatmreports.wprofit', 'microatmreports.mdid', 'microatmreports.mdprofit', 'microatmreports.disid', 'microatmreports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'microatmreports.user_id']);

                break;



            case 'whitelable':

            case 'md':

            case 'apiuser':

            case 'reseller':

            case 'distributor':

            case 'retailer':

                $datas = $query->get(['users.id','users.created_at','users.name','users.email','users.mobile','users.role_id','users.company_id','users.mainwallet','users.status','users.address','users.state','users.city','users.pincode','users.gstin','users.pancard','users.aadharcard','users.bank','users.ifsc','users.account','companies.companyname as companyname','roles.name as rolename','parents.name as parentname','parents.mobile as parentmobile']);

            break;



            case 'fundrequest':

                $datas = $query->get(['fundreports.id', 'fundreports.created_at', 'fundreports.paymode', 'fundreports.amount', 'fundreports.amount', 'fundreports.ref_no', 'fundreports.paydate', 'fundreports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'sender.name as sendername', 'sender.mobile as sendermobile', 'fundbanks.name as fundbank']);

                break;



            case 'fund':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.amount', 'reports.refno', 'reports.product', 'reports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'sender.name as sendername', 'sender.mobile as sendermobile', 'reports.user_id', 'reports.credit_by', 'reports.remark']);

                break;



            case 'aepsfundrequestview':

            case 'aepsfundrequest':

            case 'aepsfundrequestviewall':

                $datas = $query->get(['aepsfundrequests.id', 'aepsfundrequests.created_at', 'aepsfundrequests.account', 'aepsfundrequests.bank', 'aepsfundrequests.ifsc', 'aepsfundrequests.amount', 'aepsfundrequests.type', 'aepsfundrequests.status', 'aepsfundrequests.remark', 'users.name as username', 'users.mobile as usermobile']);

                break;



            case 'microatmfundrequestview':

            case 'microatmfundrequest':

            case 'microatmfundrequestviewall':

                $datas = $query->get(['microatmfundrequests.id', 'microatmfundrequests.created_at', 'microatmfundrequests.account', 'microatmfundrequests.bank', 'microatmfundrequests.ifsc', 'microatmfundrequests.amount', 'microatmfundrequests.type', 'microatmfundrequests.status', 'microatmfundrequests.remark', 'users.name as username', 'users.mobile as usermobile']);

                break;



            case 'aepsagentstatement':

                $datas = $query->get(['mahaagents.id', 'mahaagents.created_at', 'mahaagents.bc_id', 'mahaagents.bc_f_name', 'mahaagents.bc_l_name', 'mahaagents.bc_l_name', 'mahaagents.emailid', 'mahaagents.phone1', 'mahaagents.phone2']);

                break;



            case 'utiid':

                $datas = $query->get(['utiids.id', 'utiids.created_at', 'utiids.vleid', 'utiids.status', 'utiids.name', 'utiids.location', 'utiids.contact_person', 'utiids.pincode', 'utiids.state', 'utiids.state', 'utiids.email', 'utiids.mobile', 'utiids.remark', 'utiids.user_id', 'users.name as username', 'users.mobile as usermobile']);

                break;



            case 'iserveuid':

                $datas = $query->get(['iserveuagents.id','iserveuagents.firstName','iserveuagents.lastName','iserveuagents.pan','iserveuagents.shopName','iserveuagents.merchantVirtualAddress','iserveuagents.merchantMobileNumber','iserveuagents.requestingUserName','iserveuagents.address','iserveuagents.shopstate','iserveuagents.shopcity','iserveuagents.shopdistrict','iserveuagents.shoparea','iserveuagents.shopaddress','iserveuagents.user_id','iserveuagents.status','iserveuagents.created_at', 'users.name as username']);

               break;



           case 'cosmosid':

            $datas = $query->get();

           break;

               

               

               



            case 'wallet':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge','reports.gst', 'reports.profit', 'reports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'reports.user_id', 'reports.product', 'reports.rtype', 'reports.trans_type', 'reports.balance']);

                break;



            case 'awallet':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.payid', 'reports.remark', 'reports.aadhar', 'reports.mobile', 'reports.refno', 'retailer.name as username', 'retailer.mobile as usermobile', 'reports.user_id', 'reports.transtype', 'reports.rtype', 'reports.status', 'reports.balance', 'reports.amount', 'reports.charge', 'reports.type']);

                break;

            

            case 'upiwallet':

                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.payid', 'reports.remark', 'reports.aadhar', 'reports.mobile', 'reports.refno', 'retailer.name as username', 'retailer.mobile as usermobile', 'reports.user_id', 'reports.transtype', 'reports.rtype', 'reports.status', 'reports.balance', 'reports.amount', 'reports.charge', 'reports.type']);

                break;    



            case 'matmwallet':

                $datas = $query->get(['microatmreports.id', 'microatmreports.created_at', 'microatmreports.payid', 'microatmreports.remark', 'microatmreports.aadhar', 'microatmreports.mobile', 'microatmreports.refno', 'retailer.name as username', 'retailer.mobile as usermobile', 'microatmreports.user_id', 'microatmreports.transtype', 'microatmreports.rtype', 'microatmreports.status', 'microatmreports.balance', 'microatmreports.amount', 'microatmreports.charge', 'microatmreports.type']);

                break;

            

            default:

                # code...

                break;

        }

        //dd($datas);

        $excelData = array();

        switch ($type) {

            case 'recharge':

            case 'billpay':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Number',' Amount', 'Charge', 'Profit', 'Ref No', 'Status', "Member Details"];



                if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Distributor Details", "Distributor Profit"]);

                }



                if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Md Details", "Md Profit"]);

                }



                if(Permission::hasRole(['whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Whitelable Details", "Whitelable Profit"]);

                }



                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['apitype'] = $record->apiname;

                    $data['provider'] = $record->providername;

                    $data['number'] = $record->number;

                    $data['amount'] = $record->amount;

                    $data['charge'] = $record->charge;

                    $data['profit'] = $record->profit;

                    $data['refno'] = $record->refno;

                    $data['status'] = $record->status;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";



                    if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                        $data['disdetails'] = $record->disname." (".$record->dismobile.")";

                        $data['disprofit'] = $record->disprofit;

                    }



                    if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                        $data['mddetails'] = $record->mdname." (".$record->mdmobile.")";

                        $data['mdprofit'] = $record->mdprofit;

                    }



                    if(Permission::hasRole(['whitelable', 'admin'])){

                        $data['wdetails'] = $record->wname." (".$record->wmobile.")";

                        $data['wprofit'] = $record->wprofit;

                    }

                    array_push($excelData, $data);

                }

                break;



            case 'pancard':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Transaction Id', 'Date','Api Name', 'Vle Id', 'No of Token', ' Amount', 'Charge', 'Profit', 'Ref No', 'Status', "Member Details"];



                if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Distributor Details", "Distributor Profit"]);

                }



                if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Md Details", "Md Profit"]);

                }



                if(Permission::hasRole(['whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Whitelable Details", "Whitelable Profit"]);

                }



                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['apitype'] = $record->apiname;

                    $data['number'] = $record->number;

                    $data['option1'] = $record->option1;

                    $data['amount'] = $record->amount;

                    $data['charge'] = $record->charge;

                    $data['profit'] = $record->profit;

                    $data['refno'] = $record->refno;

                    $data['status'] = $record->status;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";



                    if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                        $data['disdetails'] = $record->disname." (".$record->dismobile.")";

                        $data['disprofit'] = $record->disprofit;

                    }



                    if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                        $data['mddetails'] = $record->mdname." (".$record->mdmobile.")";

                        $data['mdprofit'] = $record->mdprofit;

                    }



                    if(Permission::hasRole(['whitelable', 'admin'])){

                        $data['wdetails'] = $record->wname." (".$record->wmobile.")";

                        $data['wprofit'] = $record->wprofit;

                    }

                    array_push($excelData, $data);

                }

                break;



            case 'money':

            case 'upi':
case 'open_acquiring':
            case 'payout':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Transaction Id', 'Date','Api Name', 'Provider',' Amount', 'Charge','GST', 'Profit', 'Order Id', 'Ref No', 'Status', "Member Details"];



                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['apitype'] = $record->apiname;

                    $data['provider'] = $record->providername;



                    $data['amount'] = $record->amount;

                    $data['charge'] = $record->charge;

                    $data['gst'] = $record->gst;

                    $data['profit'] = $record->profit;

                    $data['txnid'] = $record->txnid;

                    $data['refno'] = $record->refno;

                    $data['status'] = $record->status;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    array_push($excelData, $data);

                }

                break;



            case 'aeps':

              

            case 'upiold':      

            case 'matm':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Bc Id', 'Bc Mobile',' Amount', 'Profit', 'Ref No', 'Status', "Member Details"];



                if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Distributor Details", "Distributor Profit"]);

                }



                if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Md Details", "Md Profit"]);

                }



                if(Permission::hasRole(['whitelable', 'admin'])){

                    $titles = array_merge($titles, ["Whitelable Details", "Whitelable Profit"]);

                }



        

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['apitype'] = $record->apiname;

                    $data['provider'] = $record->providername;

                    $data['rname'] = $record->aadhar;

                    $data['rmobile'] = $record->mobile;

                    $data['amount'] = $record->amount;

                    $data['profit'] = $record->charge;

                    $data['refno'] = $record->refno;

                    $data['status'] = $record->status;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";



                    if(Permission::hasRole(['distributor', 'md', 'whitelable', 'admin'])){

                        $data['disdetails'] = $record->disname." (".$record->dismobile.")";

                        $data['disprofit'] = $record->disprofit;

                    }



                    if(Permission::hasRole(['md', 'whitelable', 'admin'])){

                        $data['mddetails'] = $record->mdname." (".$record->mdmobile.")";

                        $data['mdprofit'] = $record->mdprofit;

                    }



                    if(Permission::hasRole(['whitelable', 'admin'])){

                        $data['wdetails'] = $record->wname." (".$record->wmobile.")";

                        $data['wprofit'] = $record->wprofit;

                    }

                    array_push($excelData, $data);

                }

                break;







            case 'whitelable':

            case 'apiuser':

            case 'reseller':

            case 'md':

            case 'distributor':

            case 'retailer':

                                                       



                $name = $type.'report'.date('d_M_Y');

                $titles = ['Id', 'Date' ,'Name', 'Email', 'Mobile', 'Role', 'Parent', 'Company', 'Status' ,'address', 'City', 'State','Pincode','Shopname', 'Gst Tin','Pancard','Aadhar Card', 'Account', 'Bank','Ifsc'];



                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['name'] = $record->name;

                    $data['email'] = $record->email;

                    $data['mobile'] = $record->mobile;

                    $data['role'] = $record->rolename;

                    $data['parents'] = $record->parentname ." (".$record->parentmobile.")";

                    $data['company'] = $record->companyname;

                    $data['status'] = $record->status;

                    $data['address'] = $record->address;

                    $data['city'] = $record->city;

                    $data['state'] = $record->state;

                    $data['pincode'] = $record->pincode;

                    $data['gstin'] = $record->gstin;

                    $data['pancard'] = $record->pancard;

                    $data['aadharcard'] = $record->aadharcard;

                    $data['account'] = $record->account;

                    $data['bank'] = $record->bank;

                    $data['ifsc'] = $record->ifsc;

                    array_push($excelData, $data);

                }

               



            break;



            case 'fundrequest':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Id', 'Date' ,'Paymode', 'Amount', 'Ref No', 'Payment Bank', 'Pay Date', 'Status', 'Requested Via', 'Approved By'];

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['paymode'] = $record->paymode;

                    $data['amount']   = $record->amount;

                    $data['ref_no']  = $record->ref_no;

                    $data['fundbank'] = $record->fundbank;

                    $data['paydate']  = $record->paydate;

                    $data['status'] = $record->status;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    $data['senderdetails'] = $record->sendername." (".$record->sendermobile.")";

                    array_push($excelData, $data);

                }

            break;



            case 'fund':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Order Id', 'Date', 'Payment Type', 'Amount', 'Ref No', 'Status', 'Remarks', 'Requested Via', 'Approved By'];

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['type'] = $record->product;

                    $data['amount'] = $record->amount;

                    $data['bankref'] = $record->refno;

                    $data['status'] = $record->status;

                    $data['remark'] = $record->remark;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    $data['senderdetails'] = $record->sendername." (".$record->sendermobile.")";

                    array_push($excelData, $data);

                }

                break;



            case 'aepsfundrequestview':

            case 'aepsfundrequest':

            case 'aepsfundrequestviewall':

            case 'microatmfundrequestview':

            case 'microatmfundrequest':

            case 'microatmfundrequestviewall':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Order Id', 'Date', 'Payment Mode' ,'Account', 'Bank', 'Ifsc', 'Amount', 'Status', 'Remarks', 'Requested Via'];

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['type'] = $record->type;

                    $data['account'] = $record->account;

                    $data['bank'] = $record->bank;

                    $data['ifsc'] = $record->ifsc;

                    $data['amount'] = $record->amount;

                    $data['status'] = $record->status;

                    $data['remark'] = $record->remark;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    array_push($excelData, $data);

                }

                break;



            case 'aepsagentstatement':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Id', 'Date','BCID' ,'Name', 'Email', 'Phone1', 'Phone2'];

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['bc_id'] = $record->bc_id;

                    $data['name'] = $record->bc_f_name. " ". $record->bc_l_name." ".$record->bc_l_name;

                    $data['email'] = $record->emailid;

                    $data['phone1'] = $record->phone1;

                    $data['phone2'] = $record->phone2;

                    array_push($excelData, $data);

                }

            break;



            case 'utiid':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Id', 'Date','Vle id','Name', 'Email', 'Mobile', 'Location', 'Contact Person', 'Pincode', 'State', 'Status', 'Remark', 'User Details'];

                foreach ($datas as $record) {

                    $data['id'] = $record->id;

                    $data['created_at'] = $record->created_at;

                    $data['vleid'] = $record->vleid;

                    $data['name'] = $record->name;

                    $data['email'] = $record->email;

                    $data['mobile'] = $record->mobile;

                    $data['location'] = $record->location;

                    $data['contact_person'] = $record->contact_person;

                    $data['pincode'] = $record->pincode;

                    $data['state'] = $record->state;

                    $data['status'] = $record->status;

                    $data['remark'] = $record->remark;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    array_push($excelData, $data);

                }





            break;



            case 'iserveuid':

                $name = $type.'Agent'.date('d_M_Y');

                $titles = ['MerchantName', 'MerchantMobile', 'MerchantPanNumber','MerchantUsername','MerchantVPA', 'MerchantAddress', 'ShopName', 'ShopState', 'ShopCity', 'ShopDistrict', 'ShopArea', 'ShopAddress', 'Status', 'Created At'];

                foreach ($datas as $record) {

                    $data['name'] = $record->firstName.' '.$record->lastName;

                    $data['merchantMobileNumber'] = $record->merchantMobileNumber;

                    $data['pan']                  = $record->pan;

                    $data['requestingUserName']     = $record->requestingUserName;

                    $data['merchantVirtualAddress']   = $record->merchantVirtualAddress;

                    $data['address']                 = $record->address;

                    $data['shopName']                = $record->shopName;

                    $data['shopstate']               = $record->shopstate;

                    $data['shopcity']               = $record->shopcity;

                    $data['shopdistrict']               = $record->shopdistrict;

                    $data['shoparea']               = $record->shoparea;

                    $data['shopaddress']             = $record->shopaddress;

                    $data['status']                  = $record->status;



                    $data['created_at']                  = $record->created_at;

                    array_push($excelData, $data);

                }





            break;



            case 'iserveuid':

                $name = $type.'Agent'.date('d_M_Y');

                $titles = ['MerchantName', 'MerchantMobile', 'MerchantPanNumber','MerchantUsername','MerchantVPA', 'MerchantAddress', 'ShopName', 'ShopState', 'ShopCity', 'ShopDistrict', 'ShopArea', 'ShopAddress', 'Status', 'Created At'];

                foreach ($datas as $record) {

                    $data['name'] = $record->firstName.' '.$record->lastName;

                    $data['merchantMobileNumber'] = $record->merchantMobileNumber;

                    $data['pan']                  = $record->pan;

                    $data['requestingUserName']     = $record->requestingUserName;

                    $data['merchantVirtualAddress']   = $record->merchantVirtualAddress;

                    $data['address']                 = $record->address;

                    $data['shopName']                = $record->shopName;

                    $data['shopstate']               = $record->shopstate;

                    $data['shopcity']               = $record->shopcity;

                    $data['shopdistrict']               = $record->shopdistrict;

                    $data['shoparea']               = $record->shoparea;

                    $data['shopaddress']             = $record->shopaddress;

                    $data['status']                  = $record->status;



                    $data['created_at']                  = $record->created_at;

                    array_push($excelData, $data);

                }

            break;





            case 'cosmosid':

                $name = $type.'Agent'.date('d_M_Y');

                $titles = ['merchentLegalName','mobileNumber', 'businessName', 'locationCountry','State','City', 'Address', 'panNo', 'mebusinessType', 'mcc', 'settlementType', 'perDaytransactionCnt', 'gstn','WebApp','WebURL','vpa','sid', 'Created At'];

                foreach ($datas as $record) {

                    $data['merchentLegalName'] = $record->merchentLegalName;

                    $data['mobileNumber']                  = $record->mobileNumber;

                    $data['businessName']     = $record->businessName;

                    $data['locationCountry']   = $record->locationCountry;

                    $data['State']                 = $record->State;

                    $data['City']                 = $record->City;

                    $data['Address']                 = $record->Address;

                    $data['panNo']                 = $record->panNo;

                    $data['mebusinessType']                 = $record->mebusinessType;

                    $data['mcc']                 = $record->mcc;

                    $data['settlementType']                 = $record->settlementType;

                    $data['perDaytransactionCnt']                 = $record->perDaytransactionCnt;

                    $data['gstn']                 = $record->gstn;

                    $data['WebApp']                 = $record->WebApp;

                    $data['WebURL']                 = $record->WebURL;

                    $data['vpa']                 = $record->vpa;

                    $data['sid']                 = $record->sid;



                    $data['created_at']                  = $record->created_at;

                    array_push($excelData, $data);

                }



            break;



            case 'wallet':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Date', 'Transaction Id', 'User Details','Product', 'Number', 'ST Type', 'Status', 'Opening Balance', 'Credit', 'Debit', 'Charge', 'GST'];

                foreach ($datas as $record) {

                    $data['created_at'] = $record->created_at;

                    $data['id'] = $record->id;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    $data['product'] = $record->product;

                    $data['number'] = $record->number;

                    $data['rtype'] = $record->rtype;

                    $data['status'] = $record->status;

                    $data['balance'] = " ".round($record->balance, 2);

                    if($record->trans_type == "credit"){

                        $data['credit'] = $record->amount + $record->charge - $record->profit;

                        $data['debit']  = '';

                    }elseif($record->trans_type == "debit"){

                        $data['credit'] = '';

                        $data['debit']  = $record->amount + $record->charge - $record->profit;

                    }else{

                        $data['credit'] = '';

                        $data['debit']  = '';

                    }

                    $data['charge'] = $record->charge;

                    $data['gst'] = $record->gst;

                    array_push($excelData, $data);

                }

            break;



            case 'awallet':

            case 'upiwallet':    

            case 'matmwallet':

                $name = $type.'report'.date('d_M_Y');

                $titles = ['Date', 'User Details', 'Transaction Details', 'Ref No', 'Transaction Type', 'Status', 'Opening Balance', 'Credit', 'Debit'];

                foreach ($datas as $record) {

                    $data['created_at'] = $record->created_at;

                    $data['userdetails'] = $record->username." (".$record->usermobile.")";

                    if($record->transtype == "fund" ){

                        $data['product'] = $record->payid."/".$record->remark;

                    }else{

                        $data['product'] = $record->aadhar."/".$record->mobile;

                    }

                    $data['refno'] = $record->refno;

                    $data['number'] = $record->transtype;

                    $data['status'] = $record->status;

                    $data['balance'] = " ".round($record->balance, 2);

                    if($record->type == "credit"){

                        $data['credit'] = $record->amount + $record->charge;

                        $data['debit']  = '';

                    }elseif($record->type == "debit"){

                        $data['credit'] = '';

                        $data['debit']  = $record->amount - $record->charge;

                    }else{

                        $data['credit'] = '';

                        $data['debit']  = '';

                    }

                    array_push($excelData, $data);

                }

            break;

        }


       if($post->downablebtntype == 'excel'){

        $fromdate = $post->fromdate;

        $todate = $post->todate;

        // My logic excel download
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:M1'); // Merge the cells for the heading
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A1', 'Transaction Report As Date From '.$fromdate.' To '.$todate);
        $sheet->fromArray($titles,null,'A2', false, false);
        $sheet->fromArray($excelData,null,'A3',false,false);

        if($type != 'wallet' && $type != 'apiuser' && $type != 'retailer' && $type != 'iserveuid' && $type != 'cosmosid'){
            $lastRowIndex = count($excelData) + 3;
           $sheet->setCellValue('D' . $lastRowIndex, 'Total Sum');
            // Set bold font for the last row
            $sheet->getStyle('D'.$lastRowIndex)->getFont()->setBold(true);
            // $sheet->row($lastRowIndex, function ($row) {
            //     $row->setFontWeight('bold');
            // });
            //set amount sum
            $sheet->setCellValue('E' . $lastRowIndex, "=SUM(E3:E" . ($lastRowIndex - 1) . ")");
            $sheet->getStyle('E'.$lastRowIndex)->getFont()->setBold(true);
            // $sheet->row($lastRowIndex, function ($row) {
            //    $row->setFontWeight('bold');
            // });
            $rangeamoumt = 'E3:E' . ($lastRowIndex - 1);
            $totalamount = $this->calculateSum($sheet, $rangeamoumt);
            $rangeCharge = 'F3:F' . ($lastRowIndex - 1);
            $totalcharge = $this->calculateSum($sheet, $rangeCharge);
            $rangegst = 'G3:G' . ($lastRowIndex - 1);
            $totalgst = $this->calculateSum($sheet, $rangegst);
             // dd($totalamount);
            $finalSettlement = $totalamount - $totalcharge - $totalgst;
            //set amount charge
            $sheet->setCellValue('F' . $lastRowIndex, "=SUM(F3:F" . ($lastRowIndex - 1) . ")");
            // $sheet->row($lastRowIndex, function ($row) {
            //     $row->setFontWeight('bold');
            // });
            $sheet->getStyle('F'.$lastRowIndex)->getFont()->setBold(true);
            //set amount charge
            $sheet->setCellValue('G' . $lastRowIndex, "=SUM(G3:G" . ($lastRowIndex - 1) . ")");
            $sheet->getStyle('G'.$lastRowIndex)->getFont()->setBold(true);
            // $sheet->row($lastRowIndex, function ($row) {
            //     $row->setFontWeight('bold');
            // });
            $finalrow = $lastRowIndex + 1;
                $sheet->setCellValue('D' . $finalrow, 'Total Settlement');
                $sheet->setCellValue('E' . $finalrow, $finalSettlement);
                $sheet->getStyle('D'.$finalrow)->getFont()->setBold(true);
                $sheet->getStyle('E'.$finalrow)->getFont()->setBold(true);
                //  $sheet->row($finalrow, function ($row) {
                //     $row->setFontWeight('bold');
                // });
            }
        $writer = new Xlsx($spreadsheet);

        $filename = $name.'.xlsx';
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
       }elseif($post->downablebtntype == 'pdf'){



            $dompdf = new Dompdf();

            

            // Load HTML content

            ini_set('memory_limit', '256M');

            ob_start(); 

            $html = $this->convertArraytoHmtl($titles,$excelData);



            sleep(6);

            $dompdf->loadHtml($html);

            // (Optional) Set paper size and orientation

            $dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF

            $dompdf->render();

            // Output the generated PDF to the browser

            $dompdf->stream($type."-report.pdf"); 

        

    }elseif($post->downablebtntype == 'word'){



        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $table   = $section->addTable();

   

        //$table->getStyle()->setTableAlignment(Table::ALIGN_LEFT);

       

        $tableData = [$titles];

        foreach ($excelData as $key => $value1) {

          $tableData[] = !empty($value1) ? array_values($value1):[];

        }

        // Add rows to the table

        foreach ($tableData as $row) {

            $table->addRow();

            foreach ($row as $cell) {

                $table->addCell(1750)->addText($cell);

            }

        }

        // Save the document as a Word file

        $filename = $type.'-report.docx';

        $phpWord->save(public_path($filename));

        return response()->download(public_path($filename))->deleteFileAfterSend();

    }



  } 



    // Custom function to calculate sum of a range

    function calculateSum($sheet, $range) {

        $sum = 0;

        foreach ($sheet->rangeToArray($range) as $row) {

            foreach ($row as $cellValue) {

                $sum += floatval($cellValue);

            }

        }

        return $sum;

    }



    public function convertArraytoHmtl($titles,$arraydata)

    {

        $td_width = 780/ count($titles);

        $html = '';

        $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="0"><thead><tr>';

        foreach ($titles as $key => $value) {

           $html .= '<th style="max-width:'.$td_width.'px;word-wrap: break-word;">'.$value.'</th>';

        }



        $html .= '</tr></thead><tbody>';



       

        foreach ($arraydata as $key => $value1) {

            $html .= '<tr>';

            foreach ($value1 as $key2 => $value2) {

              $html .= '<td style="padding:5px;max-width:'.$td_width.'px;word-wrap: break-word;">'.$value2.'</td>';  

            }

            $html .= '</tr>';

            



        }



        $html .= '</tbody></table>';

        return $html;

    }


public function openacquiring(){
    $data['openacquiring'] = Openacquiring::where('user_id', \Auth::id())->first();
    return view('statement.openacquiring')->with($data);
}
public function store_openacquiring(Request $request){
    $data = $request->post();
    unset($data['_token']);
    $data['user_id'] = \Auth::id();
    $data['status'] = 1;
    $data['created_at'] =  $data['updated_at'] =  date('Y-m-d H:i:s');
    $id = Openacquiring::create($data);
    if($id){
            return response()->json(['statuscode'=>"TXN","message"=>"Onboarding successfully"]);
        }else{
                return response()->json(['statuscode'=>'ERR', 'message'=> "Something went wrong, try again"]); 
        }
    }
    public function openacquirings(){
        
    }


}