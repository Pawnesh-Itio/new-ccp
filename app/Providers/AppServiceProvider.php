<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Openacquiring;
use App\Models\Api;
use App\Models\Apitoken;
use App\Models\Beneficiarybank;
use App\Models\Report;
use App\Models\Refund;
use App\Models\Provider;
use App\Models\Apilog;
use App\Models\Aepsfundrequest;
use App\Models\User;
use App\Models\Mahabank;
use App\Models\Packagecommission;
use App\Models\Package;
use App\Models\PortalSetting;
use App\Models\Scheme;
use App\Models\Commission;

use App\Observers\OpenacquiringReplicator;
use App\Observers\ApiReplicator;
use App\Observers\ApitokenReplicator;
use App\Observers\BeneficiarybankApiReplicator;
use App\Observers\BeneficiarybankReplicator;
use App\Observers\ReportReplicator;
use App\Observers\ReportApiReplicator;
use App\Observers\RefundReplicator;
use App\Observers\RefundApiReplicator;
use App\Observers\ProviderReplicator;
use App\Observers\AepsfundrequestReplicator;
use App\Observers\ApilogsReplicator;
use App\Observers\UserReplicator;
use App\Observers\UserdataApiReplicator;
use App\Observers\MahabankReplicator;
use App\Observers\PackagecommissionReplicator;
use App\Observers\CommissionReplicator;
use App\Observers\PackageReplicator;
use App\Observers\PortalSettingReplicator;
use App\Observers\SchemeReplicator;


class AppServiceProvider extends ServiceProvider
{
        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {
            \Schema::defaultStringLength(191);
            
            // Observers

            Api::observe(ApiReplicator::class);
            Aepsfundrequest::observe(AepsfundrequestReplicator::class);
            Apilog::observe(ApilogsReplicator::class);
            Apitoken::observe(ApitokenReplicator::class);
            Beneficiarybank::observe(BeneficiarybankReplicator::class);
            Mahabank::observe(MahabankReplicator::class);
            Openacquiring::observe(OpenacquiringReplicator::class);
            Packagecommission::observe(PackagecommissionReplicator::class);
            Package::observe(PackageReplicator::class);
            PortalSetting::observe(PortalSettingReplicator::class);
            Provider::observe(ProviderReplicator::class);
            Report::observe(ReportReplicator::class);
            Refund::observe(RefundReplicator::class);
            Scheme::observe(SchemeReplicator::class);
            User::observe(UserReplicator::class);
            Commission::observe(CommissionReplicator::class);

            // Api Models Observer  
            \App\Models\Api\Beneficiarybank::observe(BeneficiarybankApiReplicator::class);
            \App\Models\Api\Userdata::observe(UserdataApiReplicator::class);
            \App\Models\Api\Refund::observe(RefundApiReplicator::class);
            \App\Models\Api\Report::observe(ReportApiReplicator::class);
    
            try {
                view()->composer('*', function ($view){
                    $mydata['links']             = \App\Models\Link::get();
                    $mydata['sessionOut']        = \App\Models\PortalSetting::where('code', 'sessionout')->first()->value;
                    $mydata['complaintsubject']  = \App\Models\Complaintsubject::get();
                    $mydata['topheadcolor']      = \App\Models\PortalSetting::where('code', "topheadcolor")->first();
                    $mydata['sidebarlightcolor'] = \App\Models\PortalSetting::where('code', "sidebarlightcolor")->first();
                    $mydata['sidebardarkcolor']  = \App\Models\PortalSetting::where('code', "sidebardarkcolor")->first();
                    $mydata['sidebariconcolor']  = \App\Models\PortalSetting::where('code', "sidebariconcolor")->first();
                    $mydata['sidebarchildhrefcolor'] = \App\Models\PortalSetting::where('code', "sidebarchildhrefcolor")->first();
                    $mydata['schememanager'] = \App\Models\PortalSetting::where('code', "schememanager")->first();
    
                    $mydata['company'] = \App\Models\Company::where('id', 1)->first();
    
                    if($mydata['company']){
                        $mydata['company_name'] =  $mydata['company']->companyname;
                        $mydata['company_logo'] =  $mydata['company']->logo;
                        $mydata['company_website'] =  $mydata['company']->website;
                        $news = \App\Models\Companydata::where('company_id', $mydata['company']->id)->first();
                    }else{
                        $news = null;
                        $mydata['company_name'] =  'CCP';
                        $mydata['company_logo'] =  'CCP.png';
                        $mydata['company_website'] =  'www.CCP.com';
                    }
    
                    if($news){
                        $mydata['news'] = $news->news;
                        $mydata['notice'] = $news->notice;
                        $mydata['billnotice'] = $news->billnotice;
                        $mydata['supportnumber'] = $news->number;
                        $mydata['supportemail'] = $news->email;
                    }else{
                        $mydata['news'] = "";
                        $mydata['notice'] = "";
                        $mydata['billnotice'] = "";
                        $mydata['supportnumber'] = "+917065588044";
                        $mydata['supportemail'] = "CCP@CCP.com";
                    }
    
                    $view->with('mydata', $mydata);    
                }); 
            } catch (\Exception $ex) {
                throw $ex;
            }
        }
    
        /**
         * Register any application services.
         *
         * @return void
         */
        public function register()
        {
            //
        }
    
}
