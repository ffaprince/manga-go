<?
function _getUserBm($user_id){
	$bm = Table::Fetch("user_bookmark",$user_id);
//		DB::LimitQuery('user_bookmark', array(
//			'select' => 'bookmark',
//			'condition' => array("user_id"=>$user_id),
//		//	'order' => 'ORDER BY begin_time DESC, id DESC',
//			'size' => 1,
//		//	'offset' => 0,
//		));
	if($bm){
		return $bm['bookmark'];
	}
	else
		return null;
}

function _outputBms($bms){
	foreach($bms as $k=>$bm){
		$bms[$k]['_time']=date('M d,g:i a',$bm['add_time']);
	}
	echo json_encode($bms);
}

if(!isLogin()){
	echo 'nologin';
	exit;
}
else{
	$u = $_SESSION['userinfo'];
	if($args[0]=='get'){
		$bms = json_decode(_getUserBm($u['email']),true);
		if($bms===null)
			$bms = array();
		_outputBms($bms);
	}elseif($args[0]=='add'){
		if(empty($_POST['manga_id'])||empty($_POST['chapter_id'])||empty($_POST['manga_name'])||empty($_POST['chapter_name'])||empty($_POST['page_id'])){
			echo "invalid";
			exit;
		}
		$bm = json_decode(_getUserBm($u['email']),true);
		$_POST['add_time']=time();
		if($bm===null){
			$bm = array();
			$bm[] = $_POST;
			DB::Insert('user_bookmark',array('id'=>$u['email'],'bookmark'=>json_encode($bm)));
//			Table::UpdateCache('user_bookmark',$u['email'],array('bookmark'=>json_encode($bm)));
		}else{
			$bm[]=$_POST;
			if(sizeof($bm)<=20){
				Table::UpdateCache('user_bookmark',$u['email'],array('bookmark'=>json_encode($bm)));
			}
//				DB::Update('user_bookmark',$u['email'],array('bookmark'=>json_encode($bm)));
			else{
				echo "max";
				exit;
			}
		}
		echo "ok";
	}elseif($args[0]='del'){
		$bm = json_decode(_getUserBm($u['email']),true);
		$nbm=array();
		if(sizeof($bm)>0){
			foreach($bm as $k=>$b){
				if($b['add_time']!=$_POST['add_time']) {
					$nbm[]=$b;
				}
			}
			Table::UpdateCache('user_bookmark',$u['email'],array('bookmark'=>json_encode($nbm)));
//			DB::Update('user_bookmark',$u['email'],array('bookmark'=>json_encode($nbm)),'id');
		}
		_outputBms($nbm);
	}else{
		echo "invalid action";
	}
}