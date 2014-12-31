<?php
error_reporting(E_ALL & ~E_NOTICE);
include "../insql.php";
/* include("../sqlhelper.php");
$sqlHelper=new SqlHelper(); */
$openid=$_GET['openid'];
$year=$_GET['year'];
$date=$_GET['date'];
$zs=getZS($date);
$sql="select *from teacher where openid='$openid'";
$rs=mysql_query($sql);
while($rst=mysql_fetch_array($rs,MYSQLI_NUM)){
	$row=$rst;
}
/* $res=$sqlHelper->execute_dql($sql);
$row=mysqli_fetch_array($res,MYSQLI_NUM); */						
$teacherNumber=$row[1];
//$teacherName=iconv('gb2312','utf-8',$row[2]);
$teacherName=$row[2];
$weekarray=array("日","一","二","三","四","五","六");
$xq=$weekarray[date('w',$date)];
/* $time = time()-strtotime('2014-09-07');
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
function getWeek($unixTime='')
{	
	$unixTime=is_numeric($unixTime)?$unixTime:time();
	return date('w',$unixTime);
}
echo "第".$zs."周 星期".(getWeek()-1)."晚自习"; */

$url=get_wz("../wzfw.txt")."queryAbsenceWX?teacherNumber=".$teacherNumber.'&year='.$year.'&date='.$date;
//echo $url;
$res=curl_get_noheader($url);
$kqqk=json_decode($res,true);
if($kqqk['msg']==='true')
{
	$classlist=$kqqk['classList'];
	$data=$kqqk['data'];
	if((count($classlist)==0)&&(count($data)==0))
	{
		echo "未考勤";
	}
	else
	{
		$date=explode('-',$date);
		$final_date=$date[0].'年'.$date[1].'月'.$date[2].'日';
		$ret=getKqxx($classlist,$data);
		echo "==========\n".$teacherName."老师您好:\n".
			"您所查询的年级是：".$year."级\n".
			"日期是：".$final_date."\n".
			"==========\n第".$zs."周  星期".$xq."\n".
			$ret;
	}
}
else
{
	echo "收包错误";
}



	
function getKqxx($classlist,$data)
{
	$j=0;
	if(count($data)==0)
	{
		return "未考勤";
	}
	else
	{
		for($i=0;$i<count($classlist);$i++)
		{
			if(isainB($classlist[$i],$data)==1)
			{
				$rest[$i]['班级']=$classlist[$i];
				$rest[$i]['全勤']=1;
				$rest[$i]['请假']="";
				$rest[$i]['未带卡']="";
				$rest[$i]['未考勤']="";
				for($j=0;$j<count($data);$j++)
				{
					if($data[$j]['classname']==$classlist[$i])
					{
						$name=$data[$j]['name'];
						switch($data[$j]['absent'])
						{
							case '请假':
								$rest[$i]['请假']=$rest[$i]['请假'].$name.'@';
								break;
							case '未带卡':
								$rest[$i]['未带卡']=$rest[$i]['未带卡'].$name.'@';
								break;
							case '未考勤':
								$rest[$i]['未考勤']=$rest[$i]['未考勤'].$name.'@';
								break;
							default:
								break;
						}
						
					}
				}
			}
			else
			{
				$rest[$i]['班级']=$classlist[$i];
				$rest[$i]['全勤']=0;
				$rest[$i]['请假']="";
				$rest[$i]['未带卡']="";
				$rest[$i]['未考勤']="";
			}
		}
		$temp='=========='."\n";
		$qq='';
		$qj='';
		$wdk='';
		for($k=0;$k<count($rest);$k++)
		{
			if($rest[$k]['全勤']==0)
			{
				$qq=$qq.$rest[$k]['班级']." ";
				$temp=$temp.$qq."：全勤\n==========\n";
			}
			else
			{
				$temp=$temp.$rest[$k]['班级'].'：'."\n";
				if($rest[$k]['未考勤']!='')
				{
					$temp=$temp."缺勤：".fg($rest[$k]['未考勤'])."\n";
				}
				if($rest[$k]['请假']!='')
				{
					$temp=$temp."请假：".fg($rest[$k]['请假'])."\n";
				}
				if($rest[$k]['未带卡']!='')
				{
					$temp=$temp."未带卡：".fg($rest[$k]['未带卡'])."\n";
				}
				$temp=$temp.'=========='."\n";
			}
			
		}
		
	}
	return $temp;

}

function getZS($date)
{
	$time = strtotime($date)-strtotime('2014-09-07');
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

function isainB($aa,$bb)
{
	for($q=0;$q<count($bb);$q++)
	{
		if($bb[$q]['classname']==$aa)
		{
			return 1;
		}
	}
	return 0;
}


function curl_post_302($url,$post)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$content = curl_exec($ch);
	curl_close ( $ch );
	if ($data != $Headers)
	{
		return $Headers["url"];
	}
	else
	{
		return false;
	}
}

function array_sort($arr,$keys,$type='asc')
{
	$keysvalue= $new_array= array();
	foreach($arr as $k=>$v)
	{
		$keysvalue[$k] = $v[$keys];
	}
	if($type== 'asc')
	{
		asort($keysvalue);
	}
	else
	{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach($keysvalue as $k=>$v)
	{
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
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

function curl_get($url)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_HEADER, 1 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$content = curl_exec ( $ch );
	curl_close ( $ch );
	return $content;
}

function curl_post($url,$post)
{
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt ( $ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$content = curl_exec($ch);
	curl_close ( $ch );
	return $content;
}

function curl_get_noheader($url)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$content = curl_exec ( $ch );
	curl_close ( $ch );
	return $content;
}
function a_array_unique($array)//写的比较好
{
   $out = array();
   foreach ($array as $key=>$value) {
	   if (!in_array($value, $out))
{
		   $out[$key] = $value;
	   }
   }
   return $out;
} 
function fg($arr)
{
	$arr=explode('@',$arr);
	$arr=array_filter($arr);
	$str='';
	for($m=0;$m<count($arr);$m++)
	{
		if($m==count($arr)-1)
		{
			$str=$str.$arr[$m];
		}
		else
		{
			$str=$str.$arr[$m]."、";
		}	
	}
	return $str;
}
function getWeek($unixTime='')
{	
	$unixTime=is_numeric($unixTime)?$unixTime:time();
	return date('w',$unixTime);
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