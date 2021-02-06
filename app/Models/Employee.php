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

    public function scopeNotDeleted($query)
    {
        $query->where('deleted', false);
    }

    public function scopeIsActive($query)
    {
        $query->where('active', true);
    }

    public function scopeBySurname($query)
    {
        $query->orderBy('surname')->orderBy('firstname');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'employee_fk');
    }

    public function activity()
    {
        return $this->hasMany(ActivityLog::class, 'employee_fk');
    }


}
