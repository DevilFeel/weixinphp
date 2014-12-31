<?php
error_reporting(E_ALL & ~E_NOTICE);
include "sqlhelper.php";

echo getKqxx("oGyCYuA34GqBtbw-2onMVItP-EHM","2013");

function getKqxx($openid,$nj)
{
	$sqlHelper=new SqlHelper();
	$sql="select *from teacher where openid='$openid'";
	$res=$sqlHelper->execute_dql($sql);
	$row=mysqli_fetch_array($res,MYSQLI_NUM);						
	//
	$id=$row[5];
	$pwd=$row[6];
	$url="http://signals.hyit.edu.cn:8080/attendanceV3/validateWX?account=".$id."&password=".$pwd;
	$temp=curl_get1($url);
	$obj = json_decode($temp,true);
	$teacherNumber=$obj['teacherNumber'];
	$name=$obj['name'];
	$rank=$obj['rank'];
	$departmentNumber=$obj['departmentNumber'];
	$msg=$obj['msg'];
	$url_kqqk="http://signals.hyit.edu.cn:8080/attendanceV3/queryAbsenceWX?teacherNumber=".$teacherNumber."&year=".$nj;
	//$url_kqqk="http://akinoneko.xicp.net:28996/attendanceV3/queryAbsenceWX?teacherNumber=".$teacherNumber."&year=".$nj;
	$content=curl_get1($url_kqqk);
	$kqqk=json_decode($content,true);
	$msg=$kqqk['msg'];
	$data=$kqqk['data'];
	$j=0;
	if($msg==='true')
	{
		for($i=0;$i<count($data);$i++)
		{
			if($i==0)
			{
				$classname[$j]=$data[$i]['classname'];
			}else
			{
				for($k=0;$k<count($classname);$k++)
				{
					if($classname[$k]===$data[$i]['classname'])
					{
						break;
					}
					else
					{
						$j++;
						$classname[$j]=$data[$i]['classname'];
						break;
					}
				}
			}
		}
		foreach ($data as $key=>$value)
		{
			$stuname[$key] = $value['name'];
			$stuclassname[$key] = $value['classname'];
			$stuabsent[$key]=$value['absent'];
		}
		
		
		$stuclassname=a_array_unique($stuclassname);
		sort($stuclassname);
		for($i=0;$i<count($stuclassname);$i++)
		{
			$c[$i][0]=$stuclassname[$i];
			$c[$i][1]=0;
			//$c[$i][2]=0;
		}
		$a=$stuclassname;
		$b=$kqqk['data'];
		$str;
		for($i=0;$i<count($a);$i++)
		{
			$tempname=$a[$i].":"."\n缺勤：";
			for($j=0;$j<count($b);$j++)
			{
				if($b[$j]['classname']===$a[$i])
				{
					
					if($b[$j]['absent']==='未考勤')
					{
						$c[$i][1]++;
						$tempname=$tempname.$b[$j]['name'].'  ';
					}
					/* if($b[$j]['absent']==='迟到')
					{
						$c[$i][2]++;
					} */
				}
			}
			$str=$str.$tempname."\n";
		}
	return $str;
	}

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
function array_sort($arr,$keys,$type='asc'){
$keysvalue= $new_array= array();
foreach($arr as $k=>$v){
$keysvalue[$k] = $v[$keys];
}
if($type== 'asc'){
asort($keysvalue);
}else{
arsort($keysvalue);
}
reset($keysvalue);
foreach($keysvalue as $k=>$v){
$new_array[$k] = $arr[$k];
}
return $new_array;
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
?>
