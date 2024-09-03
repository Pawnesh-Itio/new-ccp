<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(Permission::class);
        $this->call(CompanySeeder::class);
        $this->call(CircleSeeder::class);
        $this->call(PoratSettingSeeder::class);
        $this->call(PoratSettingSeederDb::class);
        $this->call(PaymodeSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(ProviderSeederDb::class);
        $this->call(ApiSeeder::class);
        $this->call(ApiSeederDb::class);
        $this->call(BankSeeder::class);
        $this->call(BankSeederDb::class);
        $this->call(HelpboxSeeder::class);
        $this->call(CurrancySeeder::class);
    }
}
