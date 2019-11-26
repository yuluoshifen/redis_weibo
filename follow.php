<?php

include "header.php";
include "lib.php";

if (($user = isLogin()) == false)
{
    header("location:index.php");
    exit;
}

/*
 * 粉丝记录： set
 * 关注记录： set
 *
 * follower 关注 following
 * follower:followingid (followerid)    粉丝表
 * following:followerid (followingid)   关注人表
 * */

/*
 * 1：判断uid和f是否合法
 * 2：判断uid是不是当前登录用户
 **/
$to_uid      = $_GET['uid'];
$isFollowing = $_GET['f'];
if (!$to_uid)
{
    error('没有对应操作的用户！');
}

if ($to_uid == $user['userid'])
{
    error('用户不能关注自己！');
}

$redis = connRedis();

if ($isFollowing == 1)
{
    $redis->sAdd('following:' . $user['userid'], $to_uid);
    $redis->sAdd('follower:' . $to_uid, $user['userid']);
}
else
{
    $redis->sRem('following:' . $user['userid'], $to_uid);
    $redis->sRem('follower:' . $to_uid, $user['userid']);
}

$to_username = $redis->get('user:userid:' . $to_uid . ':username');
header('location:profile.php?u=' . $to_username);
include "footer.php";
