<?
$index_url = restoreFetchUrl($args);
$content = get_url_content($index_url,3600*24*365);
$doc = get_doc($content['code']);
$information = $doc->getElementById('information');
fixHref($information,$content['host']);
$trs = $information->getElementsByTagname("tr");
$pnode = $trs->item(0)->parentNode;
$_trs = array();
foreach($trs as $tr){
	$_trs[]=$tr;
}
while($tr =array_pop($_trs)){
	$_title = $tr->getElementsByTagname('th')->item(0)->nodeValue;
	if($_title=='Rating'||trim($_title)==''||$_title=='Related Scanlators'||$_title=='Rank'||$_title=='Artist(s)'){
		$pnode->removeChild($tr);
	}
	elseif($_title=='Like it'){
		$fbz = '<iframe frameborder="0" scrolling="no" allowtransparency="true" style="border: medium none; overflow: hidden; width: 450px; height: 80px;" src="http://www.facebook.com/plugins/like.php?href='.urlencode(WEB_ROOT.'/r/l_manga/manga/'.$args[1].'/').'&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80"></iframe>';
		$rss = '<a target="_self" class="like rss" href="http://www.mangafox.com/rss/'.$args[1].'.xml">RSS</a>';
		$addthis='<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=ffaprince" class="addthis_button_compact">Share</a>
		<span class="addthis_separator">|</span>
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_preferred_5"></a>
		</div>
		<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=ffaprince"></script>
		<!-- AddThis Button END -->
		';
		$listing = $doc->getElementById("listing");
		if(!$listing) $rss="";
		$tr->getElementsByTagname('td')->item(0)->innerHTML=$rss.$addthis."<br>".$fbz;
	}
}

include('header.php');
echo "<div class='left' style='padding:10px;'>";
echo get_outerhtml($information);

$listing = $doc->getElementById("listing");
if(!$listing){
	require_once(DIR_ROOT.'/apps/mangapark/o_book.php');
}
else{
	$trs = $listing->getElementsByTagname("tr");
	foreach($trs as $tr){
		$tr->removeChild($tr->childNodes->item(4));
		$tr->removeChild($tr->childNodes->item(2));
	}
	$as = get_elements_by_attr($listing,"class","edit","tr/td/a");
	foreach($as as $a) {
		$a->parentNode->removeChild($a);
	}
	$hots = get_elements_by_attr($doc,"class","newch","span");
	foreach($hots as $h){
		$h->innerHTML="";
		$h->setAttribute("title","new");
	}
	fixHref($listing,$content['host']);
	echo get_outerhtml($listing);
}


echo "</div>";
include('footer.php');
