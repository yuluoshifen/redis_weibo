<?php
include 'header.php';
include 'lib.php';

if (($user = isLogin()) == false)
{
    header('location:index.php');
    exit;
}

$content = $_POST['status'];
if (!$content)
{
    error('请写点什么提交！');
}

$redis  = connRedis();
$postid = $redis->incr('global:postid');
$redis->set('post:postid:' . $postid . ':time', time());
$redis->set('post:postid:' . $postid . ':content', $content);
$redis->set('post:postid:' . $postid . ':userid', $user['userid']);

header('location:home.php');

include 'footer.php';