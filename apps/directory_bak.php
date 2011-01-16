<?
global $sortkeys;
session_start();
$hasSortKey = false;
foreach($_GET as $k=>$v){
	foreach($sortkeys as $key){
		if(str_replace(".","_",$key)==$k){
			unset($_GET[$k]);
			$_GET[$key]=null;
			$_SESSION['sortkey']=$key;
			$hasSortKey=true;
			break;
		}
	}
}
if(!$hasSortKey&&$_SESSION['sortkey']){
	$_GET[$_SESSION['sortkey']]=null;
}
$index_url = restoreFetchUrl($args);
//var_dump($index_url);exit;
$content = get_url_content($index_url,$_usecache!==false?3600*24:0);
$doc = get_doc($content['code']);

$left = get_elements_by_attr($doc,"class","left","div")->item(2);
$left->setAttribute('id','dir_main');
$adv = get_elements_by_attr($left,"class","clear gap","div")->item(0);
$adv2 = get_elements_by_attr($left,"class","clear","div")->item(0);
$left->removeChild($adv);
$left->removeChild($adv2);

$trs = $doc->getElementsByTagname("tr");
if($trs->length>0){
	$trs->item(0)->getElementsbytagname("a")->item(4)->innerHTML="Last Updated";
	$i=1;
	while($tr = $trs->item($i++)){
		$tds = $tr->getElementsByTagname("td");
		$as = $tds->item(4)->getElementsByTagname("a");
		foreach($as as $a){
			$tds->item(4)->removeChild($a);
		}
		if(trim($tds->item(4)->nodeValue)=='None')
			$tds->item(4)->innerHTML="on Aug 6,2010";
		
	}
}
//remove_element(get_elements_by_attr($doc,"name","name_method","select")->item(0));
remove_element("optbtn",$doc);
remove_element("advoptions",$doc);
$doc->getElementById("slider_prev")->removeAttribute("onclick");
$doc->getElementById("slider_next")->removeAttribute("onclick");
$showcase = $doc->getElementById("showcase");
$_t = $showcase->getElementsByTagname("div")->item(0);
$_t->removeAttribute("style");
$_t->setAttribute("class","left tp na");
$searchbar = $doc->getElementById("searchbar")->getElementsByTagname('ol')->item(0);
$_completea = $doc->createElement("li");
$_completea->setAttribute("class","comp_li");
$_completea->innerHTML="<a id='comp_a' href='javascript:void(0)'>Completed Filter</a>";
$searchbar->appendChild($_completea);
fixHref($showcase,$content['host']);
fixHref($left,$content['host']);