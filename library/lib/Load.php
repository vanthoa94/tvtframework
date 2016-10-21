<?php 

class Load{
	private $db;
	private $model=null;
	public function __construct($db){
		$this->db=$db;
	}
	public function library($library){
		if(gettype($library)=="string"){
			$library=ucfirst($library);
			if(file_exists(SYSTEM_PATH."lib/".$library.".php")){
				require_once SYSTEM_PATH."lib/".$library.".php";

				if(class_exists($library)){
					return new $library();
				}
			}
		}
		return new TException("Không tìm thấy library ".$library,401);
	}
	public function model($modelname,$options=null){
		if(gettype($modelname)=="string"){
			if($this->model==null){
				require SYSTEM_PATH.'/mvc/model/Model.php';
				$this->model=new Model($this->db);
			}
			return $this->model->load($modelname,$options);
		}
		return new TException("Không tìm thấy model ".$modelname,401);
	}
	public function __call($method,$args){
		return new TException('Phương thức '.$method.' không có trong class Load.',401);
	}


	public function __destruct(){
		$this->model=null;
	}
}