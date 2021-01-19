<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table='activity_log';
    protected $primaryKey='uid';
    protected $guarded = [
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_fk', 'uid');
    }


}
