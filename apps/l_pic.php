<?
$index_url = restoreFetchUrl($args);
$id = md5($index_url);
$res = Table::Fetch('html_img',$id);
if(!$res){
	$content = get_url_content($index_url);
	$doc = get_doc($content['code']);

	$bottom = get_elements_by_attr($doc,"class","widepage page","div")->item(1);
	$scripts = $bottom->getElementsbytagname("script");
	$scriptval = $scripts->item($scripts->length-4)->nodeValue;
	preg_match("/image_width=([^;]*);[^;]*image_height=([^;]*);/is",$scriptval,$matchs);
	$image_width = $matchs[1];
	$image_height = $matchs[2];
	$image = $doc->getElementById('image');
	fixHref($image,$content['host']);
	$image = $doc->getElementById("image");
	$attrs = array("width"=>$image_width,"height"=>$image_height,"src"=>$image->getAttribute("src"),"title"=>str_replace(" at MangaFox.com","",$image->getAttribute("alt")));
	$out = json_encode($attrs);
	DB::Insert('html_img',array('id'=>$id,'imginfo'=>$out));
}else
	$out = $res['imginfo'];
echo $out;
