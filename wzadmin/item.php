<?php
$mod='blank';
include("../includes/common.php");
$title='事件列表';
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
          <li>
            <a href="./"><span class="glyphicon glyphicon-align-justify"></span> 记录列表</a>
          </li>
          <li>
            <a href="./time.php"><span class="glyphicon glyphicon-user"></span> 用时列表</a>
          </li>          
          <li>
            <a href="./stuff.php"><span class="glyphicon glyphicon-user"></span> 员工列表</a>
          </li>
          <li class="active">
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
$sql=$DB->query("DELETE FROM item WHERE Id='$id'");
if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");
}

// 删除选中
elseif($my=='del2'){
$checkbox=$_POST['checkbox'];
$i=0;
foreach($checkbox as $id){
	$DB->query("DELETE FROM item WHERE Id='$id'");
	$i++;
}
exit("<script language='javascript'>alert('成功删除{$i}条记录');history.go(-1);</script>");
}

//清空所有
elseif($my=='qk'){
  $sql=$DB->query("DELETE FROM item");
  if($sql){$res='删除成功！';}
  else{$res='删除失败！';}
  exit("<script language='javascript'>alert('{$res}');history.go(-1);</script>");

}

//编辑事件
elseif($my == "edit") {
	$name = $_GET['name'];
	$time = $_GET['time'];
	$id = $_GET['id'];

	if(!preg_match("/^\d+$/", $time)) 
		exit("<script language='javascript'>alert('时间格式不正确');history.go(-1);</script>");

	$nrows = $DB->count("SELECT count(*) FROM item WHERE name='$name' AND Id!='$id'");

	if($nrows) {
		exit("<script language='javascript'>alert('名称已占用');history.go(-1);</script>");
	} else {
		$re = $DB->query("SELECT * FROM item WHERE Id='$id' LIMIT 1");  //查询现有员工信息

		$row = $DB->fetch($re);

		if($row['name'] == $name && $row['time'] == $time) {
			exit("<script language='javascript'>alert('无更改');history.go(-1);</script>");
		}
			
		else {
			$sql = $DB->query("UPDATE item SET name='$name', time='$time' WHERE Id='$id'");  //更新事件资料
			if($sql) {
				exit("<script language='javascript'>alert('更新成功');history.go(-1);</script>");				
			}

		}
		
	}

}



//新增事件
elseif(isset($_GET['name'])){
	if($_GET['name'] != "" && $_GET['time'] != "") {
		$name = trim($_GET['name']);
		$time = trim($_GET['time']);
		if(preg_match("/\d+/", $time)) {

			$nrows = $DB->count("SELECT count(*) FROM item WHERE name='$name' LIMIT 1");  //查询q名称是否已存在
			
			if($nrows) {
				exit("<script language='javascript'>alert('名称已占用');history.go(-1);</script>");
			} else {

				$re = $DB->query("INSERT INTO item (name, time) VALUES ('$name', '$time')");

				if($re) {
					exit("<script language='javascript'>alert('添加成功！');history.go(-1);</script>");
				} else {
					exit("<script language='javascript'>alert('添加失败！');history.go(-1);</script>");
				}

			}

		}else {
			exit("<script language='javascript'>alert('时间格式不正确，规定时间是以分钟为单位的整数');history.go(-1);</script>");
		}

	} else {
		exit("<script language='javascript'>alert('请确认名称和时间已填');history.go(-1);</script>");
	}


}


//默认列表
else{

$numrows=$DB->count("SELECT count(*) from item WHERE 1");
$sql=" 1";
$con='系统共有 <b id="data-num">'.$numrows.'</b> 条记录&nbsp;';

$con.='<form style="margin: 20px 0;" class="form-inline" action="'.$_SERVER['PHP_SELF'].'" method="get"><div class="form-group"><label for="name">事件名称</label><input type="text" class="form-control" placeholder="名称" id="name" name="name"></div><div class="form-group"><label for="name">规定时间</label><input type="text" class="form-control" placeholder="时间(单位-分钟)" id="time" name="time"></div><button type="submit" class="btn btn-primary">添加</button></form>';
echo $con;
?>
      <div class="table-responsive">
	  <form name="form1" method="post" action="item.php?my=del2">
        <table class="table table-striped">
          <thead><tr><th>选择</th><th>事件名称</th><th>规定时间</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM item WHERE{$sql} order by id asc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td><input type="checkbox" name="checkbox[]" value="'.$res['Id'].'"> </td><td>'.$res['name'].'</td><td>'.$res['time'].'</td><td><a href="./item.php?my=del&id='.$res['Id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a>&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-success btn-edit" data-toggle="modal" data-target="#myModal" id="'.$res['Id'].'">编辑</a></td></tr>';
}
?>
          </tbody>
        </table>
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
echo '<li><a href="item.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="item.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="item.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="item.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="item.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="item.php?page='.$last.$link.'">尾页</a></li>';
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
  	$('.btn-edit').click(function() {
  		var name = $(this).parent().parent().find('td:eq(1)').text();
  		var time = $(this).parent().parent().find('td:eq(2)').text();

  		$('#edit-id').attr('value', $(this).attr('id'));
  		$('.modal-dialog').find('[name=name]').val(name);
  		$('.modal-dialog').find('[name=time]').val(time);

  	})

  </script>

<!-- 编辑模态框 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					编辑员工信息
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>">
				  <div class="form-group">
				    <label class="sr-only" for="name">事件名称</label>
				    <input type="text" class="form-control" id="name" placeholder="名称" name="name">
				  </div>
				  <div class="form-group">
				    <label class="sr-only" for="time">规定时间</label>
				    <input type="text" class="form-control" id="time" placeholder="时间" name="time">
				  </div>
				  <div class="form-group" style="display: none;">
				    <input type="text" class="form-control" name="my" value="edit">
				  </div>
				  <div class="form-group" style="display: none;">
				    <input type="text" class="form-control" id="edit-id" name="id" value="">
				  </div>			  
				  <button type="submit" class="btn btn-primary">提交</button>
				</form>		
			</div>
			<div class="modal-footer">
				<p style="text-align: left;">填入事件名称和规定时间(以分钟为单位)</p>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>  
  </body>
</html>