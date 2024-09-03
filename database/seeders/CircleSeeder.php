<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Circle::create(['state' => 'ASSAM','plan_code' => '2']);
        \App\Models\Circle::create(['state' => 'BIHAR JHARKHAND','plan_code' => '3']);
        \App\Models\Circle::create(['state' => 'CHENNAI','plan_code' => '4']);
        \App\Models\Circle::create(['state' => 'GUJARAT','plan_code' => '6']);
        \App\Models\Circle::create(['state' => 'HARYANA','plan_code' => '7']);
        \App\Models\Circle::create(['state' => 'HIMACHAL PRADESH','plan_code' => '8']);
        \App\Models\Circle::create(['state' => 'JAMMU KASHMIR','plan_code' => '9']);
        \App\Models\Circle::create(['state' => 'KARNATAKA','plan_code' => '10']);
        \App\Models\Circle::create(['state' => 'KERALA','plan_code' => '11']);
        \App\Models\Circle::create(['state' => 'KOLKATA','plan_code' => '12']);
        \App\Models\Circle::create(['state' => 'MAHARASHTRA','plan_code' => '13']);
        \App\Models\Circle::create(['state' => 'MADHYA PRADESH','plan_code' => '14']);
        \App\Models\Circle::create(['state' => 'CHHATTISGARH','plan_code' => '0']);
        \App\Models\Circle::create(['state' => 'MUMBAI','plan_code' => '15']);
        \App\Models\Circle::create(['state' => 'NORTH EAST','plan_code' => '16']);
        \App\Models\Circle::create(['state' => 'ORISSA','plan_code' => '17']);
        \App\Models\Circle::create(['state' => 'PUNJAB','plan_code' => '18']);
        
        \App\Models\Circle::create(['state' => 'RAJASTHAN','plan_code' => '19']);
        \App\Models\Circle::create(['state' => 'TAMIL NADU','plan_code' => '20']);
        \App\Models\Circle::create(['state' => 'UP EAST','plan_code' => '21']);
        \App\Models\Circle::create(['state' => 'UP WEST','plan_code' => '22']);
        \App\Models\Circle::create(['state' => 'WEST BENGAL','plan_code' => '23']);
        \App\Models\Circle::create(['state' => 'DELHI NCR','plan_code' => '5']);
        \App\Models\Circle::create(['state' => 'ANDHRA PRADESH','plan_code' => '1']);
        \App\Models\Circle::create(['state' => 'Delhi/NCR','plan_code' => '1']);
        \App\Models\Circle::create(['state' => 'UTTARAKHAND','plan_code' => '0']);
    }
}
