<?
//$_temp = array_filter($args);
$_temp=$args;
$_mappinged_manga_id=null;
include('mp/url_mapping.php');
foreach($urlmaps as $foxname=>$parkname){
	if(sizeof($parkname)>0) {
		$_t = explode("/",$parkname[0]);
		$_t = array_pop($_t);
		$_t = substr($_t,0,strlen($_t)-5);
		//$_tÎªmangaparkµÄmanga_id
		if($_t==$args[1]){
			$_mappinged_manga_id=$foxname;
			break;
		}
	}
}
if(sizeof($_temp)==3&&$_temp[2]==""){
	$_GET['no_warning']=1;
	include('mp/url_mapping.php');
	if($_mappinged_manga_id)
		$args[1]=$_mappinged_manga_id;
	include('l_book.php');
}elseif($_mappinged_manga_id){
	include('mp/o_chapter.php');
}else
	include('l_chapter.php');