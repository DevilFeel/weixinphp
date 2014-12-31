<?php
	include "../insql.php";
/* 		$conn=mysql_connect("localhost","root","nicai");          //连接MYSQL
		//echo mysql_error();
		mysql_select_db("user",$conn);                //连接数据库
		mysql_query("set names utf8");  */


/* 		mysql_query("SET NAMES 'UTF8'"); 
		mysql_query("SET CHARACTER SET UTF8");
		mysql_query("SET CHARACTER_SET_RESULTS=UTF8'"); */

		//设置编码格式
		echo "数据库已连接";
		$teacherNumber=60105;
		$name='hello';
		$openid='ababrbfafaf';
		$sql="insert into teacher(openid,teacherNumber,name)values('$openid','$teacherNumber','$name')";
		mysql_query($sql);
		echo mysql_error();
		echo $sql;
		//mysql_close($conn);
		
?>