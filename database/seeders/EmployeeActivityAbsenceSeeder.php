<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Employee;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class EmployeeActivityAbsenceSeeder extends Seeder
{

    protected function randtime($min,$max, $d = null) : string
    {
        $date = $d ?? date('Y-m-d');
        $t1 = strtotime($date. "{$min}:00:00");
        $t2 = strtotime($date. "{$max}:00:00")-1;
        $rt = rand($t1,$t2);
        return date('Y-m-d H:i:s', $rt);
    }

    protected $activities = [
        ['ENTRY','07','09'],
        ['EXIT','12','13'],
        ['ENTRY','13','14'],
        ['EXIT','16','18']
    ];

    protected function isWeekend($date) : bool
    {
        return (date('N', $date) >= 6);
    }

    /**
     * Add 4 random entry & exit times for each day
     * on weekdays in past 30 days
     * @param $id Employee->uid
     */
    protected function seedActivity($id) : void
    {
        $faker = Faker::create();

        foreach(range(0,30) as $i) {
            $ts = strtotime("-$i day");
            if($this->isWeekend($ts)) {
                continue;
            }
            $date = date('Y-m-d',$ts);
            foreach ($this->activities as $a) {
                $al = ActivityLog::create([
                    'employee_fk' => $id,
                    "activity" => $a[0],
                    "time_logged" => $this->randtime($a[1], $a[2], $date),
                    'notification_read' => rand(0,1),
                    'created_at' => $faker->dateTimeThisYear,
                ]);
            }
        }

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        Employee::factory()
            ->count(6)
            ->create()
            ->each(function ($employee) {
                $this->seedActivity($employee->uid);
                $employee->absences()->saveMany(
                    Absence::factory()->count(rand(0,6))->make()
                );
            });
    }
}
