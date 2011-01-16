<?
$index_url = restoreFetchUrl($args);
$content = get_url_content($index_url,3600*24);
$content['code'] = str_replace(FETCH_SITE."/manga/",WEB_ROOT."/r/l_manga/manga/",$content['code']);
$content['code'] = str_replace("/media/logo.gif","/images/logo.png",$content['code']);
$content['code'] = str_replace("Manga Fox","Manga Go",$content['code']);

echo $content['code'];