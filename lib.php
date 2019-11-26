<?php

/**
 * 提示错误信息
 *
 * @param $msg
 */
function error($msg)
{
    echo $msg;
    exit;
}

/**
 * 连接redis
 *
 * @return null|Redis
 */
function connRedis()
{
    static $redis = null;
    if ($redis === null)
    {
        $redis = new Redis();
    }
    $redis->connect('localhost');

    return $redis;
}

/**
 * 判断是否登陆
 *
 * @return array|bool
 */
function isLogin()
{
    if (!$_COOKIE['userid'] || !$_COOKIE['username'])
    {
        return false;
    }

    if (!$_COOKIE['authsecret'])
    {
        return false;
    }

    $redis      = connRedis();
    $authsecret = $redis->get('user:userid:' . $_COOKIE['userid'] . ':authsecret');

    if ($_COOKIE['authsecret'] != $authsecret)
    {
        return false;
    }

    return [
        'userid'   => $_COOKIE['userid'],
        'username' => $_COOKIE['username']
    ];
}

/**
 * 生成一个16位的随机字符串
 *
 * @return bool|string
 */
function randString()
{
    $str = "abcdefghijklmnopqrstuvwxyz1234567890";

    return substr(str_shuffle($str), 0, 16);
}

/**
 * 格式化时间
 * @param $time
 * @return string
 */
function formatTime($time)
{
    $diff = time() - $time;

    if ($diff <= 60)
    {
        $str = floor($diff) . ' 秒';
    }
    elseif ($diff <= 3600)
    {
        $str = floor($diff / 60) . ' 分钟';
    }
    elseif ($diff <= 86400)
    {
        $str = floor($diff / 3600) . ' 小时';
    }
    else
    {
        $str = floor($diff / 86400) . ' 天';
    }

    return $str;
}