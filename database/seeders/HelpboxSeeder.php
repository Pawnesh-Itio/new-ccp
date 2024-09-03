<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class HelpboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $help_box = array(
            array('id' => '1','description' => 'This section shows the total collection have done till date.','slug' => 'total_collections','type' => 'dashboard','created_at' => '2024-02-17 11:52:38','updated_at' => '2024-02-17 13:37:02'),
            array('id' => '2','description' => 'This is description for total withdrawal','slug' => 'total_withdrawal','type' => 'dashboard','created_at' => '2024-02-17 12:26:48','updated_at' => '2024-02-17 12:26:48'),
            array('id' => '8','description' => 'This section shows the present day total collection .','slug' => 'today_collection','type' => 'dashboard','created_at' => '2024-02-17 11:52:38','updated_at' => '2024-02-17 13:37:02'),
            array('id' => '3','description' => 'This is description for active merchants','slug' => 'active_merchants','type' => 'dashboard','created_at' => '2024-02-17 12:27:35','updated_at' => '2024-02-17 12:27:35'),
            array('id' => '4','description' => 'This is description for latest transaction','slug' => 'latest_transaction','type' => 'dashboard','created_at' => '2024-02-17 12:29:11','updated_at' => '2024-02-17 12:29:11'),
            array('id' => '5','description' => 'This section shows the list of Top 5 Members of our platform.','slug' => 'active_user','type' => 'dashboard','created_at' => '2024-02-17 12:29:44','updated_at' => '2024-02-21 09:34:02'),
            array('id' => '6','description' => 'This is description for company list','slug' => 'company_list','type' => 'resourcecompany','created_at' => '2024-02-17 16:20:26','updated_at' => '2024-02-17 16:20:26'),
            array('id' => '7','description' => 'This is functionality for company edit.','slug' => 'company_edit','type' => 'resourcecompany','created_at' => '2024-02-17 16:21:08','updated_at' => '2024-02-17 16:21:08'),
            array('id' => '9','description' => 'This is description for company data list','slug' => 'companydata_list','type' => 'resourceCompanydata','created_at' => '2024-02-17 16:55:08','updated_at' => '2024-02-17 16:55:08'),
            array('id' => '10','description' => 'This is description for company data add functionality','slug' => 'companydata_add','type' => 'resourceCompanydata','created_at' => '2024-02-17 16:55:37','updated_at' => '2024-02-17 16:55:37'),
            array('id' => '11','description' => 'This is description for company data edit functionality.','slug' => 'companydata_edit','type' => 'resourceCompanydata','created_at' => '2024-02-17 16:56:00','updated_at' => '2024-02-17 16:56:00'),
            array('id' => '12','description' => 'This is description for scheme list','slug' => 'scheme_list','type' => 'resourceScheme','created_at' => '2024-02-17 17:16:04','updated_at' => '2024-02-17 17:16:04'),
            array('id' => '13','description' => 'This is description of scheme add functionality.','slug' => 'scheme_add','type' => 'resourceScheme','created_at' => '2024-02-17 17:16:32','updated_at' => '2024-02-17 17:16:32'),
            array('id' => '14','description' => 'This is description for scheme edit functionality.','slug' => 'scheme_edit','type' => 'resourceScheme','created_at' => '2024-02-17 17:19:05','updated_at' => '2024-02-17 17:19:05'),
            array('id' => '15','description' => 'This is description for scheme payout service charge functionality','slug' => 'scheme_payout_sc','type' => 'resourceScheme','created_at' => '2024-02-17 17:19:41','updated_at' => '2024-02-17 17:19:41'),
            array('id' => '16','description' => 'This is description for scheme payin service charge functionality','slug' => 'scheme_payin_sc','type' => 'resourceScheme','created_at' => '2024-02-17 17:20:12','updated_at' => '2024-02-17 17:20:12'),
            array('id' => '17','description' => 'This is description for merchant list','slug' => 'merchant_list','type' => 'memberMerchant','created_at' => '2024-02-17 17:51:32','updated_at' => '2024-02-17 17:51:32'),
            array('id' => '18','description' => 'This is description for merchant fund transfer return functionality','slug' => 'merchant_fund_t_r','type' => 'memberMerchant','created_at' => '2024-02-17 17:52:04','updated_at' => '2024-02-17 17:52:04'),
            array('id' => '19','description' => 'This is description for merchant scheme.','slug' => 'merchant_scheme','type' => 'memberMerchant','created_at' => '2024-02-17 17:54:56','updated_at' => '2024-02-17 17:54:56'),
            array('id' => '20','description' => 'This description is for merchant permission functionality.','slug' => 'merchant_permission','type' => 'memberMerchant','created_at' => '2024-02-17 17:55:25','updated_at' => '2024-02-17 17:55:25'),
            array('id' => '21','description' => 'This description is for merchant sound setting functionality.','slug' => 'merchant_sound_setting','type' => 'memberMerchant','created_at' => '2024-02-17 17:56:04','updated_at' => '2024-02-17 17:56:04'),
            array('id' => '22','description' => 'This description is for merchant gst charge functionality','slug' => 'merchant_gst_charge','type' => 'memberMerchant','created_at' => '2024-02-17 17:56:37','updated_at' => '2024-02-17 17:56:37'),
            array('id' => '23','description' => 'This description is for merchant settle amount functionality','slug' => 'merchant_settle_amount','type' => 'memberMerchant','created_at' => '2024-02-17 17:57:05','updated_at' => '2024-02-17 17:57:05'),
            array('id' => '24','description' => 'This description is for agent list open aquiring','slug' => 'agentList','type' => 'agentOpenacquiring','created_at' => '2024-02-19 09:41:36','updated_at' => '2024-02-19 09:41:36'),
            array('id' => '25','description' => 'This description is for agent Edit Openacquiring','slug' => 'agentEdit','type' => 'agentOpenacquiring','created_at' => '2024-02-19 09:42:02','updated_at' => '2024-02-19 09:42:02'),
            array('id' => '26','description' => 'This is description for agent list cosmos agent','slug' => 'agentList','type' => 'agentCosmos','created_at' => '2024-02-19 09:45:47','updated_at' => '2024-02-19 09:45:47'),
            array('id' => '27','description' => 'This is description for agent edit cosmos','slug' => 'agentEdit','type' => 'agentCosmos','created_at' => '2024-02-19 09:46:13','updated_at' => '2024-02-19 09:46:13'),
            array('id' => '28','description' => 'This is description of fund member list','slug' => 'fundMemberList','type' => 'fund','created_at' => '2024-02-19 09:53:51','updated_at' => '2024-02-19 09:53:51'),
            array('id' => '29','description' => 'This is description for fund transfer.','slug' => 'fundTr','type' => 'fund','created_at' => '2024-02-19 09:54:15','updated_at' => '2024-02-19 09:54:15'),
            array('id' => '30','description' => 'This description is for payment list statement.','slug' => 'paymentList','type' => 'statementPayment','created_at' => '2024-02-19 10:14:05','updated_at' => '2024-02-19 10:14:05'),
            array('id' => '31','description' => 'This description is for payment capture statement','slug' => 'paymentCapture','type' => 'statementPayment','created_at' => '2024-02-19 10:14:27','updated_at' => '2024-02-19 10:14:27'),
            array('id' => '32','description' => 'This description is for payment complaint','slug' => 'paymentComplain','type' => 'statementPayment','created_at' => '2024-02-19 10:15:37','updated_at' => '2024-02-19 10:15:37'),
            array('id' => '33','description' => 'This is description for payout list statement.','slug' => 'payoutList','type' => 'statementPayout','created_at' => '2024-02-19 10:58:48','updated_at' => '2024-02-19 10:58:48'),
            array('id' => '34','description' => 'This is description for payout edit statement.','slug' => 'payoutEdit','type' => 'statementPayout','created_at' => '2024-02-19 10:59:12','updated_at' => '2024-02-19 10:59:12'),
            array('id' => '35','description' => 'This is description for payout complain statement.','slug' => 'payoutComplain','type' => 'statementPayout','created_at' => '2024-02-19 10:59:36','updated_at' => '2024-02-19 10:59:36'),
            array('id' => '36','description' => 'This is description for complaint list of statements','slug' => 'complaintList','type' => 'statementComplaint','created_at' => '2024-02-19 11:05:18','updated_at' => '2024-02-19 11:05:18'),
            array('id' => '37','description' => 'This is description for complaint Edit statement','slug' => 'complaintEdit','type' => 'statementComplaint','created_at' => '2024-02-19 11:05:44','updated_at' => '2024-02-19 11:05:44'),
            array('id' => '38','description' => 'This is description for account list statement.','slug' => 'accountList','type' => 'statementAccount','created_at' => '2024-02-19 11:12:18','updated_at' => '2024-02-19 11:12:18'),
            array('id' => '39','description' => 'This description is for tools role list','slug' => 'roleList','type' => 'toolsRoles','created_at' => '2024-02-19 11:33:33','updated_at' => '2024-02-19 11:33:33'),
            array('id' => '40','description' => 'This description is for tool role add.','slug' => 'roleAdd','type' => 'toolsRoles','created_at' => '2024-02-19 11:34:15','updated_at' => '2024-02-19 11:34:15'),
            array('id' => '41','description' => 'This description is for tools role edit','slug' => 'roleEdit','type' => 'toolsRoles','created_at' => '2024-02-19 11:34:42','updated_at' => '2024-02-19 11:34:42'),
            array('id' => '42','description' => 'This is tools permission description.','slug' => 'rolePermission','type' => 'toolsRoles','created_at' => '2024-02-19 11:39:47','updated_at' => '2024-02-19 11:39:47'),
            array('id' => '43','description' => 'This is role scheme description.','slug' => 'roleScheme','type' => 'toolsRoles','created_at' => '2024-02-19 11:40:16','updated_at' => '2024-02-19 11:42:43'),
            array('id' => '44','description' => 'This is description for tools permission list.','slug' => 'permissionList','type' => 'toolsPermission','created_at' => '2024-02-19 11:51:25','updated_at' => '2024-02-19 11:51:25'),
            array('id' => '45','description' => 'This is description for permission add.','slug' => 'permissionAdd','type' => 'toolsPermission','created_at' => '2024-02-19 11:51:58','updated_at' => '2024-02-19 11:51:58'),
            array('id' => '46','description' => 'This is description for permission edit.','slug' => 'permissionEdit','type' => 'toolsPermission','created_at' => '2024-02-19 11:52:21','updated_at' => '2024-02-19 11:55:38'),
            array('id' => '47','description' => 'This description is for tools Help List','slug' => 'helpList','type' => 'toolsHelp','created_at' => '2024-02-19 12:07:33','updated_at' => '2024-02-19 12:07:33'),
            array('id' => '48','description' => 'This description is for tools help add.','slug' => 'helpAdd','type' => 'toolsHelp','created_at' => '2024-02-19 12:07:55','updated_at' => '2024-02-19 12:07:55'),
            array('id' => '49','description' => 'This description is for tools help edit.','slug' => 'helpEdit','type' => 'toolsHelp','created_at' => '2024-02-19 12:08:15','updated_at' => '2024-02-19 12:08:15'),
            array('id' => '50','description' => 'This is a description for profile details tab.','slug' => 'profileDetail','type' => 'userprofile','created_at' => '2024-02-19 12:29:29','updated_at' => '2024-02-19 12:29:29'),
            array('id' => '51','description' => 'This is a description of kyc detail tab.','slug' => 'kycDetail','type' => 'userprofile','created_at' => '2024-02-19 12:29:50','updated_at' => '2024-02-19 12:29:50'),
            array('id' => '52','description' => 'This is description for password manager tab.','slug' => 'passwordManager','type' => 'userprofile','created_at' => '2024-02-19 12:30:12','updated_at' => '2024-02-19 12:30:12'),
            array('id' => '53','description' => 'This is description for pin manager tab.','slug' => 'pinManager','type' => 'userprofile','created_at' => '2024-02-19 12:30:29','updated_at' => '2024-02-19 12:30:29'),
            array('id' => '54','description' => 'This is description for role manager tab.','slug' => 'rolemanager','type' => 'userprofile','created_at' => '2024-02-19 12:30:48','updated_at' => '2024-02-19 12:30:48'),
            array('id' => '55','description' => 'This is description for mapping manager tab.','slug' => 'mappingManager','type' => 'userprofile','created_at' => '2024-02-19 12:31:10','updated_at' => '2024-02-19 12:31:10'),
            array('id' => '56','description' => 'This is description for profile image tab','slug' => 'profileImage','type' => 'userprofile','created_at' => '2024-02-19 13:06:31','updated_at' => '2024-02-19 13:06:31'),
            array('id' => '57','description' => 'This is description for company details tab.','slug' => 'companyDetails','type' => 'companyprofile','created_at' => '2024-02-19 13:09:13','updated_at' => '2024-02-19 13:09:13'),
            array('id' => '58','description' => 'This is description for company news tab.','slug' => 'companyNews','type' => 'companyprofile','created_at' => '2024-02-19 13:09:30','updated_at' => '2024-02-19 13:09:30'),
            array('id' => '59','description' => 'This is description for company notice tab.','slug' => 'companyNotice','type' => 'companyprofile','created_at' => '2024-02-19 13:09:57','updated_at' => '2024-02-19 13:09:57'),
            array('id' => '60','description' => 'This is description for company\'s support tab.','slug' => 'companySupport','type' => 'companyprofile','created_at' => '2024-02-19 13:10:54','updated_at' => '2024-02-21 12:53:33'),
            array('id' => '61','description' => 'This is description for company\'s logo.','slug' => 'companyLogo','type' => 'companyprofile','created_at' => '2024-02-19 13:18:05','updated_at' => '2024-02-21 12:53:12'),
            array('id' => '62','description' => 'This is description for Buisness key.','slug' => 'businessKey','type' => 'setting','created_at' => '2024-02-19 13:19:05','updated_at' => '2024-02-21 12:54:12'),
            array('id' => '63','description' => 'This is description for API document.','slug' => 'apiDocument','type' => 'setting','created_at' => '2024-02-19 13:20:05','updated_at' => '2024-02-21 12:55:12')
          );
          foreach ($help_box as &$help_boxs) {
            unset($help_boxs['id']);
        }
        \App\Models\Help_box::insert($help_box);
    }
}
