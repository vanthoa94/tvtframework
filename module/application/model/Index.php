<?php 

class Index extends Database{
	protected $table="san_pham";

	public function Add($data){
		return $this->insert($data);
	}

	public function Edit($data,$id){
		return $this->where(array('id'=>$id))->update($data);
	}

	public function Remove($data,$id){
		return $this->where(array('id'=>$id))->delete();
	}

	public function getAll(){
		//return $this->select('id,fullname')->orderBy('id')->fetchAll();
		return $this->fetchAll();
	}

	public function getA(){
		//return $this->select('id,fullname')->orderBy('id')->fetchAll();
		return $this->limit(0,5)->fetchAll();
	}

	public function getLimit($start,$count){
		return $this->select('id,fullname')->orderBy('id')->limit($start,$count)->fetchAll();
	}

	public function getNotWhere($id){
		return $this->select('id,fullname')->not_where(array('id'=>$id))->fetchAll();
	}

	public function getInfo($id){
		return $this->select('id,fullname')->where(array('id'=>$id))->row();
	}

	public function getFullInfo($id){
		return $this->select('id,fullname,class.classname')->join('class','student.classid=class.classid')->where(array('id'=>$id))->row();
	}

	public function CountAll(){
		return $this->count('id');
	}
}