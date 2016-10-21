<?php 

class RegisterController extends Controller{

	public function init(){

	}

	public function indexAction(){
		$this->view->title($this->view->language->menu->item4);


		if($this->request->isPost()){
			$validate=$this->load->library('Validate');
			$session=$this->load->library('Session');

			$validate->add('isCharacter',$this->request->post('fullname'),'FullName Wrong');
			$validate->add('isEmail',$this->request->post('username'),'Email Wrong');
			$validate->add('isNotEmpty',$this->request->post('password'),'Password Wrong');
			$validate->add('isEqual',$this->request->post('password'),$this->request->post('repassword'),'Retype password wrong');
			$validate->add('isEqual',$this->request->post('captcha'),$session->captcha,'Captcha wrong');
			
			$result=$validate->check();
			
			if($result!=''){
				$this->view->message=$result;
			}else{
				$this->request->unsetPost();
				$this->view->message="Successfully";
			}
		}

		$this->view->appendScript($this->view->asset()."js/validate.js");
		$this->view->appendCss($this->view->asset()."css/validate.css");

		$this->view->captcha=$this->load->library('captcha');

		return $this->view();
	}
}