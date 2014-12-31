<?php
	$conn=mysql_connect("localhost","root","nicai");          //连接MYSQL
	mysql_select_db("user",$conn);                //连接数据库
	mysql_query("set names utf8");                          //设置编码格式
	echo mysql_error();
?>