<?php 

class Request{
	public function get($key=null){
		if($key==null){
			return $_GET;
		}
		if(isset($_GET[$key])){
			return $_GET[$key];
		}
		return null;
	}
	public function isPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	public function isGet(){
		return $_SERVER['REQUEST_METHOD']=='GET';
	}
	public function post($key=null){
		if($key==null){
			return $_POST;
		}
		if(isset($_POST[$key])){
			return $_POST[$key];
		}
		return null;
	}
	public function getParam($key=null){
		if($key==null){
			return new TException("getParam() không tồn tại. <pre>public function getParam(string key)</pre>",401);
		}
		if(!is_string($key)){
			return new TException("getParam(".gettype($key)." key) không tồn tại. Thử Lại với function dưới<br /> <pre>public function getParam(string key)</pre>",401);
		}
		if(isset($_POST[$key])){
			return $_POST[$key];
		}
		if(isset($_GET[$key])){
			return $_GET[$key];
		}
		return null;
	}

	public function unsetPost(){
		$_POST=array();	
	}

	public function unsetGet(){
		$_GET=array();	
	}

	public function all(){
		if($this->isGet())
			return $this->get();
		return $this->post();
	}
}