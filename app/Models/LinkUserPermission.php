<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkUserPermission extends Model
{
    use HasFactory;

    protected $table = 'link_user_permission';
    protected $primaryKey = 'id';
    protected $guarded = [
        'created_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_fk', 'user_id');
    }

    public function permission() {
        return $this->belongsTo(UserPermission::class, 'permission_fk', 'id');
    }

}
