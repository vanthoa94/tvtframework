<?php 
require_once SYSTEM_PATH."db/Database.php";
require_once SYSTEM_PATH."db/TMd5.php";
require_once SYSTEM_PATH."db/TTimeNow.php";
require_once SYSTEM_PATH."db/TCode.php";

class Model{
	private $db=null;
	public function __construct($db){
		if($db!=null){
			$this->db=$db;
		}
	}

	public function load($model,$options=null){
		return $this->requireModel($GLOBALS['t_path']."model/".$model.".php",$model,$options);
	}

	public function module_load_model($path,$model,$options=null){
		return $this->requireModel($path,$model,$options);
	}

	private function requireModel($path,$model,$options){
		if(!class_exists($model)){
			if(file_exists($path)){
				require $path;
			}else{
				return new TException("Không tìm thấy đường dẫn ".$path,401);
			}
		}

		if(!class_exists($model)){
			return new TException("Không tìm thấy model ".$model,401);
		}
		
		$cmodel=null;
		if($options==null)
			$cmodel=new $model();
		else
			$cmodel=new $model($options);
		if($this->db!=null){
			if(method_exists($cmodel, "connect")){
				$cmodel->connect($this->db);
			}
		}
		return $cmodel;
	}
}