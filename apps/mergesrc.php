<?
$mode = $args[0];
$files = array();
switch($mode){
	case 'alljs':
		 $files=array('/js/genre.js','/js/bookmark.js','/js/index.js');
		 header("Content-type: application/x-javascript");
		 break;
	case 'allcss':
		 $files=array('/css/index.css','/css/content.css','/css/header.css','/css/pagination.css');
		 header("Content-type: text/css");
		 break;
}
$output = "";
foreach($files as $k => $file) {
	$output .= "\n\n".file_get_contents(IMG_ROOT . $file);
}
echo($output);
