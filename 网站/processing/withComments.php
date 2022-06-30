<?php
/*
 * @Date: 2022-05-30 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-06-02 19:37:39
 * Github:https://github.com/Andwxa
 * @Description: 发表评论处理
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
//获得内容
$cookName = $mysqls_interface->selectSessionDateMysql($_COOKIE['funLoginCookie']);
$urst = $mysqls_interface->selectUserByNameMysql($cookName);
if ($urst->num_rows > 0) {
    // 输出数据
    while($row = $urst->fetch_assoc()) {
        $uid =  $row["id"];
    }
}
if(!$uid){
    echo '非法进入';
}
$pid = isset($_GET['pid']) ? $_GET['pid'] : '';
$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
$rs = $mysqls_interface->addReplyMysql($pid,$uid,$comment);
if ($rs) {
    echo '评论成功！';
    $rst = $mysqls_interface->updatePostReplyMysql($pid);
    if ($rst) {
        echo '更新评论条数成功！';
    }else{
        echo '更新评论条数失败！';
    }
}else{
    echo ' 评论失败！';
}
header("Refresh:1;url=../article.php?id=$pid");
?>