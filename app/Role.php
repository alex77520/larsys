<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'admin_roles';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'admin_user_role', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_role_permission', 'role_id', 'permission_id');
    }
}
