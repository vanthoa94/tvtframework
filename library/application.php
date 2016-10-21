
<?php 

/*
	Copyright 2015 By Tran Van Thoa
*/ 
	require_once SYSTEM_PATH."exception/TException.php";
	require_once SYSTEM_PATH."/mvc/controller/Controller.php";
	require_once SYSTEM_PATH."/mvc/TBoostrap.php";
	require_once SYSTEM_PATH.'/mvc/controller/Device.php';
	require_once SYSTEM_PATH.'/mvc/controller/Request.php';

	$GLOBALS['t_path']='';
	$GLOBALS['lang']='vi';
	$GLOBALS['default_lang']='';

	define("DIR",dirname($_SERVER['SCRIPT_NAME']).(dirname($_SERVER['SCRIPT_NAME'])=="/"?"":"/"));

	class Application{
		public function run(){
			if(isset($_GET['mobile'])){

				setcookie("mobile", $_GET['mobile'], time()+(60*60*24),"/");
				header('location: '.$this->UrlRemoveMobile());
			}

			$config=require "config/config.php";

			if(isset($config['default_lang'])){
				$GLOBALS['default_lang']=$config['default_lang'];
			}else{
				$GLOBALS['default_lang']=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0, 2);
			}

			$arr_module=require_once "config/module.php";
			$userLanguage=false;
			if(file_exists("config/language.php")){
				$arr_lang=require_once "config/language.php";
				if(is_array($arr_lang)){
					$lang=$GLOBALS['default_lang'];
					$userLanguage=true;
				}
			}

			$module_default=$this->searchModule($arr_module,'/');

			$path_info=$this->getPathInfo();

			if($path_info!='' && $path_info!='/'){
				$arr_url=explode("/", $path_info);

				if($userLanguage){

					foreach ($arr_lang as $value) {
						if($arr_url[1]==$value){
							$lang=$value;
							unset($arr_url[1]);
							$arr_url=array_values($arr_url);
							break;
						}
					}
				}

				$index_arr=1;

				$module_name='';
				if(isset($arr_url[$index_arr])){
					$modu="/".$arr_url[$index_arr];

					foreach ($arr_module as $key => $value) {
						if($key==$modu){
							$module_name=$value;
							$index_arr++;
							break;
						}
					}
				}

				if($module_name==''){
					$module_name=$module_default;
				}

				if(!$this->rewriteUrl($arr,$arr_url,$config,$module_name,$arr_module)){
					if(isset($arr_url[$index_arr]) && $arr_url[$index_arr]!=''){
						$arr['controller']=$this->create_controller($arr_url[$index_arr]);

						$at="index";
						if(isset($arr_url[$index_arr+1]) && $arr_url[$index_arr+1]!=''){
							$at=$arr_url[$index_arr+1];
							$arr['action']=strtolower($arr_url[$index_arr+1])."Action";
						}else{
							$arr['action']='indexAction';
						}

						$arr['view_file']=$arr_url[$index_arr]."/".$at;

						$arr['module']=$module_name;
					}else{
						$this->callDefault($config,$arr,$module_name);
					}

				}
			}else{
				$this->callDefault($config,$arr,$module_default);
			}
			if($userLanguage && $lang!='')
				$GLOBALS['lang']=$lang;
			$this->CallController($arr,$config);

		}

		private function callDefault($config,&$arr,$module_name){

			if(isset($config['default'])){
				$ct="index";
				if(isset($config['default']['controller'])){
					$arr['controller']=$this->create_controller($config['default']['controller']);
					$ct=$config['default']['controller'];
				}else{
					$arr['controller']='IndexController';
				}
				$at='index';
				if(isset($config['default']['action'])){
					$arr['action']=strtolower($config['default']['action'])."Action";
					$at=$config['default']['action'];
				}else{
					$arr['action']='indexAction';
				}
				$arr['view_file']=$ct."/".$at;
				$arr['module']=$module_name;
			}else{
				$arr['controller']='IndexController';
				$arr['action']='indexAction';
				$arr['view_file']='index/index';
				$arr['module']=$module_name;
			}
		}

		private function searchModule($arr_module,$k){
			$module=null;

			foreach ($arr_module as $key => $value) {
				if($key==$k){
					$module=$value;
				}
			}

			if(empty($module)){
				$module['e']=($k=='/')?'default(/)':$k;
				$module['e2']='.<br />Các module hiện có:';
				foreach ($arr_module as $key => $value) {
					$module['e2'].="<br /><b>+ ".$key.'</b>';
				}
			}

			return $module;
		}

		private function rewriteUrl(&$arr,$arr_url,$config,$module_name,$arr_module){
			$flag=false;

			if(isset($config['route'])){
				$url_str=implode('/', $arr_url);

				foreach ($config['route'] as $key => $value) {
					if($key==$url_str){
						$arr['controller']=$this->create_controller($value['controller']);
						$arr['action']=strtolower($value['action'])."Action";
						$arr['view_file']=$value['controller'].'/'.$value['action'];
						if(isset($value['module'])){
							$arr['module']=$this->searchModule($arr_module,$value['module']);
						}else{
							$arr['module']=$module_name;
						}
						$flag=true;
						break;
					}
					$match=$this->testRegex($key,$url_str);
					if($match!=null){
						$arr['controller']=$this->create_controller($value['controller']);
						$arr['action']=strtolower($value['action'])."Action";
						$arr['view_file']=$value['controller'].'/'.$value['action'];
						if(isset($value['module'])){
							$arr['module']=$this->searchModule($arr_module,$value['module']);
						}else{
							$arr['module']=$module_name;
						}
						$flag=true;
						for($i=1;$i<count($match);$i++){
							if(isset($value['param'][$i-1]))
								$_GET[$value['param'][$i-1]]= $match[$i];	
						}
						break;
					}
				}
			}

			return $flag;
		}

		private function testRegex($pattern,$subject){
			if(preg_match("/".$pattern."/", $subject,$match))
				return $match;
			return null;
		}

		private function CallController(array $arr,array $config){
			if(isset($arr['module']['e'])){
				return new TException('Không tìm thấy module <b>'.$arr['module']['e'].'</b> trong project của bạn'.$arr['module']['e2'],404);
			}

			$device=new Device();

			$module_n='';
			$module_p='';

			if(isset($arr['module']['mobile']) && $device->isMobile()){
				$module_n=$arr['module']['mobile'];
				$module_p=(isset($arr['module']['desktop'])?$arr['module']['desktop']:'');
			}else{
				if(isset($arr['module']['desktop'])){
					$module_n=$arr['module']['desktop'];
					$module_p=(isset($arr['module']['mobile'])?$arr['module']['mobile']:'');
				}
			}

			$GLOBALS['t_path']="module/".$module_n."/";

			$controller_path=$GLOBALS['t_path'].'controller/'.$arr['controller'].'.php';

			$flagg=false;

			if(!file_exists($controller_path)){
				if($module_p!=''){
					$GLOBALS['t_path']="module/".$module_p."/";
					$controller_path=$GLOBALS['t_path'].'controller/'.$arr['controller'].'.php';
					$flagg=true;
					if(!file_exists($controller_path))
						return new TException('Không tìm thấy file controller '.$arr['controller'].".php trong module ".$module_n,404);
				}else{
					return new TException('Không tìm thấy file controller '.$arr['controller'].".php",404);
				}	
			}

			require_once $controller_path;

			if(!class_exists($arr['controller'])){
				if(!$flagg)
					return new TException('Không tìm thấy controller '.$arr['controller'],404);
				else{
					if($module_p!=''){
						$GLOBALS['t_path']="module/".$module_p."/";
						$controller_path=$GLOBALS['t_path'].'controller/'.$arr['controller'].'.php';
						require_once $controller_path;
						if(!class_exists($arr['controller'])){
							return new TException('Không tìm thấy controller '.$arr['controller'],404);
						}
					}else{
						return new TException('Không tìm thấy controller '.$arr['controller'],404);
					}
				}

			}

			$controller=new $arr['controller']();

			if(!is_subclass_of($controller, 'Controller')){
				return new TException('Controller '.$arr['controller'].' phải kế thừa lớp Controller. <br />VD: <pre>class HomeController extend Controller{<br />	public function init(){<br /><br />	}<br />}</pre>',401);
			}

			if(!method_exists($controller, $arr['action'])){
				return new TException('action '.$arr['action'].' không tồn tại trong controller '.$arr['controller'],404);
			}

			$boostrap_file=$GLOBALS['t_path'].'/Bootstrap.php';

			if(!file_exists($boostrap_file)){
				return new TException('Không tìm thấy boostrap file',401);
			}

			require $boostrap_file;

			if(!class_exists("Bootstrap")){
				return new TException('Không tìm thấy class bootstrap file',401);
			}
			if(isset($config['layout'])){
				$controller->setLayout($config['layout']);
			}

			if(strpos($arr['action'], "post_")===0){
				
				$arr['action']=substr($arr['action'], 5);
				$arr['view_file']=str_replace("post_", "", $arr['view_file']);
			}

			$controller->setView($arr['view_file']);



			$boostrap=new Bootstrap($controller,$arr['controller'],$arr['action']);

			if(isset($config['db'])){
				$controller->setDb($config['db']);
			}

			if(isset($config['time_zone'])){
				date_default_timezone_set($config['time_zone']);
			}else{
				date_default_timezone_set('Asia/Ho_Chi_Minh');
			}

			$controller->init();


			if($_SERVER['REQUEST_METHOD']==="POST"){
				$postaction= "post_".$arr['action'];
				if(method_exists($controller,$postaction)){
					$r = new ReflectionMethod($arr['controller'], $postaction);
					$params = $r->getParameters();
					
					if(count($params)==1){
						$r=new Request();
						$controller->$postaction($r);
					}else{
						$controller->$postaction();
					}
				}else{
					$controller->$arr['action']();
				}
			}else{
				$controller->$arr['action']();
			}

		}

		private function create_controller($controller){
			$arr=explode("-", $controller);
			$controller_name='';
			foreach ($arr as $value) {
				$controller_name.=ucfirst(strtolower($value));
			}

			return $controller_name."Controller";
		}

		private function UrlRemoveLanguage(){
			$url=$_SERVER['REQUEST_URI'];

			return preg_replace("/(\?|&)lang=([a-zA-Z0-9]*)/", "", $url);
		}

		private function UrlRemoveMobile(){
			$url=$_SERVER['REQUEST_URI'];

			return preg_replace("/(\?|&)mobile=([a-zA-Z0-9]*)/", "", $url);
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

		public function __destruct(){
			$arr_error=error_get_last();

			if(count($arr_error)>0){
				return new TException($arr_error['message']."<p>File Lỗi: ".$arr_error['file']."</p><p>Dòng Lỗi: ".$arr_error['line']."</p>",401);
			}
			$arr_error=null;

		}

	}