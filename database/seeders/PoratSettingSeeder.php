<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PoratSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of data
        $portal_settings = array(
            array('id' => '1','name' => 'Session Logout Time','code' => 'sessionout','value' => '3600000','company_id' => NULL),
            array('id' => '2','name' => 'Wallet Settlement Type','code' => 'settlementtype','value' => 'auto','company_id' => NULL),
            array('id' => '3','name' => 'Login required otp','code' => 'otplogin','value' => 'no','company_id' => NULL),
            array('id' => '4','name' => 'Sending mail id for otp','code' => 'otpsendmailid','value' => 'mer.care@instantcharge.co','company_id' => NULL),
            array('id' => '5','name' => 'Sending mailer name id for otp','code' => 'otpsendmailname','value' => 'Backoffice','company_id' => NULL),
            array('id' => '6','name' => 'Scheme Maneger','code' => 'schememanager','value' => 'admin','company_id' => NULL),
            array('id' => '7','name' => 'Transaction Id Code','code' => 'transactioncode','value' => 'Backoffice','company_id' => NULL),
            array('id' => '10','name' => 'Top Header Color','code' => 'topheadcolor','value' => '#273246','company_id' => NULL),
            array('id' => '11','name' => 'Sidebar Light Color','code' => 'sidebarlightcolor','value' => '#259dab','company_id' => NULL),
            array('id' => '12','name' => 'Sidebar Dark Color','code' => 'sidebardarkcolor','value' => '#2574ab','company_id' => NULL),
            array('id' => '13','name' => 'Sidebar Icon Color','code' => 'sidebariconcolor','value' => '#409cab','company_id' => NULL),
            array('id' => '15','name' => 'Sidebar Child Href Color','code' => 'sidebarchildhrefcolor','value' => '#3f9bab','company_id' => NULL),
            array('id' => '16','name' => 'CP Id for dmt','code' => 'cpid','value' => '','company_id' => NULL),
            array('id' => '17','name' => 'Bc Id for dmt','code' => 'bcid','value' => '','company_id' => NULL),
            array('id' => '28','name' => 'Bank Settlement Charge','code' => 'settlementcharge','value' => '3','company_id' => NULL),
            array('id' => '29','name' => 'Wallet Settlement Type','code' => 'banksettlementtype','value' => 'auto','company_id' => NULL),
            array('id' => '30','name' => 'Bank Settlement Charge Upto 25000','code' => 'impschargeupto25','value' => '5','company_id' => NULL),
            array('id' => '31','name' => 'Bank Settlement Charge Above 25000','code' => 'impschargeabove25','value' => '10','company_id' => NULL),
            array('id' => '32','name' => 'Aeps Settlement Time','code' => 'aepsslabtime','value' => '24/7','company_id' => NULL),
            array('id' => '33','name' => 'Main Wallet Locked Amount','code' => 'mainlockedamount','value' => '0','company_id' => NULL),
            array('id' => '34','name' => 'Aeps Bank Settlement Locked Amount','code' => 'aepslockedamount','value' => '0','company_id' => NULL),
            array('id' => '35','name' => 'Bank Settlement Api','code' => 'bankpayoutapi','value' => 'paytm','company_id' => NULL),
            array('id' => '36','name' => 'X-Upi api','code' => 'xupi','value' => 'old','company_id' => NULL),
            array('id' => '37','name' => 'Country api','code' => 'capi','value' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7InVzZXJfZW1haWwiOiJwYXduZXNoa2l0aW9AZ21haWwuY29tIiwiYXBpX3Rva2VuIjoieGJQYm1ySXczQlM0NXBJampzSmZpNG9UdVppV0NwdlN3bGhrbkdXSF9lWVFRbHNhX1FFNm5fcktBU0QtVkZPM0N6SSJ9LCJleHAiOjE3MTgyNzMzMzB9.zKK2Ag2_eCmN5FnswoK5xzo0noexjoKMdS2CrLyHvBg','company_id' => NULL)
          );
          foreach ($portal_settings as &$portal_setting) {
            unset($portal_setting['id']);
        }
        // End Array of data
        \App\Models\PortalSetting::insert($portal_settings);
    }
}
