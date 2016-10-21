<?php 

class TCode{
	private $code=null;
	public function __construct($code){
		$this->code=$code;
	}
	public function get(){
		return $this->code;
	}
}