<?php
/*
 * @Date: 2022-06-02 19:36:07
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:25:21
 * Github:https://github.com/Andwxa
 * @Description: 用户控制处理
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

if ($_POST['dele']) {
    $id = $_POST['id'];
    $rs = $mysqls_interface->deleUserByIdMysql($id);
    if ($rs) {
        echo '删除成功';
        header("Refresh:0;url=../customerController.php");
        die('三秒后自动返回');
    }else{
        echo '删除失败，请联系管理人员！';
        header("Refresh:3;url=../customerController.php");
        die('三秒后自动返回');
    }
}
?>