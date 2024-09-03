<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            "name" => "Super Admin",
            'slug' => "admin"
        ]);

        \App\Models\Role::create([
            "name" => "Admin",
            'slug' => "whitelable"
        ]);

        \App\Models\Role::create([
            "name" => "Master Distributor",
            'slug' => "md"
        ]);

        \App\Models\Role::create([
            "name" => "Distributor",
            'slug' => "distributor"
        ]);

        \App\Models\Role::create([
            "name" => "Retailer",
            'slug' => "retailer"
        ]);

        \App\Models\Role::create([
            "name" => "Merchant",
            'slug' => "apiuser"
        ]);
    }
}
