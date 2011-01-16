<?
$index_url = restoreFetchUrl($args);
$content = get_url_content("http://www.mangafox.com/",3600*24);
$doc = get_doc(translateStyleBgImg($content['code'],$content['host']));

$contentdiv = $doc->getElementById('content');
$sidebar = $doc->getElementById("sidebar");
$sidebar->removeChild($doc->getElementById("advertisment"));
$sidebar->removeChild($doc->getElementById("rss"));
$top = get_elements_by_attr($sidebar,"id","top","div")->item(0);
$top->parentNode->removeChild($top);
$sidebar->removeChild($doc->getElementById("activity"));
$sidebar->removeChild($sidebar->getElementsBytagname("iframe")->item(0));
$advc = createMainLunhuan();
$idSlider = $doc->getElementById("idSlider");
$idSlider->innerHTML=$advc[0];
$idSelector = $doc->getElementById("idSelector");
$idSelector->innerHTML=$advc[1];
$ac = $doc->createElement("div");
$ac->setAttribute('id','activity');
remove_element("statistics",$doc);
//$sidebar->appendChild($ac);
$ac->innerHTML='<iframe src="http://www.facebook.com/plugins/activity.php?site='.DOMAIN.'.com&amp;width=322&amp;height=540&amp;header=true&amp;colorscheme=light&amp;border_color=%233B5998&amp;recommendations=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:322px; height:540px;" allowTransparency="true"></iframe>'.'<iframe src="http://www.facebook.com/plugins/likebox.php?href='.DOMAIN.'.com&amp;width=322&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=true&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:322px; height:62px;" allowTransparency="true"></iframe>';

fixHref($contentdiv,$content['host']);
fixHref($doc->getElementById('weeklymanga'),$content['host']);
fixHref($doc->getElementById('popular'),$content['host']);
remove_element("hotnews",$doc);

$index_selected=true;
include('header.php');
echo get_outerhtml($contentdiv);
echo get_outerhtml($sidebar);
include('footer.php');
