<?php
/*
 * @Date: 2022-06-01 23:16:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-30 18:25:31
 * Github:https://github.com/Andwxa
 * @Description: 删除评论处理
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
$reply = $_GET['reply'];
$pid = $_GET['pid'];
if ($reply) {
    $rs = $mysqls_interface->deleReplyByIdMysql($reply);
    if ($rs) {
        echo'删除成功';
        $rst = $mysqls_interface->updatePostReplyMysql($pid);
        if ($rst) {
            echo'<br>评论更新成功';
        }else{
            echo'评论更新失败';
        }
        header("Refresh:0;url=../article.php?id=$pid"); 
        die('<br>三秒后自动返回...');
    }else{
        echo'删除失败,请联系管理人员';
        header("Refresh:3;url=../article.php?id=$pid"); 
        die('<br>三秒后自动返回...');
    }
}else{
    echo '非法进入';
}
?>