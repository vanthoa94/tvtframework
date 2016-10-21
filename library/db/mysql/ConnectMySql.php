<?php 

class ConnectMySql{
	protected $conn=null;

	public function __construct($db=null){
		if($db!=null)
			$this->connect($db);
	}

	public function connect($db){
		$this->conn=mysqli_connect($db['host'],$db['username'],$db['password'],$db['dbname']);
		if(mysqli_connect_errno()){
			return new TException("Error Connection: ".mysqli_connect_errno(),401);

		}
		if (!$this->conn->set_charset($db['charset'])) {
			return new TException("Error loading character set ".$db['charset'].": %s\n".$mysqli->error,401);
		}

	}

	public function getConnect(){
		return $this->conn;
	}

	public function __destruct(){
		if($this->conn!=null && $this->conn){
			mysqli_close($this->conn);
		}
		$this->conn=false;
	}
}