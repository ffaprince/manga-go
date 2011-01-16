<?
class Summary{

	static public function getSummary($mid){
		$info = Table::Fetch("manga_info",$mid);
		if(empty($info)){
			list($info,$chapters) = self::getOutSummary($mid);
			DB::Insert('manga_info',array('id'=>$mid,'info'=>json_encode($info),'view'=>$info['view'],'rate'=>$info['rate'],'finished'=>$info['finished'],'create_date'=>time(),'num'=>sizeof($chapters),'year'=>$info['year'],'index'=>self::getIndex($info['title'])));
			DB::Insert('manga_chapter',array('id'=>$mid,'chapters'=>json_encode($chapters),'num'=>sizeof($chapters),'update_date'=>time()));
			self::updateGenre($mid,$info['genres']);
			return $info;
		}else
			return json_decode($info['info'],true);
	}

	static public function getChapters($mid){
		$chapters = Table::Fetch("manga_chapter",$mid);
		if(empty($chapters)){
			list($info,$chapters) = self::getOutSummary($mid);
			DB::Insert('manga_info',array('id'=>$mid,'info'=>json_encode($info),'view'=>$info['view'],'rate'=>$info['rate'],'finished'=>$info['finished'],'create_date'=>time(),'num'=>sizeof($chapters),'year'=>$info['year'],'index'=>self::getIndex($info['title'])));
			DB::Insert('manga_chapter',array('id'=>$mid,'chapters'=>json_encode($chapters),'num'=>sizeof($chapters),'update_date'=>time()));
			return array('update_date'=>time(),'chapters'=>$chapters);
		}else
			return array('update_date'=>$chapters['update_date'],'chapters'=>json_decode($chapters['chapters'],true));
	}

	static public function getGenreCount($gid){
		if($gid=='all'){
			$res = DB::GetQueryResult("select count(1) as num from manga_info",true,300);
		}else
			$res = Table::Fetch('genre_info',$gid);
		return intval($res['num']);
	}

	static public function getGenresNum($gids){
		$res = DB::LimitQuery("genre_info",array(
			'select'=>'id,num',
			'condition' => array("id"=>$gids),
			'cache'=> 300,
		));
		return $res;
	}

	static public function getMidsByGenreId($gid,$size=1,$offset=0){
		if($gid=='all'){
			$temp = DB::GetQueryResult("select id from manga_info",false,300);
			$mids = array();
			foreach($temp as $t){
				$mids[]=$t['id'];
			}
		}else{
			$res = Table::Fetch('genre_info',$gid);
			$mids = explode(",",$res['mids']);
		}
		if(sizeof($mids)>=$offset+$size)
			return array_slice($mids,$offset,$size);
		return array_slice($mids,$offset);
	}

	static public function updateGenre($mid,$genres){
		if(empty($genres)) return;
		foreach($genres as $gid){
			$gid = trim($gid);
			$info = Table::Fetch("genre_info",$gid);
			if(empty($info)){
				DB::Insert('genre_info',array('id'=>$gid,'mids'=>$mid,'num'=>1));
			}else{
				$mids = explode(",",$info['mids']);
				if(!in_array($mid,$mids)){
//					var_dump($mids);
					Table::UpdateCache('genre_info',$gid,array('mids'=>$info['mids'].",".$mid,'num'=>sizeof($mids)+1));
				}
			}
		}
	}

	static private function getIndex($t){
		$i = substr($t,0,1);
		if(preg_match('/[a-z]/i',$i,$m))
			return strtolower($i);
		return '#';
	}

	static public function updateSummary($mid){
		list($info,$chapters) = self::getOutSummary($mid,0);
		$_a = Table::FetchForce("manga_info",$mid);
		if(!empty($_a)){
			if($_a['num']==sizeof($chapters)) return false;
			Table::UpdateCache('manga_info',$mid,array('info'=>json_encode($info),'view'=>$info['view'],'rate'=>$info['rate'],'finished'=>$info['finished'],'update_date'=>time(),'num'=>sizeof($chapters),'year'=>$info['year'],'index'=>self::getIndex($info['title'])));
			Table::UpdateCache('manga_chapter',$mid,array('chapters'=>json_encode($chapters),'num'=>sizeof($chapters),'update_date'=>time()));
		}
		else{
			DB::Insert('manga_info',array('id'=>$mid,'info'=>json_encode($info),'view'=>$info['view'],'rate'=>$info['rate'],'finished'=>$info['finished'],'create_date'=>time(),'update_date'=>time(),'num'=>sizeof($chapters),'year'=>$info['year'],'index'=>self::getIndex($info['title'])));
			DB::Insert('manga_chapter',array('id'=>$mid,'chapters'=>json_encode($chapters),'num'=>sizeof($chapters),'update_date'=>time()));
		}
		self::updateGenre($mid,$info['genres']);
		return true;
	}

	static private function getOutSummary($mid,$time){
		$content = get_url_content(FETCH_SITE.'/manga/'.$mid.'/?no_warning=1',$time);
		$doc = get_doc($content['code']);
		$title="";
		$aname="";
		$author=array();
		$genres=array();
		$updates = array();
		$summary="";
		$src="";
		//取信息
		$information = $doc->getElementById('information');
		$trs = $information->getElementsByTagname("tr");
		$table = $trs->item(0)->parentNode;
		foreach($trs as $tr){
			$_title = $tr->getElementsByTagname('th')->item(0)->nodeValue;
			$_td = $tr->getElementsByTagname('td')->item(0);
			$_v = $_td->nodeValue;
			if($_title=='Alternative Name'){
				$aname=$_v;
			}elseif($_title=='Author(s)'){
				$as = $_td->getElementsBytagname('a');
				$i=0;
				$len = $as->length;
				while($i<$len){
					$author[] = array('href'=>$as->item($i)->getAttribute('href'),'name'=>$as->item($i++)->nodeValue);
				}
			}elseif($_title=='Genre(s)'){
				$genres = explode(',',$_v);
				foreach($genres as $k=>$u){
					$genres[$k]=trim($u);
				}
			}elseif($_title=='Latest Chapters'){
				$as = $_td->getElementsBytagname('a');
				$i=0;
				$len = $as->length;
				$updates = array();
				while($i<$len-1){
					$updates[] = array('href'=>$as->item($i)->getAttribute('href'),'name'=>$as->item($i++)->nodeValue);
				}
				$chapters = self::getFoxChapters($doc);
			}elseif($_title=='Rank'){
				preg_match("/.*has(.*)monthly/i",$_v,$match);
				$view = intval(str_replace(",","",$match[1]));
			}elseif($_title=='Status'){
				if(strpos($_v,"Completed")!==false)
					$finished = 1;
				else
					$finished=0;
			}elseif($_title=='Rating'){
//				Average 4.81 / 5 out of 53 total votes.  
				preg_match("/.*Average(.*)\//im",$_v,$match);
				$rate = floatval($match[1]);
			}elseif($_title=='Years of Released'){
				$year = $_v;
			}
		}
		$information->removeChild($table);
		$listing = $doc->getElementById("listing");
		if(!$listing){
			list($updates,$chapters) = self::getParkUpdated($mid,$time);
		}
		//取summary
		$summary = $information->getElementsByTagname("p")->item(0)->nodeValue;
		//取title
		$title = ucwords(strtolower($information->getElementsByTagname("h2")->item(0)->nodeValue));
		//取封面
		$img = $information->getElementsByTagname("img")->item(0);
		$src = WEB_ROOT.'/images/nocover.jpg';
		if($img)
			$src=$img->getAttribute('src');
		//save
		$info = array('title'=>$title,
				'aname'=>$aname,
				'author'=>$author,
				'genres'=>$genres,
				'summary'=>$summary,
				'cover'=>$src,
				'updates'=>$updates,
				'view'=>$view,
				'finished'=>$finished,
				'rate'=>$rate,
				'year'=>intval($year),
			);
		return array($info,$chapters);
	}
	
	static private function getFoxChapters($doc){
		$listing = $doc->getElementById("listing");
		if(!$listing){
			return array();
		}
		else{
			$trs = $listing->getElementsByTagname("tr");
			$len = $trs->length;
			$i=1;
			$chapters = array();
			while($tr = $trs->item($i++)){
				$a = $tr->getElementsByTagname('a')->item(1);
				$chapters[]=array('href'=>$a->getAttribute('href'),'name'=>$a->nodeValue);
			}
			return $chapters;
		}
	}

	static private function getParkUpdated($mid,$time){
		include('mp/url_mapping.php');
		$parkurl = $urlmaps[$mid];
		if(empty($parkurl)||empty($parkurl[0])){
			return array();
		}
		$content = get_url_content($parkurl[0],$time);
		$doc = get_doc($content['code']);
		$as = get_elements_by_attr($doc,'class','summary','div')->item(0)->getElementsBytagname('a');
		$i=0;
		$len=$as->length;
		$updates = array();
		while($i<3&&$a = $as->item($i++)){
			$updates[]=array('href'=>$a->getAttribute('href'),'name'=>$a->nodeValue);
		}
		//获取chapter信息
		$dts = $doc->getElementById('manga-book-list')->getElementsBytagname('dt');
		$chapters = array();
		foreach($dts as $dt){
			$a = $dt->getElementsByTagname('a')->item(0);
			if($a)
				$chapters[]=array('href'=>$a->getAttribute('href'),'name'=>$a->nodeValue);
		}
		
		return array($updates,$chapters);
	}
}
