<?php
$mod='blank';
include("../includes/common.php");
$title='后台首页';
include './head.php';

if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style>
  form input{
    margin-right: 10px;
    margin-left: 4px;
  }
</style>
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
            <a href="./"><span class="glyphicon glyphicon-align-justify"></span> 记录列表</a>
          </li>
          <li>
            <a href="./stuff.php"><span class="glyphicon glyphicon-user"></span> 员工列表</a>
          </li>
          <li>
            <a href="./item.php"><span class="glyphicon glyphicon-th-list"></span> 事件列表</a>
          </li>
          <li>
            <a href="./set.php"><span class="glyphicon glyphicon-cog"></span> 网站设置</a>
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


//单个删除
if($my=='del'){
$id=intval($_GET['id']);
$sql=$DB->query("DELETE FROM log WHERE Id='$id'");
if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}

// 删除选中
elseif($my=='del2'){
$checkbox=$_POST['checkbox'];
$i=0;
foreach($checkbox as $id){
	$DB->query("DELETE FROM log WHERE Id='$id'");
	$i++;
}
exit("<script language='javascript'>alert('成功删除{$i}条记录');history.go(-1);</script>");
}

//清空所有
elseif($my=='qk'){
  $sql=$DB->query("DELETE FROM log");
  if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");

}




//默认列表
else{

if(isset($_GET['kw'])) {
	if($_GET['type']==1) {
    $keyword = trim($_GET['kw']); 


    if(preg_match("/\d+/", $keyword)) {
      $sql=" `qq` LIKE '%$keyword%'";
    } else {
      $sql=" `name` LIKE '%$keyword%'";
    }


    //时间筛选
    if($_GET['start-date'] != "") {
      if($_GET['end-date'] == "") {

        $end_date = date("Y-m-d H:i:s");   //如果没有指定结束日期，默认结束日期为此刻

      } else {
        if(strtotime($_GET['end-date']) - strtotime($_GET['start-date']) < 0) {
          exit("<script language='javascript'>alert('结束时间要比开始时间晚');history.go(-1);</script>");
        } else {
          $end_date = $_GET['end-date'] . " 23:59:59";  //结束日期默认为当日23:59:59
         
        }
      }
       $sql .= " AND UNIX_TIMESTAMP(add_time) >= UNIX_TIMESTAMP('{$_GET['start-date']}') AND UNIX_TIMESTAMP(add_time) <= UNIX_TIMESTAMP('$end_date')";

    }

    //超时筛选

    if(isset($_GET['over']) && $_GET['over'] == "on") {
      $sql .= " AND over_time";
    }
		

		$numrows=$DB->count("SELECT count(*) from log WHERE{$sql}");
		$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个记录';
	}

  $queryStr = "?".$_SERVER["QUERY_STRING"];
}else{
	$numrows=$DB->count("SELECT count(*) from log WHERE 1");
	$sql=" 1";
	$con='系统共有 <b id="data-num">'.$numrows.'</b> 条记录&nbsp;';
}
$con.='<a href="#" id="export"class="btn btn-primary btn-sm">导出列表</a>&nbsp;&nbsp;<a href="./index.php?my=qk" class="btn btn btn-danger btn-sm" onclick="return confirm(\'你确实要删除所有记录吗？\');">清空所有</a>&nbsp;&nbsp;<form style="margin: 20px 0;" class="form-inline" action="./" method="get"><div class="form-group"><label for="keyword">关键词</label><input type="text" class="form-control" placeholder="姓名或QQ号" id="keyword" name="kw"><label for="start-date">开始日期</label><input  class="form-control" type="date" id="start-date" name="start-date"/><label for="end-date">结束日期</label><input type="date"  class="form-control" id="end-date" name="end-date"/><input type="text" class="form-control" value="1" name="type" style="display: none;"></div><div class="checkbox"><label><input type="checkbox" name="over">超时</label></div><button type="submit" class="btn btn-primary">搜索</button></form>';
echo $con;
?>
      <div class="table-responsive">
	  <form name="form1" method="post" action="index.php?my=del2">
        <table class="table table-striped">
          <thead><tr><th>选择</th><th>QQ</th><th>姓名</th><th>事件</th><th>登记时间</th><th>返回时间</th><th>超时</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM log WHERE{$sql} order by add_time desc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td><input type="checkbox" name="checkbox[]" value="'.$res['Id'].'"> '.htmlspecialchars($res['user']).'</td><td>'.($res['qq']).'</td><td>'.$res['name'].'</td><td>'.$res['item'].'</td><td>'.$res['add_time'].'</td><td>'.$res['back_time'].'</td><td>'.$res['over_time'].'</td><td><a href="./index.php?my=del&id='.$res['Id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
<input id="select-all" type="checkbox">全选
<input type="submit" name="Submit" value="删除选中" class="btn btn btn-danger btn-sm">
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
    var queryStr = "<?php echo $queryStr ?>";
    $('#export').click(function() {
      window.location.href="export.php" + queryStr;

    })

   $("#select-all").click(function() {
        $('input[name="checkbox[]"]').prop("checked",this.checked); 
    });
    var $subBox = $("input[name='checkbox[]']");
    $subBox.click(function(){
        $("#select-all").prop("checked",$subBox.length == $("input[name='checkbox[]']:checked").length ? true : false);
    });

    
  </script>
  </body>
</html>