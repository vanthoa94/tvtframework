<?php 

class TBoostrap{
	private $controller;
	private $controller_name;
	public function __construct(Controller $controller,$controller_name,$action_name){
		$this->controller=$controller;
		$this->controller_name=$controller_name;
		$this->action_name=$action_name;
		$this->init();
	}

	protected function init(){
		
	}

	public function __get($key){
		switch ($key) {
			case 'load':
				return $this->controller->load;
				break;
			case 'controller':
				return $this->controller;
				break;
			case 'layout':
				return $this->controller->view->getLayout();
				break;
			case 'view':
				return $this->controller->view;
			case 'lang':
				return $GLOBALS['lang'];
			case 'language':
				return $this->controller->language;
			default:
				return new TException("biến ".$key." không tồn tại trong class Boostrap",401);
				break;
		}
	}

	protected function getControllerName(){
		return $this->controller_name;
	}

	protected function getActionName(){
		return $this->action_name;
	}

	protected function getCurrentPath(){
		return $this->controller->getCurrentPath();
	}

	protected function redirect($path){
		$this->controller->redirect($path);
	}
}