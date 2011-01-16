<?
$index_url = restoreFetchUrl($args,'http://www.mangapark.com');
//var_dump($index_url);exit;
$content = get_url_content($index_url);
$html = $content['code'];
$doc = get_doc($html);

$page = $doc->getElementById("page-mainer");
$chapter_name = $page->getElementsByTagname('h2')->item(0)->nodeValue;
$div = get_elements_by_attr($page,"class","header","div")->item(0);
$page->removeChild($div);
$div = get_elements_by_attr($page,"class","ad-468x60x2","div")->item(0);
$page->removeChild($div);
$div = get_elements_by_attr($page,"class","ad-300x250x3","div")->item(0);
$page->removeChild($div);

$selects = get_elements_by_attr($doc,"id","ch_sn_sel","select");
$onchange = $selects->item(0)->getAttribute('onchange');
preg_match("/'([^']*)'/im",$onchange,$matchs);
$selects->item(0)->setAttribute('id','top_chapter_list');
$selects->item(1)->setAttribute('id','bottom_chapter_list');
$selects->item(0)->removeAttribute("onchange");
$selects->item(1)->removeAttribute("onchange");
$total_chapters = $selects->item(0)->getElementsByTagname("option")->length;

$divs = get_elements_by_attr($doc,"class","pager book-list","div");
$divs->item(0)->appendChild($selects->item(0));
$divs->item(1)->appendChild($selects->item(1));
$divs->item(0)->removeChild($divs->item(0)->getElementsbytagname("ul")->item(0));
$divs->item(1)->removeChild($divs->item(1)->getElementsbytagname("ul")->item(0));

$divs = get_elements_by_attr($doc,"class","pager","div");
remove_element($divs->item(0));
remove_element($divs->item(1));

$numtexte = get_elements_by_attr($doc,"class","gray","a")->item(0);
$numtext = $numtexte->nodeValue;
$_t = explode(" ",$numtext);
$_tt = explode("/",$_t[1]);
$current_page = $_tt[0];
$total_pages = $_tt[1];

$mainer = get_elements_by_attr($doc,"class","mainer","div")->item(0);
$mainer->setAttribute('id','pic_container');
$piclist = get_elements_by_attr($doc,"class","pic-list","div")->item(0);
$mainer->removeChild($piclist);
$image = $doc->getELementById("manga_pic_1");
$nexthtmlurl = $image->parentNode->getAttribute("href");
$title = $image->getAttribute("title");
$imgsrc = $image->getAttribute("src");
$newimg = $doc->createElement("img");
$newimg->setAttribute("id","page$current_page");
$newimg->setAttribute("title",$title);
$newimg->setAttribute("_next",$nexthtmlurl);
$newimg->setAttribute("src",translateImg($imgsrc));
$mainer->appendChild($newimg);

$manga_name = get_elements_by_attr($doc,"class","walk","div")->item(0)->getElementsByTagname("li")->item(4)->nodeValue;
$pagerbar = get_elements_by_attr($doc,"class","pagebar","div")->item(0);
$tool = $doc->createElement("div");
$tool->setAttribute('id','tool');
$pagerbar->appendChild($tool);
$tool->innerHTML='<a href="'.WEB_ROOT.'/r/l_manga/manga/'.$args[1].'/" id="series" target="_self">'.$manga_name.' Manga</a>
    <a href="javascript:void(0);" id="bookmark" title="add bookmark for this page">Bookmark</a><div id="fblike"><iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode(WEB_ROOT.$_SERVER['REQUEST_URI']).'&amp;layout=button_count&amp;show_faces=false&amp;width=200&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe></div>';
//	<fb:like href="fff" layout="button_count" show_faces="false" width="450"></fb:like>
include(DIR_ROOT.'/apps/header.php');
echo get_outerhtml($page);
echo '<script>var series_name="'.$matchs[1].'",total_chapters='.$total_chapters.',current_page='.$current_page.',total_pages='.$total_pages.',manga_name="'.$manga_name.'",chapter_name="'.$chapter_name.'",manga_id="'.$args[1].'";</script>';

echo '<script type="text/javascript" src="/js/mp_chapter.js"></script>';
include(DIR_ROOT.'/apps/footer.php');
