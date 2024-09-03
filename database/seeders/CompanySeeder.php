<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Company::create([
            "companyname" => "Instant Charge Backoffice",
            'website' => $_SERVER['HTTP_HOST'],
            'status' => '1'
        ]);
    }
}
