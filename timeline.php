<?php
include 'header.php';
include 'lib.php';

//$redis = connRedis();
//print_r($_COOKIE['authsecret']);
//print_r($redis->get('user:userid:' . $_COOKIE['userid'] . ':authsecret'));
////var_dump($_COOKIE['authsecret'] != $redis->get('user:userid:' . $_COOKIE['userid'] . ':authsecret'));
//
//if ($_COOKIE['authsecret'] == $redis->get('user:userid:' . $_COOKIE['userid'] . ':authsecret'))
//{
//    echo "pre";
//    var_dump(1);
//}
//die;

if (($user = isLogin()) == false)
{
    header('location:index.php');
    die;
}

$redis = connRedis();
$latestFiftyUserid = $redis->sort('latestfiftyuserid',['sore'=>'desc','get'=>'user:userid:*:username']);

?>
    <div id="navbar">
        <a href="index.php">主页</a>
        | <a href="timeline.php">热点</a>
        | <a href="logout.php">退出</a>
    </div>
    </div>
    <h2>热点</h2>
    <i>最新注册用户(redis中的sort用法)</i><br>
    <?php foreach ($latestFiftyUserid as $v) { ?>
    <div><a class="username" href="profile.php?u=<?php echo $v;?>"><?php echo $v;?></a></div>
    <?php } ?>


    <br><i>最新的50条微博!</i><br>
    <div class="post">
        <a class="username" href="profile.php?u=test">test</a>
        world<br>
        <i>22 分钟前 通过 web发布</i>
    </div>

    <div class="post">
        <a class="username" href="profile.php?u=test">test</a>
        hello<br>
        <i>22 分钟前 通过 web发布</i>
    </div>
<?php include 'footer.php'; ?>