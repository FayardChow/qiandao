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

$rs=$DB->query("SELECT * FROM log");
while($res = $DB->fetch($rs)) {
	echo $res['qq']."\t".$res['name']."\t".$res['item']."\t".$res['add_time']."\t".$res['back_time']."\t".$res['over_time'];
	echo "\r\n";
}
?>