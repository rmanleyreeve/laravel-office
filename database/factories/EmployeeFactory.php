<?php

namespace Database\Factories;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Create dummy employee
     *
     * @return array
     */
    public function definition(): array
    {
        $firstname = $this->faker->firstName;
        $surname = $this->faker->lastName;
        return [
            'firstname' => $firstname,
            'surname' => $surname,
            'role' => $this->faker->jobTitle,
            'initials' => substr($firstname, 0, 1) . substr($surname, 0, 1),
            'email' => $this->faker->unique()->safeEmail,
            'notes' => $this->faker->realText(),
            'image' => file_get_contents($this->faker->image()),
            'rfid_code' => md5(Str::random(10)),
            'active' => 1,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => Carbon::now()
        ];
    }
}
