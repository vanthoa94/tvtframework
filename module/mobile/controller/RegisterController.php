<?php 

class RegisterController extends Controller{
	public function init(){

	}
	public function indexAction(){
		$this->view->title("Mobile - ".$this->module('application')->loadLanguage('menu')->item4);
		$this->view->appendScript($this->view->asset()."js/validate.js");
		$this->view->appendCss($this->view->asset()."css/validate.css");
		
		$this->view->captcha=$this->load->library('captcha');

		return $this->module('application')->loadView("register/index");
	}
}