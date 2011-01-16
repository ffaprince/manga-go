<?php
//$refer = $_SERVER['HTTP_REFERER'];
//if(empty($refer)) exit("no pic");
//$p = parse_url($refer);
//if(strpos($p['host'],DOMAIN)===false) exit("no pic");
if(empty($fetch_site))
	$fetch_site='http://8.p.s.mfcdn.net';
//$imgurl=restoreFetchUrl($args,$fetch_site);
$imgurl = decode_url($args[0]);
if(strpos($imgurl,'nocover.jpg'))
	Util::redirect(WEB_ROOT.'/images/nocover.jpg');
$iscover = strpos($imgurl,'cover.jpg')===false?false:true;
$GLOBALS['imgurl']=$imgurl;
set_time_limit(60);
$options=array(
		'cacheDir'	=>	DIR_ROOT.'/imgcache',
		'lifeTime'	=>	IMG_TIME,
		'dirLevels'	=>	2,
	);

function _cache() {
	   header('expires:'.gmdate('D, d M Y H:i:s',mktime(0, 0, 0, 12, 30, 2020)).' GMT');
	   header('cache-control:max-age=36000000');
      $etag = "Ujdsh3Dxce3";  //标记字符串，可以任意修改 
      if ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
			 header('Etag:'.$etag,true,304);
			 exit;
      } 
      else {
		  header('Etag:'.$etag); 
//		  header('last-modified:'.gmdate('D, d M Y H:i:s').' GMT');
	  }
	  

} 

function _echoimg($Content){
//	exit;
//	Util::redirect('http://www.gonline.com/images/bg_header/arena.jpg');
	global $imgurl;
	$t = parse_url($imgurl);
	$suffix = explode(".",$t['path']);
	$pos=strpos($Content,"\r\n\r\n");
	$head=substr($Content,0,$pos);
	$text=substr($Content,$pos+4);
//	header($head);
//	var_dump($imgurl,$head);exit;
	preg_match("/Content-Length:(.*)Age/is",$head,$matchs);
	$GLOBALS['length'] = intval($matchs[1]);
//	var_dump(strlen($text),$matchs[1],$GLOBALS['length']);
	header("Content-Type:image/".array_pop($suffix));
	_cache();
	echo $text;
}

function _redirect($url){
	preg_match("/(Location:|URI:)[ ]+(.*)/i",$url,$matches);
	$url = $matches[2];
	Util::Redirect($matches[2]);
	exit;
}

function _fetchImg($imgurl,$iscover){
	$cache = TempCache::instance();
	$imgurl = trim($imgurl);
	$host=$path=str_replace('http://','',$imgurl);
	$host=explode('/',$host);
	$host=$host[0];
	$path=strstr($path,'/');
	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if ($fp)
	{
		@fputs($fp, "GET $path HTTP/1.1\r\n");
		@fputs($fp, "Host: $host\r\n");
		@fputs($fp, "Accept: */*\r\n");
		@fputs($fp, "Referer: http://www.mangafox.com/\r\n");
		@fputs($fp, "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)\r\n");
		@fputs($fp, "Connection: Close\r\n\r\n");
	}
	$Content = '';
	$outputhead = false;
	$redirecturl=null;
	while ($str = fread($fp, 4096)){
		$redirecturl=null;
		$Content .= $str;
		if(!$outputhead){
			if(strpos($Content,"\r\n\r\n")!==false){
				if(strpos($Content,'301 Moved')!==false){
					preg_match("/(Location:|URI:)[ ]+(.*)/i",$Content,$matches);
					$redirecturl = $matches[2];
					break;
				}
				_echoimg($Content);
				$outputhead=true;
			}
		}else{
			echo($str);
		}
	}
	@fclose($fp);
	if($redirecturl)
		_fetchImg($redirecturl);
	else{
		$pos=strpos($Content,"\r\n\r\n");
		$text=substr($Content,$pos+4);
		if(strlen($text)>=$GLOBALS['length']&&strpos($Content,"HTTP/1.1 404 Not Found")===false){
			$cache->save($Content);
		}
	}
}
if($iscover&&$Content = Cache::Get(md5($imgurl))) {
		_echoimg($Content);
}else{
	$cache = TempCache::instance($options);
	if (!$Content=$cache->get($imgurl)) {
		_fetchImg($imgurl,$iscover);
	}else{
		if(strlen($Content)<1000)
			_fetchImg($imgurl,$iscover);
		else {
			if($iscover)
				Cache::Set(md5($imgurl),$Content,0,300);
			_echoimg($Content);
		}
	}
}
?> 
