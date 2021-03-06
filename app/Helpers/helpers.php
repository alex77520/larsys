<?php

/**
* 无线级导航
*
* @param array $nav
* @param int $pid
* @return array
*/
function buildTree($nav = [], $pid = 0)
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


/**
 * 选择下拉选项拼接
 *
 * @param array $buildArr 数组
 * @param string $selected_id 已选择项ID
 * @param string $separation 菜单层级分隔符
 * @param int $repeat_num 分隔符重复次数
 * @return string
 */
function buildOptionStr($buildArr = [], $selected_id = '', $separation = '', $repeat_num = 1)
{
    $options = '';
    $repeat_num = $repeat_num * 2;

    foreach ($buildArr as $item) {

        $options .= '<option value="'. $item['id'] .'"';

        if ($item['id'] == $selected_id) $options .= ' selected>';

        $options .= str_repeat($separation, $repeat_num) . $item['name'] .'</option>';

        if ($item['sub_menu'] != '') {

            $options .= buildOptionStr($item['sub_menu'], $selected_id, '—', $repeat_num);

        }
    }

    return $options;
}

/**
 * 一级展示下拉菜单
 *
 * @param $mixed
 * @param int $pid
 * @return array
 */
function setDropDownMenu($mixed, $pid = 0)
{
    static $menu = [];

    foreach ($mixed as $item) {
        if ($item->pid == $pid) {
            $menu[] = $item;
            setDropDownMenu($mixed, $item->id);
        }
    }

    return $menu;
}

/* ===================================================================================================== */
/* 文件读取操作 */

/**
 * 读取文件夹下的全部文件
 *
 * @param $dir
 * @return array
 */
function readDirFiles($dir)
{
    $handler = opendir($dir);
    $files = [];

    while( ($filename = readdir($handler)) !== false ) {

        if($filename != "." && $filename != ".."){

            $files[] = $filename;

        }
    }

    closedir($handler);

    return $files;
}

/**
 * 获取blade模板的文件名前缀和文件名本身
 *
 * @param $blades
 * @return array
 */
function getBladePrefixs($blades)
{
    $prefixs = [];

    foreach ($blades as $key => $blade) {

        $explodes = explode('.', $blade);

        $prefixs[$key]['prefix'] = $explodes[0];
        $prefixs[$key]['file'] = $blade;
    }

    return $prefixs;
}

function getTemps($dir = '/')
{
    return getBladePrefixs(readDirFiles($dir));
}

/* ===================================================================================================== */

/**
 * 根据模型（类型）代码判断模型（类型）名称
 * 主要用在model和type的判断
 * Cate.php Image.php
 *
 * @param $arr
 * @param $type
 * @return mixed
 */
function judgeType($arr, $type) {

    if ($type !== null) {

        return array_key_exists($type, $arr) ? $arr[$type] : '未知';

    }

    return $arr;
}