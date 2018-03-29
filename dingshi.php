<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set("Asia/Shanghai");
include 'config.php';



//连接数据库
$con = mysql_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd']);
if (!$con){
  die('无法连接数据库: ' . mysql_error());
}
mysql_select_db($dbconfig['dbname'], $con);


$r_g = mysql_query("SELECT `group` FROM setting LIMIT 1");  //查询群号

if($row_g = mysql_fetch_array($r_g)) {
	$group = $row_g['group'];  //群号信息
}else {
	$CQ->sendGroupMsg($group, "群号未设置，请到管理后台设置群号");
	unset($CQ);//释放连接
	exit();
}


$re = mysql_query("SELECT * FROM log WHERE isnull(back_time) ORDER BY add_time ASC");

while ($row = mysql_fetch_array($re)) {
	//$CQ->sendGroupMsg($group, $row['add_time']);
    $company = $row['company'];
    if(strpos($row['item'], '+') !== FALSE) {
        $item = explode("+", $row['item']);
        $re2 = mysql_query("SELECT * FROM item WHERE company='$company'");  //所有事件

        $all_min = 0;  //设定的超时时间
        while ( $arr = mysql_fetch_array($re2)) {
            foreach ($item as $i) {
                if($arr['name'] == $i) {
                    $all_min += $arr['time'];
                }
            }
        }
        //如果预计时间超过网站设置的单次最大时间，则以设置的最大时间为准
        $re3 = mysql_query("SELECT max_time FROM setting  WHERE company='$company'");
        $arr = mysql_fetch_array($re3);
        $all_min = $all_min > $arr['max_time'] ? $arr['max_time'] : $all_min;          
    } else {
        $item = $row['item'];
        $re2 = mysql_query("SELECT * FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
        $arr = mysql_fetch_array($re2);
        $all_min = $arr['time'];
    }


    $min = round((time() - strtotime($row['add_time']))/60);  //时间相差分钟数
    $over_time = $min - $all_min;  //超时时间
  
  	if($over_time >= -3 && $over_time <= 0) {
      $CQ->sendPrivateMsg($row['qq'], $CQ->cqShake());
      $CQ->sendPrivateMsg($row['qq'], "您".$row['item']."还有".abs($over_time)."分钟, 请尽快签回");
  	}


  	if($over_time == 5) {
      $CQ->sendPrivateMsg($row['qq'], $CQ->cqShake());
      $CQ->sendPrivateMsg($row['qq'], "您已超时5分钟,系统将不再提示");
  	}
    

}

// $CQ->sendGroupMsg($group, "定时任务");





mysql_close($con);
unset($CQ);//释放连接
