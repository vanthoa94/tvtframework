<?php 

class IndexController extends Controller{

	public function init(){
		
	}
	
	public function indexAction(){
		$this->view->title("Mobile - ".$this->module('application')->loadLanguage('menu')->item1);
		
		return $this->module('application')->loadView("index/index");
	}


}