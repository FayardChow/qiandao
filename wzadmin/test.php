<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include("../includes/common.php");
if(isset($_SESSION['company'])) {
	// $sql=$DB->query("SELECT company FROM user WHERE name={$_SESSION['user']} LIMIT 1");
	// if($row = mysql_fetch_array($sql)) {
	// 	var_dump($row);
	// }

	var_dump($_SESSION['company']);
}


