<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class ProviderSeederDb extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = array(
        array('id' => '1242','name' => 'UPI-1 commision slab','recharge1' => 'upi1','recharge2' => 'upi1','logo' => NULL,'api_id' => '7','type' => 'upi','status' => '1','paramcount' => NULL,'manditcount' => NULL,'paramname' => NULL,'maxlength' => NULL,'minlength' => NULL,'regex' => NULL,'ismandatory' => NULL,'fieldtype' => NULL,'state' => NULL),
        array('id' => '1243','name' => 'Payout Upto 1000','recharge1' => 'payout1k','recharge2' => 'payout1k','logo' => NULL,'api_id' => '6','type' => 'payout','status' => '1','paramcount' => NULL,'manditcount' => NULL,'paramname' => NULL,'maxlength' => NULL,'minlength' => NULL,'regex' => NULL,'ismandatory' => NULL,'fieldtype' => NULL,'state' => NULL),
        array('id' => '1244','name' => 'Payout 1000 to 25000','recharge1' => 'payout25k','recharge2' => 'payout25k','logo' => NULL,'api_id' => '6','type' => 'payout','status' => '1','paramcount' => NULL,'manditcount' => NULL,'paramname' => NULL,'maxlength' => NULL,'minlength' => NULL,'regex' => NULL,'ismandatory' => NULL,'fieldtype' => NULL,'state' => NULL),
        array('id' => '1245','name' => 'Payout 25000 To 2Lakh','recharge1' => 'payout2l','recharge2' => 'payout2l','logo' => NULL,'api_id' => '6','type' => 'payout','status' => '1','paramcount' => NULL,'manditcount' => NULL,'paramname' => NULL,'maxlength' => NULL,'minlength' => NULL,'regex' => NULL,'ismandatory' => NULL,'fieldtype' => NULL,'state' => NULL),
        array('id' => '1248','name' => 'UPI-2 commision slab','recharge1' => 'upi2','recharge2' => 'upi2','logo' => NULL,'api_id' => '8','type' => 'upi','status' => '1','paramcount' => NULL,'manditcount' => NULL,'paramname' => NULL,'maxlength' => NULL,'minlength' => NULL,'regex' => NULL,'ismandatory' => NULL,'fieldtype' => NULL,'state' => NULL)
      );
      foreach ($providers as &$provider) {
        unset($provider['id']);
    }

        \App\Models\Api\Provider::insert($providers);
    }
}
