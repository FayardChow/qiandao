<?php
	date_default_timezone_set("Asia/Shanghai");
	include 'config.php';
	//连接数据库
	$con = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'], $dbconfig['dbname']);
	if (!$con){
		die('无法连接数据库: ' . mysqli_connect_error());
	}

	// 从cookie获取登录信息
	$islogin = 1;  // 是否登录
	if($_COOKIE['qq'] && $_COOKIE['company'] && $_COOKIE['md5str']) {
		$qq = $_COOKIE["qq"];
		$md5str = $_COOKIE["md5str"];
		$re = mysqli_query($con, "SELECT Id FROM stuff WHERE qq='".$qq."' AND md5str='".$md5str."'");
		if(!mysqli_num_rows($re)) {
			unset($_COOKIE["qq"]);
			unset($_COOKIE["name"]);
			unset($_COOKIE["company"]);
			unset($_COOKIE["md5str"]);
			mysqli_query($con, "UPDATE stuff SET md5str='' WHERE qq='".$qq."'");
			if(!isset($_GET['count_time'])) {
				exit('<script>alert("请先登录");window.location.href="/login.php"</script>');
			} else {
				$data =  new stdClass();
				$data->flag = -1;
				$reJSON = json_encode($data);
				exit($reJSON);
			}
		}
	} else {
			if(!isset($_GET['count_time'])) {
				exit('<script>alert("请先登录");window.location.href="/login.php"</script>');
			} else {
				$data =  new stdClass();
				$data->flag = -1;
				$reJSON = json_encode($data);
				exit($reJSON);
			}
	}

	// $qq = '3468935316'; //从cookie获取
	$company = $_COOKIE['company']; 	//从cookie获取
	$all_min = 0;		// 设定时间
	$now_use_time = 0;	// 目前用时
	$add_time = ''; 	// 添加时间
	$times = 0;			// 今日次数
	$use_time = 0;		// 今日使用时间
	$item = [];			// 时间数组
	$name = $_COOKIE['name']; //昵称
	// 今日离开次数和时间
	$re = mysqli_query($con, "SELECT use_time,times FROM time WHERE qq='".$qq."' AND date='".date("Y-m-d")."'");
	if($row = mysqli_fetch_assoc($re)) {
		$times = $row['times'];
		$use_time = $row['use_time'];
	}

	// 规定每日最多离开次数
	$re = mysqli_query($con, "SELECT time,max_time,max_times FROM setting WHERE company='".$company."'");
	if($row = mysqli_fetch_assoc($re)) {
		$time = $row['time'];
		$max_time = $row['max_time'];
		$max_times = $row['max_times'];
	}	

	// 签到时间
	$re = mysqli_query($con, "SELECT item,add_time FROM log WHERE qq='$qq' AND ISNULL(back_time) AND company='$company' ORDER BY add_time asc LIMIT 1");
	if($row = mysqli_fetch_assoc($re)) {
		$item = $row['item'];
		$add_time = $row['add_time'];
		$now_time = time();
		// exit(strtotime("now").'');
		// exit($now_time-$add_time.'');
		$now_use_time = round(($now_time-strtotime($add_time))/60);  // 目前使用时间


		// 计算签回剩余时间
		if(strpos($item, '+') !== FALSE) {
			$item = explode("+", $item);

			$re = mysqli_query($con, "SELECT name,time FROM item WHERE company='$company'");  //所有事件

			$all_min = 0;  //设定的超时时间
			while ( $row = mysqli_fetch_assoc($re)) {
				foreach ($item as $i) {
					if($row['name'] == $i) {
						$all_min += $row['time'];
					}
				}
			}

			//如果预计时间超过网站设置的单次最大时间，则以设置的最大时间为准
			$all_min = $all_min > $max_time ? $max_time : $all_min;
		} else {
			$re = mysqli_query($con, "SELECT time FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
			$row = mysqli_fetch_assoc($re);
			$all_min = $row['time'];
		}
	}

	if(isset($_GET['count_time']) && $_GET['count_time']==1) {
		$data =  new stdClass();
		$data->flag = 1;
		$data->all_min = $all_min;
		$data->now_use_time = $now_use_time;
		$data->item = $item;
		$data->add_time = $add_time;
		$reJSON = json_encode($data);
		exit($reJSON);
	}
	
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>签到系统</title>
  <link rel="stylesheet" href="/assets/layui-v2.2.5/css/layui.css">
  <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>
	<!-- 导航开始 -->
<ul class="layui-nav" lay-filter="">
  <li class="layui-nav-item layui-this"><a href="/">首页</a></li>
  <li class="layui-nav-item">
    <a href="#"><img src="http://t.cn/RCzsdCq" class="layui-nav-img"><?php echo $name; ?> </a>
    <dl class="layui-nav-child">
      <dd><a href="javascript: change_pass();">修改密码</a></dd>
      <dd><a href="/login.php?logout=1">退了</a></dd>
    </dl>
  </li>  
</ul>
	<!-- 导航结束 -->

	<!-- 进度条 -->

<div class="progress">
	<h5>今日次数(<?php echo $max_times-$times."/".$max_times ?>) 超次:<?php echo $times-$max_times>0 ? $times-$max_times:0; ?></h5>
	<div class="layui-progress layui-progress-big" lay-showPercent="yes">
	  <div class="layui-progress-bar layui-bg-green" lay-percent="<?php echo $max_times-$times."/".$max_times ?>"></div>
	</div>

	<h5>今日时间(<?php echo $time-$use_time."/".$time ?>) 超时:<?php echo $use_time-$time>0 ? $use_time-$time:0; ?></h5>
	<div class="layui-progress layui-progress-big" lay-showPercent="yes">
	  <div class="layui-progress-bar layui-bg-green" lay-percent="<?php echo $time-$use_time."/".$time ?>"></div>
	</div>

	<h5>本次时间(<span id="update-time"><?php echo $all_min-$now_use_time."/".$all_min; ?></span>) 超时:<span id="overtime"><?php echo $now_use_time-$all_min>0 ? $now_use_time-$all_min:0; ?></span> 离开时间:<span id="add-time"><?php echo $add_time;?></span></h5>
	<div class="layui-progress layui-progress-big" lay-filter="count-time" lay-showPercent="yes">
	  <div id="this-time" class="layui-progress-bar layui-bg-green" lay-percent="
	  <?php 
	  	if($all_min != 0) {
			echo 100*($all_min-$now_use_time)/$all_min.'%';
		} else {
			echo "100%";
		}
	  ?>
	  "></div>
	</div>
</div>
	<!-- 进度条结束 -->

<div class="action-btn">
	<button class="layui-btn layui-btn-radius layui-btn-normal">吃饭</button>
	<button class="layui-btn layui-btn-radius layui-btn-normal">抽烟</button>
	<button class="layui-btn layui-btn-radius layui-btn-normal">厕所</button>
	<button class="layui-btn layui-btn-radius layui-btn-warm"><i class="layui-icon">&#xe605;</i>签回</button>
</div>

<!-- 最近记录-->
<h2>最近签到记录(10条)</h2>
<table class="layui-table">
  <colgroup>
    <col width="150">
    <col width="200">
    <col>
  </colgroup>
  <thead>
    <tr>
      <th>昵称</th>
      <th>事件</th>
      <th>离开时间</th>
	  <th>返回时间</th>
	  <th>用时</th>
	  <th>超时</th>
    </tr> 
  </thead>
  <tbody>

<?php
	$re = mysqli_query($con, "SELECT * FROM log WHERE qq='".$qq."' AND company='".$company."' ORDER BY id DESC LIMIT 10");
	while($row=mysqli_fetch_assoc($re)) {
    	echo "<tr><td>{$row['name']}</td><td>{$row['item']}</td><td>{$row['add_time']}</td><td>{$row['back_time']}</td><td>{$row['use_time']}</td><td>{$row['over_time']}</td></tr>";		
	}

?>
  </tbody>
</table>
<!-- 最近记录结束 -->
<audio id="new">
    <source = src="/assets/new.wav" type="audio/wav" loop="loop">
</audio>

<script src="/assets/layui-v2.2.5/layui.js"></script>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
//一般直接写在一个js文件中
// layui.use(['layer', 'form'], function(){
//   var layer = layui.layer
//   ,form = layui.form;
  
//   layer.msg('Hello World');
// });


var layer;
layui.use('layer', function(){
	layer = layui.layer;
}); 


var emName = getCookie('name'); // 用户名
var qq = getCookie('qq');		// qq

// 获取cookie
function getCookie(name) {
	var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
	if (arr = document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}
// 修改密码
function change_pass() {
	layer.prompt({title: '输入新密码，并确认', formType: 1}, function(pass, index){
		if(!/^\S{3,}$/.test(pass)) {
			layer.msg("密码最少为三位，且不包含空格");
			return;
		}
		$.post('/ajax_action.php', {new_pass: pass}, function(data) {
			data = JSON.parse(data);
			if(data.flag == 1) {
				layer.close(index);
				layer.alert('您的新密码是：'+pass+' 三秒后重新登录');
				setTimeout(function() {
					window.location.href="/login.php?logout=1";
				}, 3000);
				
			} else {
				layer.alert(data.msg);
			}

		});
	});
}

// 签到以及签回
$(".layui-btn").click(function() {
	var $this = $(this);
	if(!$this.hasClass('layui-btn-disabled')) {
		var action = $this.text();
		$.post('/ajax_action.php', { action: action}, function (data) {
			data = JSON.parse(data);
			if (data.flag == 1) {
				layer.msg(data.msg);
				if($this.hasClass('layui-btn-normal')) {
					$this.addClass('layui-btn-disabled');
					$this.prepend('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i>');
				}
				if($this.hasClass('layui-btn-warm')) {
					$('.layui-btn-normal').removeClass('layui-btn-disabled');
					$('i').remove('.layui-anim-loop');
					setTimeout(function() {
						window.location.reload();
					}, 1000);
				}
			} else if(data.flag == 0) {
				layer.alert(data.msg);
			} else {
				window.location.href="/login.php";
			}
		});

	}
});

$(function() {
	count_time();
	var timer = setInterval(function() {
		count_time();
	}, 10000);


	// 获取本次用时信息， 
	function count_time() {
		$.get('/index.php', {count_time: 1}, function(data) {
			data = JSON.parse(data);
			if (data.flag == 1) {
				layui.use('element', function(){
					element = layui.element;
					if(data.all_min!=0) {
						var timeLeft = data.all_min-data.now_use_time;
						var percent = 100*timeLeft/data.all_min+'%';
						element.progress('count-time', percent);
						$('#this-time').attr('lay-percent', percent);
						$('#update-time').text(timeLeft+"/"+data.all_min);
						if(timeLeft < 0) {
							$('#overtime').text(-timeLeft);
						}
						// 超时提醒
						if(timeLeft < 3) {
							var audio = document.getElementById("new");
							audio.play();
						}

						// 离开时间
						if(data.add_time) {
							$('#add-time').text(data.add_time);
						}
					} else {
						element.progress('count-time', "100%");
						$('#this-time').attr('lay-percent', "100%");
					}
					// console.log( 100*(data.all_min-data.now_use_time)/data.all_min+'%');
					
				}); 

				//页面加载设置按钮状态
				if(data.item) {
					var itemArr = [];
					if(typeof(data.item)=='string') {
						itemArr.push(data.item);
					} else {
						itemArr = data.item;
					}
					itemArr.forEach(i => {
						$('.layui-btn-normal').each(function() {
							if($(this).text()==i) {
								$(this).addClass('layui-btn-disabled');
								$(this).prepend('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i>');
							}
						});
					});
				}
			} else if(data.flag == -1) {
				window.location.href="/login.php";
			}			
		});		
	}
});
</script>
</script> 
</body>
</html>