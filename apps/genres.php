<?
include_once("summary.php");
$_genres_array = array(
	"Action"=>"Action :: Featuring fast paced action, i.e. fighting, violence, or some other form of aggressive activity.",
	"Adult"=>"Adult :: Like adult movie, an adult manga often features depictions of intense violence and/or erotica activity.",
	"Adventure"=>"Adventure :: Titles included in this genre usually revolve around a character going on a trip or exploring the world to find his/her luck.",
	"Comedy"=>"Comedy :: A piece of work generally intended to amuse. Light, humorous, sometimes even riduculous...ly funny. Have a good laugh. :p",
	"Doujinshi"=>"Doujinshi :: Literally, doujin means &quot;same person&quot; and shi &quot;magazine&quot;. It's fan-art inspired by published anime or manga by professional artists.",
	"Drama"=>"Drama :: If a manga's labeled as drama, it's usually meant to bring on strong emotional response from its reader, i.e. tension and/or sadness.",
	"Ecchi"=>"Ecchi :: Ecchi manga's rather a grey area or borderline between hentai and non-hentai. Sexy and juicy? Yeah. Nudity? Not that over the edge. The sexy part just adds some spice to the work to attract fans of this specific genre.",
	"Fantasy"=>"Fantasy :: Fancy stuff, magical creatures, dream land... Anything but routine.",
	"Gender Bender"=>"Gender Bender :: It's all about changing genders - by dressing in the opposite sex, wearing make-ups, or even switching bodies in some cases.",
	"Harem"=>"Harem :: Usually in a harem manga, you can see one male character and many female characters who're apparently or less obviously attracted to him. At times the genders can be other way around and is referred to as a 'Reverse Harem'.",
	"Historical"=>"Historical :: Historical works show you glimpses of our own history by leading you along the ancient lane.",
	"Horror"=>"Horror :: Horror mangas are not for the faint of heart. It's for those who enjoy fear and terror inspired by frightful scenes from the work.",
	"Josei"=>"Josei :: &quot;Ladies' comics&quot;. Target women from 18 to 30. The male equivalent to josei is seinen. Most of the times, the stories tend to be about everyday experiences of women living in Japan - style tends to be a more restrained, realistic version of shoujo manga.",
	"Martial Arts"=>"Martial Arts :: Aikido, judo, tae kwon do, karate, kendo... One word in general to sum up its theme: arts of combat.",
	"Mature"=>"Mature :: Contains subject matter which may be too extreme for people under the age of 17. Titles in this category may contain intense violence, blood and gore, sexual content and/or strong language.",
	"Mecha"=>"Mecha :: The term itself refers to walking vehicles controlled by a pilot. Titles under this genre're usually with some fantastic or futuristic element and always have something to do with large robotic machines.",
	"Mystery"=>"Mystery :: An unsolved case, a mysterious event... Keep you guessing right to the end while catching all the clues and riddles along the way.",
	"One Shot"=>"One Shot :: Manga that denotes series that are short, usually consisting of just one chapter.",
	"Psychological"=>"Psychological :: Usually deals with the philosophy or a state of mind - nine cases out of ten, the crazy kind. May contain some disturbing contents.",
	"Romance"=>"Romance :: L-O-V-E, love! Closely related to the shoujo genre.",
	"School Life"=>"School Life :: Set in campus or some sort of school.",
	"Sci-fi"=>"Sci-fi :: Short for science fiction, series labeled as sci-fi often involves speculation based on current or future science or technology. There're no boundaries where imagination may carry you.",
	"Seinen"=>"Seinen :: For young men 18 to 30 years old. Often involving male heroes, themes of honor, slapstick humor, and sometimes explicit sexuality, etc.",
	"Shoujo"=>"Shoujo :: Targeted for girls roughly between the ages of 10 to 18. Covers many subjects in a variety of styles, often closely tied to romance and love.",
	"Shoujo Ai"=>"Shoujo Ai :: Often synonymous with yuri. You can consider it a mild case of yuri, aka. &quot;Girls love&quot;.",
	"Shounen"=>"Shounen :: Also known as Shonen, it's primarily written for boys. Fighting and/or violence can often be seen in these works. Closely tied to genres like action, adventure and/or martial arts.",
	"Shounen"=>"Shounen Ai :: Often synonymous with yaoi, you can consider it a mild case of yaoi, aka. &quot;Boys love&quot;.",
	"Slice Of Life"=>"Slice of Life :: Usually portrays a &quot;cut-out&quot; sequence of events in a character's life. It usually tends to depict the everday life of ordinary people, sometimes with fantasy or sci-fi elements involved.",
	"Smut"=>"Smut :: Series in this genre contain offensive contents, especially sexually profane materials.",
	"Sports"=>"Sports :: Centers around any kind of sports. As the name suggests, anything sports related. Baseball, football, roller blading, soccer...sports manga depicts the stories of loss and gain in the world of a specific sport.",
	"Supernatural"=>"Supernatural :: Superman, UFOs... Deals with unexplained powers or things that do not happen in nature.",
	"Tragedy"=>"Tragedy :: Get yourself a handy or some tissue paper beforehand. That's all I've got to say.",
	"Yaoi"=>"Yaoi :: &quot;Boys love&quot;. Focuses on homoerotic or homoromantic relationships between men.",
	"Yuri"=>"Yuri :: &quot;Girls love&quot;. Usually involves intimate relationships between women. The male equivalence of yuri is yaoi.",
	);
$gids = array();
foreach($_genres_array as $k=>$v){
	$gids[]=$k;
}
$res = Summary::getGenresNum($gids);
$numarray = array();
foreach($res as $k=>$v){
	$numarray[$v['id']]=$v['num'];
}
if(empty($genresid)) $genresid="genres";
$hotgeneres = createHotGenre(true);
?>
<div id="<?=$genresid?>">
	<ul>
	<li class='genres_title'>
		Choose your favorite category!
	</li>
	<?
	foreach($_genres_array as $k=>$t){
	?>
    <li>
        <label <?=$gid==$k?'class="selected_genre"':''?>>
            <a href="/r/l_genre/<?=$k?>/" _title="<?=$t?>"
            class="tips">
                <?=$k?><?if($genresid!="genres"){?>&nbsp;<span <?=in_array($k,$hotgeneres)?'style="padding-right:15px;background: url(/images/hot.png) no-repeat top right"':""?>>[<?=$numarray[$k]?>]</span><?}?>
            </a>
        </label>
    </li>
	<?}?>
</ul>
</div>