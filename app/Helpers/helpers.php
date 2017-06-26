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
