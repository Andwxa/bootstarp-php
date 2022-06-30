<?php
/*
 * @Date: 2022-05-30 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:24:55
 * Github:https://github.com/Andwxa
 * @Description: 登录处理
 */
//取消警告
error_reporting(0);
//获得绝对路径
dirname(__FILE__);
$RootDir = $_SERVER['DOCUMENT_ROOT']; 
$getCheck = "$RootDir/网站/utility/check_form.php";
$getAfter = "$RootDir/网站/deploy/after_end.php";
//引入表单验证函数库
require($getCheck);
//引入数据库
require($getAfter);
//设定字符集
header('Content-Type:text/html;charset=utf-8');
//开启session
session_start();
//当没有表单提交时退出程序
if(empty($_POST)){
	die('没有表单提交，退出');
}

//判断表单中各字段是否都已填写
$chcek_fields = array('name','pwd','captcha');
foreach($chcek_fields as $v){
	if(empty($_POST[$v])){
		echo ("错误：'.$v.'字段不能为空!三秒后自动返回");
		echo '<br>';
		header("Refresh:3;url=../loginAndRegister.html");
		die("");
	}
}
//接收需要处理的表单字段
$username = $_POST['name'];
$password = $_POST['pwd'];
//获取用户输入的验证码字符串
$code = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';

//判断SESSION中是否存在验证码
if(empty($_SESSION['captcha_code'])){
	echo '五秒后自动返回...<br>';
	header("Refresh:5;url=../loginAndRegister.html"); 
	die('验证码已过期，请重新登录。');
}
//将验证码转成小写然后再进行比较
if (strtolower($code) == strtolower($_SESSION['captcha_code'])){
	//匹配
	if ($username || $password) {
		$data = array(
			'username' => $username,
			'password' => $password,
		);
		$validate = array(
			'username' => 'checkUsername',
			'password' => 'checkPassword',
			
		);
		$error = array();
		foreach($validate as $k=>$v){
			//运用可变函数，实现不同字段调用不同函数
			$result = $v($data[$k]);
			if($result !== true){
				$error[] = $result;
			}
		}
	}
	if(empty($error)){
		//连接数据库
		$mysqls_interface = new mysqls();
		$mysqli = $mysqls_interface->linkMysql();
		//判断用户名是否存在
		$rst = $mysqls_interface->issetUserNameMysql();
		if($rst->fetch_row())
		{
			echo '五秒后自动返回...<br>';
			$transmission = array('type'=>0,'data'=>'用户名或密码错误！');
			header("Refresh:5;url=../loginAndRegister.html?transmission=$transmission"); 
			die('用户名或密码错误！');
		}else {
			$rst = $mysqls_interface->issetUserPasswordMysql();
			if($rst){
				$loginType = $_POST['loginType'];
				$time = time();
				//生成随机数
				$code = substr(md5(time()),0,200);
				//保存7天登录,否则一小时记录
				if ($loginType == 'loginType') {
					$time = time()+3600*24*7;
					setcookie("funLoginCookie", $code, time()+3600*24*7,'/');
				}else{
					$time = time()+3600;
					setcookie("funLoginCookie", $code, time()+3600,'/');
				}
				//用户名存在
				if ($mysqls_interface->issetSessionDataMysql($username)){
					$delSessionName = $mysqls_interface->deleSessionMysql('',$username);
					if (!$delSessionName) {
						echo '出现问题';
					}else{
						echo '欢迎再次登录<br>';
					}
				}
				if ($mysqls_interface->issetSessionMysql($code)) {
					$deleMy = $mysqls_interface->deleSessionMysql($code,'');
					if (!$deleMy) {
						echo '重置session出现错误';
					}
				}
				if($mysqls_interface->addSessionMysql($code,$time)){
					echo '添加session成功';
				}else{
					echo '添加session失败';
				}

				echo '三秒后自动到主页...<br>';
				header("Refresh:0;url=../index.php"); 
				die('登录成功');
			}else{
				echo '五秒后自动返回...<br>';
				header("Refresh:5;url=../loginAndRegister.html"); 
				die('用户名或密码错误！：'.$mysqls_interface->error);
			}
		}
	}else{
		foreach($error as $val){
			echo $val."\n";
		}
		echo '三秒后自动返回';
		header("Refresh:3;url=../loginAndRegister.html"); 
	}
} else{
	echo'输入的验证码是:',strtolower($code),'系统的验证码是:',strtolower($_SESSION['captcha_code']);
	echo '五秒后自动返回...<br>';
	header("Refresh:5;url=../loginAndRegister.html"); 
	unset($_SESSION['captcha_code']); //清除SESSION数据
	die('验证码输入错误');
}
unset($_SESSION['captcha_code']); //清除SESSION数据
?>
