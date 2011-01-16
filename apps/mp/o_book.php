<?
include("url_mapping.php");
$book_info = $urlmaps[$args[1]];

if(empty($book_info[0])) {
}else{
$content = get_url_content($book_info[0],3600*24*365);
$doc = get_doc($content['code']);

$t_row = get_elements_by_attr($doc,"class","t-row","ul")->item(1);
$dds = $t_row->getElementsByTagname("dd");
$len = $dds->length;

$vol = $t_row->childNodes->item(0);
$vol->innerHTML="<dl><dt class='th'>Chapter Name</dt><dd class='th-c'>Scanlator</dd><dd class='th-r'>Date Added</dd></dl>";
while($len>0){
	$dd = $dds->item(--$len);
	$pn = $dd->parentNode;
	if($dd->getAttribute("class")=='ppp'){
		$pn->removeChild($dd);
	}
	if($dd->getAttribute("class")=='views'){
		$dd->innerHTML="n/a";
	}
}
fixHref($t_row,$content['host']);
echo get_outerhtml($t_row);
}