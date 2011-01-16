<?
function get_outerhtml($ele){
	$d = new DOMDocument('1.0'); 
	if(is_array($ele)||$ele instanceof DOMNodeList){
		foreach($ele as $e){
			$d->appendChild($d->importNode($e->cloneNode(true),true)); 
		}
	}else{
		$b = $d->importNode($ele->cloneNode(true),true); 
		$d->appendChild($b); 
	}
	return $d->saveHTML(); 
}

function remove_element($ele,$doc){
	if($doc){
		$ele = $doc->getElementById($ele);
	}
//	if($ele)
		$ele->parentNode->removeChild($ele);
}

function get_innerhtml($element){
    $innerHTML = ""; 
    $children = $element->childNodes; 
    foreach ($children as $child) 
    { 
        $tmp_dom = new DOMDocument(); 
        $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
        $innerHTML.=trim($tmp_dom->saveHTML()); 
    } 
    return $innerHTML; 
}

function get_doc($html){
	if(!_checkHtml($html))
		Util::redirect($_SERVER['REQUEST_URI']);
	$dom = new DOMDocument('1.0'); 
	$dom->registerNodeClass('DOMElement', 'JSLikeHTMLElement'); 
	$dom->loadHTML($html);
	$html = $dom->saveHTML();
	@$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false; 
	return $dom;
}

function get_elements_by_attr($ele,$attr,$value,$tagName='*'){
	if($ele instanceof DOMDocument){
		$x = new DOMXPath($ele);
		$nodelist = $x->query("//".$tagName."[@$attr='$value']");
	}
	else{
		$x = new DOMXPath($ele->ownerDocument);
		$nodelist = $x->query($tagName."[@$attr='$value']",$ele);
	}
	return $nodelist;
}
function get_url_content($url,$cache_time=null,$refer){
	$options=array(
		'cacheDir'	=>	DIR_ROOT.'/cache',
		'lifeTime'	=>	($cache_time===null)?TEMP_TIME:$cache_time,
		'dirLevels'	=>	2,
	);
	$cache=TempCache::instance($options);
	$oriurl = str_replace("?no_warning=1","",$url);
	$html=$cache->get($oriurl);
	if (!$html||!is_array($html)||!_checkHtml($html['code'])) {
		$html=_doget($url,$refer);
		$cache->save($html);
	}
	return $html;
}

function _checkHtml($html){
	return strlen($html)>1000;
}
function _getSnoopy(){
	$snoopy = new Snoopy;
	 
	// need an proxy?:
	if(USE_PROXY){
		$snoopy->proxy_host = PROXY_HOST;
		$snoopy->proxy_port = PROXY_PORT;
	}
	 
	// set browser and referer:
	$snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
	 
	// set an raw-header:
	$snoopy->rawheaders["Pragma"] = "no-cache";
	return $snoopy;
}

function _doget($url,$refer){
	if(empty($refer)){
		$host=str_replace('http://','',$url);
		$host=explode('/',$host);
		$refer=$host[0];
	}
	$snoopy = _getSnoopy();
	$snoopy->referer = $refer;
	if($snoopy->fetch($url)){ 
		// other methods: fetch, fetchform, fetchlinks, submittext and submitlinks
	 
		// response code:
//		print "response code: ".$snoopy->response_code."<br/>\n";
//	 
//		// print the headers:
//	 
//		print "<b>Headers:</b><br/>";
//		while(list($key,$val) = each($snoopy->headers)){
//			print $key.": ".$val."<br/>\n";
//		}
//		print $snoopy->referer;
//	 
//		print "<br/>\n";
//	 
//		// print the texts of the website:
//		print "<pre>".htmlspecialchars($snoopy->results)."</pre>\n";
		return array('code'=>$snoopy->results,'host'=>empty($snoopy->lastredirectaddr)?$url:$snoopy->lastredirectaddr);
	 
	}
	else {
		print "Oops! please refresh this page by click F5!,".$snoopy->error."\n";
	}
}

function get_img($url,$refer){
	if(empty($refer)){
		$host=str_replace('http://','',$url);
		$host=explode('/',$host);
		$refer=$host[0];
	}

	$snoopy = _getSnoopy();
	$snoopy->referer = $refer;
	if($snoopy->fetch($url)){ 
		while(list($key,$val) = each($snoopy->headers)){
			$head.=" $val";
		}
		return $head."rnrn".$snoopy->results;
	}
	else {
		print "Oops! please refresh this page by click F5!,".$snoopy->error."\n";
	}
}

function transToAbsolutePath($src,$host){
	
	if(strpos($src,'http')!==0){
		if($host){
			$temp=str_replace('http://','',$host);
			$temp=explode('/',$temp);
			if(strpos($src,"/")===0){
				$src = "http://".$temp[0].$src;
			}elseif(strpos($src,"?")===0){
				$t = parse_url($host);
				$src = "http://".$t['host'].$t['path'].$src;
			}else{
				while(strpos($src,'../')!==false){
					$src = preg_replace('/\.\.\//','',$src,1);
					array_pop($temp);
				}
				array_pop($temp);
				$src = "http://".implode($temp,'/').'/'.$src;
			}
		}
	}
//	if(strpos($src,'is_completed')){
//		var_dump($src,$host);
//		exit;
//	}
	return $src;
}
