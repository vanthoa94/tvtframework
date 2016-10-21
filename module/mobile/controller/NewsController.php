<?php 

class NewsController extends Controller{
	public function init(){

	}

	public function indexAction(){
		$this->view->title($this->module('application')->loadLanguage('menu')->item2);

		$start=0;
        $count=2;

        if($this->request->get('page')!=null){
            $start=$this->request->get('page');
        }

		$this->view->page=$this->load->library('Paginator');
		$this->view->page->setSumItem(6);
		$this->view->page->setItemInPage($count);

		$model=$this->module('application')->loadModel('News');

		$this->view->data=$model->getData($this->view->page->getStart(),$count);



		return $this->module('application')->loadView("news/index");
	}

	public function detailAction(){
		$this->view->title("Mobile - ".$this->module('application')->loadLanguage('menu')->item2." - ".$this->request->get('name'));

		$this->view->name=$this->request->get('name');
		$this->view->id=$this->request->get('id');

		return $this->module('application')->loadView("news/detail");
	}
}