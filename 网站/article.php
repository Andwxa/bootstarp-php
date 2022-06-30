<?php
/*
 * @Date: 2022-05-30 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-03 21:27:54
 * Github:https://github.com/Andwxa
 * @Description: 显示文章通用模板
 */
//取消警告
error_reporting(0);
//获得绝对路径
$RootDir = $_SERVER['DOCUMENT_ROOT'];
$getCheck = "$RootDir/网站/processing/deleteState.php";
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
}
//获得文章id
$pid = isset($_GET['id']) ? $_GET['id'] : '';
$rs = $mysqls_interface->selectPostByIdMysql($pid);
if ($rs->num_rows > 0) {
// 输出数据
while($row = $rs->fetch_assoc()) {
    $textTitle = $row['title'];
    $textCategory = $row['cid'];
    $textUid = $row['uid'];
    $textCategory = $mysqls_interface->selectCategoryByIdMysql($textCategory);
    $textTime = $row['time'];
    $textContent = $row['content'];
    $textHits = $row['hits'];
    $textReply = $row['reply'];
    }
}
// 获得当前用户的用户组和id
$user = $mysqls_interface->selectUserByNameMysql($data);
if ($user->num_rows > 0) {
    // 输出数据
    while($user1 = $user->fetch_assoc()) {
        $userGroup = $user1['group'];
        $MainUserId = $user1['id'];
        $userName = $user1['name'];
        $userAvatar = $user1['avatar'];
    }
}
// 判断是否属于管理人员或者文章发布者
if ($userGroup == 'admin' or $textUid == $MainUserId) {
    $issDele = 'true';
}
//访问自增
$uprs = $mysqls_interface->updatePostHitsMysql($pid);
if (!$uprs){
    echo '无效访问';
}
//翻页
$nowadayPage = 0;
$allPage = $mysqls_interface->selectReplyPageMysql($pid,$uid);
$action = $_GET['act'];
if ($action=='next'){
    $nowadayPage = $_GET['nowadayPage'];
    if ($nowadayPage <= $allPage-2){
        $nowadayPage+=1;
    }
}elseif ($action=='last'){
    $nowadayPage = $_GET['nowadayPage'];
    if ($nowadayPage > 0){
        $nowadayPage-=1;
    }
}
$userId = "";
$comment = "";
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
        <div class="container">
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
                            <li class="active"><a href="interchange.php">文化话题</a></li>
                            <?php
                            if (isset($_COOKIE['funLoginCookie'])) {
                                echo '<li><a href="./publish.php">发表文章</a></li>';
                            }else {
                                echo '<li><a href="loginAndRegister.html">登录后可以发表文章</a></li>';
                            }
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">操作<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php
                                    if (isset($_COOKIE['funLoginCookie'])) {
                                        echo '<li><a href="./userCenter.php">用户中心:',$data,'</a></li>';
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
            <ul class="breadcrumb" style="margin-top: -20px;">
                <li><a href="interchange.php">文化话题</a></li>
                <li><?php echo $textCategory ?>分类</li>
                <li class="active"><?php echo $textTitle?></li>
            </ul>
            <!--主体-->
            <div class="container">
                <div class="blog-header">
                    <?php
                    echo "
                    <h1 class='blog-title'>$textTitle</h1>
                    <p class='lead blog-description'>发布时间:$textTime</p>
                    ";
                    ?>
               </div>
            </div>
            <div class="row">
                <!-- 内容 -->
                <div class="col-sm-8 blog-main">
                    <div class="blog-post">
                        <!--文章内容-->
                        <blockquote><p><?php echo $textContent ?></p></blockquote>
                        <!--发表评论-->
                        <div class="bs-example" style="text-align: center;">
                            <form class="form-inline" method="post" action='./processing/withComments.php?pid=<?php echo $pid?>' onsubmit="return addComment()">
                                <textarea class="form-control" style="width: 400px;" rows="4" placeholder="评论内容不能大于40个字符！" id="comment" name="comment"></textarea><br>
                                <input type="submit" class="btn btn-default" style="margin: 5px;" value="--发表评论--">
                            </form>
                        </div>
                        </form>
                        <!--评论-->
                        <?php
                            $ReplyDate = $mysqls_interface->selectReplyAllMysql($pid,$nowadayPage*4);
                            if ($ReplyDate->num_rows > 0) {
                                // 输出数据
                                while($row = $ReplyDate->fetch_assoc()) {
                                    $ReplyId =  $row['id'];
                                    $ReplyUid =  $row['uid'];
                                    $UserData =  $mysqls_interface->selectUserByIdMysql($ReplyUid);
                                    if ($UserData->num_rows > 0) {
                                        while($row1 = $UserData->fetch_assoc()) {
                                            $rst =  $row1['name'];
                                        }
                                    }
                                    $ReplyTime =  $row['time'];
                                    $ReplyContent =  $row['content'];
                                    echo "
                        <pre><code>用户:$rst 在$ReplyTime 发表
                                    ";
                                    if ($issDele == 'true') {
                                        echo "
                        <br>$ReplyContent<br><a href='./processing/deleteComment.php?reply=$ReplyId&pid=$pid'>删除评论</a></code></pre>
                                        ";
                                    }
                                }
                            }
                        ?>
                        <!--按钮区-->
                        <nav>
                            <ul class="pager">
                                <li><a href="article.php?id=<?php echo$pid ?>&act=last&nowadayPage=<?php echo$nowadayPage?>">上一页</a></li>
                                <span><?php $showNowPage = $nowadayPage+1; echo"$showNowPage/$allPage";?></span>
                                <li><a href="article.php?id=<?php echo$pid ?>&act=next&nowadayPage=<?php echo$nowadayPage?>">下一页</a></li>
                            </ul>
                        </nav>
                    </div><!-- /.blog-post -->
                </div><!-- /.blog-main -->
                <!-- 右侧框 -->
                <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
                    <div class="sidebar-module sidebar-module-inset">
                        <p><img style='width: 100px;height: 100px;' src="<?php echo $userAvatar ?>"/></p>
                    </div>
                    <div class="sidebar-module">
                        <h4><?php echo $userName?></h4>
                        <!-- <ol class="list-unstyled">
                            <li><a href="#">March 2014</a></li>
                            <li><a href="#">February 2014</a></li>
                            <li><a href="#">January 2014</a></li>
                            <li><a href="#">December 2013</a></li>
                            <li><a href="#">November 2013</a></li>
                            <li><a href="#">October 2013</a></li>
                            <li><a href="#">September 2013</a></li>
                            <li><a href="#">August 2013</a></li>
                            <li><a href="#">July 2013</a></li>
                            <li><a href="#">June 2013</a></li>
                            <li><a href="#">May 2013</a></li>
                            <li><a href="#">April 2013</a></li>
                        </ol> -->
                    </div>
                </div><!-- /.blog-sidebar -->
            </div><!-- /.row -->
        </div>
        <!--共享底部-->
        <footer class="blog-footer"></footer>
        <script>
            //发表评论检查
            function addComment() {
                if ($("#comment").val() == "") {
                    alert('评论内容不能为空！');
                    console.log("评论内容不能为空！");
                    return false;
                }
                else if ($("#comment").val().length > 40) {
                    alert('评论内容不能大于40个字符！');
                    console.log("评论内容不能大于40个字符！");
                    return false;
                }
                var cook = '<?php echo isset($_COOKIE['funLoginCookie']) ?>';
                if (cook != "") {
                    var cookMysql = '<?php $userId = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']); echo $userId; ?>';
                    if(cookMysql != ""){
                            return true;
                    }
                }
                alert('发表评论需要登录！');
                console.log("发表评论需要登录！");
                return false;
            }
        </script>
    </body>
</html>