<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $table = 'user_permissions';
    protected $primaryKey = 'id';
    protected $guarded = [
        'created_at'
    ];

    public function link_user_permission()
    {
        return $this->hasMany(LinkUserPermission::class, 'permission_fk');
    }


}
