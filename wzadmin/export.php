<?php
/**
 * 导出
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
Header( "Content-type:   application/octet-stream "); 
Header( "Accept-Ranges:   bytes "); 
header( "Content-Disposition:   attachment;   filename=export.txt "); 
header( "Expires:   0 "); 
header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
header( "Pragma:   public "); 



if(isset($_GET['kw'])) {

    $keyword = trim($_GET['kw']); 
    $sql = "1=1";

    if($_GET['kw'] != "") {
      if(preg_match("/\d+/", $keyword)) {
        $sql .= " AND `qq` LIKE '%$keyword%'";
      } else {
        $sql .= " AND `name` LIKE '%$keyword%'";
      }
    }


    //时间筛选
    elseif($_GET['start-date'] != "") {
      if($_GET['end-date'] == "") {

        $end_date = date("Y-m-d H:i:s");   //如果没有指定结束日期，默认结束日期为此刻

      } else {
        if(strtotime($_GET['end-date']) - strtotime($_GET['start-date']) < 0) {
          exit("<script language='javascript'>alert('结束时间要比开始时间晚');history.go(-1);</script>");
        } else {
          $end_date = $_GET['end-date'] . " 23:59:59";  //结束日期默认为当日23:59:59
         
        }
      }
      if($_GET['table'] == 'log') {
        $sql .= " AND UNIX_TIMESTAMP(add_time) >= UNIX_TIMESTAMP('{$_GET['start-date']}') AND UNIX_TIMESTAMP(add_time) <= UNIX_TIMESTAMP('$end_date')";
      } else {
        $sql .= " AND UNIX_TIMESTAMP(`date`) >= UNIX_TIMESTAMP('{$_GET['start-date']}') AND UNIX_TIMESTAMP(`date`) <= UNIX_TIMESTAMP('$end_date')";
      }
       

    }

    //超时筛选

    elseif(isset($_GET['over']) && $_GET['over'] == "on") {
      $sql .= " AND over_time";
    }

    else {
      $sql .= " AND 1=1";
    }

   
    if($_GET['table'] == 'log') {
      $res = $DB->query("SELECT * FROM log WHERE {$sql}");
    } else {
      $res = $DB->query("SELECT * FROM time WHERE {$sql}");
    }
}


else {
  if($_GET['table'] == 'log') {
	  $res = $DB->query("SELECT * FROM log ORDER BY add_time ASC");  //没有查询参数时查询全部
  } else{
    $res = $DB->query("SELECT * FROM time ORDER BY `date` ASC");
  }
}

if($_GET['table'] == 'log') {
  while($row = $DB->fetch($res)) {
  	echo $row['qq']."\t".$row['name']."\t".$row['item']."\t".$row['add_time']."\t".$row['back_time']."\t".$row['use_time']."\t".$row['over_time'];
  	echo "\r\n";
  }
} else {
   while($row = $DB->fetch($res)) {
    echo $row['qq']."\t".$row['name']."\t".$row['use_time']."\t".$row['over_time']."\t".$row['date'];
    echo "\r\n";
  } 
}
?>