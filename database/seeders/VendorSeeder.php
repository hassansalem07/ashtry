<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('vendors')->insert([
            'name' =>$faker->name(),
            'email' => $faker->safeEmail,
            'password' => Hash::make('12345678'),
        ]);
    }
}