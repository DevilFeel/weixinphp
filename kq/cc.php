<?php
error_reporting(E_ALL & ~E_NOTICE);
//include "../sqlhelper.php";
include "../insql.php";
//$sqlHelper=new SqlHelper();
$id=$_POST["id"];
$pwd=$_POST["pwd"];
$openid=$_GET["openid"];
//echo $openid;
$url=get_wz("../wzfw.txt")."validateWX?account=".$id."&password=".$pwd;
$temp=curl_get($url);
//echo $temp;
$obj = json_decode($temp,true);
/* print_r($obj); */
$msg=$obj['msg'];
if($msg==='true')
{
	$teacherNumber=$obj['teacherNumber'];
	//$name=iconv('utf-8','gb2312',$obj['name']);
	$name=$obj['name'];
	$rank=$obj['rank'];
	$departmentNumber=$obj['departmentNumber'];
	$sql="insert into teacher(openid,teacherNumber,name)values('$openid','$teacherNumber','$name')";
	//$res=$sqlHelper->execute_dml($sql);
	mysql_query($sql);
/* 	echo $openid."<br>";
	echo $teacherNumber."<br>";
	echo $name; */
	$name=$obj['name'];
	if(isExit($openid))
	{
		$contentStr="恭喜您".$name."老师,您已经绑定成功，请返回微信聊天界面，再次点击考勤查询按钮，即可获得当日考勤结果。";
	}
	else
	{
		$contentStr="存储错误！";
	}
}
else
{
	$contentStr="您的用户名或密码有误，请查证后重新输入！";
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
	$myfile = fopen($name,r) or die("1");
	$wz = fgets($myfile);
	return $wz;
}
//检测数据库是否存在该用户(通过openid)
function isExit($openid){
	$sql="select * from teacher where openid = '$openid'";
	$rs=mysql_query($sql);
	$num=0;
	while($rst=mysql_fetch_array($rs,MYSQLI_NUM))
	{
		$num++;
	}	
	if($num===0){
		return false;
	}else{
		return true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="http://cdn.amazeui.org/amazeui/1.0.1/css/amazeui.css" rel="stylesheet" type="text/css" />
<title>绑定</title>
<style>
	.header {
		text-align: center;
	}
	.header h1 {
		font-size: 200%;
		color: #333;
		margin-top: 30px;
	}
	.header p {
		font-size: 14px;
	}
	.am-cf{
		text-align: center;
		margin: auto;
	}
</style>
</head>

<body>
    <div class="header">
        <h1>绑定结果</h1>
        <hr />
    </div>
    <div class="header">
			<h1><?php echo $contentStr; ?></h1> 
    </div>
    <div class="am-footer">
        <hr />
        <p></p>
    </div>
</body>
</html>