<?php
/*
*获取数据总数
*/
include("../includes/common.php");


if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
//获取数据条数
$numrows=$DB->count("SELECT count(*) from frame_list WHERE 1");
exit($numrows);
