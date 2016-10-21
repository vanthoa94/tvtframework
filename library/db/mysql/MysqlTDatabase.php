<?php 

require_once SYSTEM_PATH."db/mysql/ConnectMySql.php";

class MysqlTDatabase extends ConnectMySql{
	
	protected $table=null;


	protected $_table=null;
	protected $_columns=null;
	protected $_from=null;
	protected $_where="";
	protected $_order=null;
	protected $_limit=null;
	protected $_join="";
	protected $_groupBy=null;
	protected $_having=null;
	protected $_query=null;

	private $beginGroup=false;

	public function setTable($tb){
		$this->table=$tb;
	}
	

	public function select($columns=null){
		if($columns!=null){
			$this->_columns=$columns;
		}else{
			$this->_columns="*";
		}
		return $this;
	}

	public function from($from=null){
		if($from!=null){
			$this->_table=$from;
		}
		return $this;
	}

	public function where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r);
	}

	public function not_where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r,'<>');
	}

	public function larger_where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r,'>');
	}

	public function less_where(array $where=null,$r='AND'){
		return $this->tWhere($where,$r,'<');
	}

	protected function tWhere(array $where=null,$r='AND',$k='='){
		if($where!=null){

			$r=trim($r);

			$w="";

			foreach ($where as $key => $value) {
				if(gettype($value)=="string")
					$w.=sprintf("%s%s'%s' %s ",mysqli_real_escape_string($this->conn,$key),$k,mysqli_real_escape_string($this->conn,$value),$r);
				else{
					if($value instanceof TMd5){
						$w.=sprintf("%s%smd5(%s) %s ",mysqli_real_escape_string($this->conn,$key),$k,mysqli_real_escape_string($this->conn,$value->value),$r);
					}else{
						if($value instanceof TTimeNow){
							$w.=sprintf("%s%snow() %s ",mysqli_real_escape_string($this->conn,$key),$k,$r);
						}else{
							if($value instanceof TCode){
								if($key=="" || is_integer($key))
									$w.=sprintf("%s %s ",mysqli_real_escape_string($this->conn,$value->get()),$r);
								else
									$w.=sprintf("%s%s%s %s ",mysqli_real_escape_string($this->conn,$key),$k,mysqli_real_escape_string($this->conn,$value->get()),$r);
							}else
							$w.=sprintf("%s%s%s %s ",mysqli_real_escape_string($this->conn,$key),$k,mysqli_real_escape_string($this->conn,$value),$r);
						}
					}
					
				}
			}
			$w=substr($w, 0,strlen($w)-strlen($r)-2);

			if($this->_where==""){
				$this->_where=$w;
			}else{
				$this->_where.=" ".$r." ".$w;
			}
		}
		
		return $this;
	}

	public function in($column=null,$value=null,$r='AND'){
		if(!empty($column) && !empty($value)){
			if($this->_where==""){
				$this->_where=$column." in(".$value.")";
			}else{
				$this->_where.=" ".$r." ".$column." in(".$value.")";
			}
		}
		return $this;
	}

	public function not_in($column=null,$value=null,$r='AND'){
		if(!empty($column) && !empty($value)){
			
			if($this->_where==""){
				$this->_where=$column." not in(".$value.")";
			}else{
				$this->_where.=" ".$r." ".$column." not in(".$value.")";
			}
		}
		return $this;
	}

	public function orderBy($order=null){
		if($order!=null){
			$order=explode(";", $order);
			$this->_order=mysqli_real_escape_string($this->conn,$order[0]);
		}
		return $this;
	}

	public function Query($query){
		if(is_string($query)){
			$this->_query=$query;
		}
		return $this;
	}

	public function limit($start=null,$count=null){
		
		if($start==null && $count==null)
			return $this;

		if($start!=null){
			$this->_limit=(mysqli_real_escape_string($this->conn,(string)$start)).','.(mysqli_real_escape_string($this->conn,(string)$count));	
		}else{
			$this->_limit='0,'.mysqli_real_escape_string($this->conn,(string)$count);
		}
		return $this;
	}

	public function join($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' inner join '.$tb.' on '.$on;
		
		return $this;
	}

	public function leftJoin($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' left outer join '.$tb.' on '.$on;
		
		return $this;
	}

	public function rightJoin($tb=null,$on=null){
		
		if($tb==null || $on==null)
			return $this;

		$this->_join.=' right outer join '.$tb.' on '.$on;
		
		return $this;
	}


	public function groupBy($g=null){
		if($g!=null){
			$this->_groupBy=mysqli_real_escape_string($this->conn,$g);
		}
		return $this;
	}

	public function having($h=null){
		if($h!=null){
			$this->_having=mysqli_real_escape_string($this->conn,$h);
		}
		return $this;
	}

	public function like($cl,$vl,$l=null){
		if(!isset($cl) || !isset($vl))
			return $this;

		if($vl==null)
			return $this;
		
		if($this->_where==""){
			$this->_where=$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."%'";
		}else{
			if(!isset($l)){
				$l="AND";
			}
			if($this->beginGroup){
				$l="";
				$this->beginGroup=false;
			}
			$this->_where.=" ".$l." ".$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."%'";
		}
		return $this;
	}

	public function begin_like($cl,$vl,$l=null){
		if(!isset($cl) || !isset($vl))
			return $this;

		if($vl==null)
			return $this;
		
		if($this->_where==""){
			$this->_where=$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."'";
		}else{
			if(!isset($l)){
				$l="AND";
			}
			if($this->beginGroup){
				$l="";
				$this->beginGroup=false;
			}
			$this->_where.=" ".$l." ".$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."'";
		}
		return $this;
	}

	public function en_like($cl,$vl,$l=null){
		if(!isset($cl) || !isset($vl))
			return $this;

		if($vl==null)
			return $this;
		
		if($this->_where==""){
			$this->_where=$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."%'";
		}else{
			if(!isset($l)){
				$l="AND";
			}
			if($this->beginGroup){
				$l="";
				$this->beginGroup=false;
			}
			$this->_where.=" ".$l." ".$cl." like '%".mysqli_real_escape_string($this->conn,$vl)."%'";
		}
		return $this;
	}

	public function no_like($cl,$vl,$l=null){
		if(!isset($cl) || !isset($vl))
			return $this;

		if($vl==null)
			return $this;
		
		if($this->_where==""){
			$this->_where=$cl." like '% ".mysqli_real_escape_string($this->conn,$vl)." %'";
		}else{
			if(!isset($l)){
				$l="AND";
			}
			if($this->beginGroup){
				$l="";
				$this->beginGroup=false;
			}
			$this->_where.=" ".$l." ".$cl." like '% ".mysqli_real_escape_string($this->conn,$vl)." %'";
		}
		return $this;
	}

	public function beginGroup($l='AND'){
		if($this->_where!=""){
			$this->_where.=" ".$l." ( ";
		}else{
			$this->_where=" ( ";
		}
		$this->beginGroup=true;
		return $this;
	}

	public function endGroup(){
		if($this->_where!=""){
			$this->_where.=" ) ";
			$this->beginGroup=false;
		}
		return $this;
	}

	public function fetchAll(array $where=null,$count=null){
		
		if($where!=null){
			$this->where($where);
		}

		if($count!=null){
			$this->limit(null,$count);
		}

		return $this->toArray();
	}

	public function toArray(){
		
		$this->CreateQuery();

		$result=mysqli_query($this->conn,$this->_query);
		$this->_query=null;

		if($result){


			$arr=array();

			while($row=mysqli_fetch_assoc($result)){
				$arr[] = $row;
			}

			return $arr;
		}
		return null;
	}

	public function row(){
		$this->CreateQuery();

		$result=mysqli_query($this->conn,$this->_query);
		$this->_query=null;

		if($result){
			$row=mysqli_fetch_assoc($result);
			return $row;
		}
		return null;
	}

	public function row_array(){
		$this->CreateQuery();

		$result=mysqli_query($this->conn,$this->_query);

		$this->_query=null;

		if($result){
			$row=mysqli_fetch_array($result);
			return $row;
		}
		return null;
	}

	public function column($column=0){
		$row=$this->row_array();
		if($row!=null){
			return $row[$column];
		}
		return null;
	}

	protected function f(){
		$result=mysqli_query($this->conn,$this->_query);
		$this->_query=null;

		$count=0;
		if($result){
			$count=mysqli_fetch_assoc($result);
			return $count['c'];
		}
		return -1;
	}

	public function exists($cl=null){
		$sl=$this->count($cl);
		return ($sl==0 || $sl==-1)?false:true;
	}

	public function count($cl=null){
		
		if($cl==null)
			$cl="*";

		$this->CreateQuery('count',$cl);

		return (int)$this->f();
	}


	public function sum($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('sum',$cl);

		return (int)$this->f();
	}

	public function avg($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('avg',$cl);

		return (int)$this->f();
	}

	public function max($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('max',$cl);

		return (int)$this->f();
	}

	public function min($cl=null){
		
		if($cl==null)
			return 0;

		$this->CreateQuery('min',$cl);

		return (int)$this->f();
	}


	protected function CreateQuery($v=null,$v2=null){
		if($this->_query==null){
			if($this->_columns==null)
				$this->_columns="*";
			$tb=$this->table;
			if($this->_table!=null)
				$tb=$this->_table;
			
			if($v==null)
				$this->_query="select ".$this->_columns;
			else
				$this->_query="select ".$v."(".$v2.") as c";

			$this->_query.=" from ".$tb;

			$this->_columns=null;
			$this->_table=null;

			if($this->_join!=""){
				$this->_query.=$this->_join;
				$this->_join="";
			}

			if($this->_where!=""){
				$this->_query.=" where ".$this->_where;
				$this->_where="";
			}

			if($this->_groupBy!=null){
				$this->_query.=" group by ".$this->_groupBy;
				$this->_groupBy=null;
			}

			if($this->_having!=null){
				$this->_query.=" having ".$this->_having;
				$this->_having=null;
			}

			if($this->_order!=null){
				$this->_query.=" order by ".$this->_order;
				$this->_order=null;
			}

			if($this->_limit!=null){
				$this->_query.=" limit ".$this->_limit;
				$this->_limit=null;
			}
		}
	}

	protected function gett($value,$key=null){
		switch (gettype($value)) {
			case 'string':
				if($key==null)
					$this->_query.="'".$value."',";
				else
					$this->_query.=$key."='".$value."',";
				break;
			case 'boolean':
				if($key==null)
					$this->_query.="b'".$value."',";
				else
					$this->_query.=$key."=b'".$value."',";
				break;
			case 'integer':
				if($key==null)
					$this->_query.=$value.",";
				else
					$this->_query.=$key."=".$value.",";
				break;
			default:
				if($value instanceof TMd5){
					if($key==null)
						$this->_query.="md5('".$value->value."'),";
					else
						$this->_query.=$key."=md5('".$value->value."'),";
				}else{
					if($value instanceof TTimeNow){
						if($key==null)
							$this->_query.="CONVERT_TZ(NOW(),@@session.time_zone,'+07:00'),";
						else
							$this->_query.=$key."=CONVERT_TZ(NOW() ,@@session.time_zone,'+07:00'),";
					}else{
						if($value instanceof TCode){
							if($key==null)
								$this->_query.=$value->get().",";
							else
								$this->_query.=$key."=".$value->get().",";
						}
					}
				}
				break;
		}
	}

}