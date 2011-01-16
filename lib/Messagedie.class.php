<?
class Messagedie{
	static public function myErrorHandler()	{
		if(is_null($e = error_get_last()) === false) {
			if($e['type']==1){
				self::setErrrLog($e);
				include(DIR_ROOT.'/apps/header.php');
				?>
					<div id="error" style=' height: 217px;
					margin: 0 auto;
					position: relative;
					width: 492px;'>
					<p id="attention"><span style="background: url(&quot;/media/info.png&quot;) no-repeat scroll left center transparent;">Sorry, the page you have requested is not available yet.</span></p>
					<div style="padding: 20px;">
					<div class="left ren"></div>
					<div class="left" style='200px'><a target="_self" href="<?=WEB_ROOT?>">Go Visit Our Homepage</a>
				</div>
					</div>
				  </div>
				<?
				include(DIR_ROOT.'/apps/footer.php');
			}
		}
	}

	static private function setErrrLog($e){
		DB::Insert('error_log',array('message'=>$e['message'],'file'=>$e['file'],'line'=>$e['line'],'date'=>date('ymdHis',time())));
	}
}