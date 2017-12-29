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
		
    $res = $DB->query("SELECT * FROM log WHERE{$sql}");

}

else {
	$res = $DB->query("SELECT * FROM log");  //没有查询参数时查询全部

}


while($row = $DB->fetch($res)) {
	echo $row['qq']."\t".$row['name']."\t".$row['item']."\t".$row['add_time']."\t".$row['back_time']."\t".$row['over_time'];
	echo "\r\n";
}
?>