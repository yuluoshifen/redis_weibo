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
//$redis->set('post:postid:' . $postid . ':time', time());
//$redis->set('post:postid:' . $postid . ':content', $content);
//$redis->set('post:postid:' . $postid . ':userid', $user['userid']);

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
 * home页的微博列表通过拉取方式生成
 * 即：自己/粉主发送微博记录在一个有序列表里，当粉丝登录时，一次性拉取每个粉主的20条微博
 * 哈希存储微博表时微乎其他字段
 * 把自己发的微博存到一个有序列表里(只存20条)
 **/
$redis->zAdd('followingpost:userid:' . $user['userid'], $postid, $postid);
//  当记录粉主发布条数超过20条时，去掉最旧的微博
if ($redis->zCard('followingpost:userid:' . $user['userid']) > 20)
{
    $redis->zRemRangeByRank('followingpost:userid:' . $user['userid'],0,0);
}

/*
 * home页的微博列表通过推送方式生成
 * 即：粉丝关注的粉主和粉丝自己发布微博时，推送给粉丝
 * 1. 获取自己的所有粉丝
 * 2. 给所有粉丝和自己推送发布的内容(链表)
 **/
//$followers   = $redis->sMembers('follower:' . $user['userid']);
//$followers[] = $user['userid'];
//foreach ($followers as $follower_id)
//{
//    $redis->lPush('receivepost:' . $follower_id, $postid);
//}


header('location:home.php');

include 'footer.php';