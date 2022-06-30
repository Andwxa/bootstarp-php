<?php
/*
 * @Date: 2022-05-30 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:33:26
 * Github:https://github.com/Andwxa
 * @Description: 首页
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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>文化交流</title>
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.4.1/dist/css/bootstrap.min.css">
        <style>
            @keyframes appear
            {
                0%   {opacity:0;}
                75%  {opacity:100;}
                100% {display:block ;}
            }
            @keyframes vanish
            {
                0%   {opacity:100;}
                75%  {opacity:0;}
                100% {display:none ;}
            }
        </style>
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
                            <li class="active"><a href="index.php">首页</a></li>
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
            <div id="myCarousel" class="carousel slide">
                <!-- 轮播指标 -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                    <li data-target="#myCarousel" data-slide-to="4"></li>
                    <li data-target="#myCarousel" data-slide-to="5"></li>
                    <li data-target="#myCarousel" data-slide-to="6"></li>
                    <li data-target="#myCarousel" data-slide-to="7"></li>
                    <li data-target="#myCarousel" data-slide-to="8"></li>
                    <li data-target="#myCarousel" data-slide-to="9"></li>
                </ol>
                <!-- 轮播项目 -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="./img/index01.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index02.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index03.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index04.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index05.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index06.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index07.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index08.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index09.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index10.jpeg">
                    </div>
                </div>
                <!-- 轮播导航 -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <!--共享底部-->
        <footer class="blog-footer" style="margin-top: 130px"></footer>
    </body>
</html>