<?php
include 'header.php';
include 'lib.php';

if (isLogin() != false)
{
    header('location:home.php');
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

if (!$username || !$password)
{
    error('请输入完整内容！');
}

$redis  = connRedis();
$userid = $redis->get('user:username:' . $username . ':userid');
if (!$userid)
{
    error('该用户不存在！');
}

if ($redis->get('user:userid:' . $userid . ':password') != $password)
{
    error('密码错误，请重新输入！');
}

setcookie('username', $username);
setcookie('userid', $userid);

header('location:home.php');

include 'footer.php';