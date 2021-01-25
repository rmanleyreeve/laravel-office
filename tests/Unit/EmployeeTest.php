<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class EmployeeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    //use RefreshDatabase;

    public function testEmployee()
    {
        $employee = $this->insertEmployee();
        $this->assertInstanceOf(Employee::class, $employee, 'Is employee class');
        $this->assertNotInstanceOf(User::class, $employee, 'Is not user class');
        $this->assertStringContainsString('@',$employee->email);

    }
}
