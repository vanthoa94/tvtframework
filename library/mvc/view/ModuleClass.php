<?php 

class ModuleClass{
	private $module=null;
	private $parent;
	public function __construct($parent){
		$this->parent=$parent;
	}
	public function setModule($module){
		$this->module=$module;
		return $this;
	}
	public function renderView($view_file){
		if($this->module!=null){
			$path="module/".$this->module."/view/".$view_file.EX_VIEW_FILE;
			if(file_exists($path)){
				$this->parent->renderModuleView($path);
			}else{
				return new TException("Không tìm thấy đường dẫn ".$view_file." trong project của bạn",401);
			}
		}else{
			return new TException("Vui lòng cho biết module cần truy cập. $this->module(module cân truy cập)",401);
		}
	}

	public function loadView($view_file){
		if($this->module!=null){
			$arr=explode("/", $view_file);
			if(count($arr)==1){
				$view_file.="/index";
			}
			$view=new View();
			$view->setModuleName("module/".$this->module);
			$view->setView($view_file);
			$view->setLayout($this->parent->getLayoutFile());
			$view->setLayoutClass($this->parent->getLayout());
			$view->setVar($this->parent->getVar());
			$view->setVar($this->parent->getVar());
			$view->setModuleMeta($this->parent->getModuleMeta());
			$view->setModuleTitle($this->parent->getModuleTitle());
			$view->setModuleCss($this->parent->getModuleCss());
			$view->setModuleScript($this->parent->getModuleScript());
			
			$view->CallView();
		}else{
			return new TException("Vui lòng cho biết module cần truy cập. $this->module(module cân truy cập)",401);
		}
	}

	public function loadModel($model_name){
		if($this->module!=null){
			$path="module/".$this->module."/model/".$model_name.".php";

			if(file_exists($path)){
				require_once SYSTEM_PATH.'/mvc/model/Model.php';
				$model=new Model($this->getDb());
				return $model->module_load_model($path,$model_name);
			}else{
				return new TException("Không tìm thấy đường dẫn ".$path." trong project của bạn",401);
			}
		}else{
			return new TException("Vui lòng cho biết module cần truy cập. $this->module(module cân truy cập)",401);
		}
	}

	private function getDb(){
		$config=require "config/config.php";
		$this->db=$config['db'];
	}

	public function loadLanguage($language_name){
		if($this->module!=null){
			$path="module/".$this->module."/language/".$GLOBALS['lang']."/".$language_name.".php";
			
			if(file_exists($path)){
				require_once SYSTEM_PATH."mvc/view/ValueLanguageModule.php";
				return new ValueLanguageModule(require $path,$this->parent->asset());
			}
			return new TException("Không tìm thấy đường dẫn ".$path." trong project của bạn",401);
		}else{
			return new TException("Vui lòng cho biết module cần truy cập. $this->module(module cân truy cập)",401);
		}
	}
}