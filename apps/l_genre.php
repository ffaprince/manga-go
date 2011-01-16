<?
include('summary.php');
$gid = urldecode($args[0]);
$current = empty($args[1])?1:$args[1];
$size = 20;
$total = floor((Summary::getGenreCount($gid)-0.1)/$size)+1;

include('header.php');
?>
<div id="all_genres">
<div class='genre_title'>All Genres:</div>
<?
$genresid="genre_panel";
include('genres.php');
?>
</div>
<?
echo("<div id='left_side' class='genre_page'>");
genPagination($current,$total,"/r/l_genre/".$gid."/{pageid}/");
echo('<div class="clear gap"></div>');
echo("<ul>");
$mids = Summary::getMidsByGenreId($gid,$size,($current-1)*$size);
foreach($mids as $mid){
	$infos = Summary::getSummary($mid);
	$nocover = strpos($infos['cover'],'nocover')===false?false:true;
	$cover = translateImg($infos['cover'],FETCH_SITE);
?>
	<li class="updatesli">
	<div class="box">
		<div class="left">
			<div class="img_wrapper">
				<a href="/r/l_manga/manga/<?=$mid?>/" target="_blank" class="thm-effect" title="<?=$infos['title']?>">
					<img src="<?=$cover?>" alt="<?=$infos['title']?>" title="<?=$infos['title']?>" width="<?=$nocover?100:150?>">
				</a>
			</div>
		</div>
		<div class="left new_info">
			<div class="row_1">
				<h2 class="title"><a style='background: url("/images/manga_<?=$infos['finished']?'closed':'opened'?>.png") no-repeat' href='/r/l_manga/manga/<?=$mid?>/'><?=$infos['title']?></a><?=$infos['view']>1040129?"<img title='hot' alt='hot' src='/images/hot.png'>":""?></h2>
			</div>
			<div class="row_2">
				<span class="tag_span"><?
				foreach($infos['genres'] as $u){
					?>
					<a href='/r/l_genre/<?=$u?>/'><?=$u?></a>
					<?
				}
				?></span>
			</div>
			<div class="row_3">
				<p><?=$infos['summary']?></p>
			</div>
		</div>
		<div class="right updates">
			<div class="row_1">
				<div class="title"><h2><a href='/r/l_manga/manga/<?=$mid?>/'>Go Reading</a></h2></div>
			</div>
			<div class="row_2">
				<div class="right updates_chapter">
					<ul><?foreach($infos['updates'] as $u){
							$_t = explode(":",$u['name']);
							$name = $_t[0];
							?>
						<li><a href='/r/l_manga/<?=$u['href']?>'><?=$name?></a></li>
					<?}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	</li>
<br>
<?
}
echo("</ul>");
genPagination($current,$total,"/r/l_genre/".$gid."/{pageid}/");
echo("</div>");
include('footer.php');

