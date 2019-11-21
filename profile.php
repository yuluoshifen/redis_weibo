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
 * follower:followingid (followerid)
 * following:followerid (followingid)
 * */

/*
 * 1：获取用户名
 * 2：通过用户名查询用户id
 * 3：查询该用户是否在我的following集合里
 **/

$redis = connRedis();
$profile_username = $_GET['u'];
$profile_userid = $redis->get('user:username:'.$profile_username.':userid');

if (!$profile_userid)
{
    error('非法用户！');
    die;
}

$isFollowing = $redis->sIsMember('following:'.$user['userid'],$profile_userid);
$isFollowingStatus = $isFollowing ? '0':'1';
$isFollowingText = $isFollowing ? '取关' : '关注ta';

?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<h2 class="username">test</h2>
<a href="follow.php?uid=<?php echo $profile_userid;?>&f=<?php echo $isFollowingStatus;?>" class="button"><?php echo $isFollowingText;?></a>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
world<br>
<i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>

<?php include "footer.php";?>
