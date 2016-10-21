<?php 

require SYSTEM_PATH."mvc/view/ValueLanguage.php";

class Language{
	private $arr=array();
	private $lurl=null;
	private $url_asset=null;
	private $module_name=null;
	private $language=null;

	public function __construct($url_asset,$module_name){
		$this->language=$GLOBALS['lang'];
		$this->url_asset=$url_asset;
		$this->module_name=(empty($module_name))?$GLOBALS['t_path']:$module_name;
	}

	public function __get($key){
		if(!isset($arr[$key])){
			$path=$this->module_name."/language/".$this->language."/".$key.".php";
			if(file_exists($path))
				$arr[$key]=new ValueLanguage(require $path);
			else
				return new TException("Không tìm thấy file ".$key.".php trong language ".$this->language,401);
		}

		return $arr[$key];
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

	public function show($key='list_lang',$co=false,$text=true,$icon_path='images/language'){
		foreach ($this->$key->all() as $key => $value) {
			echo "<li ".(($this->language==$key)?"class='active'":"").">";
			if($co){
				if($text)
					echo "<img src='".$this->url_asset.$icon_path."/".$key.".png' />";
				else{
					if($this->language!=$key)
						echo "<a title='".$value."' href='".$this->url($key)."'><img src='".$this->url_asset.$icon_path."/".$key.".png' /></a>";
					else
						echo "<span title='".$value."'><img src='".$this->url_asset.$icon_path."/".$key.".png' /></span>";
				}
			}
			if($this->language!=$key){
				if($text)
					echo "<a href='".$this->url($key)."'>".$value."</a> ";
				
			}
			else{
				if($text)
				echo "<span >".$value."</span> ";
			}
			echo "</li>";
		}
	}
}