<?
$sortkeys = array("name.za","name.az","rating.az","rating.za","views.az","views.za","total_chapters.az","total_chapters.za","last_chapter_time.az","last_chapter_time.za","rating.az","rating.za");
function translateHref($url,$host,$params){
	if(strpos($url,"javascript")!==false)
		return $url;
	if(strpos($url,"/rss/")!==false){
		return translateRss($url,$host);
	}
	if(strpos($url,"addthis.com")!==false){
		return $url;
	}
	$prefix = _getPrefix(transToAbsolutePath($url,$host));
	if($prefix===null)
		return translateImg($url,$host);
	$url = WEB_ROOT.$prefix._getUrlSuffix(transToAbsolutePath($url,$host),$params);
	global $sortkeys;
	foreach($sortkeys as $key){
		$skey = str_replace(".","_",$key);
		$url = str_replace($skey."=",$key,$url);
	}
//	if(strpos($url,'search.php')){
//		var_dump($url,$host);
//		exit;
//	}
	return $url;
}

function _getUrlSuffix($url,$param){
	$a = parse_url($url);
	if(strpos($a['query'],'=')){
		$t = array();
		if(!empty($a['query']))
			parse_str($a['query'],$t);
		if(empty($param))
			$param = array();
		$param = array_merge($t,$param);
		if(sizeof($param)>0)
			$url = $a['path'].'?'.http_build_query($param);
		else
			$url = $a['path'];
	}else{
		if(empty($a['query'])&&sizeof($param)===0)
			$t="";
		else
			$t="?";
		if(sizeof($param)===0)
			$s="";
		else
			$s="&";
		$url = $a['path'].$t.$a['query'].$s.http_build_query($param);
	}
	return $url;
}

function translateImg($src,$host){
	if(strpos($src,'http://s.mfcdn.net/media')!==false){
		return str_replace('http://s.mfcdn.net/media','/images/',$src);
	}
	return IMG_ROOT.'/r/imglink/'.encode_url(transToAbsolutePath($src,$host)).'/';
}

function translateJs($src,$host){
	if(strpos($src,"addthis.com")!==false){
		return $src;
	}
	return IMG_ROOT.'/r/jslink'._getUrlSuffix(transToAbsolutePath($src,$host));
}

function translateRss($src,$host){
	return IMG_ROOT.'/r/rsslink'._getUrlSuffix(transToAbsolutePath($src,$host));
}

function _setHref(&$ele,$url,$target){
	$ele->setAttribute('href',$url);
	if(empty($target)){
		$ele->setAttribute('target','_self');
	}else
		$ele->setAttribute('target',$target);
}

function _doFix(&$ele,$host,$target,$params){
	if(empty($ele)){
		$cache = TempCache::instance();
		$cache->remove();
	}
	if($ele->tagName=='a'){
		$url = $ele->getAttribute('href');
		_setHref($ele,translateHref($url,$host,$params),$target);
	}
	else{
		$xouts = &$ele->getElementsByTagName('a');
		foreach($xouts as $x){
			$url = $x->getAttribute('href');
			_setHref($x,translateHref($url,$host,$params),$target);
		}
	}
	_fixImg($ele,$host);
	_fixJs($ele,$host);
	_fixForm($ele,$host);
}


function fixHref(&$ele,$host,$target,$params){
	if(is_array($ele)||$ele instanceof DOMNodeList){
		foreach($ele as $e){
			_doFix($e,$host,$target,$params);
		}
	}else{
		_doFix($ele,$host,$target,$params);
	}
}
function _fixForm(&$ele,$host=null){
	if($ele->tagName=='form')
		$ele->setAttribute('action',translateHref($ele->getAttribute('action'),$host));
	else{
		$imgs = &$ele->getElementsByTagName('form');
		foreach($imgs as $x){
			$x->setAttribute('action',translateHref($x->getAttribute('action'),$host));
		}
	}
}

function _setSrc(&$ele,$src,$host=null){
	$ele->setAttribute('src',translateImg($src,$host));
}

function _fixImg(&$ele,$host=null){
	if($ele->tagName=='img')
		_setSrc($ele,$ele->getAttribute('src'),$host);
	else{
		$imgs = &$ele->getElementsByTagName('img');
		foreach($imgs as $x){
			_setSrc($x,$x->getAttribute('src'),$host);
		}
	}
}

function _fixJs(&$ele,$host=null){
	if($ele->tagName=='script'){
		$src = $ele->getAttribute('src');
		if(!empty($src))
			$ele->setAttribute('src',translateJs($src,$host));
	}
	else{
		$imgs = $ele->getElementsByTagName('script');
		foreach($imgs as $x){
			$src = $x->getAttribute('src');
			if(!empty($src))
				$x->setAttribute('src',translateJs($src,$host));
		}
	}
}

function _getPrefix($url){
	if(strpos($url,'/manga/')!==false)
		return '/r/l_manga';
	if(strpos($url,'/directory/')!==false)
		return '/r/l_directory';
	if(strpos($url,'/search/')!==false)
		return '/r/l_directory';
	if(strpos($url,'/search.php')!==false)
		return '/r/l_search';
	if($url==="http://www.mangafox.com/")
		return "";
	return null;
}

function getTitle($doc){
	try{
		$titleEle = $doc->getElementsByTagName('title')->item(0);
		return _fixTKD($titleEle->nodeValue);
	}catch(Exception $e){
		return "";
	}
}

function getKeywords($doc){
	try{
		$kele = get_elements_by_attr($doc,'name','keywords','meta');
		return _fixTKD($kele->item(0)->getAttribute('content'));
	}catch(Exception $e){
		return "";
	}
}

function getDescription($doc){
	try{
		$kele = get_elements_by_attr($doc,'name','description','meta');
		return _fixTKD($kele->item(0)->getAttribute('content'));
	}catch(Exception $e){
		return "";
	}
}
//title,keywords,description
function _fixTKD($str){
	$str=trim($str);
	if(substr($str,strlen($str)-1,1)==",")
		$str = substr($str,0,strlen($str)-1);
	$str=preg_replace("/\s/","",$str);
	$str=trim($str);
	return $str;
}

function _bgimgtranslate($a){
//	var_dump($a);exit;
//	return 'background-image: url('.IMG_ROOT."/r/simglink".$a[1].')';
	return 'background-image: url('.translateImg($a[1],FETCH_SITE).')';
}

function translateStyleBgImg($html,$host){
//	background-image: url("/icon/1276.jpg");
	return preg_replace_callback('/background-image:\s*url\((.*)\)/im','_bgimgtranslate',$html);
}

function restoreFetchUrl($args,$site){
	global $sortkeys;
	$path = (empty($site)?FETCH_SITE:$site)."/".implode("/",$args);
	if(sizeof($_GET)>0){
		$path.="?";
		$a=array();
//		var_dump($_POST,$_GET,http_build_query($_GET));exit;
		foreach($_GET as $k=>$v){
			if(array_search($k,$sortkeys)!==false)
				$a[]=$k;
			elseif(is_array($v)){
				foreach($v as $vk => $vv){
					$a[]=$k."[".$vk."]=".$vv;
				}
			}else
				$a[]=$k."=".$v;
		}
		$path.=implode("&",$a);
	}
	$path = str_replace(" ","+",$path);
	return $path;
}

function createHotManga(){
	$hots = array(
		"Naruto"=>"naruto",
		"One Piece"=>"one_piece",
		"Bleach"=>"bleach",
		"Psyren"=>"psyren",
		"Beelzebub"=>"beelzebub",
		"Fairy Tail"=>"fairy_tail",
		"Claymore"=>"claymore",
		"History's Strongest"=>"history_s_strongest_disciple_kenichi",
	);
	$len = sizeof($hots);
	$i=0;
	foreach($hots as $n=>$url){
		echo('<li><a class="track" name="head-rmzq-1" href="'.WEB_ROOT.'/r/l_manga/manga/'.$url.'/">'.$n.'</a></li>');
		$i++;
		if($i<$len)
			echo('<li class="dotfgbg">&nbsp;</li>');
	}
}
function createHotGenre($return=false){
	$hots = array(
		"Action",
		"School Life",
		"Adventure",
		"Shoujo",
		"Fantasy",
		"Comedy",
	);
	if($return)
		return $hots;
	else{
		$len = sizeof($hots);
		$i=0;
		foreach($hots as $n){
			echo('<li><a class="track" name="head-rmzq-1" href="'.WEB_ROOT.'/r/l_genre/'.$n.'/">'.$n.'</a></li>');
			$i++;
			if($i<$len)
				echo('<li class="dotfgbg">&nbsp;</li>');
		}
	}
}

function createMainLunhuan(){
	$adv = array('i_accept_you','the_world_god_only_knows','the_one','naruto','gakuen_alice');
	$f="";
    $d="";
	foreach($adv as $book){
		$f.='<li style="background:url('.WEB_ROOT.'/images/adv/'.$book.'.jpg) no-repeat scroll 0 0 transparent;"><a href="/manga/'.$book.'/" target="_self"></a></li>';
	}
	foreach($adv as $book){
		$d.='<li><div style="background:url('.WEB_ROOT.'/images/adv/'.$book.'_s.jpg) no-repeat scroll 0 0 transparent;"></div></li>';
	}
	return array($f,$d);
}

define('FACEBOOK_APP_ID', '126315704101624');
define('FACEBOOK_SECRET', 'fe47e20f292e3e72bc2e2b19abbefa23');

function get_facebook_cookie($app_id, $application_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $application_secret) != $args['sig']) {
    return null;
  }
  return $args;
}

function isLogin(){
	$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
	if($cookie){
		if(empty($_SESSION['userinfo'])){
			$_SESSION['userinfo']=getUserInfo($cookie);
			DB::Insert('user_bookmark',array('id'=>$_SESSION['userinfo']['email'],'bookmark'=>json_encode(array())));
		}
	}
	else
		unset($_SESSION['userinfo']);
	return $cookie;
}

function createLoginButton(){
	if(isLogin()){
		$u = $_SESSION['userinfo'];
		$now = intval(date('H',time()));
		echo ("Hi,".$u['name']);
	}else{
		echo('<fb:login-button perms="email"></fb:login-button>');
	}
}

function getUserInfo($cookie){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?access_token=' . $cookie['access_token']);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$httpResponse = curl_exec($ch);
	$user = json_decode($httpResponse,true);
	return $user;
}