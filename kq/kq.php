<?php
error_reporting(E_ALL & ~E_NOTICE);
include "../sqlhelper.php";
$sqlHelper=new SqlHelper();
$id=$_GET["id"];
$pwd=$_GET["pwd"];
$openid=$_GET["openid"];

$url=get_wz("../wzfw.txt")."validateWX?account=".$id."&password=".$pwd;
$temp=curl_get($url);
$obj = json_decode($temp,true);
print_r($obj);
$msg=$obj['msg'];
if($msg==='true')
{
	$teacherNumber=$obj['teacherNumber'];
	$name=$obj['name'];
	$rank=$obj['rank'];
	$departmentNumber=$obj['departmentNumber'];
	
	$contentStr="恭喜您".$name."老师,您已经绑定成功，请返回微信聊天界面，再次点击考勤查询按钮，即可获得当日考勤结果。";
	echo '1';
}
else
{
	$contentStr="您的用户名或密码有误，请查证后重新输入！";
	echo $contentStr;
	echo '0';
}
			
function curl_get($url)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$content = curl_exec ( $ch );
	curl_close ( $ch );
	return $content;
}
//读取文件
function get_wz($name)
{
	//读取文件网址
	$myfile = fopen($name,"r") or die("1");
	$wz = fgets($myfile);
	return $wz;
}
?>