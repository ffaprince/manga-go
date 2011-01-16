<?
$ms = DB::LimitQuery("manga_info",array(
			'order' => 'ORDER BY update_date desc',
			'size' => 30,
			'cache'=> 300,
));
$cms = DB::LimitQuery("manga_info",array(
			'order' => 'ORDER BY create_date desc',
			'size' => 30,
			'cache'=> 300,
));
$rms = DB::LimitQuery("manga_info",array(
			'order' => 'ORDER BY view desc',
			'size' => 30,
			'cache'=> 300,
));
$recommands = Table::Fetch("manga_info",array('i_accept_you','the_world_god_only_knows','the_one','naruto'));

$index_selected=true;
include('header.php');

?>
<div id="recommand">
	<ul>
	<?foreach($recommands as $m){
		$infos = json_decode($m['info'],true);
		$mid = $m['id'];
		$cover = translateImg($infos['cover'],FETCH_SITE);
		$u = array_pop($infos['updates']);
		$_t = explode(":",$u['name']);
		$name = $_t[0];
		?>
		<li class="left">
			<div class="wrapper">
				<div class="img_wrapper">
					<div class="rname"></div>
					<div class="rname_wrapper">
						<a class='title' href='/r/l_manga/manga/<?=$mid?>/'><?=$infos['title']?></a><br>
						<a href='/r/l_manga<?=$u['href']?>'><?=$name?></a>
					</div>
					<a href='/r/l_manga/manga/<?=$mid?>/'>
					<img src="<?=$cover?>" height="244">
					</a>
				</div>
			</div>
		</li>
	<?}?>
	</ul>
</div>
<div id="left_side">
<div id='left_tab'>
	<div class="border"></div>
	<div class="ru" style='background-color:#B6B6B6;'><a href='javascript:void(0)'>Recently updated</a></div>
	<div class="rc"><a href='javascript:void(0)'>Recently collected</a></div>
	<div class="am"><a href='/r/l_directory/directory/'>All manga</a></div>
</div> 
<div id="updates_panel">
<ul>
<?
foreach($ms as $m){
	$infos = json_decode($m['info'],true);
	$mid = $m['id'];
	$nocover = strpos($infos['cover'],'nocover')===false?false:true;
	$cover = translateImg($infos['cover'],FETCH_SITE);
	if(strpos($conver,"nocover")!==false) continue;
	if($m['update_date']>=strtotime('today'))
		$time = "today ".date("g:ia",$m['update_date']);
	elseif($m['update_date']+86400>=strtotime('today'))
		$time = "yesterday ".date("g:ia",$m['update_date']);
	else
		$time = date("M j, Y g:ia",$m['update_date']);
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
				<h2 class="title"><a href='/r/l_manga/manga/<?=$mid?>/'><?=ucwords(strtolower($infos['title']))?></a>
				<?=$m['view']>1040129?"<img title='hot' alt='hot' src='/images/hot.png'>":""?></h2>
			</div>
			<div class="row_2">
				<div class="left updates_chapter">
					<ul><?foreach($infos['updates'] as $u){
							$_t = explode(":",$u['name']);
							$name = $_t[0];
							?>
						<li><a href='/r/l_manga/<?=$u['href']?>'><div class="chapter_name"><?=$u['name']?></div>
						<div class="time"><em><?=$time?></em></div></a></li>
					<?}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	</li>
	<?
}
?>
</ul>
</div>
<div id="new_panel" style="display:none">
<ul>
<?
foreach($cms as $m){
	$infos = json_decode($m['info'],true);
	$mid = $m['id'];
	$nocover = strpos($infos['cover'],'nocover')===false?false:true;
	$cover = translateImg($infos['cover'],FETCH_SITE);
	if(strpos($conver,"nocover")!==false) continue;
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
				<h2 class="title"><a style='background: url("/images/manga_<?=$infos['finished']?'closed':'opened'?>.png") no-repeat' href='/r/l_manga/manga/<?=$mid?>/'><?=$infos['title']?></a></h2>
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
	</div>
	</li>
	<?
}
?>
</ul>
</div>
</div>
<?
/**************************************************ср╠ъ0**********************************************/
?>
<div id="right_side">
<div id='left_tab'></div> 
<div id="toplist_panel">
<ul>
<?
$i=0;
foreach($rms as $m){
	$infos = json_decode($m['info'],true);
	$mid = $m['id'];
	if(strpos($infos['cover'],"nocover")!==false) continue;
	$cover = translateImg($infos['cover'],FETCH_SITE);
	?>
	<li class="toplist">
		<div class='toplist_bg' style="display:none"></div>
		<div class="row_1">
			<span class="index"><?=++$i?></span>
			<div class="title"><h2><a href='/r/l_manga/manga/<?=$mid?>/'><?=ucwords(strtolower($infos['title']))?></a></h2></div>
			<span class="view"><?=$m['view']?></span>
		</div>
		<div class="row_2" style="display:none">
			<div class="left listimg">
				<a href="/r/l_manga/manga/<?=$mid?>/" target="_blank" class="thm-effect" title="<?=$infos['title']?>">
					<img src="<?=$cover?>" alt="<?=$infos['title']?>" title="<?=$infos['title']?>" width="93">
				</a>
			</div>
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
	</li>
	<?
}
?>
</ul>
</div>
</div>
<?
include('footer.php');
