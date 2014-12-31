<?php
include "../../insql.php";
$openid=$_GET['openid'];
$sql="select teacherNumber from teacher where openid='$openid'";
$rs=mysql_query($sql);
while($rst=mysql_fetch_array($rs,MYSQLI_NUM)){
	$row=$rst;
}
$teacherNumber=$row[0];
$wzmy=get_wz("../../wzmy.txt");
//$teacherNumber='13025';
$url=get_wz("../../wzfw.txt")."queryUsingRateWX?teacherNumber=".$teacherNumber."&type=cron";
$res=curl_get($url);
$res=json_decode($res,true);
$msg=$res['msg'];
$data=$res['data'][0];
if($msg==="true")
{
    for($i=0;$i<count($data);$i++)
    {
        $rate[$i]=round(($data[$i]['number']/$data[$i]['count'])*100,2);
		$date[$i]=$data[$i]['date'];
	}
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
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<meta name="format-detection" content="telephone=no">
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<title>考勤系统使用率折线图</title>
		<link rel="stylesheet" href="./css/amazeui.min.css">
		<script type="text/javascript" src="http://cdn.hcharts.cn/jquery/jquery-1.8.2.min.js"></script>		
		<script type="text/javascript" src="./js/highcharts.js"></script>
		<script type="text/javascript">
$(function () {
		var a = eval('<?php echo json_encode($rate)?>');
		var b = eval('<?php echo json_encode($date)?>');
		//alert(a);
		//var a = eval(decodeURIComponent(slist));
        $('#container').highcharts({
            chart: {
                type: 'line'          
            },
            colors: ['#058DC7'],
            credits: {
                enabled: false
            }, 
            exporting: {
            	enabled: false
            },   
            title: {
                text: '最近考勤系统使用率折线图'
            },
            subtitle: {
                text: '使用率=当天执行的考勤次数/当天应执行的考勤次数'
            },
            xAxis: {
                categories: b,
                labels: {
                   rotation: -45,
                }
            },
            yAxis: {
                title: {
                    text: '使用率(%)',
					margin:0
                },
                min: 0,
                max:100	
                
            },
			legend: {
				enabled: false
			},
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,
						formatter: function () {
							return this.y + "%";
						}
                    },
                    enableMouseTracking: true
                }
            },
			tooltip: {
                valueSuffix: '%'
            },
            series: [{
				name: '使用率(%)',
                data:a
			}]
        });
    });
    

		</script>
	</head>
	<body>
		<header data-am-widget="header" style="text-align:center;margin: 0 auto;background-color: #058DC7;" class="am-header am-header-default">
		  <img alt="校徽" src="./img/xm.png" width="130px">
		</header>
		
	<div id="container" style="min-width: 100px; min-height: 340px;text-align:center; margin: 0 auto"></div>
	
	
	<footer data-am-widget="footer" class="am-footer am-footer-default">

  <div class="am-footer-miscs ">
      <a href="http://ced.hyit.edu.cn" title="计算机工程学院" target="_blank">计算机工程学院 </a>

  </div>
</footer>

<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default " id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-4" style="background-color: #058DC7;">
    <li>
      <a href="<?php echo $wzmy;?>res/highchart/syl.php?openid=<?php echo $openid;?>">
        <span class="am-navbar-label">使用率</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $wzmy;?>res/highchart/cql.php?openid=<?php echo $openid;?>">
        <span class="am-navbar-label">出勤率</span>
      </a>
    </li>
  </ul>
</div>
	</body>
</html>
