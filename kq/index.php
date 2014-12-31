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
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<title>用户绑定</title>
<style>
	.header {
		text-align: center;
	}
	.header h1 {
		font-size: 150%;
		color: #333;
		margin-top:5px;
	}
	.roundimg{
		margin-top:50px;
		border-radius:100px;
		width:100px;
		height:100px;
	}
	.am-g{
		margin-top:50px;
	}
	.tip{
		margin-top:5px;
		text-align:center;
		color:#F00;
	}
</style>
<script>
function valid()
{
	var id=document.getElementById("id").value;
	var pwd=document.getElementById("pwd").value;
	if(id=="" || pwd=="")
	{
		document.getElementById("tip-p").innerHTML="请检查您的用户名或密码是否有误！"	
	}
	else
	{
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		} 
		var url="kq.php"
		var openid="<?php echo $_GET["openid"];?>";
		url=url+"?id="+id+"&pwd="+pwd+"&openid"+openid;
		xmlHttp.onreadystatechange=stateChanged();
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
}
function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		/* if(xmlHttp.responseText=="1")
		{
			alert("ok");
		}
		else
		{
			document.getElementById("tip-p").innerHTML="请检查您的用户名或密码是否有误！"
		} */
		
		if(xmlHttp.responseText=='0')
		{
			document.getElementById("tip-p").innerHTML="请检查您的用户名或密码是否有误！"
		}else if(xmlHttp.responseText=='1')
		{
			document.getElementById("stu").submit();
		}
	} 
}
function pclear()
{
	document.getElementById("tip-p").innerHTML="";
}
function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	{
	// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
	// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}
</script>
</head>

<body>
   	<div class="header">   	
        <img class="roundimg" src="img/微信标志.png" />
    </div>
    <div class="am-g">  
    	<div class="col-lg-6 col-md-8 col-sm-centered">
            <form class="am-form" action="cc.php?openid=<?php echo $_GET["openid"];?>" method="post" id="stu"> <!--action="cc.php?openid=<?php echo $_GET["openid"];?>" -->
                <div class="am-input-group">                   
                    <span class="am-input-group-label"><i class="am-icon-user"></i></span>
                    <input type="text" id="id" name="id" class="am-form-field" placeholder="请输入您的用户名"
                    maxlength="10" onfocus="pclear()">
                    
                </div>
                <br />
                <div class="am-input-group">                    
                    <span class="am-input-group-label"><i class="am-icon-lock"></i></span>
                    <input type="password" id="pwd" name="pwd" class="am-form-field" placeholder="请输入您的密码" onfocus="pclear()">
                </div>
                <div class="tip">
                	 <p id="tip-p"></p>
                </div>
               
                <br />
                <div class="am-cf">
					
                    <button type="submit" class="am-btn am-btn-success am-btn-block">
                    <i class="am-icon-link"></i>
                    绑定
                    </button>
            </form>	
        </div>
    </div>  
    <div class="am-footer">
		<p></p>
    </div>  
</body>
</html>
