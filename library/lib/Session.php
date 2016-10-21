<?php 

class Session{
	public function __construct(){
		if(session_id() == '') {
   	 		session_start();
		}
	}
	public function __set($key,$value){
		if(is_string($key)){
			session_regenerate_id(true);
			$_SESSION[$key]=$value;
		}
		else
			return new TException($key." phải là string",401);
	}

	public function __get($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		return null;
	}

	public function __unset($key){
		if(isset($_SESSION[$key])){
			unset($_SESSION[$key]);
		}
	}

	public function removeAll(){
		session_destroy();
	}

	public function remove($key){
		if(isset($_SESSION[$key])){
			unset($_SESSION[$key]);
		}
	}

	public function getAll(){
		return $_SESSION;
	}

	public function has($key){
		return isset($_SESSION[$key]);
	}
}