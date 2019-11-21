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

    return [
        'userid'   => $_COOKIE['userid'],
        'username' => $_COOKIE['username']
    ];
}