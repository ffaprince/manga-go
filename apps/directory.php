<?
global $sortkeys;
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
$left->setAttribute('style','');
$adv = get_elements_by_attr($left,"class","clear gap","div")->item(0);
$adv2 = get_elements_by_attr($left,"class","clear","div")->item(0);
$left->removeChild($adv);
$left->removeChild($adv2);

$trs = $doc->getElementsByTagname("tr");
if($trs->length>0){
	$trs->item(0)->getElementsbytagname("a")->item(4)->innerHTML="Lastest Update";
	$trs->item(0)->getElementsbytagname("th")->item(2)->setAttribute('class','view_th');
	$trs->item(0)->getElementsbytagname("th")->item(3)->setAttribute('class','chapters_th');
	$trs->item(0)->getElementsbytagname("th")->item(4)->setAttribute('class','lc_th');
//	$_completea = $doc->createElement("span");
//	$_completea->setAttribute("class","comp_span");
//	$_completea->innerHTML="<input type='checkbox' id='comp_a'><label for='comp_a'>only completed</label>";
//	$trs->item(0)->getElementsByTagname('th')->item(0)->appendChild($_completea);
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
remove_element("showcase",$doc);
$hots = get_elements_by_attr($doc,"class","hotch","span");
$doc->getElementById("searchform_name")->setAttribute("autocomplete","off");
foreach($hots as $h){
	$h->innerHTML="";
	$h->setAttribute("title","hot");
}
$hots = get_elements_by_attr($doc,"class","updatedch","span");
foreach($hots as $h){
	$h->innerHTML="";
	$h->setAttribute("title","updated");
}
$hots = get_elements_by_attr($doc,"class","newch","span");
foreach($hots as $h){
	$h->innerHTML="";
	$h->setAttribute("title","new");
}
fixHref($left,$content['host']);