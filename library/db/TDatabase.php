<?php 

require_once SYSTEM_PATH."db/ConnectDb.php";

class TDatabase extends ConnectDb{


	protected function select($columns=null){
		return $this->clsDb->select($columns);
	}

	protected function from($from=null){
		return $this->clsDb->from($from);
	}

	protected function where(array $where=null,$r='AND'){
		return $this->clsDb->where($where,$r);
	}

	protected function not_where(array $where=null,$r='AND'){
		return $this->clsDb->not_where($where,$r);
	}

	protected function larger_where(array $where=null,$r='AND'){
		return $this->clsDb->larger_where($where,$r);
	}

	protected function less_where(array $where=null,$r='AND'){
		return $this->clsDb->less_where($where,$r);
	}
	
	protected function in($column=null,$value=null,$r='AND'){
		return $this->clsDb->in($column,$value,$r);
	}

	protected function not_in($column=null,$value=null,$r='AND'){
		return $this->clsDb->not_in($column,$value,$r);
	}

	protected function orderBy($order=null){
		return $this->clsDb->orderBy($order);
	}

	protected function Query($query){
		return $this->clsDb->Query($query);
	}

	protected function limit($start=null,$count=null){
		
		return $this->clsDb->limit($start,$count);
	}

	protected function join($tb=null,$on=null){
		
		return $this->clsDb->join($tb,$on);
	}

	protected function leftJoin($tb=null,$on=null){
		return $this->clsDb->leftJoin($tb,$on);
	}

	protected function rightJoin($tb=null,$on=null){
		return $this->clsDb->rightJoin($tb,$on);
	}


	protected function groupBy($g=null){
		return $this->clsDb->groupBy($g);
	}

	protected function having($h=null){
		return $this->clsDb->having($h);
	}

	protected function like($cl,$vl,$l=null){
		return $this->clsDb->like($cl,$vl,$l);
	}

	protected function begin_like($cl,$vl,$l=null){
		return $this->clsDb->begin_like($cl,$vl,$l);
	}

	protected function en_like($cl,$vl,$l=null){
		return $this->clsDb->en_like($cl,$vl,$l);
	}

	protected function no_like($cl,$vl,$l=null){
		return $this->clsDb->no_like($cl,$vl,$l);
	}

	protected function beginGroup($l='AND'){
		return $this->clsDb->beginGroup($l);
	}

	protected function endGroup(){
		return $this->clsDb->endGroup();
	}

	protected function fetchAll(array $where=null,$count=null){
		return $this->clsDb->fetchAll($where,$count);
	}

	protected function toArray(){
		return $this->clsDb->toArray();
	}

	protected function row(){
		return $this->clsDb->row();
	}

	protected function row_array(){
		return $this->clsDb->row_array();
	}

	protected function column($column=0){
		return $this->clsDb->column($column);
	}

	protected function exists($cl=null){
		return $this->clsDb->exists($cl);
	}

	protected function count($cl=null){
		return $this->clsDb->count($cl);
	}


	protected function sum($cl=null){
		return $this->clsDb->sum($cl);
	}

	protected function avg($cl=null){
		return $this->clsDb->avg($cl);
	}

	protected function max($cl=null){
		return $this->clsDb->max($cl);
	}

	protected function min($cl=null){
		return $this->clsDb->min($cl);
	}

}