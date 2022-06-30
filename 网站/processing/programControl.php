<?php
/*
 * @Date: 2022-06-01 21:01:46
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:27:41
 * Github:https://github.com/Andwxa
 * @Description: 栏目处理
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
$id = $_POST['id'];
$name = $_POST['name'];
$sort = $_POST['sort'];
// 更新
if ($_POST['update']){
    if (!is_numeric($sort) or strlen($sort) > 10) {
        echo '显示顺序必须是数值型，并且长度必须小于10';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    if (strlen($name) > 12) {
        echo '栏目名称长度必须小于12';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    if (!is_numeric($id) or strlen($id) > 10) {
        echo 'id必须是数值型，并且长度必须小于10';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    $rs = $mysqls_interface->updateCategorySortMysql($id,$name,$sort);
    if ($rs) {
        echo '更新成功';
        header("Refresh:0;url=../programControl.php");
        die('<br>零秒后自动返回...');
    }else{
        echo '更新失败，请联系管理人员';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    // 删除
}elseif ($_POST['add']){
    if (!is_numeric($sort) or strlen($sort) > 10) {
        echo '显示顺序必须是数值型，并且长度必须小于10';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    if (strlen($name) > 12) {
        echo '栏目名称长度必须小于12';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
    $rs = $mysqls_interface->addCategoryMysql($name,$sort);
    if ($rs){
        echo '添加栏目成功';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }else{
        echo '添加栏目失败，请联系管理人员';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
}elseif ($_POST['dele']){
    $rs = $mysqls_interface->deleCategoryByIdMysql($id);
    if ($rs){
        echo '删除栏目成功';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }else{
        echo '删除栏目失败，请联系管理人员';
        header("Refresh:3;url=../programControl.php");
        die('<br>三秒后自动返回...');
    }
}else{
    echo '非法进入';
}

?>