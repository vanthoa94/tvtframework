<?php 

class LoginController extends Controller{
	public function init(){

	}

	public function indexAction(){
		$this->view->title("Mobile - ".$this->module('application')->loadLanguage('menu')->item5);
		$this->view->appendScript($this->view->asset()."js/validate.js");
		$this->view->appendCss($this->view->asset()."css/validate.css");
		
		return $this->module('application')->loadView("login/index");
	}
}