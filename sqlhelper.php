<?php
   class SqlHelper{
     private $mysqli;
	 
	 /* private static $host="rdsbr63ifv7vziv.mysql.rds.aliyuncs.com";
     private static $user="r498f43006b02van";
     private static $pwd="xh07SFZ8810";
     private static $db="r498f43006b02van"; */

	 private static $host="localhost";
     private static $user="root";
     private static $pwd="nicai";
     private static $db="user";


     public function __construct(){
	   //完成初始化的任务
	   $this->mysqli=new MySQLi(self::$host,self::$user,self::$pwd,self::$db);
	   if($this->mysqli->connect_error){
	     die("连接失败".$this->mysqli->connect_error);
		 //设置数据库的访问字符集,目的是让php以utf8操作数据库的。
		 $this->mysqli->query("set names utf8"); 
	   }
	 }


	   public function execute_dql($sql){
	     $res= $this->mysqli->query($sql) or die("操作dql".$this->mysqli->error);
		 return $res;
	   }



	    public function execute_dml($sql){
	     $res= $this->mysqli->query($sql) or die("操作dml".$this->mysqli->error);
		 if(!$res){
		     return 0;//表示失败
		 }else{
			 if($this->mysqli->affected_rows>0){
		     return 1;//表示成功！
			 }else{
			 return 2;//表示没有行受到影响
			 }
		 }
	   }


   }
?>