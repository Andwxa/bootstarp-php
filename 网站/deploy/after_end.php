<?php

// 声明接口
interface mysqls_interface
{
    function linkMysql();
    function updateCategorySortMysql($id,$name,$sort);
    function updateUserAvatarMysql($name,$url);
    function updateUserPasswordMysql();
    function updatePostHitsMinishMysql($id);
    function updatePostHitsMysql($id);
    function updatePostReplyMysql($id);
    function addCategoryMysql($name,$sort);
    function addUserMysql();
    function addPostMysql($cid,$uid,$title,$content);
    function addSessionMysql($code,$time);
    function addReplyMysql($pid,$uid,$content);
    function selectCategoryMysql($order);
    function selectUserByNameMysql($id);
    function selectUserByIdMysql($id);
    function selectReplyPageMysql($pid,$uid);
    function selectReplyAllMysql($id,$initial);
    function selectCategoryByIdMysql($id);
    function selectPostByUidMysql($uid);
    function selectPostAllMysql($category,$title,$initial);
    function selectPostPageMysql($id,$title);
    function selectPostByIdMysql($id);
    function selectSessionTimeMysql($id);
    function selectSessionDateMysql($id);
	function issetUserNameMysql();
	function issetUserPasswordMysql();
	function issetUserEMailMysql();
    function issetSessionDataMysql($name);
    function deleUserByIdMysql($id);
    function delePostByIdMysql($id);
    function deleCategoryByIdMysql($id);
    function deleReplyByIdMysql($id);
	function deleSessionMysql($id,$name);
}

// 实现接口
class mysqls implements mysqls_interface
{
    // 保存数据库连接
    private $link;

    // 连接数据库
    public function linkMysql()
    {
        $link = new mysqli('localhost', 'root', 'root', 'traditional_culture_exchange_network', '3306');
        if ($link->connect_error)
            die('连接数据库失败!错误信息：' . $link->connect_error);
        $this->link = $link;
    }
    // 更具id更新sort
    public function updateCategorySortMysql($id,$name,$sort){
        if ($id and $sort) {
            $sql = "UPDATE `fun_category` SET `sort`='$sort',`name`='$name' WHERE `id` = '$id'";
            $result = $this->link->query($sql);
            return $result;
        }
    }
    // 根据用户的名称更新头像的路径
    public function updateUserAvatarMysql($name,$url){
        if ($name and $url){
            $sql = "UPDATE `fun_user` SET `avatar`='$url' WHERE `name` = '$name'";
            $result = $this->link->query($sql);
            return $result;
        }
    }
    public function updatePostHitsMinishMysql($id){
        if ($id) {
            $sql = "select `hits` from `fun_post` WHERE 1=1 AND `id` = '$id'";
            //执行SQL语句
            $result = $this->link->query($sql);
            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
                    $count =  $row['hits'];
                }
            }
            $count -= 1;
            $sql = "UPDATE `fun_post` SET `hits` = '$count' WHERE `id` = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }      
    }
    // 根据文章id更新hist
    public function updatePostHitsMysql($id){
        if ($id) {
            $sql = "select `hits` from `fun_post` WHERE 1=1 AND `id` = '$id'";
            //执行SQL语句
            $result = $this->link->query($sql);
            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
                    $count =  $row['hits'];
                }
            }
            $count += 1;
            $sql = "UPDATE `fun_post` SET `hits` = '$count' WHERE `id` = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }
    }
    // 根据文章id更新回复条数
    public function updatePostReplyMysql($id){
        if ($id) {
            $sql = "select count(*) from `fun_reply` WHERE 1=1 AND `pid` = '$id'";
            //执行SQL语句
            $result = $this->link->query($sql);
            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
                    $count =  $row['count(*)'];
                }
            }
            $sql = "UPDATE `fun_post` SET `reply` = '$count' WHERE `id` = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }
    }
    // 修改用户密码
    public function updateUserPasswordMysql()
    {
        $findUsername = isset($_POST['findUsername']) ? $_POST['findUsername'] : '';
        $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
        //使用MD5增强密码安全性
        $newPassword = md5($newPassword);
        $sql = "UPDATE `fun_user` SET `password`='$newPassword' WHERE `name` = '$findUsername'";
        $rst = $this->link->query($sql);
        return $rst;
    }
    // 添加栏目
    public function addCategoryMysql($name,$sort){
        if ($name and $sort){
            //拼接SQL语句
            $sql = "insert into `fun_category` (`name`,`sort`) values ('$name','$sort')";
            //执行SQL语句
            $rst = $this->link->query($sql);
            //返回
            return $rst;
        }
    }
    // 添加文章
    public function addPostMysql($cid,$uid,$title,$content){
        if ($cid and $uid and $title and $content){
            //拼接SQL语句
            $sql = "insert into `fun_post` (`cid`,`uid`,`title`,`content`,`hits`,`reply`) values ('$cid','$uid','$title','$content','0','0')";
            //执行SQL语句
            $rst = $this->link->query($sql);
            //返回
            return $rst;
        }
    }
    // 添加用户名
    public function addUserMysql()
    {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['pw2']) ? $_POST['pw2'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        //使用MD5增强密码安全性
        $password = md5($password);
        //拼接SQL语句
        $sql = "insert into `fun_user` (`group`,`name`,`password`,`email`,`salt`,`avatar`) values ('user','$username','$password','$email','$password','./img/headPortrait/UserAvatar.jpeg')";
        //执行SQL语句
        $rst = $this->link->query($sql);
        //返回
        return $rst;
    }

    // 添加session数据库
    public function addSessionMysql($code,$time)
    {
        $rst = 0;
        if (isset($_POST['name'])) {
            $username = $_POST['name'];
            $sql = "insert into `fun_session` (`id`,`expires`,`data`) values ('$code','$time','$username')";
            //执行SQL语句
            $rst = $this->link->query($sql);
        }
        //返回
        return $rst;
    }
    // 添加Reply表数据
    public function addReplyMysql($pid,$uid,$content){
        if ($pid and $uid and $content) {
            //拼接SQL语句
            $sql = "insert into `fun_reply` (`pid`,`uid`,`content`) values ('$pid','$uid','$content')";
            //执行SQL语句
            $rst = $this->link->query($sql);
            //返回
            return $rst;
        }
    }
    //输出栏目所有信息,默认从大到小
    public function selectCategoryMysql($order){
        if ($order)
            $sql = "SELECT * FROM `fun_category` ORDER BY sort $order";
        else
            $sql = "SELECT * FROM `fun_category` ORDER BY sort DESC";
        $result = $this->link->query($sql);
        return $result;
    }
    //根据用户名称获得数据
    public function selectUserByNameMysql($id){
        if ($id) {
            $id = "And name = '$id'";
        }
        //拼接SQL语句
        $sql = "select * from `fun_user` WHERE 1=1 $id";
        //执行SQL语句
        $result = $this->link->query($sql);
        return $result;
    }
    //根据用户id获得数据
    public function selectUserByIdMysql($id){
        if ($id) {
            $id = "And id = '$id'";
        }
        //拼接SQL语句
        $sql = "select * from `fun_user` WHERE 1=1 $id";
        //执行SQL语句
        $result = $this->link->query($sql);
        return $result;
    }
    // 获得Reply总页数
    public function selectReplyPageMysql($pid,$uid){
        if($pid){
            $pid = "And pid = $pid";
        }
        if($uid){
            $uid = "And uid = $uid";
        }
        //拼接SQL语句
        $sql = "select count(*) from `fun_reply` WHERE 1=1 $uid $pid";
        //执行SQL语句
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $rst =  $row['count(*)'];
            }
        }
        $rst = ceil($rst / 4);
        return $rst;
    }
    // 根据文章id获得评论数据
    public function selectReplyAllMysql($id,$initial){
        if ($id){
            $id = "And pid = '$id'";
        }
        if ($initial){
            $initial = "LIMIT $initial,4";
        }else{
            $initial = "LIMIT 0,4";
        }
        //拼接SQL语句
        $sql = "select * from `fun_reply` WHERE 1 = 1 $id $initial";
        //执行SQL语句
        $result = $this->link->query($sql);
        return $result;
    }
    // 根据id获得栏目的名称
    public function selectCategoryByIdMysql($id){
        if ($id){
            $id = "And id = '$id'";
        }
        //拼接SQL语句
        $sql = "select * from `fun_category` WHERE 1 = 1 $id";
        //执行SQL语句
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $rst =  $row['name'];
            }
        }
        return $rst;
    }
    // 根据id获得Post数据
    public function selectPostByIdMysql($id){
        if ($id){
            $id = "And id = '$id'";
        }
        //拼接SQL语句
        $sql = "select * from `fun_post` WHERE 1 = 1 $id";
        //执行SQL语句
        $result = $this->link->query($sql);
        return $result;
    }
    // 获得Post总页数
    public function selectPostPageMysql($id,$title){
        if ($id) {
            $id = "And cid = '$id'";
        }
        if ($title) {
            $title = "And title LIKE '%$title%'";
        }
        //拼接SQL语句
        $sql = "select count(*) from `fun_post` WHERE 1=1 $id $title";
        //执行SQL语句
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $rst =  $row['count(*)'];
            }
        }
        $rst = ceil($rst / 8);
        return $rst;
    }
    public function selectPostByUidMysql($uid){
        if ($uid){
            //拼接SQL语句
            $sql = "select * from `fun_post` where `uid`='$uid'";
            //执行SQL语句
            $result = $this->link->query($sql);
            return $result;
        }else{
            //拼接SQL语句
            $sql = "select * from `fun_post`";
            //执行SQL语句
            $result = $this->link->query($sql);
            return $result;
        }
    }
    // 查询Post数据数据库
    public function selectPostAllMysql($category,$title,$initial){
        if ($category){
            $category = "And cid = '$category'";
        }
        if ($title){
            $title = "And title LIKE '%$title%'";
        }
        if ($initial){
            $initial = "LIMIT $initial,8";
        }else{
            $initial = "LIMIT 0,8";
        }

        //拼接SQL语句
        $sql = "SELECT fun_post.id,fun_post.cid,fun_post.uid,fun_post.title,fun_post.content,fun_post.time,fun_post.hits,fun_post.reply,fun_category.name,fun_category.sort FROM `fun_post` JOIN `fun_category` ON fun_post.cid = fun_category.id where 1=1 $category $title ORDER BY fun_category.sort DESC $initial";
        //执行SQL语句
        $rst = $this->link->query($sql);
        return $rst;
    }
    // 根据id查询session数据库data
    public function selectSessionDateMysql($id)
    {
        $rst = '';
        if ($id){
            //拼接SQL语句
            $sql = "select * from `fun_session` where `id`='$id'";
            //执行SQL语句
            $result = $this->link->query($sql);
            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
                    $rst =  $row["data"];
                }
            }
        }
        //返回
        return $rst;
    }
    // 根据id查询session数据库time
    public function selectSessionTimeMysql($id)
    {
        $rst = '';
        if ($id){
            //拼接SQL语句
            $sql = "select * from `fun_session` where `id`='$id'";
            //执行SQL语句
            $result = $this->link->query($sql);
            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
                    $rst =  $row["expires"];
                }
            }
        }
        //返回
        return $rst;
    }

    // 判断session数据库id是否存在
    public function issetSessionMysql($id)
    {
        //拼接SQL语句
        $sql = "select * from fun_session where `id`='$id'";
        //执行SQL语句
        $rst = $this->link->query($sql);
        //返回
        return $rst;
    }
    // 判断session数据库data是否存在
    public function issetSessionDataMysql($name)
    {
        //拼接SQL语句
        $sql = "select * from fun_session where `data`='$name'";
        //执行SQL语句
        $rst = $this->link->query($sql);
        //返回
        return $rst;
    }
    // 判断用户名是否存在
    public function issetUserNameMysql()
    {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        //拼接SQL语句
        $sql = "select * from fun_user where `name`='$username'";
        //执行SQL语句
        $rst = $this->link->query($sql);
        //返回
        return $rst;
    }

    // 判断用户和密码是否一致
    public function issetUserPasswordMysql()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
        $password = '';
        $rs = false;
        //拼接SQL语句
        $sql = "select * from fun_user where `name`='$name'";
        //执行SQL语句
        $rst = $this->link->query($sql);
        if ($rst->num_rows > 0) {
            // 输出数据
            while($row = $rst->fetch_assoc()) {
                $password =  $row["password"];
            }
        }
        if($password == $pwd)
            $rs = true;
        //返回
        echo $rs;
        return $rs;
    }

    // 判断用户和邮箱是否一致
    public function issetUserEMailMysql()
    {
        $findUsername = isset($_POST['findUsername']) ? $_POST['findUsername'] : '';
        $findEmail = isset($_POST['findEmail']) ? $_POST['findEmail'] : '';
        //拼接SQL语句
        $sql = "select * from fun_user where `name`='$findUsername' and `email`='$findEmail'";
        //执行SQL语句
        $rst = $this->link->query($sql);
        //返回
        return $rst;
    }
    // 根据用户id删除用户
    public function deleUserByIdMysql($id){
        if($id){
            $sql = "DELETE FROM fun_user WHERE id = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }
    }
    // 根据文章id删除文章
    public function delePostByIdMysql($id){
        if ($id) {
            $sql = "DELETE FROM fun_post WHERE id = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }
    }
    // 根据栏目id删除栏目
    public function deleCategoryByIdMysql($id){
        if ($id){
            $sql = "DELETE FROM fun_category WHERE id = '$id'";
            $deleSql = "DELETE FROM fun_post WHERE cid = '$id'";
            $rst = $this->link->query($sql);
            $rst1 = $this->link->query($deleSql);
            if ($rst and $rst1)
                $a = 1;
            return $a;
        }
    }
    // 根据评论id删除评论
    public function deleReplyByIdMysql($id){
        if ($id) {
            $sql = "DELETE FROM fun_reply WHERE id = '$id'";
            $rst = $this->link->query($sql);
            return $rst;
        }
    }
    // 根据id删除session
    public function deleSessionMysql($id, $name)
    {
        if ($id){
            $id = " AND id = '$id'";
        }
        if ($name){
            $name = " AND data = '$name'";
        }
        $sql = "DELETE FROM fun_session WHERE 1 = 1 $id $name";
        $deleMy = $this->link->query($sql);
        return $deleMy;
    }
}
