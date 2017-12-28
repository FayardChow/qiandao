<?php
/**
 * 网站设置
**/
$mod='blank';
include("../includes/common.php");
$title='网站设置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if(isset($_POST['fxurl'])) {
	$rs=$DB->query("UPDATE frame_set SET fxurl='".$_POST['fxurl']."' WHERE 1=1 LIMIT 1");
	if($rs){$res='设置成功';}
	else{$res='设置失败';}
	exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}

$rs=$DB->query("SELECT * FROM frame_set LIMIT 1");
if($rs) {
	$res = $DB->fetch($rs);
	$fxurl = $res['fxurl'];  //分享链接

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
        <a href="./"><span class="glyphicon glyphicon-user"></span> 数据列表</a>
      </li>
      <li class="active">
        <a href="./set.php"><span class="glyphicon glyphicon-user"></span> 网站设置</a>
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
						<span class="input-group-addon">分享链接<br>(带http://)</span>
						<textarea type="text" name="fxurl" value="" class="form-control"  rows="10" placeholder="" autocomplete="on" required=""><?php echo $fxurl;?></textarea>
					</div><br>				
					<!-- <div class="input-group">
						<span class="input-group-addon">主页标题</span>
						<input type="text" name="zytitle" value="王者荣耀全心出击 周年狂欢" class="form-control" placeholder="" autocomplete="on" required="">
					</div>
					<div class="input-group">
						<span class="input-group-addon">背景地址</span>
						<input type="text" name="backpng" value="https://www.yunzhijia.com/microblog/filesvr/5a03e732ea3b4a0abc9ef908?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">左上图片地址</span>
						<input type="text" name="tp1" value="https://www.yunzhijia.com/microblog/filesvr/5a03e731364a0f1418e74022?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">左上图片标题</span>
						<input type="text" name="tp1title" value="钟无艳 海滩丽影" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">右上图片地址</span>
						<input type="text" name="tp3" value="https://www.yunzhijia.com/microblog/filesvr/5a03e732364a0f1418e74046?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">右上图片标题</span>
						<input type="text" name="tp3title" value="韩信 逐梦之影" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中左图片地址</span>
						<input type="text" name="tp4" value="https://www.yunzhijia.com/microblog/filesvr/5a03e732364a0f1418e74049?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中左图片标题</span>
						<input type="text" name="tp4title" value="夏侯淳 乘风破浪" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">正中图片地址</span>
						<input type="text" name="tp5title" value="抽奖按钮，标题无需更改" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中右图片地址</span>
						<input type="text" name="tp6" value="https://www.yunzhijia.com/microblog/filesvr/5a03e7319b521a5b4295a7e2?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中右图片标题</span>
						<input type="text" name="tp6title" value="诸葛亮 黄金分割率" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">左下图片地址</span>
						<input type="text" name="tp7" value="https://www.yunzhijia.com/microblog/filesvr/5a03e73250f8dd619273a51d?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">左下图片标题</span>
						<input type="text" name="tp7title" value="缤纷独角兽 小乔" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中下图片地址</span>
						<input type="text" name="tp8" value="https://www.yunzhijia.com/microblog/filesvr/5a03e7319b521a5b4295a7e5?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">中下图片标题</span>
						<input type="text" name="tp8title" value="爱与和平 苏烈" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">右下图片地址</span>
						<input type="text" name="tp9" value="https://www.yunzhijia.com/microblog/filesvr/5a03e7322711cd7059ce5c2d?original" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">右下图片标题</span>
						<input type="text" name="tp9title" value="伊势巫女 大乔" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">引流QQ号码</span>
						<input type="text" name="qrurl" value="2723557017" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">分享出去的地址</span>
						<input type="text" name="fxurl" value="http://t.cn/RYvNriv" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">随机字符长度</span>
						<input type="text" name="changdu" value="15" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">自动检测密匙</span>
						<input type="text" name="cronrand" value="123456" class="form-control" placeholder="" autocomplete="on" required="">
					</div><br>  -->
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