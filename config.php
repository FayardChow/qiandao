<?php
/*数据库配置*/
$dbconfig=array(
	'host' => '127.0.0.1', //数据库服务器
	'port' => 3306, //数据库端口
	'user' => 'root', //数据库用户名
	'pwd' => 'root', //数据库密码
	'dbname' => 'dy' //数据库名
);

/*网站配置*/
$conf=array(
	'admin_user' => 'wzadmin', //管理员用户名
	'admin_pwd' => 'adminwz', //管理员密码
	'jump_url' => 'http://fh.bb24.cn' //接口域名
);
/*邮箱配置*/
$mail=array(
	'smtp' => 'smtp.mxhichina.com', //smtp地址
	'port' => 25, //端口
	'name' => 'admin@ylkj.site', //邮箱帐号
	'pass' => '123456', //邮箱密码
	'key' => '123456', //监控密钥
	'addressee' => '202068724@qq.com' //接收邮箱
);
?>