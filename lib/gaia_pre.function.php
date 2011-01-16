<?php
class GaiaPreTR{
	var $str;
	var $lineNum;
	var $stLineNum=null;
	var $edLineNum;
	var $ns;
	public function __construct($n){
		$this->ns=$n;
	}
	public function createTr(){
		$t = "<div ns='$this->ns' id='$this->ns"."_print_r_$this->lineNum' ".($this->stLineNum!==NULL?"stLine='$this->stLineNum' edLine='$this->edLineNum' onclick='__expand(this)' show='block'":"").">$this->str</div>";
		return $t;
	}
}
function gaia_pre($args) {
	// output the arrays for testing / viewing. reset arrays
	// after viewing. will take up to 5 incoming arrays..
	// dan - upated this to work on objects too
	//		if ( (bool) SC::get('board_config.local_domain_enable') === TRUE )
	{
			
		// if we're set up for a log file, record to that instead...
		if ( defined('GAIA_PRE_LOG_FILE') ) {
			ob_start();
		}
			
		// dump out the desired information...
		echo "<div align=\"left\"><pre>";$i=0;
		foreach ($args as $argument) {
			$ns=rand(10000000000,90000000000);
			if ($i>0) echo "\n-----------------------------------------\n\n";
			if (is_array($argument) || is_object($argument)) {
				//								printr($argument);
				printr($argument,$ns);
			} else {
				//$v = var_export($argument, true);
				var_dump($argument);
			}
				
			$i++;
		} // end-foreach
		echo "</pre></div>";
		echo "<script>function __expand(tr){ var ns=tr.getAttribute('ns');var stLine=tr.getAttribute('stLine')*1;var edLine=tr.getAttribute('edLine')*1;var show = tr.getAttribute('show')=='block'?'none':'block';tr.setAttribute('show',show);tr.style.backgroundColor=show=='block'?'':'yellow'; for (var i=stLine+1;i<=edLine;i++){document.getElementById(ns+'_print_r_'+i).style.display=show;temp=document.getElementById(ns+'_print_r_'+i);if (temp.getAttribute('show')=='none'){i=i+1+temp.getAttribute('edLine')*1-temp.getAttribute('stLine')*1;};}}</script>";
		// if we're using a log file, save the information to it now...
		if ( defined('GAIA_PRE_LOG_FILE') ) {
			$log_file_output = ob_get_contents();
			ob_end_clean();
			define('FILE_APPEND',TRUE);
			$file_write_status = file_put_contents(GAIA_PRE_LOG_FILE,$log_file_output,FILE_APPEND);
			// last-ditch try to get someone's attention if the file write fails...
			if ( $file_write_status === FALSE ) echo $log_file_output;
		}

	} // end-if
} // end function gaia_pre()
//gaia_pre(array(new Aaa(),"asdf"));
function printr($arg,$ns){
	$re = print_r($arg,true);
	//echo $re;
	//print_r(get_object_vars(new Aaa()));
	$rearray = explode("\n",$re);
	$trs=array();
	$stack=array();
	$plus="+";
	foreach($rearray as $x=>$r){
		$tr = new GaiaPreTR($ns);
		$tr->str=$r;
		$tr->lineNum=$x;
		if (strpos($r,"Object")===strlen($r)-6||strpos($r,"Array")===strlen($r)-5){
			$tr->stLineNum=$x;
			array_push($stack,$tr);
		}
		elseif (trim($r)===")"){
			$temp = array_pop($stack);
			$temp->edLineNum=$x;
		}
		array_push($trs,$tr);
	}
	foreach($trs as $tr){
		echo $tr->createTr();
	}
}
?>