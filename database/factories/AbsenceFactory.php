<?php

namespace Database\Factories;

use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Absence::class;

    /**
     * Insert a random absence in a date range
     * from 2 months before to 2 months ahead of today
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'absence_date' => date(
                'Y-m-d',
                $this->faker->dateTimeBetween('-2 months','+ 2 months')->getTimestamp()
            ),
            'absence_type' => ['HOLIDAY', 'SICKNESS'][rand(0,1)],
            'duration' => ['FULL_DAY', 'HALF_DAY_AM', 'HALF_DAY_PM'][rand(0,2)],
            'notes' => $this->faker->realText(20),
            'created_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
