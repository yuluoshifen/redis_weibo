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

/*
 * 微博表以哈希形式存储
 **/
$post = [
    'time' => time(),
    'content' => $content,
    'userid' => $user['userid'],
    'username' => $user['username']
];
$redis->hMSet('post:postid:' . $postid, $post);

/*
 * 把微博推给自己的粉丝
 * 1. 获取自己的所有粉丝
 * 2. 给所有粉丝和自己推送发布的内容(链表)
 **/
$followers   = $redis->sMembers('follower:' . $user['userid']);
$followers[] = $user['userid'];
foreach ($followers as $follower_id)
{
    $redis->lPush('receivepost:' . $follower_id, $postid);
}


header('location:home.php');

include 'footer.php';