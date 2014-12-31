<?php
include("./class/pData.class.php");
include("./class/pDraw.class.php");
include("./class/pImage.class.php");
$teacherNumber="500000012";
$type="cron";
$url="http://210.29.152.145:8080/attendanceV3/queryUsingRateWX?teacherNumber=".$teacherNumber."&type=".$type;
$res=curl_get($url);
$res=json_decode($res,true);
$msg=$res['msg'];
$data=$res['data'][0];
if($msg==="true")
{
	for($i=0;$i<count($data);$i++)
	{
		$rate[$i]=round(($data[$i]['number']/$data[$i]['count'])*100,2).'%';
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
/* Create and populate the pData object */
$MyData = new pData();  
$MyData->addPoints($rate,"q");
$MyData->addPoints($date,"Labels");
$MyData->setSerieDescription("Labels","date");
$MyData->setAbscissa("Labels");
$MyData->setPalette("q",array("R"=>5,"G"=>141,"B"=>199));
 
$myPicture = new pImage(850,350,$MyData);


$myPicture->setGraphArea(50,50,800,300);

$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
$scaleSettings = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"XMargin"=>0,"YMargin"=>0,"Floating"=>TRUE,"GridR"=>250,"GridG"=>250,"GridB"=>250,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings); 
$myPicture->drawLineChart();
$myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));
//$myPicture->setColorPalette(0,255,0,0); 
$myPicture->autoOutput("");
$myPicture->render('a.png');
?>