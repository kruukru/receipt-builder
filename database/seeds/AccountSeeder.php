<?php

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayAccounts = [[
            'name' => "Jermaine", 
            "username" => "qwe", 
            "password" => bcrypt("qwe"), 
            'type' => 0
        ],];

        DB::table('account')->insert($arrayAccounts);
    }
}
