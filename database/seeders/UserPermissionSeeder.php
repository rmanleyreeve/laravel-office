<?php

namespace Database\Seeders;

use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class UserPermissionSeeder extends Seeder
{

    protected $data = [
        ['Manage Employees', 'EMPLOYEE'],
        ['Manage Reports', 'REPORT'],
        ['Manage Users', 'USER'],
        ['View Attendance', 'ATTENDANCE'],
        ['Perform Administration tasks', 'ADMIN'],
        ['Manage Absences', 'ABSENCE']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach($this->data as $d) {
            UserPermission::create([
                'permission_name' => $d[0],
                'permission_code' => $d[1],
                'permission_notes' => $faker->realText(20),
                'created_at' => $faker->dateTimeThisYear,
            ]);
        }
    }
}
