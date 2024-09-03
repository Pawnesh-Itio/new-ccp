<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apis = array(
            array('id' => '1','product' => 'Fund','name' => 'fund','url' => NULL,'username' => NULL,'password' => NULL,'optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'fund','type' => 'fund','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-16 16:42:49'),
            array('id' => '2','product' => 'I-Payin-Uat','name' => 'IserveU-PayIn-Uat','url' => 'https://apidev.iserveu.online/staging/','username' => 'EMI8PIj5Esi7T5Q4LVH5X5LHe5uNwqIp0BKdL3sCl8WHlAAb','password' => 'Q4jAlwLuSUcNNE87D2W3b8PQHv25aKxiYrotlXcxcyX6AOx8BdcLprJCqFGHGVXG','optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'uatloadwallet','type' => 'fund','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-19 09:31:40'),
            array('id' => '3','product' => 'NewPayout','name' => 'Payout','url' => 'Jenw url','username' => NULL,'password' => NULL,'optional1' => 'TEST','optional2' => NULL,'optional3' => NULL,'code' => 'kppayout','type' => 'money','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-17 13:03:23'),
            array('id' => '4','product' => 'I-Payout-uat','name' => 'IserveU-Payout-uat','url' => 'https://apidev.iserveu.online/staging/','username' => 'EMI8PIj5Esi7T5Q4LVH5X5LHe5uNwqIp0BKdL3sCl8WHlAAb','password' => 'Q4jAlwLuSUcNNE87D2W3b8PQHv25aKxiYrotlXcxcyX6AOx8BdcLprJCqFGHGVXG','optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'ipayoutuat','type' => 'money','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-19 09:31:40'),
            array('id' => '5','product' => 'CS uat UPI','name' => 'CS UPI uat','url' => 'https://merchantuat.timepayonline.com/evok/','username' => '2b273ac2cc334f05812b34a04310360a','password' => '40103a8179f140d78867648587655baa','optional1' => '46efbba174d340d791ba66fa8f6606c1','optional2' => 'SPAYFIN','optional3' => 'SPAYFIN001','code' => 'uatcosmosupi','type' => 'money','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2024-02-28 12:25:03'),
            array('id' => '6','product' => 'I-Payout','name' => 'IserveU-Payout','url' => 'https://apiprod.iserveu.tech/productionV2/','username' => 'OATk1KkBd9kSLbYa1ocys0ALhDoIg1DGnCoCWCP2p9KX0hgl','password' => 'kcoC04O9tDgKNAMEytLOSaRIGJAeUAUGGRmUPkEEKDhoQRC4NkrHSB15u17jcH9P','optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'ipayout','type' => 'money','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-19 09:31:40'),
            array('id' => '7','product' => 'I-PayIn','name' => 'IserveU-PayIn','url' => 'https://apiprod.iserveu.tech/productionV2/','username' => 'oEPGclBgS5TD0UYDW0Hv5qNG2PonGaAwxdRwzdGZRCpkPq7x','password' => '1tzVQBczh6TzOLXVEuQM9kzvgjmMfcV6qQaiFFYtCF7TkG0rXEi1onrCHUNtEAfG','optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'loadwallet','type' => 'fund','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-19 09:31:40'),
            array('id' => '8','product' => 'CS UPI','name' => 'CS UPI','url' => 'https://merchantprod.timepayonline.com/evok/','username' => '58d915f30f0e4421b90ca903c97859e6','password' => 'e76c6205bc4b46a0a4c3301c94587e9a','optional1' => 'c0019ec9cb994345a8a180d377ba6f4a','optional2' => 'SPAYFIN','optional3' => 'SPAYFIN001','code' => 'cosmosupi','type' => 'money','status' => '1','created_at' => '2023-05-16 16:42:49','updated_at' => '2023-05-19 09:31:40'),
            array('id' => '9','product' => 'Open Acquiring','name' => 'Open Acquiring','url' => NULL,'username' => NULL,'password' => NULL,'optional1' => NULL,'optional2' => NULL,'optional3' => NULL,'code' => 'open_cquiring','type' => 'payment','status' => '1','created_at' => '2023-11-30 21:57:25','updated_at' => '2023-11-30 21:57:25')
          );
          foreach ($apis as &$api) {
            unset($api['id']);
        }
        \App\Models\Api::insert($apis);
    }
}
 