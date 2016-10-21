<?php 

class TException{
	private $message;
	private $error_number;
	private $title;
	public function __construct($message='Không tìm thấy trang',$error_number=404,$title="Có lỗi trong quá trình sử lý"){
		$this->message=$message;
		$this->error_number=$error_number;
		$this->title=$title;

		if($error_number==401 && ENVIRONMENT=='production'){
			$error_number=402;
		}
		else{
			if($error_number==404 && ENVIRONMENT=='development'){
				$error_number=401;
			}
		}
		$path=realpath(dirname($_SERVER['SCRIPT_FILENAME']))."/".$GLOBALS['t_path'].'view/error/'.$error_number.EX_VIEW_FILE;
		ob_clean();
		if(file_exists($path)){
			include_once $path;
		}else{
			include_once realpath(dirname(__FILE__).'/view').'/'.$error_number.".phtml";
		}

		exit("");
	}

	private function getMessage(){
		return $this->message;
	}
	private function getErrorNumber(){
		return $this->error_number;
	}
	private function getTitle(){
		return $this->title;
	} 

	public function renderModuleView($path){
		require $path;
	}

	private function module($module_name){
		if(!class_exists('ModuleClass'))
		require_once SYSTEM_PATH."mvc/view/ModuleClass.php";
		$moduleclass=new ModuleClass($this);
		return $moduleclass->setModule($module_name);
	}
}