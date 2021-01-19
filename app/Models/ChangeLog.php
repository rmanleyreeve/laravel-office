<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $table='change_log';
    protected $primaryKey='uid';
    protected $guarded = [
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_fk', 'uid');
    }


}


