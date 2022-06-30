<?php
/*
 * @Date: 2022-06-03 10:53:53
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-03 20:28:36
 * Github:https://github.com/Andwxa
 * @Description: 客服
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
	    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	    <script src="https://cdn.staticfile.org/twitter-bootstrap/5.1.1/js/bootstrap.bundle.min.js"></script>
        <style>
            @keyframes example {
                0% {
                    left: 30px;
                    top: 10px;
                }
                50% {
                    left: 40px;
                    top: 10px;
                }
                100% {
                    left: 30px;
                    top: 10px;
                }
            }
            
            #backtrack {
                color: white;
                text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;
                position: absolute;
                left: 30px;
                top: 10px;
                text-decoration: none;
                animation-name: example;
                animation-duration: 3s;
                animation-iteration-count: infinite;
            }
            .button1{opacity: 0;}
            .button2{opacity: 0;}
            .button3{opacity: 0;}
            @keyframes buttonAnimationIn{
                0%{opacity: 0;}
                100%{opacity: 1;}
            }
            @keyframes buttonAnimationOut{
                0%{opacity: 1;}
                100%{opacity: 0;}
            }
            .card1:hover .button1{display: block;animation-name: buttonAnimationIn;animation-duration: 1.5s;animation-iteration-count:1;}
            .card1 .button1{animation-name: buttonAnimationOut;animation-duration: 1.5s;animation-iteration-count:1;}
            .card2:hover .button2{display: block;animation-name: buttonAnimationIn;animation-duration: 1.5s;animation-iteration-count:1;}
            .card2 .button2{animation-name: buttonAnimationOut;animation-duration: 1.5s;animation-iteration-count:1;}
            .card3:hover .button3{display: block;animation-name: buttonAnimationIn;animation-duration: 1.5s;animation-iteration-count:1;}
            .card3 .button3{animation-name: buttonAnimationOut;animation-duration: 1.5s;animation-iteration-count:1;}
        </style>
	</head>
	<body>
        <a href="index.php" id="backtrack">返回首页</a>
		<div class="container text-center">
			<br />
			<h1>客服渠道</h1>
			<h4>多种方式联系客服</h4>
			<br />
		</div>
		<div class="container text-center">
			<div class="card-deck">
				<div class="card card1">
					<div class="card-header"><h2>方法一</h2></div>
					<div class="card-body">
						<h2>智能客服为您服务</h2>
						<ul class="list-group" style="margin-bottom: 10px;">
							<li class="list-group-item">无需登录也可对话</li>
							<li class="list-group-item">问题n个工作日内回复</li>
							<li class="list-group-item">一个月100次数</li>
						</ul>
						<button class="btn btn-primary btn-lg btn-block button1">进入</button>
					</div>
				</div>
				
				<div class="card card2">
					<div class="card-header"><h2>方法二</h2></div>
					<div class="card-body">
						<h2>人工客服为您服务</h2>
						<ul class="list-group" style="margin-bottom: 10px;">
							<li class="list-group-item">需登录才能对话</li>
							<li class="list-group-item">问题1个工作日内回复</li>
							<li class="list-group-item">一个月10次数</li>
						</ul>
						<button class="btn btn-primary btn-lg btn-block button2">进入</button>
					</div>
				</div>
				
				<div class="card card3">
					<div class="card-header"><h2>方法三</h2></div>
					<div class="card-body">
						<h2>微信公众进行留言</h2>
						<ul class="list-group" style="margin-bottom: 10px;">
							<li class="list-group-item">微信关注公众号</li>
							<li class="list-group-item">问题3个工作日内回复</li>
							<li class="list-group-item">一个月30次数</li>
						</ul>
						<button class="btn btn-primary btn-lg btn-block button3">进入</button>
					</div>
				</div>
				
			</div>
		</div>
	</body>
</html>
