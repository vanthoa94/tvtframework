<?php 

require_once SYSTEM_PATH.'/mvc/view/View.php';


class Controller{

	private $view;
	private $model;
	private $request=null;
	private $db=null;
	private $_load=null;
	private $device=null;
	private $moduleclass=null;
	private $cacheClass=null;

	public function __construct(){
		$this->view=new View();
	}

	public function init(){

	}

	public function __get($option){
			switch ($option) {
				case 'load':
					if($this->db==null)
						$this->getDb();
					if($this->_load==null){
						require_once SYSTEM_PATH.'lib/Load.php';
						$this->_load=new Load($this->db);

					}
					return $this->_load;
					break;
				case 'view':
					return $this->view;
					break;
				case 'layout':
					return $this->view->layout();
					break;
				case 'request':
					return $this->request();
				case 'device':
					if($this->device==null){
						$this->device=new Device();
					}
					return $this->device;
				case 'cache':
					if($this->cacheClass==null){
						require_once SYSTEM_PATH."mvc/controller/Cache.php";
						$this->cacheClass=new Cache($this->view->getViewFile());	
					}
					return $this->cacheClass;
				case 'language':
					return $this->view->language;
				case 'lang':
					return $GLOBALS['lang'];
				default:
					return new TException("Biến <b>".$option."</b> không tồn tại trong controller. Vui lòng thử lại với: <pre>1. \$this->load<br />2. \$this->view<br />3. \$this->layout<br />4. \$this->request<br />5. \$this->device<br />6. \$this->language<br />7. \$this->lang<br /></pre>",401);
					break;
			}
		return new TException(var_dump($option)." không tồn tại",401);
	}
	
	public function setLayout($layout){
		$this->view->setLayout($layout);
	}

	public function setView($view_file){
		$this->view->setView($view_file);
	}

	public function setViewClass($view){
		$this->view=$view;
	}

	protected function module($module_name){
		return $this->view->getModule($module_name);
	}

	protected function view(){
		$this->view->CallView();

		return true;
	}

	protected function json($var=null){
		header('Content-Type:application/json;charset=utf-8');
		if($var==null){
			$var=$this->view->getVar();
			if(count($var)==1){
				echo json_encode($var[0]);
			}else{
				echo json_encode($var);
			}
			
		}else{
			if(gettype($var)=='array'){
				echo json_encode($var);
			}else{
				echo $var;
			}
		}
	}

	public function getView(){
		return $this->view;
	}

	public function request(){
		if($this->request==null){
			$this->request=new Request();
		}

		return $this->request;
	}

	private function getDb(){
		$config=require "config/config.php";
		if(isset($config['db'])){
			$this->db=$config['db'];
		}else
			$this->db=null;
	}

	public function setDb($db){
		$this->db=$db;
	}
	public function redirect($url=null){
		header("location:".$this->view->baseUrl().$url);
		exit();
	}

	public function getDirPath(){
		return dirname($_SERVER['SCRIPT_FILENAME']);
	}

	protected function saveMessage($msg,$name='message'){
		$cookie=$this->load->library("Cookie");
		$cookie->set($name,$msg,"30S",'/');
	}

	protected function callMessage($name='message'){
		$cookie=$this->load->library("Cookie");
		if($cookie->has($name)){
			
			$this->view->$name=$cookie->get($name);
			$cookie->remove($name,'/');
		}
		
	}


	protected function formatText($text){
		return trim(addslashes($text));
	}

	private function convert($type,$v){
        switch ($type) {
            case 'string':
                return trim($v);
            case 'br':
                return str_replace("\n", "<br>", $v);
            case 'int':
                return (int)$v;
            case 'boolean':
                return (boolean)$v;
            case 'md5':
                return md5($v);
            case 'time':
                return "getdate()";
            case 'price':
            	return preg_replace("/(\.|-| |\,)*/", "", $v);
            case 'decode':
            	return htmlentities($v,ENT_QUOTES,'UTF-8',false);
        }
    }

    protected function post_r(array $remove,$id=null,$key=null){
    	$arr=$this->post($id,$key);

    	foreach ($remove as $item) {
    		unset($arr[$item]);
    	}
    	return $arr;
    }

	protected function post($id=null,$key=null){
        $arr=array();
        if($id!=null)
            $arr[$id]='NULL';
        if($key==null){
            foreach($_POST as $name=>$value){
                $strarr=explode('-', $name);
                if(isset($strarr[1])){
                    $vvv=$this->convert($strarr[1],$value);
                    $arr[$strarr[0]]=$vvv;
                }else{
                    $arr[$name]=$value;
                }
            }
        }else{
            $key=$key."-";
            foreach($_POST as $name=>$value){
                if($this->startWith($name,$key)){
                    $strarr=explode('-', $name);
                    if(isset($strarr[2])){
                        $vvv=$this->convert($strarr[2],$value);
                        $arr[$strarr[1]]=$vvv;
                    }else{
                        $arr[$strarr[1]]=$value;
                    }
                }
            }
        }
        return $arr;
    }

    public function __call($method,$args){
		return new TException('Phương thức '.$method.' không có trong class Controller.',401);
	}

	public function __destruct(){
		if($this->cacheClass!=null){
			if($this->cacheClass->isSave()){
				
				$cached = fopen($this->cacheClass->getFileCache(), 'w');
				fwrite($cached, ob_get_contents());
				fclose($cached);
				ob_end_flush(); 
			}
		}
	}
}