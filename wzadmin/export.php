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

$rs=$DB->query("SELECT * FROM frame_list");
while($res = $DB->fetch($rs))
{
echo $res['user'].'----'.$res['pass'].'----'.$res['date'];
echo "\r\n";
}
?>