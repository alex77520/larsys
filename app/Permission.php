<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table = 'admin_permission';

    protected $primaryKey = 'id';

    protected $fillable = [ 'name', 'uri', 'status', 'is_menu', 'pid', 'taxis' ];

    public function roles()
    {
        return $this->belongsToMany( Role::class, 'admin_role_permission', 'permission_id', 'role_id' )->withTimestamps();
    }

}
