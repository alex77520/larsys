<?php

/**
* 无线级导航
*
* @param array $nav
* @param int $pid
* @return array
*/
function buildTree(array $nav = [], $pid = 0)
{
    $arr = [];

    foreach ($nav as $v) {
        // 寻找子节点
        if ($v['pid'] == $pid) {
            $v['sub_menu'] = buildTree($nav, $v['id']);
            $arr[] = $v;
        }
    }

    return $arr;
}

/*
 * 获取客户端真实IP
 */
function getClientIP()
{
    global $ip;

    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "未知";

    return $ip;
}

/**
 * 正则过滤访问url中的无用字符，获取纯净的uri
 *
 * @param $uri
 * @return mixed
 */
function pregReplaceUri($uri)
{
    return preg_replace('/(((\?)(\w|=)+)|(\/\d+))/', '', $uri);
}
