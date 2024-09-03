<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsArr = array(
            array('id' => '2','name' => 'Manage Master Distributor','slug' => 'view_md','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '3','name' => 'Manage Distributor','slug' => 'view_distributor','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '4','name' => 'Manage Retailer','slug' => 'view_retailer','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '11','name' => 'Create Master Distributor','slug' => 'create_md','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '12','name' => 'Create Distributor','slug' => 'create_distributor','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '13','name' => 'Create Retailer','slug' => 'create_retailer','type' => 'member','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '17','name' => 'Member Profile Edit','slug' => 'member_profile_edit','type' => 'memberaction','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '18','name' => 'Member Password Reset','slug' => 'member_password_reset','type' => 'memberaction','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '21','name' => 'Fund Transfer Action','slug' => 'fund_transfer','type' => 'fund','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '22','name' => 'Fund Return Action','slug' => 'fund_return','type' => 'fund','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '23','name' => 'Wallet Load Request','slug' => 'fund_request','type' => 'fund','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '25','name' => 'Account Statement View','slug' => 'account_statement','type' => 'setting','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '26','name' => 'Profile Edit','slug' => 'profile_edit','type' => 'setting','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '27','name' => 'Password Manager','slug' => 'password_reset','type' => 'setting','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '28','name' => 'Wallet Payments Report','slug' => 'fund_report','type' => 'fundreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '29','name' => 'Recharge Statement','slug' => 'recharge_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '30','name' => 'Bill Payment Statement','slug' => 'billpayment_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '31','name' => 'Vpa Agent List','slug' => 'upiid_statement','type' => 'idreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '32','name' => 'Uti Pancard Report','slug' => 'utipancard_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '33','name' => 'Money Transfer Statement','slug' => 'money_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '34','name' => 'Api Manager','slug' => 'setup_api','type' => 'setup','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '35','name' => 'Bank Account Setup','slug' => 'setup_bank','type' => 'setup','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '36','name' => 'Operator Manager','slug' => 'setup_operator','type' => 'setup','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '37','name' => 'Recharge Service','slug' => 'recharge_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '38','name' => 'Billpayment Service','slug' => 'billpayment_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '39','name' => 'Uti Pancard Service','slug' => 'utipancard_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '40','name' => 'I-Money','slug' => 'dmt1_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '41','name' => 'I-Aeps Service','slug' => 'aeps_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '42','name' => 'Uti Vle id Report Editing','slug' => 'utiid_statement_edit','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '43','name' => 'Uti Pancard Report Editing','slug' => 'Utipancard_statement_edit','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '44','name' => 'Billpay Report Editing','slug' => 'billpay_statement_edit','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '45','name' => 'Recharge Report Editing','slug' => 'recharge_statement_edit','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '46','name' => 'Money Transfer Report Editing','slug' => 'money_statement_edit','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '49','name' => 'Change Company Profile','slug' => 'change_company_profile','type' => 'resource','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '50','name' => 'Recharge Status','slug' => 'recharge_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '51','name' => 'Bill Payment Status','slug' => 'billpayment_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-06-21 07:51:40'),
            array('id' => '52','name' => 'Uti Pancard Status','slug' => 'utipancard_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-06-21 07:52:21'),
            array('id' => '53','name' => 'Complaint Subject Manager','slug' => 'complaint_subject','type' => 'complaint','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '54','name' => 'Complaint Manager','slug' => 'complaint_edit','type' => 'complaint','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '55','name' => 'Complaint Submission','slug' => 'complaint','type' => 'complaint','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '57','name' => 'Billpayment Report','slug' => 'member_billpayment_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '58','name' => 'Recharge Report','slug' => 'member_recharge_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '59','name' => 'Money Transfer Report','slug' => 'member_money_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '60','name' => 'Utipancard Report','slug' => 'member_utipancard_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '61','name' => 'Utiid Report','slug' => 'member_utiid_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '62','name' => 'Main Wallet Statement','slug' => 'member_account_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '65','name' => 'Aeps Statement','slug' => 'aeps_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-06-24 23:04:02','updated_at' => '2019-06-24 23:04:24'),
            array('id' => '66','name' => 'Aeps Report','slug' => 'member_aeps_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-06-24 23:09:42','updated_at' => '2019-06-24 23:09:42'),
            array('id' => '67','name' => 'Payout Request','slug' => 'aeps_fund_request','type' => 'aepsfund','help_box' => 'Permission Description','created_at' => '2019-06-24 23:38:38','updated_at' => '2019-06-24 23:39:40'),
            array('id' => '68','name' => 'Aeps Settlement Request','slug' => 'aeps_fund_view','type' => 'aepsfundreport','help_box' => 'Permission Description','created_at' => '2019-06-24 23:39:04','updated_at' => '2019-06-24 23:39:36'),
            array('id' => '69','name' => 'Aeps Settlement Report','slug' => 'aeps_fund_report','type' => 'aepsfundreport','help_box' => 'Permission Description','created_at' => '2019-06-24 23:39:30','updated_at' => '2019-06-24 23:39:30'),
            array('id' => '70','name' => 'Aeps Wallet Statement','slug' => 'awallet_statement','type' => 'setting','help_box' => 'Permission Description','created_at' => '2019-06-25 03:37:30','updated_at' => '2019-06-25 03:37:30'),
            array('id' => '71','name' => 'Aeps Wallet Statement','slug' => 'member_awallet_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2019-06-25 03:38:01','updated_at' => '2019-06-25 03:38:01'),
            array('id' => '72','name' => 'Member Stock Manager','slug' => 'member_stock_manager','type' => 'memberaction','help_box' => 'Permission Description','created_at' => '2019-06-25 05:05:08','updated_at' => '2019-06-25 05:05:08'),
            array('id' => '73','name' => 'View Commission','slug' => 'view_commission','type' => 'resource','help_box' => 'Permission Description','created_at' => '2019-06-25 05:57:11','updated_at' => '2019-06-25 05:57:11'),
            array('id' => '74','name' => 'Payout List','slug' => 'contact_statement','type' => 'idreport','help_box' => 'Permission Description','created_at' => '2019-07-05 13:34:44','updated_at' => '2021-12-09 13:51:38'),
            array('id' => '75','name' => 'K-Aeps Service','slug' => 'kaeps_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2019-08-27 18:17:43','updated_at' => '2019-08-27 18:17:43'),
            array('id' => '76','name' => 'Aeps id Statement Editing','slug' => 'aepsid_statement_editing','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2020-01-26 06:28:02','updated_at' => '2020-01-26 06:28:02'),
            array('id' => '77','name' => 'Aeps Statement Editing','slug' => 'aeps_statement_editing','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2020-01-26 06:28:49','updated_at' => '2020-01-26 06:28:49'),
            array('id' => '78','name' => 'Money Statement Status','slug' => 'money_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2020-01-26 06:29:44','updated_at' => '2020-04-24 00:48:17'),
            array('id' => '79','name' => 'Aeps Statement Status','slug' => 'aeps_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2020-01-26 06:30:09','updated_at' => '2020-04-24 00:47:00'),
            array('id' => '80','name' => 'Member Aeps Agent List','slug' => 'member_aepsid_statement','type' => 'memberreport','help_box' => 'Permission Description','created_at' => '2020-01-26 06:39:54','updated_at' => '2020-01-26 06:39:54'),
            array('id' => '81','name' => 'Gst Service','slug' => 'gst_service','type' => 'service','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '82','name' => 'gst Statement','slug' => 'gst_statement','type' => 'report','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '83','name' => 'Itr Service','slug' => 'itr_service','type' => 'service','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '84','name' => 'ITR Statement','slug' => 'itr_statement','type' => 'report','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '85','name' => 'Nsdlpancard Service','slug' => 'nsdl_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2020-09-08 00:36:51','updated_at' => '2020-09-08 00:36:51'),
            array('id' => '86','name' => 'Nsdlpancard Report','slug' => 'nsdlpan_report','type' => 'report','help_box' => 'Permission Description','created_at' => '2020-09-08 01:28:07','updated_at' => '2020-09-08 01:28:07'),
            array('id' => '87','name' => 'Api Partner Account','slug' => 'apiuser_acc_manager','type' => 'apisetting','help_box' => 'Permission Description','created_at' => '2020-09-09 09:24:37','updated_at' => '2020-09-09 09:24:37'),
            array('id' => '88','name' => 'I-Aeps Service','slug' => 'ifaeps_service','type' => 'service','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '89','name' => 'I-Matm Service','slug' => 'matm_service','type' => 'service','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '90','name' => 'Matm Statement','slug' => 'matm_statement','type' => 'report','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '91','name' => 'Matm Report','slug' => 'member_matm_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '92','name' => 'Matm Settlement Request','slug' => 'matm_fund_request','type' => 'aepsfund','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '93','name' => 'Matm Settlement Request','slug' => 'matm_fund_view','type' => 'aepsfundreport','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '94','name' => 'Matm Settlement Report','slug' => 'matm_fund_report','type' => 'aepsfundreport','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '95','name' => 'Matm Wallet Statement','slug' => 'matmwallet_statement','type' => 'setting','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '96','name' => 'matm Wallet Statement','slug' => 'member_matmwallet_statement_view','type' => 'memberreport','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '98','name' => 'Matm Status','slug' => 'matm_status','type' => 'service','help_box' => 'Permission Description','created_at' => NULL,'updated_at' => NULL),
            array('id' => '99','name' => 'UPI Service','slug' => 'upi_service','type' => 'service','help_box' => 'Permission Description','created_at' => '2021-10-08 03:15:02','updated_at' => '2021-10-08 03:15:02'),
            array('id' => '100','name' => 'Payout Status','slug' => 'payout_status','type' => 'reportstatus','help_box' => 'Permission Description','created_at' => '2021-10-30 12:42:39','updated_at' => '2021-10-30 12:42:39'),
            array('id' => '101','name' => 'Payout Statement','slug' => 'payout_statement','type' => 'report','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '102','name' => 'Api Documents','slug' => 'api_document','type' => 'apisetting','help_box' => 'Permission Description','created_at' => '2019-05-27 04:36:54','updated_at' => '2019-05-27 04:36:54'),
            array('id' => '103','name' => 'scheme','slug' => 'scheme','type' => 'member','help_box' => 'Permission Description','created_at' => '2023-11-27 18:11:30','updated_at' => '2023-11-27 18:11:30'),
            array('id' => '104','name' => 'refund','slug' => 'refund','type' => 'reportedit','help_box' => 'Permission Description','created_at' => '2023-12-06 15:51:59','updated_at' => '2023-12-06 15:51:59'),
            array('id' => '105','name' => 'capture','slug' => 'capture','type' => 'reportedit','help_box' => 'Permission\'s Description','created_at' => '2023-12-06 15:52:18','updated_at' => '2024-02-21 12:48:26')
          );
          foreach ($permissionsArr as &$permissionArr) {
            unset($permissionArr['id']);
        }
        \App\Models\Permission::insert($permissionsArr);
    }
}
