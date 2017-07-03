<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_log';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'uri', 'ip'
    ];

    public function getNameByUri($uri)
    {
        $permission_name = Permission::where('uri', '=', $uri)->select('name')->first();

        return $permission_name->name;
    }
}
