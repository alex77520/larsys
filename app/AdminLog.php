<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{

    protected $table = 'admin_log';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'uri', 'ip', 'username', 'expired_at'
    ];

    public function getNameByUri( $uri )
    {
        $permission_name = Permission::where( 'uri', '=', $uri )->select( 'name' )->first();

        return $permission_name->name;
    }
}
