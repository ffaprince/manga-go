<?
$index_url = restoreFetchUrl($args);
$content = get_url_content($index_url,3600*24*365);
$doc = get_doc($content['code']);

$error = $doc->getElementById("error");
if($error){
	fixHref($error,$content['host']);
	include('header.php');
	echo get_outerhtml($error);
	include('footer.php');
}else{
	$ts = get_elements_by_attr($doc,"class","widepage page","div");
	$top = $ts->item(0);
	$bottom = $ts->item(1);
	$top->removeChild(get_elements_by_attr($top,"class",'clear gap',"div")->item(0));
	$viewer = $doc->getElementById('viewer');

	$topad = $doc->getElementById("topad");
	$topad->parentNode->removeChild($topad);
	//var_dump($trs->item(8),$trs->item(0)->parentNode);exit;
	fixHref($top,$content['host']);
	fixHref($bottom,$content['host']);
	fixHref($viewer,$content['host']);
	$scripts = $bottom->getElementsbytagname("script");
	$pn = $scripts->item(0)->parentNode;
	$pn->removeChild($scripts->item($scripts->length-1));//remove ondomready
	$pn->removeChild($scripts->item($scripts->length-1));//remove page.js
//	var image_width=728;
//	var image_height=1055; 
	$scriptval = $scripts->item($scripts->length-2)->nodeValue;
	preg_match("/image_width=([^;]*);[^;]*image_height=([^;]*);/is",$scriptval,$matchs);
	$image_width = $matchs[1];
	$image_height = $matchs[2];
	$tool = $doc->getElementById("tool");
	$tool->removeChild($tool->getElementsByTagname("iframe")->item(0));

	$fblike = $doc->createELement("div");
	$tool->appendChild($fblike);
	$fblike->setAttribute("id",'fblike');
	$fblike->innerHTML='<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode(WEB_ROOT.$_SERVER['REQUEST_URI']).'&amp;layout=button_count&amp;show_faces=false&amp;width=200&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>';

	$bookmark = $doc->getElementById("bookmark");
	$bookmark->removeAttribute("style");
	$bookmark->setAttribute("title","add bookmark for this page");
	$bookmark->innerHTML="bookmark it";
	$as = get_elements_by_attr($doc,"class","button prev_page","a");
	foreach($as as $a){
		$a->setAttribute("href","javascript:void(0)");
		$a->removeAttribute("onclick");
	}
	$as = get_elements_by_attr($doc,"class","button next_page","a");
	foreach($as as $a){
		$a->setAttribute("href","javascript:void(0)");
		$a->removeAttribute("onclick");
	}
	$doc->getElementById("top_chapter_list")->removeAttribute("onchange");
	$doc->getElementById("bottom_chapter_list")->removeAttribute("onchange");

	$middls = get_elements_by_attr($doc,"class","middle","select");
	foreach($middls as $m){
		$m->removeAttribute("onchange");
	}

	$image = $doc->getELementById("image");
	$image->removeAttribute("width");
	$image->setAttribute("alt",str_replace(" at MangaFox.com","",$image->getAttribute("alt")));
	$image->setAttribute('_width',$image_width);
	$image->setAttribute('_height',$image_height);
	$pic_c = $doc->createElement("div");
	$pic_c->setAttribute("id","pic_container");
	$doc->getELementById("viewer")->appendChild($pic_c);
	$pic_c->appendChild($image);
	
	include('header.php');
	echo get_outerhtml($top);
	echo get_outerhtml($viewer);
	echo get_outerhtml($bottom);
	echo '<script type="text/javascript" src="/js/chapter.js"></script>';
	include('footer.php');
}