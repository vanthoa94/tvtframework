<?php 

class View{
	private $layout_file='';
	private $view_file='';
	private $var;
	private $layout=null;
	private $baseurl=null;
	private $assetu=null;
	private $t;
	private $meta=array();
	private $script=array();
	private $css=array();
	private $lang=null;
	private $config=null;
	private $moduleclass=null;
	private $module_names=null;
	private $requestClass=null;

	public function __construct(){
		$this->module_names=$GLOBALS['t_path'];
		
		require_once SYSTEM_PATH.'/mvc/view/Layout.php';
		$this->layout=new Layout($this);
	}

	public function setLayout($layout_file){
		$this->layout_file=$layout_file;
	}

	public function setLayoutClass($layout){
		$this->layout=$layout;
	}

	public function setView($view_file){
		$this->view_file=$view_file;
	}

	public function getLayoutFile(){
		return $this->layout_file;
	}

	public function getVar(){
		return $this->var;
	}

	public function getModuleTitle(){
		return $this->t;	
	}

	public function getModuleScript(){
		return $this->script;
	}

	public function getModuleCss(){
		return $this->css;
	}

	public function setModuleName($module_name){
		$this->module_names=$module_name;
	}

	public function setVar($var){
		$this->var=$var;
	}

	public function setModuleTitle($t){
		$this->t=$t;	
	}

	public function setModuleScript($script){
		$this->script=$script;
	}

	public function setModuleCss($css){
		$this->css=$css;
	}

	public function __set($key,$value){
		$this->var[$key]=$value;
	}

	public function __get($key){
		switch ($key) {
			case 'language':
				$this->createLanguage();
				return $this->lang;
			case 'lang':
				return $GLOBALS['lang'];
			case 'request':
				if($this->requestClass==null){
					if(!class_exists("Request")){
						require SYSTEM_PATH."mvc/controller/Request.php";

					}
					$this->requestClass=new Request();
				}
				return $this->requestClass;
			default:
				if(isset($this->var[$key])){
					return $this->var[$key];
				}
				break;
		}
		

		return null;
	}

	public function renderModuleView($path){
		require $path;
	}

	public function createLanguage(){
		if($this->lang==null){
			require_once SYSTEM_PATH."mvc/view/Language.php";
			$this->lang=new Language($this->asset(),$this->module_names);
		}
	}

	private function module($module_name=null){
		if($this->moduleclass==null){
			require_once SYSTEM_PATH."mvc/view/ModuleClass.php";
			$this->moduleclass=new ModuleClass($this);
		}
		return $this->moduleclass->setModule($module_name);
	}

	public function getModule($module_name){
		return $this->module($module_name);
	}

	public function disableLayout(){
		$this->layout_file='';
	}

	public function CallView(){
		if($this->layout_file!=''){
			$layout_path=$GLOBALS['t_path'].'/layout/'.$this->layout_file.EX_VIEW_FILE;
			if(!file_exists($layout_path)){
				return new TException('layout '.$this->layout_file.' không tồn tại<br /><br />File: config/config.php',401);
			}
			require $layout_path;
		}else{
			$this->runView();
		}
		
	}

	private function content(){
		$this->runView();
	}

	private function runView(){
		$view_path=$this->module_names.'/view/'.$this->view_file.EX_VIEW_FILE;
		if(!file_exists($view_path)){
			return new TException('view '.$this->view_file.EX_VIEW_FILE.' không tồn tại',401);
		}
		require $view_path;
	}

	public function layout(){
		return $this->getLayout();
	}

	public function getLayout(){
		return $this->layout;
	}
	
	private function renderView($path){
		$view_path=$this->module_names.'/view/'.$path.EX_VIEW_FILE;
		if(!file_exists($view_path)){
			return new TException('view '.$path.' không tồn tại',401);
		}
		require $view_path;
	}

	public function getViewFile(){
		return $this->view_file;
	}


	private function current_url(){
		return $_SERVER['REQUEST_URI'];
	}

	public function baseUrl($url=null){
		if($this->baseurl==null){
			if($GLOBALS['lang']==$GLOBALS['default_lang']){
				$this->baseurl=DIR;
			}else{
				$this->baseurl=DIR.$GLOBALS['lang']."/";
			}
			if($this->baseurl=="")
				$this->baseurl="/";
		}
		return $this->baseurl.$url;
	}

	public function pageUrl(){
		$pageURL = 'http';
         if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
         $pageURL .= "://";
         if ($_SERVER["SERVER_PORT"] != "80") {
             $pageURL .=
             $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
         }
         else {
             $pageURL .= $_SERVER["SERVER_NAME"];
         }

         return $pageURL;
	}

	public function fullUrl()
	{
	     
         return $this->pageUrl().$this->current_url();
	}

	public function title($title=null){
		if($title==null){
			echo "<title>".$this->t."</title>\n";
		}else{
			$this->t=$title;
		}
	}

	public function getTitle($title=null){
		if($this->t=='')
			return $title;
		else
			return $this->t.($title!=null?' | ':'').$title;
		
	}


	public function appendMeta($name=null,$content=null){
		if($name!=null){
			$this->meta($name,$content);
		}
	}

	public function meta($name=null,$content=null){
		if($name==null){
			foreach ($this->meta as $item) {
				echo "<meta".$this->implodearray($item)." />\n";
			}
		}else{
			if(is_array($name)){
				$this->meta[]=$name;
			}else{
				$this->meta[]=array('name'=>$name,'content'=>$content);
			}
		}
	}

	public function getModuleMeta(){
		return $this->meta;
	}

	public function setModuleMeta($meta){
		$this->meta=$meta;
	}

	public function appendScript($path=null){
		if($path!=null){
			$this->script($path);
		}
	}

	public function script($path=null){
		if($path==null){
			foreach ($this->script as $item) {
				echo '<script type="text/javascript" src="'.$item.'"></script>'."\n";
			}
		}else{
			$this->script[]=$path;
		}
	}

	public function appendCss($path=null){
		if($path!=null){
			$this->css($path);
		}
	}

	public function css($path=null){
		if($path==null){
			foreach ($this->css as $item) {
				echo '<link type="text/css" rel="stylesheet" href="'.$item.'"/>'."\n";
			}
		}else{
			$this->css[]=$path;
		}
	}

	private function implodearray($arr){
		$str='';
		foreach ($arr as $key => $value) {
			$str.=" ".$key.'="'.$value.'"';
		}
		return $str;
	}


	public function asset($path=''){
		if($this->assetu==null){
			if(strpos(ASSET_FOLDER, "http")===0)
				$this->assetu=ASSET_FOLDER;
			else
				$this->assetu=DIR.ASSET_FOLDER.'/';
		}
		return $this->assetu.$path;
	}
	

	private function showImage($path,$folder){
		if(strpos($path, 'http')===0)
			return $path;
		return $this->asset($folder.'/'.$path);
	}
	private function getDirPath(){
		return dirname($_SERVER['SCRIPT_FILENAME']);
	}

	private function cutText($text,$length){
		$lengtext=strlen($text);
		if($lengtext<=$length){
			return $text;
		}

		--$length;
		while($text[$length]!=' '){
			$length--;
		}
		if($length>0)
		return substr($text,0,$length)."...";
		return $text;
	}

	public function __call($method,$e){
		return new TException("Không tìm thấy method ".$method." trong class View",401);
	}

	public function __unset($key=null){
		if($key!=null){
			if(isset($this->var[$key]))
			unset($this->var[$key]);
		}else{
			unset($this->var);
		}
	}
}