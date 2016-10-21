<?php 

class IndexController extends Controller{

	public function init(){
		
	}
	
	public function indexAction(){
		$this->view->title("admin control panel");
		
		return $this->view();
	}


}