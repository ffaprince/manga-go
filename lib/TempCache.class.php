<?php

class TempCache {

	public $cacheDir = '/cache';
	public $lifeTime = 3600;
	public $dirLevels = 0;	//缓存保存时的目录层数
	
	private $maxDirLevels = 16;
	private $md5id;	//md5后的缓存id
	private static $instance=null;
	
	public static function instance($config){
		if(!self::$instance)
			self::$instance = new TempCache($config);
		else
			self::$instance->init($config);
		return self::$instance;
	}

	private function __construct($config=array())
	{
		$this->init($config);
	}

	private function init($config=array()){
		foreach ($config as $key=>$val) {
			$this->$key=$val;
		}
		
		if ($this->dirLevels>$this->maxDirLevels) {
			$this->dirLevels=$this->maxDirLevels;
		}
	}
	
	public function get($id)
	{
		$this->md5id=md5($id);
		$file=$this->getCacheFile($this->md5id);
		if (is_file($file) && (filemtime($file)+$this->lifeTime)>time()) {
			return unserialize(file_get_contents($file));
		} else {
			return false;
		}
	}

	public function remove(){
		$file=$this->getCacheFile($this->md5id);
		unlink($file);
	}
	
	public function save($data,$id=null)
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
}//class