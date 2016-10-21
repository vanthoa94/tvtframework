<?php 

class Index extends Database{
	protected $table="student";

	public function getData(){
		return $this->select('id,fullname')->orderBy('id')->fetchAll();
	}

	public function getInfo($id){
		return $this->select('id,fullname')->where(array('id'=>$id))->row();
	}

	public function Count(){
		return $this->count('id');
	}
}