<?
require_once(dirname(dirname(__FILE__)). '/config.php');
include(DIR_ROOT."/apps/summary.php");
$result = array('i_accept_you','the_world_god_only_knows','the_one','naruto','gakuen_alice');
foreach($result as $m){
	Summary::updateSummary($m);	
	print($m."\r\n");
}
exit;