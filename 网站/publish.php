<?php
/*
 * @Date: 2022-05-30 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-03 21:29:18
 * Github:https://github.com/Andwxa
 * @Description: 发布文章
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
        header("Refresh:0;url=index.php");
    }
    $overdueTime = $mysqls_interface->selectSessionTimeMysql($_COOKIE['funLoginCookie']);
    if ($time > $overdueTime){
        $deleMy = $mysqls_interface->deleSessionMysql($_COOKIE['funLoginCookie'],'');
        setcookie("funLoginCookie", $data, time()-3600,'/');
        header("Refresh:0;url=index.php");
    }
}else{
    header("Refresh:0;url=index.php");
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
    <script type="text/javascript" charset="utf-8" src="./utf8-php/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="./utf8-php/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="./utf8-php/lang/zh-cn/zh-cn.js"></script>
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
                        <li><a href="interchange.php">文化话题</a></li>
						<?php
						if (isset($_COOKIE['funLoginCookie'])) {
							echo '<li class="active"><a href="publish.php">发表文章</a></li>';
						}else {
							echo '<li class="active"><a href="loginAndRegister.html">登录后可以发表文章</a></li>';
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
        <!--主体-->
        <div>
            <form method="post" action='./processing/publishArticle.php' onsubmit="return checkPublishForm()">
                <input type="text" class="form-control" id="title" name="title" placeholder="文章标题">
                <select class="form-control" name='culture' style="margin-top: 5px">
                    <?php
                        $categorData = $mysqls_interface->selectCategoryMysql('');
                        if ($categorData->num_rows > 0) {
                            // 输出数据
                            while($row = $categorData->fetch_assoc()) {
                                $categorId =  $row['id'];
                                $categorName =  $row['name'];
                                echo "<option value='$categorId'>$categorName</option>";
                            }
                        }
                    ?>
                </select>
                <script id="editor" name="content" type="text/plain" style="height:500px;margin-top: 5px"></script>
                <div class="text-center" style="margin-top: 5px">
                    <input class="btn btn-default" id="submit" type="submit" value="提交文章"/>
                </div>
            </form>
        </div>
    </div>
    <!--共享底部-->
    <footer class="blog-footer" style="margin-top: 50px"></footer>
    <script type="text/javascript">
        var ue = UE.getEditor('editor');
    </script>
    <script>
        function checkPublishForm() {
            var content = UE.getEditor('editor').hasContents();
            if ($("#title").val() == "") {
                alert('标题不能为空！');
                return false;
            } else if (content == false) {
                alert('内容不能为空！');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>