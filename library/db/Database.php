<?php 

require_once SYSTEM_PATH."db/TDatabase.php";

class Database extends TDatabase{

	protected $table=null;

	protected function delete(array $where=null,$tb=null){
		return $this->clsDb->delete($where,$tb);
	}

	protected function update(array $arr,array $where=null,$tb=null){

		return $this->clsDb->update($arr,$where,$tb);

	}

	protected function insert(array $arr,$tb=null){
		return $this->clsDb->insert($arr,$tb);

	}

	public function TQuery($query){
		return $this->clsDb->TQuery($query);
	}
}