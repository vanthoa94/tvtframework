<?php 

class LoginController extends Controller{
	
	public function init(){

	}

	public function indexAction(){
		$this->view->title($this->view->language->menu->item5);

		if($this->request->isPost()){
			echo "<pre>";
			print_r($this->request->post());
			echo "</pre>";
		}

		$this->view->appendScript($this->view->asset()."js/validate.js");
		$this->view->appendCss($this->view->asset()."css/validate.css");

		return $this->view();
	}
}