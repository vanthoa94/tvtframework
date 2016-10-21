<?php 

class ValueLanguage{
	private $data;

	public function __construct($data){
		$this->data=$data;
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
}