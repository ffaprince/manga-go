<?
//search.php?is_completed=1&advopts=1
$args[0]='search.php';
$_GET['is_completed']=1;
$_GET['advopts']=1;

include("directory.php");
$completed_selected=true;

include('header.php');
echo get_outerhtml($left);
include('footer.php');