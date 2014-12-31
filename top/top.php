<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>上周TOP10</title>

  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <link rel="icon" type="image/png" href="assets/i/favicon.png">

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="assets/i/app-icon72x72@2x.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
  <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileColor" content="#0e90d2">

  <link rel="stylesheet" href="assets/css/amazeui.min.css">
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<div align="center" class="am-panel am-panel-primary">
	<header class="am-panel-hd">
	  <span class="am-article-title">上周TOP10</span>
    </header>
</div>

<!--在这里编写你的代码-->
<?php
	include "../insql.php";
	$openid=$_GET['openid'];
	get_top10($openid,2014);
/* 	echo "<div class='am-panel am-panel-warning'>";
		echo "<header class='am-panel-hd'>";
			echo "<h3 class='am-panel-title'>=========================</h3>";
			//echo "</div>";
		echo "</header>";
	echo "</div>"; */
	get_top10($openid,2013);
	
//一周Top10
function get_top10($openid, $year)
{
	//统计每周之最
	//$year=2014;
	//获得teacherNumber
	$sql="select * from teacher where openid = '$openid'";
	$rs=mysql_query($sql);
	while($rst=mysql_fetch_array($rs,MYSQLI_NUM))
	{
		$teacherNumber=$rst[1]; //获得teacherNumber
	}
	$weekDays=get_zhou()-1;
	//$teacherNumber=1314520;
	
	$url_zhouzui=get_wz("../wzfw.txt")."queryLastWeekDataWX?year=".$year."&weekDays=".$weekDays."&teacherNumber=".$teacherNumber;
	
	$temp=curl_get1($url_zhouzui);
	$zui_info=json_decode($temp,true); //周最
	//print_r($zui_info);
	$zui_msg=$zui_info['msg']; //获得结果标记，true则有数据,false则无，返回所有成员都正常考勤
	$zui_data=$zui_info['data']; //获得未正常考勤人
	if($zui_msg==='true')
	{
		if(count($zui_data)===0){//如果没有缺勤数据的情况下，返回上周无Top10信息
			$yearxs= $year."级学生";
			echo "<span  class='am-article-title'>".$yearxs."</span>";
			$temp="没有TOP10情况哦。";
			echo "<div class='am-panel am-panel-primary'>";
				echo "<header class='am-panel-hd'>";
					echo "<h3 class='am-panel-title'>".$temp."</h3>";
					//echo "</div>";
				echo "</header>";
			echo "</div>";				
			return;
		}
		//统计状态如下
/* 		    [0] => Array
        (
			[id] =>
            [name] => 曹瀚文
            [classname] => 物联网1141
            [wdk] => 1
            [qingjia] => 0
            [chidao] => 0
            [absent] => 0
        ) */
		for($i=0;$i<count($zui_data);$i++)
		
		{
			if($i===0)
			{
				$zui_count[$i]['id']=$zui_data[$i]['id']; //学号
				$zui_count[$i]['name']=$zui_data[$i]['name'];
				$zui_count[$i]['classname']=$zui_data[$i]['classname'];
				$zui_count[$i]['wdk']=0;  //未带卡
				$zui_count[$i]['qingjia']=0; //请假
				$zui_count[$i]['chidao']=0; //迟到
				$zui_count[$i]['absent']=0;  //缺勤
				$state_absent=return_state($zui_data[$i]);
				$zui_count[$i][$state_absent]++; //对应状态加1
			}
			else
			{
				//$j=return_xb($zui_count,$zui_data[$i]['name'],$zui_data[$i]['classname']);
				$j=return_xb($zui_count,$zui_data[$i]['id']);
				if($j>=count($zui_count))//新同学
				{
					$zui_count[$j]['id']=$zui_data[$i]['id'];
					$zui_count[$j]['name']=$zui_data[$i]['name'];
					$zui_count[$j]['classname']=$zui_data[$i]['classname'];
					$zui_count[$j]['wdk']=0;  //未带卡
					$zui_count[$j]['qingjia']=0; //请假
					$zui_count[$j]['chidao']=0; //迟到
					$zui_count[$j]['absent']=0;  //缺勤
					$state_absent=return_state($zui_data[$i]); 
					$zui_count[$j][$state_absent]++; //对应状态加1
				}
				else
				{
					$state_absent=return_state($zui_data[$i]);
					$zui_count[$j][$state_absent]++; //对应状态加1
				}
			}
			
		}
		//Top10之缺勤
		foreach($zui_count as $key => $value)
		{
			$zui_name[$key] = $value['name'];
			$zui_class[$key] = $value['classname'];
			$zui_wdk[$key] = $value['wdk'];
			$zui_qingjia[$key] = $value['qingjia'];
			$zui_chidao[$key] = $value['chidao'];
			$zui_absent[$key] = $value['absent'];
		}
		array_multisort($zui_absent, SORT_DESC, $zui_count);
		//$str_top="<br>".$year."级学生<br>====缺勤Top10====<br><br>";
		//$str_top=$str_top.out_top($zui_count,10,'absent');
		$yearxs= "<br>".$year."级学生<br>";
		echo "<span  class='am-article-title'>".$yearxs."</span>";
		if($zui_count[0]['absent']===0){
			$temp= "没有".return_sname('absent')."Top10的人员。";
			
			echo "<div class='am-panel am-panel-primary'>";
				echo "<header class='am-panel-hd'>";
					echo "<h3 class='am-panel-title'>".$temp."</h3>";
					//echo "</div>";
				echo "</header>";
			echo "</div>";
		}else{
			out_top($zui_count,10,'absent');
		}
		
		
		//Top10之未带卡
		foreach($zui_count as $key => $value)
		{
			$zui_name[$key] = $value['name'];
			$zui_class[$key] = $value['classname'];
			$zui_wdk[$key] = $value['wdk'];
			$zui_qingjia[$key] = $value['qingjia'];
			$zui_chidao[$key] = $value['chidao'];
			$zui_absent[$key] = $value['absent'];
		}
		array_multisort($zui_wdk, SORT_DESC, $zui_count);
		//$str_top=$str_top."====未带卡Top10====<br><br>";
		//$str_top=$str_top.out_top($zui_count,10,'wdk');
		//echo "<br>====未带卡Top10====<br><br>";
		if($zui_count[0]['wdk']===0){
			$temp="没有".return_sname('wdk')."Top10的人员。";
			echo "<div class='am-panel am-panel-primary'>";
				echo "<header class='am-panel-hd'>";
					echo "<h3 class='am-panel-title'>".$temp."</h3>";
					//echo "</div>";
				echo "</header>";
			echo "</div>";			
		}else{
			out_top($zui_count,10,'wdk');
		}
		
		//Top10之请假
		foreach($zui_count as $key => $value)
		{
			$zui_name[$key] = $value['name'];
			$zui_class[$key] = $value['classname'];
			$zui_wdk[$key] = $value['wdk'];
			$zui_qingjia[$key] = $value['qingjia'];
			$zui_chidao[$key] = $value['chidao'];
			$zui_absent[$key] = $value['absent'];
		}
		array_multisort($zui_qingjia, SORT_DESC, $zui_count);
		//$str_top=$str_top."====请假Top10====<br><br>";
		//$str_top=$str_top.out_top($zui_count,10,'qingjia');
		//echo "<br>====请假Top10====<br><br>";
		if($zui_count[0]['qingjia']===0){
			$temp="没有".return_sname('qingjia')."Top10的人员。";
			echo "<div class='am-panel am-panel-primary'>";
				echo "<header class='am-panel-hd'>";
					echo "<h3 class='am-panel-title'>".$temp."</h3>";
					//echo "</div>";
				echo "</header>";
			echo "</div>";			
		}else{
			out_top($zui_count,10,'qingjia');
		}
		
		
		//Top10之迟到
		foreach($zui_count as $key => $value)
		{
			$zui_name[$key] = $value['name'];
			$zui_class[$key] = $value['classname'];
			$zui_wdk[$key] = $value['wdk'];
			$zui_qingjia[$key] = $value['qingjia'];
			$zui_chidao[$key] = $value['chidao'];
			$zui_absent[$key] = $value['absent'];
		}
		array_multisort($zui_chidao, SORT_DESC, $zui_count);
		//$str_top=$str_top."=====迟到Top10=====<br><br>";
		//$str_top=$str_top.out_top($zui_count,10,'chidao');
		//echo "<br>=====迟到Top10=====<br><br>";
		if($zui_count[0]['chidao']===0){
			$temp="没有".return_sname('chidao')."Top10的人员";
			echo "<div class='am-panel am-panel-primary'>";
				echo "<header class='am-panel-hd'>";
					echo "<h3 class='am-panel-title'>".$temp."</h3>";
					//echo "</div>";
				echo "</header>";
			echo "</div>";
		}else{
			out_top($zui_count,10,'chidao');
		}
		
		
		//return $str_top;
	}
	else
	{
		return "没有缺勤哦";		
	}
}


//返回数组下标

function return_xb($arr,$id)
{
	$i=0;
	for($i=0;$i<count($arr); $i++)
	{
		if($arr[$i]['id']===$id){
			break;
		}
	}
	return $i;
}
//判断absent状态（未带卡0，请假1，迟到2，未考勤3）
function return_state($arr)
{
	if($arr['absent']==='未带卡')
	{
		return 'wdk';
	}
	else if($arr['absent']==='请假')
	{
		return 'qingjia';
	}
	else if($arr['absent']==='迟到')
	{
		return 'chidao';
	}
	else
	{
		return 'absent';
	}
}
//
function return_sname($name)
{
	if($name==='absent')
	{
		return "缺勤";
	}else if($name==='wdk'){
		return "未带卡";
	}else if($name==='qingjia'){
		return "请假";
	}else{
		return "迟到";
	}
}
//输出Top10
function out_top($arr,$n,$state)
{
	$str="";
	$state_name=return_sname($state);
	echo "<div class='am-panel am-panel-primary'>";
		echo "<header class='am-panel-hd'>";
			echo "<h3 class='am-panel-title'>====".$state_name."Top10====</h3>";
			echo "</div>";
		echo "</header>";
		echo "<div class='am-panel-bd'>";
	for($i=0;$i<$n; $i++)
	{
		$j=$i+1;
		$name=$arr[$i]['name'];
		$classname=$arr[$i]['classname'];
		$count_state=$arr[$i][$state];
		if($count_state===0)
		{
			break;
		}
		$id=$arr[$i]['id'];
		$count=exit_count($id,$state);
		if($count===0){
			echo "Top".$j.": ".$classname."   ".$name."（".$count_state."次）<br>";
		}else{
			$rs_lx="Top".$j.": ".$classname."   ".$name."（".$count_state."次）已经连续".(++$count)."周".$state_name."  <br>";
			echo "<span style='color:#F00'>$rs_lx</span>";
		}		
	}
		echo "</div>";
	echo "</div>";
}
//读取文件
function get_wz($name)
{
	//读取文件网址
	$myfile = fopen($name,"r") or die("1");
	$wz = fgets($myfile);
	return $wz;
}
//获得当前周
function get_zhou()
{
	$time = time()-strtotime('2014-09-07');
	$xg_time=(int)($time/(3600*24));
	$q1=$xg_time/7;
	$q2=$xg_time%7;
	if($q2<>0)
	{
		$zs=$q1+1;
		$zs=(int)($zs);
	}
	else
	{
		$zs=(int)($q1);
	}
	return $zs;
}
function curl_get1($url)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$content = curl_exec ( $ch );
	curl_close ( $ch );
	return $content;
}
//上周是否也连续进入TOP10
 function exit_count($id, $absent)
 {
	 $num=0;
	 $sql="SELECT * FROM top WHERE studentid='$id' AND absent='$absent'";
	 $rs=mysql_query($sql);
	 while($rst=mysql_fetch_array($rs,MYSQLI_NUM))
	 {
		 $row=$rst;
		 $num++;
	 }
	 if($num===0){
		 return 0;
	 }else{
		 $count=$row[2];
		 return $count;
	 }
 }
?>


<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<![endif]-->
</body>
</html>
