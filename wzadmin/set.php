<?php
/**
 * 网站设置
**/
$mod='blank';
include("../includes/common.php");
$title='网站设置';
include './head.php';
if($_SESSION['islogin']==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if(isset($_POST['group'])) {

	if(!preg_match("/^\d{6,}$/", $_POST['group'])) {
		exit("<script language='javascript'>alert('群号格式错误');history.go(-1);</script>");
	}
	if(!preg_match("/^\d+$/", $_POST['time'])) {
		exit("<script language='javascript'>alert('时间格式错误');history.go(-1);</script>");
	}
	$r = $DB->query("SELECT * FROM setting WHERE company='".$_SESSION['company']."' LIMIT 1");

	if($row = $DB->fetch($r)) {
		$rs=$DB->query("UPDATE setting SET `group`='".$_POST['group']."', `time`='".$_POST['time']."' WHERE company='".$_SESSION['company']."'");
	}else {
		$rs=$DB->query("INSERT INTO setting (`group`,`time`) VALUES ({$_POST['group']}, {$_POST['time']})");
	}

	
	if($rs)	{ 
		$res='设置成功';
	}
	else{
		$res='设置失败';
	}
	exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}



$rs=$DB->query("SELECT `group`,`time`, `company` FROM setting WHERE company='".$_SESSION['company']."' LIMIT 1");
if($rs) {
	$res = $DB->fetch($rs);
	$group = $res['group'];  //群号
	$time = $res['time'];    //每日用时
	$company = $res['company'];  //公司名称
}




?>

<nav class="navbar navbar-fixed-top navbar-default">
<div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
      <span class="sr-only">导航按钮</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="./">后台管理</a>
  </div><!-- /.navbar-header -->
  <div id="navbar" class="collapse navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
      <li>
        <a href="./"><span class="glyphicon glyphicon-align-justify"></span> 记录列表</a>
      </li>
      <li>
        <a href="./time.php"><span class="glyphicon glyphicon-time"></span> 用时列表</a>
      </li>          
      <li>
        <a href="./stuff.php"><span class="glyphicon glyphicon-user"></span> 员工列表</a>
      </li>
      <li>
        <a href="./item.php"><span class="glyphicon glyphicon-th-list"></span> 事件列表</a>
      </li>
      <li class="active">
        <a href="./set.php"><span class="glyphicon glyphicon-cog"></span> 网站设置</a>
      </li>      
      <li><a href="./login.php?logout"><span class="glyphicon glyphicon-log-out"></span> 退出登陆</a></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav><!-- /.navbar -->
<div class="container" style="padding-top:70px;">
<div class="col-xs-12 col-sm-10 center-block" style="float: none;">

	<div class="panel panel-primary">
					<div class="panel-heading"><h3 class="panel-title">站点配置</h3></div>
			<div class="panel-body">
				<form action="?" method="post" class="form-horizontal" role="form"> 
				<input type="hidden" name="do" value="set">
					<div class="input-group">
						<span class="input-group-addon">签到群号</span>
						<input type="text" name="group" value="<?php echo $group; ?>" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>			
					<div class="input-group">
						<span class="input-group-addon">每日超时</span>
						<input type="text" name="time" value="<?php echo $time; ?>" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>	
					<div class="input-group">
						<span class="input-group-addon">公司名称</span>
						<input type="text" value="<?php echo $company; ?>" class="form-control" placeholder="" autocomplete="on" required="" disabled="disabled">
					</div><br>					
					<div class="form-group">
						<div class="col-sm-12"><button type="submit" class="btn btn-primary form-control">确认修改</button></div>
					</div>
				</form>
			</div>
					<div class="panel-footer">
				<span class="glyphicon glyphicon-info-sign"></span> 当前版本 请同步app版本除更新外！<br>
				<span class="glyphicon glyphicon-info-sign"></span> 下载地址 此处是新版本下载地址！(带http://)
			</div>
		</div>

</div>
</div>
</body>
</html>