<?php
class tuling
{
	public function get_return($info)
	{
		$Apikey='e071cd1f1423a3d780a6a0503669e9b3';
		$url='http://www.tuling123.com/openapi/api';
		$url=$url.'?key='.$Apikey.'&info='.$info;
		$content=$this->curl_get_noheader($url);
		$json = json_decode($content,true);
		$json_code=$this->tuling($json);
		switch($json_code)
		{
			case 1:
				$temp=$this->get_text($json);
				break;
			case 2:
				$temp=$this->get_url($json);
				break;
			case 3:
				$temp=$this->get_xs($json);
				break;
			case 4:
				$temp=$this->get_news($json);
				break;
			case 5:
				$temp=$this->get_yy($json);
				break;
			case 6:
				$temp=$this->get_lc($json);
				break;
			case 7:
				$temp=$this->get_hb($json);
				break;
			case 8:
				$temp=$this->get_tg($json);
				break;
			case 9:
				$temp=$this->get_yh($json);
				break;
			case 10:
				$temp=$this->get_jd($json);
				break;
			case 11:
				$temp=$this->get_cp($json);
				break;
			case 12:
				$temp=$this->get_jg($json);
				break;
			case 13:
				$temp=$this->get_ct($json);
				break;
		}
		return $temp;
	}
	/* 文字类： 
	{ 
		"code":100000,								
		"text":"你好，我是图灵机器人"				
	};  */

	public function get_text($json)
	{
		$retext[0]=1;
		$retext[1]=$json['text'];
		return $retext;
	}

	/* 链接类： 
	{ 
		"code":200000,								
		"text":"已帮你找到了图灵机器人官方网站",
		"url":"http://www.tuling123.com/openapi"				
	}  */
	public function get_url($json)
	{
		$retext[0]=1;
		$retext[1]=$json['text'].$json['url'];
		return $retext;
	}

	/* 列表类--->小说： 

	{ 
		"code":301000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"author":"",							
			"detailurl":"",							
			"icon":""						
		}]

	}  */
	public function get_xs($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["author"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["author"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);		
			}					
		}
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;	
	}

	/* 列表类--->新闻： 

	{ 
		"code":302000,								
		"text":"********",					
		"list":[{
			"article":"",							
			"source":"",							
			"detailurl":"",						
			"icon":""						
		}]

	}  */
	public function get_news($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		$j=0;
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["article"],
								'Description'=>$json['list'][$i]["source"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);			
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["article"],
								'Description'=>$json['list'][$i]["source"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}

		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}

	/* 列表类--->应用、软件、下载： 

	{ 
		"code":304000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"count":"",							
			"detailurl":"",							
			"icon":""						
		}]

	}  */
	public function get_yy($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["count"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["count"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}			
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}



	/* 列表类--->列车： 

	{ 
		"code":305000,								
		"text":"********",					
		"list":[{
			"trainnum":"",							
			"start":"",							
			"terminal":"",						
			"starttime":"",							
			"endtime":"",						
			"detailurl":"",						
			"icon":""						
		}]

	} */ 
	public function get_lc($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["trainnum"],
								'Description'=>$json['list'][$i]["starttime"].' '.$json['list'][$i]["endtime"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["trainnum"],
								'Description'=>$json['list'][$i]["starttime"].' '.$json['list'][$i]["endtime"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}

	/* 列表类--->航班： 

	{ 
		"code":306000,								
		"text":"********",					
		"list":[{
			"flight":"",							
			"route":"",						
			"starttime":"",							
			"endtime":"",							
			"state":"",							
			"detailurl":"",							
			"icon":""						
		}]

	} */ 
	public function get_hb($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["flight"],
								'Description'=>$json['list'][$i]["route"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["flight"],
								'Description'=>$json['list'][$i]["route"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}

	/* 列表类--->团购： 

	{ 
		"code":307000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"price":"",							
			"detailurl":"",							
			"icon":"",							
			"info":"",						
			"count":""						
		}]

	}  */
	public function get_tg($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}
	/* 列表类--->优惠： 

	{ 
		"code":308000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"info":"",						
			"detailurl":"",							
			"icon":""							
		}]

	}  */
	public function get_yh($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["info"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["info"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}
	/* 

	列表类--->酒店： 

	{ 
		"code":309000,								
		"text":"********",					
		"list":[{
			"name":"",						
			"price":"",							
			"satisfaction":"",							
			"count":"",							
			"detailurl":"",						
			"icon":""						
		}]

	} 
	 */
	public function get_jd($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}
	/* 列表类--->彩票： 

	{ 
		"code":310000,								
		"text":"********",					
		"list":[{
			"icon":"",							
			"detailurl":"",							
			"number":"",							
			"info":""						
		}]

	}  */
	public function get_cp($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["number"],
								'Description'=>$json['list'][$i]["info"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["number"],
								'Description'=>$json['list'][$i]["info"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}

	/*  列表类--->价格： 

	{ 
		"code":311000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"price":"",							
			"detailurl":"",							
			"icon":""							
		}]

	}*/
	public function get_jg($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}

	/* 列表类--->餐厅： 

	{ 
		"code":312000,								
		"text":"********",					
		"list":[{
			"name":"",							
			"price":"",							
			"icon":"",							
			"detailurl":""							
		}]

	}  */
	public function get_ct($json)
	{
		$temp[0]=array('Title'=>$json['text'],
					'Description'=>"",
					'PicUrl'=>"",
					'Url'=>"");	
		foreach($json['list'] as $k=>$v)
		{	
			$j=$j+count($v);
		}
		if($j/4>=9)
		{
			for($i=1;$i<=8;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}
		}else
		{
			for($i=1;$i<=$j/4-1;$i++)
			{
				$temp[$i]=array('Title'=>$json['list'][$i]["name"],
								'Description'=>$json['list'][$i]["price"],
								'PicUrl'=>$json['list'][$i]["icon"],
								'Url'=>$json['list'][$i]["detailurl"]);	
			}					
		}	
		$retext[0]=2;
		$retext[1]=$temp;
		return $retext;
	}



	public function curl_get_noheader($url)
	{
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$content = curl_exec ( $ch );
		curl_close ( $ch );
		return $content;
	}

	public function tuling($json)
	{
		$code[1]=100000;  //文本类数据  
		$code[2]=200000;  //网址类数据  
		$code[3]=301000;  //小说  
		$code[4]=302000;  //新闻  
		$code[5]=304000;  //应用、软件、下载  
		$code[6]=305000; //列车  
		$code[7]=306000;  //航班  
		$code[8]=307000; //团购  
		$code[9]=308000;  //优惠  
		$code[10]=309000;  //酒店  
		$code[11]=310000;  //彩票  
		$code[12]=311000; //价格
		$code[13]=312000;  //餐厅
		for($i=1;$i<=13;$i++)
		{
			if($code[$i]===$json['code'])
			{
				return $i;
			}
		}
	}
}


?>