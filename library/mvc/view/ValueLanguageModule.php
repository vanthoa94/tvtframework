<?php 

class ValueLanguageModule{
	private $data,$url_asset,$language;
	private $lurl=null;


	public function __construct($data,$url_asset){
		$this->data=$data;
		$this->url_asset=$url_asset;
		$this->language=$GLOBALS['lang'];
	}

	public function __get($key){
		if(isset($this->data[$key])){
			return $this->data[$key];
		}

		return null;
	}

	public function all(){
		return $this->data;
	}

	public function url($lang){
		if($this->lurl==null){
			$arr=explode("/", $this->getPathInfo());
			$first=true;
			foreach ($arr as $value) {
				if($value!=$this->language){
					if(!$first)
						$this->lurl.="/".$value;
					else{
						$this->lurl.=$value;
						$first=false;
					}
				}
			}
		}
		return DIR.$lang.$this->lurl;
		
	}

	private function getPathInfo(){
		$url=$_SERVER['REQUEST_URI'];
		$length=strlen(dirname($_SERVER['SCRIPT_NAME']));
		if($length>1){
			$url=substr($url,$length);
		}
		$sp_url=explode("?", $url);
		$sp_url[0]=str_replace("/index.php", "", $sp_url[0]);
		return $sp_url[0];
	}

	public function show($co=false,$icon_path='images/language'){
		foreach ($this->all() as $key => $value) {
			echo "<li ".(($this->language==$key)?"class='active'":"").">";
			if($co){
				echo "<img src='".$this->url_asset.$icon_path."/".$key.".png' />";
			}
			if($this->language!=$key)
				echo "<a href='".$this->url($key)."'>".$value."</a> ";
			else
				echo "<span >".$value."</span> ";
			echo "</li>";
		}
	}
}