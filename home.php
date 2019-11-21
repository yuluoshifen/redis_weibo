<?php
include 'header.php';
include 'lib.php';

if (($user = isLogin()) == false)
{
    header('location:index.php');
    exit;
}

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
        0 粉丝<br>
        0 关注<br>
    </div>
</div>
<div class="post">
    <a class="username" href="profile.php?u=test">test</a> hello<br>
    <i>11 分钟前 通过 web发布</i>
</div>
<?php include 'footer.php'; ?>
