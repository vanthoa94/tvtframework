<?php 

class Cache{
	private $cachetime = 30;
	private $filename="";
	private $save=false;
	public function __construct($file){
		$this->filename=dirname($_SERVER['SCRIPT_FILENAME'])."/".$GLOBALS['t_path']."cache/".str_replace("/", "_", $file)."_".$GLOBALS['lang'].".html";
		
	}
	public function run(){
		if (file_exists($this->filename) && time() - $this->cachetime < filemtime($this->filename)) { 
		 	include($this->filename);
		 	exit;
		}
		
		$this->save=true;
		ob_start();
	}

	public function setTime($t){
		if(is_integer($t))
			$this->cachetime=$t;
		else
			return new TException('Không tìm thấy function setTime('.gettype($t).'); Vui lòng thử với <pre>public function setTime(int time)</pre>');
	}

	public function isSave(){
		return $this->save;
	}

	public function getFileCache(){
		return $this->filename;
	}
}