<?php
/*
 * @Date: 2022-05-30 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:25:53
 * Github:https://github.com/Andwxa
 * @Description: 注销用户处理
 */
//取消警告
error_reporting(0);
header("Content-type:text/html;charset=utf-8");
$username = $_GET['username'];
// echo $username,'<br>';
setcookie("funLoginCookie", $username, time()-3600,'/');
if ($_COOKIE['funLoginCookie']){
	echo '注销用户成功！<br>';
	header("Refresh:0;url=../index.php"); 
}else{
	echo '未能注销用户！请联系管理人员<br>';
	echo '二秒后自动返回...<br>';
	header("Refresh:2;url=../index.php"); 
}
?>