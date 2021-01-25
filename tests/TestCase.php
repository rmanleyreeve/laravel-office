<?php

namespace Tests;

use App\Models\Employee;
use App\Models\LinkUserPermission;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;




abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;

    /**
     * Mocks the creation of an Employee
     *
     * @return Employee
     */
    protected function insertEmployee($attrs = [])
    {
        $firstname = $this->faker->firstName;
        $surname = $this->faker->lastName;
        $default = [
            'firstname' => $firstname,
            'surname' => $surname,
            'role' => $this->faker->jobTitle,
            'initials' => substr($firstname,0,1) . substr($surname,0,1),
            'email' => $this->faker->freeEmail,
            'notes' => $this->faker->realText(),
            'image' => $this->faker->image(),
            'rfid_code' => $this->faker->md5('foo'),
            'active' => 1,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ];

        $attrs = array_merge($default, $attrs);
        $employee = Employee::factory()->create($attrs);
        return $employee;
    }

    /**
     * Mocks the creation of a User
     *
     * @return User
     */
    protected function insertUser($attrs = [])
    {
        $firstName = $this->faker->firstName;
        $default = [
            'username' => substr($firstName,0,6).$this->faker->numberBetween(1, 100),
            'password' => bcrypt($this->faker->password(8)),
            'fullname' => $firstName . ' ' . $this->faker->lastName,
            'user_email' => $this->faker->freeEmail,
            'administrator' => 0,
            'active' => 1,
            'user_notes' => $this->faker->realText(),
            'telephone' => $this->faker->e164PhoneNumber,
            'company_id' => $company->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'deleted' => 0
        ];
        $attrs = array_merge($default, $attrs);
        $user = factory(\App\User::class)->create($attrs);

        $permissions = UserPermission::select(permission_id)->inRandomOrder()->limit(3)->get();
        foreach($permissions as $p) {
            LinkUserPermission::create([
                'permission_fk' => $p,
                'user_fk' => $user->user_id
            ]);
        }

        return $user;
    }


}
