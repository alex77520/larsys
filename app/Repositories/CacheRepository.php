<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class CacheRepository
{
    /**
     * 删除所有权限菜单和权限URI缓存
     */
    public function removeAllCache()
    {
        Redis::del(env('REDIS_ADMIN_HASH_KEY'));
    }

    /**
     * 通过用户ID删除该用户权限菜单和权限URI缓存
     *
     * @param $user_id
     */
    public function removeCacheBy($user_id)
    {
        Redis::hdel(env('REDIS_ADMIN_HASH_KEY'), 'menus_' . $user_id, 'uris_' . $user_id);
    }

    /**
     * 添加redis hash格式的缓存
     *
     * @param $key
     * @param $field
     * @param $value
     */
    public function hashSet($key, $field, $value)
    {
        Redis::hset($key, $field, $value);
    }

    /**
     * 获取hash缓存
     *
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hashGet($key, $field)
    {
        return Redis::hget($key, $field);
    }

    /**
     * 检查缓存中hash格式某个键值的字段是否存在
     *
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hashFieldExist($key, $field)
    {
        return Redis::hexists($key, $field);
    }

    /**
     * 检查hash缓存中某个键是否存在
     *
     * @param $key
     * @return mixed
     */
    public function keyExist($key)
    {
        return Redis::exists($key);
    }
}