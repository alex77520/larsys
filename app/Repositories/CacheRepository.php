<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class CacheRepository
{
    public function removeAllCache()
    {
        Redis::del(env('REDIS_ADMIN_HASH_KEY'));
    }

    public function removeCacheBy($user_id)
    {
        Redis::hdel(env('REDIS_ADMIN_HASH_KEY'), 'menus_' . $user_id, 'uris_' . $user_id);
    }

    public function hashSet($key, $field, $value)
    {
        Redis::hset($key, $field, $value);
    }

    public function hashGet($key, $field)
    {
        return Redis::hget($key, $field);
    }

    public function hashFieldExist($key, $field)
    {
        return Redis::hexists($key, $field);
    }

    public function keyExist($key)
    {
        return Redis::exists($key);
    }
}