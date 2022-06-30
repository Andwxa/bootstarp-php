<?php
/*
 * @Date: 2022-06-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-03 22:34:19
 * Github:https://github.com/Andwxa
 * @Description: 文章管理
 */
//取消警告
error_reporting(0);
//获得绝对路径
$RootDir = $_SERVER['DOCUMENT_ROOT'];
$getAfter = "$RootDir/网站/deploy/after_end.php";
//引入数据库
require($getAfter);
//连接数据库
$mysqls_interface = new mysqls();
$mysqli = $mysqls_interface->linkMysql();
//获得当前时间
$time = time();
// 查询session的data
if (isset($_COOKIE['funLoginCookie'])) {
    $data = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']);
    if (!$data) {
        setcookie("funLoginCookie", '', time()-3600,'/');
    }
    $overdueTime = $mysqls_interface->selectSessionTimeMysql($_COOKIE['funLoginCookie']);
    if ($time > $overdueTime){
        $deleMy = $mysqls_interface->deleSessionMysql($_COOKIE['funLoginCookie'],'');
        setcookie("funLoginCookie", $data, time()-3600,'/');
    }
}else{
    echo'请先登录！';
    header("Refresh:3;url=./loginAndRegister.html");
    die('<br>三秒后自动返回...');
}
$rs = $mysqls_interface->selectUserByNameMysql($data);
if ($rs->num_rows > 0) {
    // 输出数据
    while($row = $rs->fetch_assoc()) {
        $userGroup =  $row['group'];
        $userName =  $row['name'];
        $userEmail =  $row['email'];
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>文化交流</title>
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.4.1/dist/css/bootstrap.min.css">
        <script src="./bootstrap-3.4.1/dist/jq/jquery-3.6.0.min.js"></script>
        <script src="./bootstrap-3.4.1/dist/js/bootstrap3_3_7.main.js"></script>
        <script>
            $(document).ready(function (){
                $(".blog-footer") .load('./utility/SharedFoot.php');
            });
        </script>
    </head>
    <body style="background-color: #FAFAFA;">
        <!-- 导航栏 -->
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!--主题-->
                <div class="navbar-header"><a class="navbar-brand" href="index.php">文化交流</a></div>
                <!--内容-->
                <div>
                    <!--向右对齐-->
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="index.php">首页</a></li>
                        <li><a href="interchange.php">文化话题</a></li>
                        <?php
                        if (isset($_COOKIE['funLoginCookie'])) {
                            echo '<li><a href="publish.php">发表文章</a></li>';
                        }else {
                            echo '<li><a href="loginAndRegister.html">登录后可以发表文章</a></li>';
                        }
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">操作<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php
                                if (isset($_COOKIE['funLoginCookie'])) {
                                    echo '<li class="active"><a href="./userCenter.php">用户中心:',$data,'</a></li>';
                                    $cookieLogin = $_COOKIE['funLoginCookie'];
                                    echo "<li><a href='./processing/deleteState.php?funLoginCookie=$cookieLogin'>注销当前用户</a></li>";
                                }else {
                                    echo '<li><a href="loginAndRegister.html">未登录</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">更多<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="mini_game.html">小游戏</a></li>
                                <li><a href="service.php">客服</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--主体-->
        <div class="row">
            <!--左侧-->
            <div class="list-group col-md-2" style="margin-top: 50px;margin-left: 10px;">
                <a href="userCenter.php" class="list-group-item">
                    <h4 class="list-group-item-heading">
                        基本信息
                    </h4>
                </a>
                <a href="articleManagement.php" class="list-group-item active">
                    <h4 class="list-group-item-heading">
                        文章管理
                    </h4>
                </a>
                <?php
                    if ($userGroup == 'admin') {
                        echo '
                    <a href="programControl.php" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            栏目控制
                        </h4>
                    </a>
                    <a href="customerController.php" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            用户控制
                        </h4>
                    </a>
                        ';
                    }
                ?>
            </div>
            <!--右侧-->
            <div class="col-md-9">
                <div class="jumbotron"><h1>文章管理</h1></div>
                <h2 class="sub-header">文章控制</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>用户名</th>
                                <th>文章名称</th>
                                <th>发布时间</th>
                                <th>浏览次数</th>
                                <th>回复次数</th>
                                <th>控制</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $userData = $mysqls_interface->selectUserByNameMysql($data);
                            if ($userData->num_rows > 0) {
                                // 输出数据
                                while($row = $userData->fetch_assoc()) {
                                    $uid =  $row['id'];
                                    $ugroup = $row['group'];
                                    if ($ugroup == 'admin'){
                                        $uid = '';
                                    }
                                }
                            }
                            $postData = $mysqls_interface->selectPostByUidMysql($uid);
                            if ($postData->num_rows > 0) {
                                // 输出数据
                                while($post = $postData->fetch_assoc()) {
                                    $postId = $post['id'];
                                    $postUid = $post['uid'];
                                    // 获得文章的用户名
                                    $postUseData = $mysqls_interface->selectUserByIdMysql($postUid);
                                    if ($postUseData->num_rows > 0) {
                                        while($user = $postUseData->fetch_assoc()) {
                                            $postUserName = $user['name'];
                                        }
                                    }
                                    $postTitle =  $post['title'];
                                    $postTime =  $post['time'];
                                    $postHits =  $post['hits'];
                                    $postReply =  $post['reply'];
                                    echo "
                            <tr>
                            <td>$postUserName</td>
                            <td>$postTitle</td>
                            <td>$postTime</td>
                            <td>$postHits</td>
                            <td>$postReply</td>
                            <td>
                                <form method='post' action='./processing/articleManagement.php'>
                                    <input style='display: none;' type='text' name='id' value='$postId'/>
                                    <input class='btn' type='submit' name='dele' value='删除'/>
                                </form>
                            </td>
                        </tr>
                                    ";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>