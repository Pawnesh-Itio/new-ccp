<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PaymodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Paymode::create(["name" => "IMPS"]);
        \App\Models\Paymode::create(["name" => "NEFT"]);
        \App\Models\Paymode::create(["name" => "NET BANKING"]);
        \App\Models\Paymode::create(["name" => "CASH"]);
        \App\Models\Paymode::create(["name" => "OTHER"]);
    }
}
