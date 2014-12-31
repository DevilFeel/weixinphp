<?php
include("./class/pData.class.php");
include("./class/pDraw.class.php");
include("./class/pImage.class.php");
$teacherNumber="500000012";
$type="rate";
$url=get_wz("../../wzfw.txt")."queryUsingRateWX?teacherNumber=".$teacherNumber."&type=".$type;
$res=curl_get($url);
$res=json_decode($res,true);
$msg=$res['msg'];
$data=$res['data'][0];
if($msg==="true")
{
	for($i=0;$i<count($data);$i++)
	{
		$rate[$i]=round((($data[$i]['count']-$data[$i]['number'])/$data[$i]['count'])*100,2).'%';
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
/* Create and populate the pData object */
$MyData = new pData();  
$MyData->addPoints($rate,"q");
$MyData->addPoints($date,"Labels");
$MyData->setSerieDescription("Labels","date");
$MyData->setAbscissa("Labels");
$MyData->setPalette("q",array("R"=>5,"G"=>141,"B"=>199));
/* Create the pChart object */
$myPicture = new pImage(850,350,$MyData);


$myPicture->setGraphArea(50,50,800,300);

/* Draw the scale */
$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
$scaleSettings = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$myPicture->drawScale($scaleSettings);

/* Draw the line chart */
//
$myPicture->drawBarChart();
$myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>FALSE,"BorderSize"=>0,"Surrounding"=>-60,"BorderAlpha"=>80));


/* Render the picture (choose the best way) */
$myPicture->autoOutput("");
$myPicture->render("b.png");
?>