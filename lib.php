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