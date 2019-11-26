<?php
include 'header.php';
include 'lib.php';

if (($user = isLogin()) == false)
{
    header('location:index.php');
    exit;
}

$redis = connRedis();

/*
 * 计算粉丝数和关注数
 **/
$myFollowerCount  = $redis->sCard('follower:' . $user['userid']);   //粉丝个数
$myFollowingCount = $redis->sCard('following:' . $user['userid']);  //关注人个数

?>
<div id="navbar">
    <a href="index.php">主页</a>
    | <a href="timeline.php">热点</a>
    | <a href="logout.php">退出</a>
</div>
</div>
<div id="postform">
    <form method="POST" action="post.php">
        <?php echo $user['username']; ?>, 有啥感想?
        <br>
        <table>
            <tr>
                <td><textarea cols="70" rows="3" name="status"></textarea></td>
            </tr>
            <tr>
                <td align="right"><input type="submit" name="doit" value="Update"></td>
            </tr>
        </table>
    </form>
    <div id="homeinfobox">
        <?php echo $myFollowerCount;?> 粉丝<br>
        <?php echo $myFollowingCount;?> 关注<br>
    </div>
</div>
<div class="post">
    <a class="username" href="profile.php?u=test">test</a> hello<br>
    <i>11 分钟前 通过 web发布</i>
</div>
<?php include 'footer.php'; ?>
