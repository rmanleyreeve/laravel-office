<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
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


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_fk', 'uid');
    }

    public function employee_name()
    {
        return $this->belongsTo(Employee::class, 'employee_fk', 'uid')
            ->where('deleted', '=', false)
            ->select(['uid', 'initials'])
            ->selectRaw("CONCAT(firstname,' ',surname) AS name");
    }

}
