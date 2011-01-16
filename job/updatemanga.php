<?
require_once(dirname(dirname(__FILE__)). '/config.php');
include(DIR_ROOT."/apps/summary.php");
print("===============".date('Ymd H:i:s',time())."=======================\r\n");
$content = get_url_content(FETCH_SITE,0);
$doc = get_doc($content['code'],$content['host']);

$updates = $doc->getElementById('updates');
$h2 = $updates->getElementsByTagname('h2');
$len = $h2->length;
while($h = $h2->item(--$len)) {
	var_dump($len);
	$a = $h->getElementsByTagname('a')->item(0);
	$_t = explode("/",$a->getAttribute('href'));
	Summary::updateSummary($_t[2]);
	print($_t[2]."\r\n");
	sleep(5);
}
