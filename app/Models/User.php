<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'user_email',
        'administrator',
        'active',
        'user_notes',
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_reset_token',
    ];

    public $permissions = [];

    public $permission_names_array = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function changes()
    {
        return $this->hasMany(ChangeLog::class, 'user_fk');
    }

    public function permissions()
    {
        return $this->hasMany(LinkUserPermission::class, 'user_fk')
            ->select('permission_fk');
    }

    public function permission_names()
    {
        return $this->hasMany(LinkUserPermission::class, 'user_fk')
            ->join('user_permissions AS up', 'up.id', '=', 'permission_fk')
            ->select('up.permission_name');
    }


}
