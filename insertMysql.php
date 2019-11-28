<?php
include 'lib.php';
$redis = connRedis();

$sql = "insert into post (postid,userid,content,create_time) values ";

$i = 0;
while ($redis->lLen('global:storage') && $i++ < 1000)
{
    $post_id = $redis->lPop('global:storage');
    $post    = $redis->hMGet('post:postid:' . $post_id, ['userid', 'content', 'time']);
    $sql     .= "($post_id," . $post['userid'] . ",'" . $post['content'] . "'," . $post['time'] . "),";
}

if ($i == 0)
{
    echo "no execute!";
    die;
}

$sql = trim($sql, ',');

$mysql = mysqli_connect('127.0.0.1', 'root', '', 'test', '3306');
if (!$mysql)
{
    die('Could not connect: ' . mysqli_error($mysql));
}

$res = mysqli_query($mysql, $sql);
echo $res;
mysqli_close($mysql);