<?
$index_url = restoreFetchUrl($args,'http://www.mangapark.com');
$id = md5($index_url);
$res = Table::Fetch('html_img',$id);
if(!$res){
	$content = get_url_content($index_url,0);
	$doc = get_doc($content['code']);
	$image = $doc->getElementById("manga_pic_1");
	$nexthtmlurl = $image->parentNode->getAttribute("href");
	$src = translateImg($image->getAttribute("src"),'http://pic.mangapark.com');
	$attrs = array("src"=>$src,"title"=>$image->getAttribute("title"),'_next'=>translateHref($nexthtmlurl,'http://www.mangapark.com'));
	$out = json_encode($attrs);
	DB::Insert('html_img',array('id'=>$id,'imginfo'=>$out));
}else
	$out = $res['imginfo'];
echo $out;