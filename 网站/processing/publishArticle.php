<?php
/*
 * @Date: 2022-05-30 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-02 19:37:26
 * Github:https://github.com/Andwxa
 * @Description: 发布文章处理
 */
//取消警告
error_reporting(0);
//获得绝对路径
dirname(__FILE__);
$RootDir = $_SERVER['DOCUMENT_ROOT'];
$getAfter = "$RootDir/网站/deploy/after_end.php";
//引入数据库
require($getAfter);
//连接数据库
$mysqls_interface = new mysqls();
$mysqli = $mysqls_interface->linkMysql();
//设定字符集
header('Content-Type:text/html;charset=utf-8');
//当没有表单提交时退出程序
if (empty($_POST)) {
    die('没有表单提交，退出');
}
$title = $_POST['title'];
$cid = $_POST['culture'];
$content = $_POST['content'];
// 获得用户id
$data = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']);
$data = $mysqls_interface->selectUserByNameMysql($data);
if ($data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $uid = $row['id'];
    }
}
$rs = $mysqls_interface->addPostMysql($cid,$uid,$title,$content);
if ($rs)
    echo '发布成功';
else
    echo '发布失败,请联系管理员';
header("Refresh:1;url=../publish.php");
?>