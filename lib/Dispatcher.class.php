<?
class Dispatcher{
	
	public function dispatch(){
		global $appconfig;
		$uri = $_SERVER['REQUEST_URI'];
		if(strpos($uri,"/")===0){
			$uri = substr($uri,1);
		}
		if(($i = strpos($uri,"?"))!==false){
			$uri = substr($uri,0,$i);
		}
		$temp = explode("/",$uri);
//		$temp = array_filter($temp);  
		if($uri===false||$uri===""){
			require_once(DIR_ROOT.'/apps/l_index.php');
		}
		else{
			$app = $temp[1];
			array_shift($temp);//remove /r/
			array_shift($temp);//remove app
			$args = $temp;
//			$temp = parse_url($_SERVER['REQUEST_URI']);
//			parse_str($temp['query'],$getargs);
//			$args = array_merge($args,$getargs);
//			var_dump($args);exit;
			if(in_array($app,$appconfig))
				require_once(DIR_ROOT.'/apps/'.$app.'.php');
			else
				Util::redirect(WEB_ROOT.'/404.html');
		}
	}
}
