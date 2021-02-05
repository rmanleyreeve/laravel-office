<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        User::create([
            'username' => 'test',
            'password' => password_hash('test',PASSWORD_BCRYPT),
            'fullname' => 'Test User',
            'user_email' => $faker->safeEmail,
            'administrator' => 1,
            'active' => 1,
            'user_notes' => $faker->realText(50),
            'created_at' => $faker->dateTimeThisYear,
            'last_login' => null,
            'password_reset_token' => '',
            'deleted' => 0
        ]);
    }
}
