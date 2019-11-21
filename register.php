<?php
include './header.php';
include './lib.php';

if (isLogin() != false)
{
    header('location:home.php');
    exit;
}

$username  = $_POST['username'];
$password  = $_POST['password'];
$password2 = $_POST['password2'];

if (!$username || !$password || !$password2)
{
    error('请填写完整信息！');
}

if ($password != $password2)
{
    error('两次填写的密码不一致！');
}

$redis = connRedis();
if ($redis->get('user:username:' . $username . ':userid'))
{
    error('该用户名已存在，请重新输入用户名！');
}

$userid = $redis->incr('global:userid');

$redis->set('user:userid:' . $userid . ':username', $username);
$redis->set('user:userid:' . $userid . ':password', $password);
$redis->set('user:username:' . $username . ':userid', $userid);

//通过一个链表，维护50个最新注册的userid
$redis->lPush('latestfiftyuserid', $userid);
$redis->lTrim('latestfiftyuserid', 0, 49);

include './footer.php';