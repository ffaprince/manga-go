<?php

class HtmlCache {

	public $cacheDir = '/htmlcache';
	public $lifeTime = HTML_TIME;
	public $dirLevels = 2;	//缓存保存时的目录层数
	public $useCache = false;
	
	private $maxDirLevels = 16;
	private $md5id;	//md5后的缓存id
	private static $instance=null;
	
	public static function instance($time){
		if(!self::$instance)
			self::$instance = new HtmlCache(array('cacheDir'=>DIR_ROOT.'/htmlcache','lifeTime'=>$time));
		return self::$instance;
	}

	private function __construct($config=array())
	{
		foreach ($config as $key=>$val) {
			$this->$key=$val;
		}
		
		if ($this->dirLevels>$this->maxDirLevels) {
			$this->dirLevels=$this->maxDirLevels;
		}
	}
	
	public static function start($time)
	{
		$time = empty($time)?HTML_TIME:$time;
		if(!$time) return;
		$hc = HtmlCache::instance($time);
		$hc->useCache=true;
		$hc->md5id=md5($_SERVER['REQUEST_URI']);
		$file=$hc->getCacheFile($hc->md5id);
		if (is_file($file) && (filemtime($file)+$hc->lifeTime)>time()) {
			echo unserialize(file_get_contents($file));
			exit;
		} else {
			ob_start();   
            ob_implicit_flush(0);   
		}
	}

	public static function remove($url){
		$hc = HtmlCache::instance();
		$file=$hc->getCacheFile(md5($url));
		unlink($file);
	}

	public static function finish(){
		$hc = HtmlCache::instance();
		if($hc->useCache)
			$hc->save(ob_get_contents());
		ob_end_flush();
		exit;
	}
	
	private function save($data,$id=null)
	{
		$md5id=$id===null ? $this->md5id :md5($id);
		$dir=$this->cacheDir.$this->getDirLevel($md5id);
		if (!file_exists($dir)) {
			mkdir($dir,0777,true);
		}
		return file_put_contents($dir.'/'.$md5id,serialize($data));
	}
	
	private function getCacheFile($md5id)
	{
		return $this->cacheDir.$this->getDirLevel($md5id).'/'.$md5id;
	}
	
	private function getDirLevel($md5id)
	{
		$levels=array();
		$levelLen=2;
		for ($i=0; $i<$this->dirLevels; $i++) {
			$levels[]='cache_'.substr($md5id,$i*$levelLen,$levelLen);
		}
		return empty($levels) ? '/' : '/'.implode('/',$levels);
	}
}