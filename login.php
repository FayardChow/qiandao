<?php
include 'config.php';
//连接数据库
$con = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'], $dbconfig['dbname']);
if (!$con){
    die('无法连接数据库: ' . mysqli_connect_error());
}

// 退出
if(isset($_GET['logout'])) {
    $qq = $_COOKIE["qq"];
    unset($_COOKIE["qq"]);
    unset($_COOKIE["name"]);
    unset($_COOKIE["company"]);
    unset($_COOKIE["md5str"]);
    mysqli_query($con, "UPDATE stuff SET md5str='' WHERE qq='".$qq."'");
}

if($_COOKIE['qq'] && $_COOKIE['md5str'] && $_COOKIE['company']) {
    $qq = $_COOKIE["qq"];
    $md5str = $_COOKIE["md5str"];
    $re = mysqli_query($con, "SELECT Id FROM stuff WHERE qq='".$qq."' AND md5str='".$md5str."'");
    if(mysqli_num_rows($re) > 0) {
        exit('<script>window.location.href="/index.php"</script>');
    } else {
        unset($_COOKIE["qq"]);
        unset($_COOKIE["name"]);
        unset($_COOKIE["company"]);
        unset($_COOKIE["md5str"]);
        mysqli_query($con, "UPDATE stuff SET md5str='' WHERE qq='".$qq."'");
    }
}

// 登录
if(isset($_POST['qq'])) {
    $qq = $_POST['qq'];
    $password = $_POST['password'];
    if(!preg_match("/\d{6,}/", $qq)) {
        exit('<script>alert("QQ号格式不正确");window.location.href="/login.php"</script>');
    }
    if(!$password) {
        exit('<script>alert("密码不能为空");window.location.href="/login.php"</script>');
    }
    $re = mysqli_query($con, "SELECT qq,pass,name,company FROM stuff WHERE qq='".$qq."' LIMIT 1");
    if($row = mysqli_fetch_assoc($re)) {
        if($password == $row['pass']) {
            setcookie("qq",$qq,time()+72*24*60*60);
            setcookie("name",$row['name'],time()+72*24*60*60);
            setcookie("company",$row['company'],time()+72*24*60*60);
            setcookie("md5str",md5($qq.time()),time()+72*24*60*60);
            $re = mysqli_query($con, "UPDATE stuff SET md5str='".md5($qq.time())."' WHERE qq='".$qq."'");
            if($re) {
                exit('<script>alert("登录成功");window.location.href="/index.php"</script>');
            } else {
                exit('<script>alert("请稍候重试");window.location.href="/login.php"</script>');
            }
        } else{
            exit('<script>alert("账号或密码不正确");window.location.href="/login.php"</script>');
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>登录</title>
  <link rel="stylesheet" href="/assets/layui-v2.2.5/css/layui.css">
  <style>
    .wrap {
        width: 500px;
        margin: 0 auto;
        margin-top: 200px;
    }
  </style>

</head>
<body>
    <div class="wrap">
        <form class="layui-form" method="post" action="/login.php">
            <div class="layui-form-item">
            <label class="layui-form-label">QQ号</label>
            <div class="layui-input-inline">
                <input type="text" name="qq" required  lay-verify="required" placeholder="请输入QQ号" autocomplete="off" class="layui-input">
            </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            </div>
            </div>
        </form>
    </div>
</body>