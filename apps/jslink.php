<?
$index_url = restoreFetchUrl($args);
$content = get_url_content($index_url,3600*24);
echo($content['code']);