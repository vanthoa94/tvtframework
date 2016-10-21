<?php 

class Layout{
	private $var;
	private $view;

	public function __construct($view){
		$this->view=$view;
	}

	public function disable(){
		$this->view->disableLayout();
	}

	public function setLayout($layout_name){
		$this->view->setLayout($layout_name);
	}

	public function set($layout_name){
		$this->view->setLayout($layout_name);
	}

	public function __set($key,$value){
		$this->var[$key]=$value;
	}

	public function __get($key){
		if(isset($this->var[$key])){
			return $this->var[$key];
		}

		return null;
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