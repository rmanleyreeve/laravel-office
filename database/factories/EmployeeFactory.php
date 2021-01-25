<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $firstname = $this->faker->firstName;
        $surname = $this->faker->lastName;
        return [
            'firstname' => $firstname,
            'surname' => $surname,
            'role' => $this->faker->jobTitle,
            'initials' => substr($firstname,0,1) . substr($surname,0,1),
            'email' => $this->faker->unique()->safeEmail,
            'notes' => $this->faker->realText(),
            'image' => $this->faker->image(),
            'rfid_code' => $this->faker->md5('foo'),
            'active' => 1,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => \Carbon\Carbon::now()
        ];
    }
}
