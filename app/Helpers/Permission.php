<?php
namespace App\Helpers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Aepsreport;
use App\Models\Microatmreport;
use App\Models\UserPermission;
use App\Models\Apilog;
use App\Models\Scheme;
use App\Models\Commission;
use App\Models\User;
use App\Models\Report;
use App\Models\Company;
use App\Models\Utiid;
use App\Models\Provider;
use App\Models\Packagecommission;
use App\Models\Package;
use App\Models\PortalSetting;
use SendGrid;
use SendGrid\Mail\Mail;

class Permission {

      public static function can($permission , $id="none") {
        if($id == "none"){
            $id = \Auth::id();
        }
        $user = User::where('id', $id)->first();

        if(is_array($permission)){
            $mypermissions = \DB::table('permissions')->whereIn('slug' ,$permission)->get(['id'])->toArray();
            if($mypermissions){
                foreach ($mypermissions as $value) {
                    $mypermissionss[] = $value->id;
                }
            }else{
                $mypermissionss = [];
            }
            $output = UserPermission::where('user_id', $id)->whereIn('permission_id', $mypermissionss)->count();

        }else{
           
            $mypermission = \DB::table('permissions')->where('slug' ,$permission)->first(['id']);
            if($mypermission){
                $output = UserPermission::where('user_id', $id)->where('permission_id', $mypermission->id)->count();
            }else{
                $output = 0;
            }
        }

        if($output > 0 || $user->role->slug == "admin"){
            return true;
        }else{
            return false;
        }
    }

    

    public static function hasRole($roles) {
        if(\Auth::check()){
            if(is_array($roles)){
                if(in_array(\Auth::user()->role->slug, $roles)){
                    return true;
                }else{
                    return false;
                }
            }else{
                if(\Auth::user()->role->slug == $roles){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public static function hasNotRole($roles) {
        if(\Auth::check()){
            if(is_array($roles)){
                if(!in_array(\Auth::user()->role->slug, $roles)){
                    return true;
                }else{
                    return false;
                }
            }else{
                if(\Auth::user()->role->slug != $roles){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public static function apiLog($url, $modal, $txnid, $header, $request, $response)
    {
        try {
            $apiresponse = Apilog::create([
                "url" => $url,
                "modal" => $modal,
                "txnid" => $txnid,
                "header" => $header,
                "request" => $request,
                "response" => $response
            ]);
        } catch (\Exception $e) {
            $apiresponse = "error";
        }
        return $apiresponse;
    }
    public static function sendGridMail($view,$data,$mailto,$name,$mailvia,$namevia,$subject){
        $apiKey = env('SENDGRIDMAIL_API_KEY');
        $emailBody = view($view)->with($data)->render();
        $email = new Mail(); 
        $email->setFrom($mailvia, $namevia);
        $email->setSubject($subject);
        $email->addTo($mailto, $name);
        $email->addContent(
            "text/html", $emailBody
        );
        $sendgrid = new SendGrid($apiKey);
        try {
            $response = $sendgrid->send($email);
            return "success";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
            return "failed";
        }
    }
    public static function sendRapidMail($view,$data,$mailto,$name,$mailvia,$namevia,$subject){
        $apiUrl = env('RAPIDMAIL_API_URL');
        $apiKey = env('RAPIDMAIL_API_KEY');
        $emailBody = view($view)->with($data)->render();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-rapidapi-host' => parse_url($apiUrl, PHP_URL_HOST),
            'x-rapidapi-key' => $apiKey,
        ])->post($apiUrl, [
            'ishtml'  => 'true',
            'sendto'  => $mailto,
            'name'    => $namevia,
            'replyTo' => $mailto,
            'title'   => $subject,
            'body'    => $emailBody,
        ]);

        return "success";
    }
    public static function elasticMail($view,$data,$mailto,$name,$mailvia,$namevia,$subject){
        // TechWizard Logic
        $apiKey = env('ELASTICMAIL_API_KEY');
        $url = env('ELASTICMAIL_API_ENDPOINT');
        $emailBody = view($view)->with($data)->render();
        $email_from = $namevia." <".$mailvia.">";
        $email_reply = $namevia." <".$mailvia.">";
        $postData = [
            'Recipients' => [['Email'=>$mailto]],
            'Content' => [
                'Body' => [
                    [
                        'ContentType' => 'HTML',
                        'Content' => $emailBody,
                        'Charset' => 'utf-8'
                    ]
                ],
                'From' => $email_from,
                'ReplyTo' => $email_reply,
                'Subject' => $subject
            ],
            'Options' => [
                'TrackOpens' => true,
                'TrackClicks' => true
            ]
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-ElasticEmail-ApiKey: ' . $apiKey
            ]
        ]);
    
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        if ($response === false) {
            return 'error';
        }
    
        return 'success';
        // TechWizard logic End
    }
    public static function mail($view, $data, $mailto, $name, $mailvia, $namevia, $subject)
    {
        \Mail::send($view, $data, function($message) use($mailto, $name, $mailvia, $namevia, $subject) {
            $message->to($mailto, $name)->subject($subject);
            $message->from($mailvia, $namevia); 
        });
        return "success";
    }

    public static function sms($mobile, $content)
    {
        $smsdata = \App\Models\Company::where('website', $_SERVER['HTTP_HOST'])->first();
        if(isset($smsdata->senderid)){
           // $url = 'https://instantalerts.co/api/web/send?apikey='.$smsdata->smsuser."&to=".$mobile."&sender=".$smsdata->senderid."&message=".urlencode($content);
           
           $url = 'http://web.springedge.com//api/web/send?apikey='.$smsdata->smsuser."&to=".$mobile."&sender=".$smsdata->senderid."&message=".urlencode($content)."&format=json";

        $result = Permission::curl($url, "GET", "", [], "yes", "SMS", $mobile);
        //dd($result['response']);
            if($result['response'] != ''){
                $response = json_decode($result['response']);
                if ($response->status == "AWAITED-DLR") {
                    return "success";
                }
            }
        }
        return "fail";
    }

   public static function commission($report)
    {
        if(in_array($report->apicode, ['aeps', 'kaeps', 'microatm'])){
            $insert = [
                'number'  => $report->aadhar,
                'mobile'  => $report->mobile,
                'provider_id' => $report->provider_id,
                'api_id'  => $report->api_id,
                'txnid'   => $report->id,
                'payid'   => $report->payid,
                'refno'   => $report->refno,
                'status'  => 'success',
                'rtype'   => 'commission',
                'trans_type' => "credit",
                'via'     => "portal",
                'product' => "aeps",
                'provider_id'=> $report->provider_id
            ];

            if($report->apicode == "microatm"){
                $report['product'] = "microatm";
            }
            
            $provider = $report->provider_id;
            $precommission = $report->charge;
        }else{
            $myreport = Report::where('id', $report->id)->first(['profit', 'gst']);
            $insert = [
                'number' => $report->number,
                'mobile' => $report->mobile,
                'provider_id' => $report->provider_id,
                'api_id' => $report->api_id,
                'txnid'  => $report->id,
                'payid'  => $report->payid,
                'refno'  => $report->refno,
                'status' => 'success',
                'rtype'  => 'commission',
                'via'    => $report->via,
                'trans_type' => "credit",
                'product' => $report->product
            ];
            if($report->product == "dmt"){
                $precommission = $report->charge - $myreport->profit - $myreport->gst;
            }elseif($report->product == "nsdlpan"){
                $precommission = $report->amount;
            }else{
                $precommission = $report->profit;
            }
            $provider = $report->provider_id;
        }

        $parent = User::where('id', $report->user->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);

        if($parent && $parent->role->slug == "distributor"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;
            $parentcommission = Permission::getCommission($report->amount, $parent->scheme_id, $provider, 'distributor');

            if(in_array($report->product, ['recharge','billpay','aeps', 'microatm'])){
                $insert['amount'] = $parentcommission - $precommission;
            }elseif($report->product == "utipancard"){
                $insert['amount'] = $report->option1 * $parentcommission - $precommission;
            }elseif($report->product == "dmt"){
                $insert['amount'] = $precommission - $parentcommission;
            }

            User::find($parent->id)->increment('mainwallet', $insert['amount']);
            Report::create($insert);
            if(in_array($report->apicode, ['aeps', 'kaeps','microatm'])){
                Aepsreport::find($report->id)->update(['disid' => $parent->id, "disprofit" => $insert['amount']]);
            }else{
                Report::find($report->id)->update(['disid' => $parent->id, "disprofit" => $insert['amount']]);
            }

            if(in_array($report->product, ['recharge','billpay','dmt','aeps','microatm'])){
                $precommission = $parentcommission;
            }elseif($report->product == "utipancard"){
                $precommission = $report->option1 * $parentcommission;
            }

            $parent = User::where('id', $parent->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);
        }

        if($parent && $parent->role->slug == "md"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;
            $parentcommission = Permission::getCommission($report->amount, $parent->scheme_id, $provider, 'md');

            if(in_array($report->product, ['recharge','billpay','aeps', 'microatm'])){
                $insert['amount'] = $parentcommission - $precommission;
            }elseif($report->product == "utipancard"){
                $insert['amount'] = $report->option1 * $parentcommission - $precommission;
            }elseif($report->product == "dmt"){
                $insert['amount'] = $precommission - $parentcommission;
            }

            User::find($parent->id)->increment('mainwallet', $insert['amount']);
            Report::create($insert);
            if(in_array($report->apicode, ['aeps', 'kaeps', 'microatm'])){
                Aepsreport::find($report->id)->update(['mdid' => $parent->id, "mdprofit" => $insert['amount']]);
            }else{
                Report::find($report->id)->update(['mdid' => $parent->id, "mdprofit" => $insert['amount']]);
            }

            if(in_array($report->product, ['recharge','billpay','dmt','aeps'])){
                $precommission = $parentcommission;
            }elseif($report->product == "utipancard"){
                $precommission = $report->option1 * $parentcommission;
            }
            $parent = User::where('id', $parent->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);
        }

        if($parent && $parent->role->slug == "whitelable"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;

            $parentcommission = Permission::getCommission($report->amount, $parent->scheme_id, $provider, 'whitelable');

            if(in_array($report->product, ['recharge','billpay','aeps', 'microatm'])){
                $insert['amount'] = $parentcommission - $precommission;
            }elseif($report->product == "utipancard"){
                $insert['amount'] = $report->option1 * $parentcommission - $precommission;
            }elseif($report->product == "dmt"){
                $insert['amount'] = $precommission - $parentcommission;
            }
            
            User::find($parent->id)->increment('mainwallet', $insert['amount']);
            Report::create($insert);
            if(in_array($report->apicode, ['aeps', 'kaeps', 'microatm'])){
                Aepsreport::find($report->id)->update(['wid' => $parent->id, "wprofit" => $insert['amount']]);
            }else{
                Report::find($report->id)->update(['wid' => $parent->id, "wprofit" => $insert['amount']]);
            }
            $parent = User::where('id', $parent->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);
        }
        
        if($parent && !in_array($parent->role->slug, ["whitelable", "md", "distributor", "retailer", "admin"])){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;

            $parentcommission = Permission::getCommission($report->amount, $parent->scheme_id, $provider, 'whitelable');

            if(in_array($report->product, ['recharge','billpay','aeps', 'microatm'])){
                $insert['amount'] = $parentcommission - $precommission;
            }elseif($report->product == "utipancard"){
                $insert['amount'] = $report->option1 * $parentcommission - $precommission;
            }elseif($report->product == "dmt"){
                $insert['amount'] = $precommission - $parentcommission;
            }
            
            User::find('id', $parent->id)->increment('mainwallet', $insert['amount']);
            Report::create($insert);
            if(in_array($report->apicode, ['aeps', 'kaeps', 'microatm'])){
                Aepsreport::find($report->id)->update(['oid' => $parent->id, "oprofit" => $insert['amount']]);
            }else{
                Report::find($report->id)->update(['oid' => $parent->id, "oprofit" => $insert['amount']]);
            }
        }
    }

    public static function getCommission($amount, $scheme, $slab, $role)
    {
        $schememanager = \DB::table('portal_settings')->where('code', 'schememanager')->first(['value']);
        if($schememanager->value != "all"){
            $myscheme = Scheme::where('id', $scheme)->first(['status']);
            if($myscheme && $myscheme->status == "1"){
                $comdata = Commission::where('scheme_id', $scheme)->where('slab', $slab)->first();
                if ($comdata) {
                        if ($comdata->type == "percent") {
                            $commission = $amount * $comdata[$role] / 100;
                        }else{
                            $commission = $comdata[$role];
                        }
                    if($commission == null){
                        $commission = 0;
                    }
                }else{
                    $commission = 0;
                }
            }else{
                $commission = 0;
            }
        }else{
            $myscheme = Package::where('id', $scheme)->first(['status']);
            if($myscheme && $myscheme->status == "1"){
                $comdata = Packagecommission::where('scheme_id', $scheme)->where('slab', $slab)->first();
                if ($comdata) {
                    if ($comdata->type == "percent") {
                        $commission = $amount * $comdata->value / 100;
                    }else{
                        $commission = $comdata->value;
                    }
                }else{
                    $commission = 0;
                }
            }else{
                $commission = 0;
            }
        }
        return $commission;
    }

    // Get Commission using ccp_api database 
    public static function getCommissionApidb($amount, $scheme, $slab, $role)
    {
        $schememanager = \App\Models\Api\PortalSetting::where('code', 'schememanager')->first(['value']);
        if($schememanager->value != "all"){
            $myscheme = \App\Models\Api\Scheme::where('id', $scheme)->first(['status']);
            if($myscheme && $myscheme->status == "1"){
                $comdata = \App\Models\Api\Commission::where('scheme_id', $scheme)->where('slab', $slab)->first();
                if ($comdata) {
                        if ($comdata->type == "percent") {
                            $commission = $amount * $comdata[$role] / 100;
                        }else{
                            $commission = $comdata[$role];
                        }
                    if($commission == null){
                        $commission = 0;
                    }
                }else{
                    $commission = 0;
                }
            }else{
                $commission = 0;
            }
        }else{
            $myscheme = \App\Models\Api\Package::where('id', $scheme)->first(['status']);
            if($myscheme && $myscheme->status == "1"){
                $comdata = \App\Models\Api\Packagecommission::where('scheme_id', $scheme)->where('slab', $slab)->first();
                if ($comdata) {
                    if ($comdata->type == "percent") {
                        $commission = $amount * $comdata->value / 100;
                    }else{
                        $commission = $comdata->value;
                    }
                }else{
                    $commission = 0;
                }
            }else{
                $commission = 0;
            }
        }
        return $commission;
    }

    public static function curl($url , $method='GET', $parameters, $header, $log="no", $modal="none", $txnid="none")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_TIMEOUT, 180);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        if($parameters != ""){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        if(sizeof($header) > 0){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($log != "no"){
            Apilog::create([
                "url" => $url,
                "modal" => $modal,
                "txnid" => $txnid,
                "header" => $header,
                "request" => $parameters,
                "response" => $response
            ]);
        }

        return ["response" => $response, "error" => $err, 'code' => $code];
    }

    public static function getParents($id)
    {
        $data = [];
        $user = \DB::table('users')->leftJoin('roles','users.role_id','=', 'roles.id')->where('users.id',$id)->first(['users.id', 'users.role_id','roles.slug']);
        if($user){
            $data[] = $id;
            switch ($user->slug) {
                case 'admin':
                    $whitelabels = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'whitelable');
                    })->get(['id']);

                    if(sizeOf($whitelabels) > 0){
                        foreach ($whitelabels as $value) {
                            $data[] = $value->id;
                        }
                    }
                
                    $mds = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'md');
                    })->get(['id']);

                    if(sizeOf($mds) > 0){
                        foreach ($mds as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $distributors = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer', 'apiuser']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                    
                case 'whitelable':
                    $mds = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'md');
                    })->get(['id']);

                    if(sizeOf($mds) > 0){
                        foreach ($mds as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $distributors = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'retailer');
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                
                case 'md':                
                    $distributors = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'retailer');
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                
                case 'distributor':                
                    $retailers = \App\Models\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'retailer');
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                
               default:              
                    $whitelabels = User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'whitelable');
                    })->get(['id']);

                    if(sizeOf($whitelabels) > 0){
                        foreach ($whitelabels as $value) {
                            $data[] = $value->id;
                        }
                    }

                    $mds = User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'md');
                    })->get(['id']);

                    if(sizeOf($mds) > 0){
                        foreach ($mds as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $distributors = User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer', 'apiuser']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
            }
        }
        return $data;
    }
    
    public static function transactionRefund($id)
    {
        $report = Report::where('id', $id)->first();
        $count  = Report::where('user_id', $report->user_id)->where('status', 'refunded')->where('txnid', $report->id)->count();
        if($count == 0){
            $user = User::where('id', $report->user_id)->first(['id', 'mainwallet']);
            if($report->trans_type == "debit"){
                User::find($report->user_id)->increment('mainwallet',$report->amount + $report->charge - $report->profit);
            }else{
                User::find($report->user_id)->decrement('mainwallet',$report->amount + $report->charge - $report->profit);
            }
            $insert = [
                'number' => $report->number,
                'mobile' => $report->mobile,
                'provider_id' => $report->provider_id,
                'api_id' => $report->api_id,
                'apitxnid' => $report->apitxnid,
                'txnid' => $report->id,
                'payid' => $report->payid,
                'refno' => $report->refno,
                'description' => "Transaction Reversed, amount refunded",
                'remark' => $report->remark,
                'option1' => $report->option1,
                'option2' => $report->option2,
                'option3' => $report->option3,
                'option4' => $report->option3,
                'status' => 'refunded',
                'rtype' => $report->rtype,
                'via' => $report->via,
                'trans_type' => ($report->trans_type == "credit") ? "debit" : "credit",
                'product' => $report->product,
                'amount' => $report->amount,
                'profit' => $report->profit,
                'charge' => $report->charge,
                'gst' => $report->gst,
                'tds' => $report->tds,
                'balance' => $user->mainwallet,
                'user_id' => $report->user_id,
                'credit_by' => $report->credit_by,
                'adminprofit' => $report->adminprofit
            ];
            Report::create($insert);

            $commissionReports = Report::where('rtype', 'commission')->where('txnid', $report->id)->get();
            foreach ($commissionReports as $report) {
                $user = User::where('id', $report->user_id)->first(['id', 'mainwallet']);

                if($report->trans_type == "debit"){
                    User::find($report->user_id)->increment('mainwallet', $report->amount - $report->profit);
                }else{
                    User::find( $report->user_id)->decrement('mainwallet', $report->amount - $report->profit);
                }
                
                $insert = [
                    'number' => $report->number,
                    'mobile' => $report->mobile,
                    'provider_id' => $report->provider_id,
                    'api_id' => $report->api_id,
                    'apitxnid' => $report->apitxnid,
                    'txnid' => $report->id,
                    'payid' => $report->payid,
                    'refno' => $report->refno,
                    'description' => "Transaction Reversed, amount refunded",
                    'remark' => $report->remark,
                    'option1' => $report->option1,
                    'option2' => $report->option2,
                    'option3' => $report->option3,
                    'option4' => $report->option3,
                    'status' => 'refunded',
                    'rtype' => $report->rtype,
                    'via' => $report->via,
                    'trans_type' => ($report->trans_type == "credit") ? "debit" : "credit",
                    'product' => $report->product,
                    'amount' => $report->amount,
                    'profit' => $report->profit,
                    'charge' => $report->charge,
                    'gst' => $report->gst,
                    'tds' => $report->tds,
                    'balance' => $user->mainwallet,
                    'user_id' => $report->user_id,
                    'credit_by' => $report->credit_by,
                    'adminprofit' => $report->adminprofit
                ];
                Report::create($insert);
            }
        }
    }

    public static function getTds($amount)
    {
        return $amount*5/100;
    }

    public static function callback($report, $product)
    {
        switch ($product) {
            case 'utipancard':
            case 'recharge':
            case 'upi':
            case 'payout':
                
                $report = Report::where('id', $report->id)->first();
                
                $apitxnid = $report->apitxnid;
                $refno = $report->refno;
                break;

            case 'utiid':
                $report = Utiid::where('id', $report->id)->first();
                $apitxnid = $report->vleid;
                $refno = $report->remark;
                break;
        }
        
        if($product == 'upi'){
            $action = 'UPI';
        }else{
            $action = strtoupper($report->product);
        }

        if($report->status == "success"){
            $status = "success";
        }elseif($report->status == "reversed"){
            $status = "failed";
        }else{
            $status = "unknown";
        }
        

        if($status != "unknown"){
            $url = $report->user->callbackurl."?txnid=".$apitxnid."&status=".$report->status."&refno=".$refno."&action=".$action;
            $result = Permission::curl($url, "GET", "", [], "no", "", "");
            Callbackresponse::create([
                'url' => $url,
                'response' => ($result['response'] != '') ? $result['response'] : $result['error'],
                'status' => $result['code'],
                'product' => $product,
                'user_id' => $report->user_id,
                'transaction_id' => $report->id
            ]);
        }
    }

    public static function FormValidator($rules, $post)
    {
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'status' => 'ERR',  
                'message' => $error
            ));
        }else{
            return "no";
        }
    }
     public static function encrypt($plainText, $key) {
        $secretKey = Permission::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }
    
    public static function decrypt($encryptedText, $key) {
        $key = Permission::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = Permission::hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
    
    public static function hextobin($hexString) {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }
    
            $count += 2;
        }
        return $binString;
    }
    // Get and Refresh Token
    public static function get_refresh_unvierse_auth_token(){
        $tokendata = PortalSetting::where('code', 'capi')->first();
        // validating token
        if($tokendata){
            $token = $tokendata->value;
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$token,
                'Accept' => 'application/json',
                ])->get('https://www.universal-tutorial.com/api/countries');
                // Check if expired by provider
                if(isset($response['error'])){
                if($response['error']['name']=='TokenExpiredError'){
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'api-token' => 'xbPbmrIw3BS45pIjjsJfi4oTuZiWCpvSwlhknGWH_eYQQlsa_QE6n_rKASD-VFO3CzI',
                        'user-email' =>'pawneshkitio@gmail.com',
                    ])->get('https://www.universal-tutorial.com/api/getaccesstoken');
                    $token = $response['auth_token'];
                    $action = PortalSetting::where('code', "capi")->update(['value'=> $token]);
                }
            }
            }
        if(!$tokendata){
            // Check if our cache is expired
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'api-token' => 'xbPbmrIw3BS45pIjjsJfi4oTuZiWCpvSwlhknGWH_eYQQlsa_QE6n_rKASD-VFO3CzI',
                'user-email' =>'pawneshkitio@gmail.com',
            ])->get('https://www.universal-tutorial.com/api/getaccesstoken');
            $token = $response['auth_token'];
            $insert['name'] ="Country api";
            $insert['code'] ="capi";
            $insert['value'] =$token;
            $action = PortalSetting::create($insert);
        }
        return $token;
    }
    // Fetch Countries from API
    public static function getCountries(){
        $token = Permission::get_refresh_unvierse_auth_token();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->get('https://www.universal-tutorial.com/api/countries');

        return $response->json();
    }
    // Fetch States from API
    public static function getStates($country_name){
        $token = Permission::get_refresh_unvierse_auth_token();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->get('https://www.universal-tutorial.com/api/states/'.$country_name);
        if(isset($response["error"]["name"])){
            return $response["error"]["name"];
        }else{
            return $response->json();
        }
    }
// Fetch States from API
    public static function getCity($state_name){
        $token = Permission::get_refresh_unvierse_auth_token();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->get('https://www.universal-tutorial.com/api/cities/'.$state_name);

        return $response->json();
    }
    public static function getCompanyDetails($companyId){
        $logo = Company::where('id', $companyId)->first();
        return $logo;
    }
    public static function generatePublicKey(){
        // Generate a unique identifier (terno)
    $terno = mt_rand(1000, 9999); // Using 'true' to make it more unique

    // Generate a public-private key pair
    $config = [
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    // Create the key pair
    $privateKey = '';
    $publicKey = '';

    // Generate a random private key containing only alphanumeric characters
    for ($i = 0; $i < 10; $i++) {
        $privateKey .= chr(rand(48, 57)) // Numbers (0-9)
                    . chr(rand(65, 90)) // Uppercase letters (A-Z)
                    . chr(rand(97, 122)); // Lowercase letters (a-z)
    }

    // Generate the corresponding public key
    $publicKey = base64_encode($privateKey);

    return [
        'terno' => $terno,
        'public_key' => $publicKey,
    ];
    }
    public static function getCurrency($userid){
      $currency= "";
      $currency = \DB::table('currancy')
                      ->Join('users', 'currancy.id', '=', 'users.currancy_id')
                      ->where('users.id', $userid)
                      ->select('currancy.fullname','currancy.shortname','currancy.symbol')
                      ->first();
                      if($currency){
                        return $currency;
                      }
    }

}