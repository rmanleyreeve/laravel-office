<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'uid';
    protected $guarded = [
        'created_at'
    ];

    public function absences()
    {
        return $this->hasMany(Absence::class, 'employee_fk');
    }

    public function activity()
    {
        return $this->hasMany(ActivityLog::class, 'employee_fk');
    }


}
