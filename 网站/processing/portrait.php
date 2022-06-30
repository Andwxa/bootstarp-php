<?php
/*
 * @Date: 2022-05-31 21:34:23
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:27:09
 * Github:https://github.com/Andwxa
 * @Description: 头像处理
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
header('Content-type:text/html;charset=utf-8');
//判断是否上传头像
if(!empty($_FILES['pic'])){	
	//获取用户上传文件信息
	$pic_info = $_FILES['pic'];
	
	//判断文件上传到临时文件是否出错
	if($pic_info['error'] >0){
		$error_msg = '上传错误:';
		switch($pic_info['error']){
			case 1: $error_msg .= '文件大小超过了php.ini中upload_max_filesize选项限制的值！'; break;
			case 2: $error_msg .= '文件大小超过了表单中max_file_size选项指定的值！'; break;
			case 3: $error_msg .= '文件只有部分被上传！'; break;
			case 4: $error_msg .= '没有文件被上传！'; break;
			case 6: $error_msg .= '找不到临时文件夹！'; break;
			case 7: $error_msg .= '文件写入失败！'; break;
			default: $error_msg .='未知错误！'; break; 
		}
		echo $error_msg;
        header("Refresh:3;url=../userCenter.php");
        die('<br>三秒后自动返回');
	}
	
	//获取上传文件的类型
	$type = substr(strrchr($pic_info['name'],'.'),1);
	//判断上传文件类型
	if($type == 'jpg' or $type == 'png' or $type == 'jpeg'){
        echo '图像类型要求，允许的类型为:jpg,png,jpeg<br>';
	}else{
        echo '图像类型不符合要求，允许的类型为:jpg,png,jpeg';
        header("Refresh:3;url=../userCenter.php");
        die('<br>三秒后自动返回');
    }

	//获取原图图像大小
	list($width, $height) = getimagesize($pic_info['tmp_name']);
	//设置缩略图的最大宽度和高度
	$maxwidth = $maxheight= 200;
	//自动计算缩略图的宽和高
	if($width > $height){
		//缩略图的宽等于$maxwidth
		$newwidth = $maxwidth;
		//计算缩略图的高度
		$newheight = round($newwidth*$height/$width);
	}else{
		//缩略图的高等于$maxwidth
		$newheight = $maxheight;
		//计算缩略图的高度
		$newwidth = round($newheight*$width/$height);
	}
	//绘制缩略图的画布
	$thumb = imageCreateTrueColor($newwidth,$newheight);
	//依据原图创建一个与原图一样的新的图像
	$source = imagecreatefromjpeg($pic_info['tmp_name']);
	//依据原图创建缩略图
	/**
	  * $thumb 目标图像
	  * $source 原图像
	  * 0,0,0,0 分别代表目标点的x坐标和y坐标，源点的x坐标和y坐标
	  * $newwidth 目标图像的宽
	  * $newheight 目标图像的高
	  * $width 原图像的宽
	  *=$height 原图像的高
	  */
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	//设置缩略图保存路径
    $data = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']);
	$new_file = '../img/headPortrait/'.$data.'.jpg';
	//保存缩略图到指定目录
    $preserve =imagejpeg($thumb,$new_file,100);
    if ($preserve){
        $data = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']);
        $rst = $mysqls_interface->updateUserAvatarMysql($data,"./img/headPortrait/$data.jpg");
        if ($rst){
            echo '保存成功';
        }else{
            echo '数据库出错，请联系管理人员！！！';
        }
    }else{
        echo '保存失败，请联系管理人员！！！';
    }
    header("Refresh:1;url=../userCenter.php");
    die('<br>一秒后自动返回');
}else{
    echo '未上传图片';
    header("Refresh:3;url=../userCenter.php");
    die('<br>三秒后自动返回');
}
?>