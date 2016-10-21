<?php 

class ConnectDb{

	protected $clsDb=null;
	protected $driver;

	public function __construct($db=null){
		if($db!=null)
			$this->connect($db);
	}

	public function connect($db){
		
		$this->driver=$db["driver"];
		switch ($this->driver) {
			case 'mysql':
				require_once SYSTEM_PATH."db/mysql/MysqlDatabase.php";
				$this->clsDb=new MysqlDatabase();
				$this->clsDb->connect($db);
				$this->clsDb->setTable($this->table);
				break;
			case 'mongo':
			return new TException("mongodb tạm thời chưa hỗ trợ ở phiên bản này.",401);
				break;
			default:
				return new TException("driver ".$this->driver." không tồn tại. Chỉ hỗ trợ mysql, mongodb",401);
				break;
		}
	}

	public function getConnect(){
		return $this->clsDb->getConnect();
	}

}