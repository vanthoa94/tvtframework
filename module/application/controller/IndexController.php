<?php 

class IndexController extends Controller{

	public function init(){
		
	}
	
	public function indexAction(){
		
		//use cache
		//$this->cache->run();


		$this->view->title($this->language->menu->item1);

		$model=$this->load->model("Index");
	
		


		return $this->view();
	}

	public function post_indexAction(Request $r){
		print_r($r->all());
	}


}