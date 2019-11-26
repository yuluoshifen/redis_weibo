<?php
include "lib.php";

$redis = connRedis();
$redis->set('user:userid:' . $_COOKIE['userid'] . ':authsecret', '');

setcookie('username', '', -1);
setcookie('userid', '', -1);
setcookie('authsecret', '', -1);

header('location:index.php');