<?
include_once("summary.php");
$mid=$args[1];
$info = Summary::getSummary($mid);
$cover = translateImg($info['cover'],FETCH_SITE);

include('header.php');
?>
    <div style="padding: 10px;" class="left">
        <div id="information">
            <h2>
                <?=$info['title']?>
            </h2>
            <div class="right cover">
                <img width="200" alt="<?=$title?>" src="<?=$cover?>">
            </div>
            <table>
                <tbody>
                    <tr>
                        <th>
                            Alternative Name
                        </th>
                        <td>
                            <?=$info['aname']?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Start Year
                        </th>
                        <td>
                            <a target="_self" href="/r/l_directory/search/released/<?=$info['year']?>/">
                                <?=$info['year']?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            <?=$info['finished']?"Completed":"Ongoing"?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Author
                        </th>
                        <td>
						<?
						$len = sizeof($info['author']);
						$i=0;
						foreach($info['author'] as $a){
							?>
							<a target="_self" style="color: rgb(102, 102, 102);" href="/r/l_directory<?=$a['href']?>">
                               <?=$a['name']?>
                            </a>
							<?
								if(++$i<$len) echo(", ");
								?>
						<?}?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Genre(s)
                        </th>
                        <td>
							<?
							$len = sizeof($info['genres']);
							$i=0;
							foreach($info['genres'] as $a){
								?>
								<a target="_self" href="/r/l_directory/search/genres/<?=$a?>/">
                                <?=$a?>
                            </a>
								<?
									if(++$i<$len) echo(", ");
									?>
							<?}?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Like it
                        </th>
                        <td>
						<?
										$fbz = '<iframe frameborder="0" scrolling="no" allowtransparency="true" style="border: medium none; overflow: hidden; width: 450px; height: 80px;" src="http://www.facebook.com/plugins/like.php?href='.urlencode(WEB_ROOT.'/r/l_manga/manga/'.$mid.'/').'&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80"></iframe>';
		$rss = '<a target="_self" class="like rss" href="/r/rsslink/rss/'.$mid.'.xml">RSS</a>';
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
						echo $rss.$addthis."<br>".$fbz;
										?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Latest Chapters
                        </th>
                        <td>
						<?
						$len = sizeof($info['updates']);
						$i=0;
						foreach($info['updates'] as $a){
							?>
							<a target="_self" class="chico" href="/r/l_manga<?=$a['href']?>">
                                <?=$a['name']?>
                            </a>
							<?
								if(++$i<$len) echo("<br>");
								?>
						<?}?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="clear">
            </div>
            <h3>
                Summary
            </h3>
            <p>
               <?=$info['summary']?>
            </p>
            <div class="clear">
            </div>
        </div>
        <table name="listing" id="listing">
            <tbody>
                <tr>
                    <th class="no">
                        Chapter Name
                    </th>
                    <th class="no">
                        Date Added
                    </th>
                </tr>
				<?
					$cinfo = Summary::getChapters($mid);
					$chapters = $cinfo['chapters'];
					foreach($chapters as $c){?>
                <tr>
                    <td>
                        <a target="_self" class="chico" href="/r/l_manga<?=$c['href']?>">
                            <?=$c['name']?>
                        </a>
                    </td>
                    <td class="no">
                        <?=date('M j, Y',$cinfo['update_date'])?>
                    </td>
                </tr>
				<?
					}?>
            </tbody>
        </table>
    </div>
<? include( 'footer.php');
