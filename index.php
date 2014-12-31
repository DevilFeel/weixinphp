<?php
include "wechat.class.php";
include "tl.class.php";
//include "sqlhelper.php";
include "insql.php";
$options = array(
		'token'=>'weixin', //填写你设定的key
		'appid'=>'wx43c7c9818464c4d4', //填写高级调用功能的app id
		'appsecret'=>'66f1f10729cf377c1f1b2d38c83fd289' //填写高级调用功能的密钥
	);
//$sqlHelper=new SqlHelper();
$weObj = new Wechat($options);
$weObj->valid();

//设置菜单
$newmenu =  array
	(
		"button"=>
			array
				(
					array
					(
						'name'=>'考勤',
						'sub_button'=>
						array
						(
							
							array
							(
								'type'=>'click',
								'name'=>'考勤查询',
								'key'=>'MENU_KEY_KQCX'
							),
							array
							(
								'type'=>'click',
								'name'=>'一周图表',
								'key'=>'MENU_KEY_YZTB'
							),
							array
							(
								'type'=>'click',
								'name'=>'上周TOP10',
								'key'=>'MENU_KEY_TOP'
							)
						)				
					),
					array
					(
						'type'=>'view',
						'name'=>'计算机',
						'url'=>'http://ced.hyit.edu.cn/'
					),
					array
					(
						'type'=>'view',
						'name'=>'生活',
						'url'=>'http://ced.hyit.edu.cn/'
						/* 'sub_button'=>
						array
						(
							array
							(
								'type'=>'click',
								'name'=>'天气',
								'key'=>'MENU_KEY_TQ'
							),
							array
							(
								'type'=>'view',
								'name'=>'快递',
								'url'=>'http://m.kiees.cn'
							),
							array
							(
								'type'=>'view',
								'name'=>'火车',
								'url'=>'http://mobile.12306.cn/weixin/wxcore/init'
							),
							array
							(
								'type'=>'click',
								'name'=>'我',
								'key'=>'MENU_KEY_LX'
							)
						) */
					)					
				)
	);
$result = $weObj->createMenu($newmenu);
$type = $weObj->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$openid=$weObj->getRevFrom();
			$keyword = trim($weObj->getRev()->getRevContent());
			$msg=preg_replace("/\s/","",$keyword);	
			if($msg==="2013")
			{
				
				if(isExit($openid))
				{
					$xq=getWeek();
					if($xq==0)
					{
						$xq=7;
					}
					$xq--;
					if($xq==3||$xq==4)
					{
						$str="昨天是公选课，2013级未考勤";
						$weObj->text($str)->reply();
						break;
					}				
					else
					{
						if($xq==5||$xq==6)
						{
							$str="昨天没有晚自习，2013级未考勤";
							$weObj->text($str)->reply();
							break;
						}
						else
						{
							$str=getKqxx($openid,"2013");
							$weObj->text($str)->reply();
						}
					}
				}else{
					$xhbd[0]=array(
						'Title'=>'用户绑定入口',
						'Description'=>'请点击进入绑定页面进行绑定哦',
						'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
						'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
					$weObj->news($xhbd)->reply();
				}

			}
			else
			{
				if($msg==="2014")
				{
					if(isExit($openid)){  //存在该用户
						$xq=getWeek();
						if($xq==0)
						{
							$xq=7;
						}
						$xq--;
						if($xq==5||$xq==6)
						{
							$str="昨天没有晚自习，2014级未考勤";
							$weObj->text($str)->reply();
							break;
						}
						else
						{
							$str=getKqxx($openid,"2014");
							$weObj->text($str)->reply();
						}
					}else{
						$xhbd[0]=array(
							'Title'=>'用户绑定入口',
							'Description'=>'请点击进入绑定页面进行绑定哦',
							'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
							'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
						$weObj->news($xhbd)->reply();
					}
					

				}
				else
				{
					$preg_str="^([2-9]\d{3}[2-9]\d{3}((0[1-9]|1[012])(0[1-9]|1\d|2[0-8])|(0[13456789]|1[012])(29|30)|(0[13578]|1[02])31)|(([2-9]\d)(0[48]|[2468][048]|[13579][26])|(([2468][048]|[3579][26])00))0229)$^";
					$preg_res=preg_match($preg_str,$msg,$match);
					if($preg_res==1)
					{
						/* $sql="select *from teacher where openid='$openid'";
						$rs=mysql_query($sql);
						$res=$sqlHelper->execute_dml($sql); */
						if(!isExit($openid))
						{
							$xhbd[0]=array(
								'Title'=>'用户绑定入口',
								'Description'=>'请点击进入绑定页面进行绑定哦',
								'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
								'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
							$weObj->news($xhbd)->reply();
						}
						else
						{							
							$year=mb_substr($match[0],0 ,4); 
							$date=mb_substr($match[0],4 ,4).'-'.mb_substr($match[0],8 ,2).'-'.mb_substr($match[0],10 ,2);
							$url=get_wz("wzmy.txt")."res/index.php?year=".$year.'&date='.$date."&openid=".$openid;
							$res=curl_get_noheader($url);
							$weObj->text($res)->reply();
						}
					}
					else
					{
						$contentStr = "亲，您发送的指令有些不正确的呢，请您发送正确的指令哦。";
						$weObj->text($contentStr)->reply();
					}
				}
				
			}
			
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			$event=$weObj->getRevEvent();
			if($event['event']=='subscribe')
			{
				$content='欢迎关注淮工计算机！';
				$weObj->text($content)->reply(); 
				exit;
				break;
			}
			switch($event['key'])
			{
				case 'MENU_KEY_KQCX':
					$openid=$weObj->getRevFrom();				
					
					
					//$res=$sqlHelper->execute_dml($sql);
					
					if(!isExit($openid))
					{
						$xhbd[0]=array(
							'Title'=>'用户绑定入口',
							'Description'=>'请点击进入绑定页面进行绑定哦',
							'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
							'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
						$weObj->news($xhbd)->reply();
					}
					else
					{
						$sql="select *from teacher where openid='$openid'";
						$rs=mysql_query($sql);
						while($rst=mysql_fetch_array($rs,MYSQLI_NUM)){
							$row=$rst;
						}
						$name=$row[2];
						$content=$name."老师您好:\n请回复要查看的年级：\n".
								  "2013->2013级；\n".
								  "2014->2014级；\n".
								  "(如回复2013即可查看2013级学生考勤情况)\n".
								  "----------------------------\n".
								  "现在也可以直接发送[年级]+[日期]查询历史考勤信息。\n".
								  "(如回复201320141222即可查询2013级学生在2014年12月22日的出勤情况)";
						//$content="请回复要查看的年级："."\n"."2013:2013级；"."\n"."2014:2014级；"."\n"."（如回复2013即可查看2013级学生考勤情况）";
						$weObj->text($content)->reply();
					}											
					
					break;
				case 'MENU_KEY_TQ':	
					$weather=getWeather();
					$weObj->news($weather)->reply();
					break;
				case 'MENU_KEY_YZTB':
					$openid=$weObj->getRevFrom(); 
					if(isExit($openid)) //该用户已绑定
					{
						//file_get_contents(get_wz("wzmy.txt")."res/chart/line.php");
						//file_get_contents(get_wz("wzmy.txt")."res/chart/bar.php");
						$tb[0]=array(
							'Title'=>'一周图表',
							'Description'=>'',
							'PicUrl'=>get_wz("wzmy.txt").'res/chart/a.png',
							'Url'=>get_wz("wzmy.txt").'res/highchart/syl.php?openid='.$openid);
						$tb[1]=array(
							'Title'=>'系统使用率',
							'Description'=>'一周图表',
							'PicUrl'=>get_wz("wzmy.txt").'res/chart/a.png',
							'Url'=>get_wz("wzmy.txt").'res/highchart/syl.php?openid='.$openid);
						$tb[2]=array(
							'Title'=>'学生出勤率',
							'Description'=>'一周图表',
							'PicUrl'=>get_wz("wzmy.txt").'res/chart/b.png',
							'Url'=>get_wz("wzmy.txt").'res/highchart/cql.php?openid='.$openid);
						$weObj->news($tb)->reply();
					}else{ //未绑定的时候
						$xhbd[0]=array(
							'Title'=>'用户绑定入口',
							'Description'=>'请点击进入绑定页面进行绑定哦',
							'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
							'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
						$weObj->news($xhbd)->reply();
					}
					
					break;
				case 'MENU_KEY_TOP':
					$openid=$weObj->getRevFrom();
					if(isExit($openid)){ //存在该用户
						$str=get_top10($openid,'2014');
						$xhbd[0]=array(
							'Title'=>'TOP10',
							'Description'=>$str.' ',
							'PicUrl'=>'',
							'Url'=>get_wz("wzmy.txt").'top/top.php?openid'=$openid);
						$weObj->news($xhbd)->reply();
						/* $wz=get_wz("wzmy.txt");
						$content ="第".(get_zhou()-1)."周". $content."<a href=$wz.'top/top.php'>详细>></a>"; //.get_top10($opendid,'2013')
						$weObj->text($content)->reply(); */
					}else{
						$xhbd[0]=array(
							'Title'=>'用户绑定入口',
							'Description'=>'请点击进入绑定页面进行绑定哦',
							'PicUrl'=>get_wz("wzmy.txt").'img/binding.png',
							'Url'=>get_wz("wzmy.txt").'kq/index.php?openid='.$openid);
						$weObj->news($xhbd)->reply();
					}
					
					
					break;
				case 'MENU_KEY_LX':	
					$lx[0]=array(
							'Title'=>'亲！有啥好的建议，可以点击下面的链接"建议意见"回复我哦！',
							'Description'=>'',
							'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ZYzbibgbX4OaAgowgqOI9hpSHibRWzB7TWoW4p3ZQE8V59Bz7bQAtSy2LjArW63LXm5stNkJeOiaRA5GpQolnEreg/0',
							'Url'=>get_wz("wzmy.txt").'jyyj/');
					$lx[1]=array(
							'Title'=>'建议意见',
							'Description'=>'',
							'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ZYzbibgbX4OaAgowgqOI9hpSHibRWzB7TWxgcDJRSiaMcJxibfcudTl18DQbIibZXiaAYlRuQsvdyDUSwP3v8jMUVA0A/0',
							'Url'=>get_wz("wzmy.txt").'jyyj/');
					/* $lx[2]=array(
							'Title'=>'新浪微博',
							'Description'=>'',
							'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ZYzbibgbX4OaAgowgqOI9hpSHibRWzB7TWMp0DekO3uveeJAgDBMmYyhlXpxzvTxbMGW1ariaHib9SxFQdd4EH17iag/0',
							'Url'=>'http://m.weibo.cn/345856391');	 */
					$weObj->news($lx)->reply();		
					exit;
					break;
			}
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$a="help info";
			$weObj->text($a)->reply();
	}
	
function getKqxx($openid,$nj)
{
	
/* 	$sql="select *from teacher where openid='$openid'";
	$rs=mysql_query($sql);
	while($rst=mysql_fetch_array($rs,MYSQLI_NUM))
	{
		$row=$rst; //获得用户名密码
	} */
	//$res=$sqlHelper->execute_dql($sql);
	//$row=mysqli_fetch_array($res,MYSQLI_NUM);						
	//
	
	$weekarray=array('日','一','二','三','四','五','六','日');
	//$sqlHelper=new SqlHelper();
	$sql="select *from teacher where openid='$openid'";
	$rs=mysql_query($sql);
	while($rst=mysql_fetch_array($rs,MYSQLI_NUM))
	{
		$row=$rst; //获得用户名密码
	}
	//$res=$sqlHelper->execute_dql($sql);
	//$row=mysqli_fetch_array($res,MYSQLI_NUM);						
	$teacherNumber=$row[1];
	$teacherName=$row[2];
	$url_kqqk=get_wz("wzfw.txt")."queryAbsenceWX?teacherNumber=".$teacherNumber."&year=".$nj;
	//$url_kqqk="http://akinoneko.xicp.net:28996/attendanceV3/queryAbsenceWX?teacherNumber=".$teacherNumber."&year=".$nj;
	$content=curl_get1($url_kqqk);
	$kqqk=json_decode($content,true);
	$classlist=$kqqk['classList'];
	$msg=$kqqk['msg'];
	$data=$kqqk['data'];
	$date=$kqqk['date'];
	$zs=getZS($date);
	$date=explode('-',$date);
	$final_date=$date[0].'年'.$date[1].'月'.$date[2].'日';
	$j=0;
	if($msg==='true')
	{
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
					$temp=$temp.$qq."：全勤";
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
	}
	return $teacherName."老师您好:\n".
			"您所查询的年级是：".$nj."级\n".
			"日期是：".$final_date."\n".
			"星期是：第".$zs."周  星期".$weekarray[getWeek()-1]."\n".$temp."\n圣诞快乐！";
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
function getWeather()
{
	$ak='854286a6f0c93eebfe400954895afedb';
	$location='淮安';
	$output='json';
	$url='http://api.map.baidu.com/telematics/v3/weather?location='.$location.'&output='.$output.'&ak='.$ak;
	$content=curl_get_noheader($url);	
	$json = json_decode($content,true);
	$weather=$json['results'][0]['weather_data'];
	$temp_weather[0]=array('Title'=>$weather[0]['date'].$weather[0]['weather'],
						'Description'=>' ',
						'PicUrl'=>'http://www.qqya.com/userimg/5707/130311235914.png',
						'Url'=>'http://waptianqi.2345.com/huaian-58141.htm');
						
	for($j=1;$j<=3;$j++)
	{
		$temp_weather[$j]=array('Title'=>$weather[$j]['date'].'   '.$weather[$j]['weather'],
							'Description'=>' ',
							'PicUrl'=>$weather[$j]['dayPictureUrl'],
							'Url'=>'http://waptianqi.2345.com/huaian-58141.htm');						
	}
	
	$temp_weather[4]=array('Title'=>'点击查看15日天气预报',
						'Description'=>'heheheh',
						'PicUrl'=>'',
						'Url'=>'http://waptianqi.2345.com/huaian-58141.htm');	
	return $temp_weather;
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
	$week=date('w',$unixTime);
	if($week==0)
	{
		$week=7;
	}
	return $week;
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
/* function getWeek($unixTime='')
{	
	$unixTime=is_numeric($unixTime)?$unixTime:time();
	return date('w',$unixTime);
} */	
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
	
	$url_zhouzui=get_wz("wzfw.txt")."queryLastWeekDataWX?year=".$year."&weekDays=".$weekDays."&teacherNumber=".$teacherNumber;
	
	$temp=curl_get1($url_zhouzui);
	$zui_info=json_decode($temp,true); //周最
	//print_r($zui_info);
	$zui_msg=$zui_info['msg']; //获得结果标记，true则有数据,false则无，返回所有成员都正常考勤
	$zui_data=$zui_info['data']; //获得未正常考勤人
	if($zui_msg==='true')
	{
		if(count($zui_data)===0){//如果没有缺勤数据的情况下，返回上周无Top10信息
			return "\n".$year."级学生\n========\n没有TOP10情况哦。";
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
		$str_top="\n".$year."级学生\n====缺勤Top10====\n\n";
		$str_top=$str_top.out_top($zui_count,10,'absent');
		
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
		$str_top=$str_top."====未带卡Top10====\n\n";
		$str_top=$str_top.out_top($zui_count,10,'wdk');
		
		/* //Top10之请假
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
		$str_top=$str_top."====请假Top10====\n\n";
		$str_top=$str_top.out_top($zui_count,10,'qingjia');
		
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
		$str_top=$str_top."=====迟到Top10=====\n\n";
		$str_top=$str_top.out_top($zui_count,10,'chidao'); */
		
		return $str_top;
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
//输出Top10
function out_top($arr,$n,$state)
{
	$str="";
	
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
		$str =$str."Top".$j.": ".$classname."   ".$name."（".$count_state."次）\n";
	}
	return $str;
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