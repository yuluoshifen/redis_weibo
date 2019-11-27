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

/*
 * 微博用hash结构存储
 * 取出粉主和自己的最新发布的50条微博
 **/
//$redis->lTrim('receivepost:' . $user['userid'],0,49);
//$pushPostId = $redis->sort('receivepost:' . $user['userid'], ['sort' => 'desc']);
//print_r($pushPostId);

/*
 * 拉取粉主和自己最近发布的微博 :
 * 1.获取登录用户的粉主，和自己组成一个数组
 * 2.存储一个上次拉取点（postid），再次拉取微博时，从拉取点之后的微博开始拉取
 * 3.循环粉主数组，获取粉主和自己发布的最近的微博
 * 4.更新上次拉取点
 * 5.将最近发布微博的数组循环写到receivepost链表中
 **/
$following = $redis->sMembers( 'following:' . $user['userid']);
$following[] = $user['userid'];

$lastPull = $redis->get('lastpull:userid:' . $user['userid']);
if (!$lastPull)
{
    $lastPull = 0;
}

$latestPost = [];
foreach ($following as $f)
{
    $latestPost = array_merge($latestPost, $redis->zRangeByScore('followingpost:userid:' . $f, $lastPull,1<<32 - 1));
}
sort($latestPost, SORT_NUMERIC);

$redis->set('lastpull:userid' . $user['userid'], end($latestPost));

foreach ($latestPost as $p)
{
    $redis->lPush('receivepost:' . $user['userid'], $p);
}
$pushPostId = $redis->sort('receivepost:' . $user['userid'], ['sort' => 'desc']);

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
<?php foreach ($pushPostId as $post_id) {
    $post = $redis->hMGet('post:postid:' . $post_id, ['time', 'userid', 'username', 'content']);
?>

<div class="post">
    <a class="username" href="profile.php?u=<?php echo $post['username'];?>"><?php echo $post['username'];?></a> <?php echo $post['content'];?><br>
    <i><?php echo formatTime($post['time']);?>前 通过 web发布</i>
</div>

<?php } ?>

<?php include 'footer.php'; ?>
