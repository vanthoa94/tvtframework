<?php 

require_once SYSTEM_PATH."lib/Validate.php";

abstract class UploadMode{
	const MULTI=0;
	const SINGLE=1;
}

abstract class UploadTyPe{
	const IMAGE=0;
	const VIDEO=1;
	const AUDIO=2;
	const FILE=3;
}

class Upload{

	public $Mode=UploadMode::SINGLE;
	public $Type=UploadTyPe::FILE;

	public function __construct(){

	}


	public function upload($input_name,$uploadFolder='upload',$filename=null){
		
		if(isset($_FILES[$input_name])){
			if(!is_string($input_name)){
				return new TException('Không tìm thấy function upload('.gettype($input_name).',string).<br /> Vui lòng thử với function UploadImage(string input_name,string folder)',401);
			}
			$path=str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']).$uploadFolder;
			if(is_integer($this->Mode)){
				switch ($this->Mode) {
					case UploadMode::SINGLE:
						return $this->uploadSingle($_FILES[$input_name],$path,$filename);
						break;
					
					case UploadMode::MULTI:
						return $this->uploadMulti($input_name,$path);
						break;
				}
			}			
		}
	}

	

	private function uploadSingle($file,$path,$filename=null){
		$result=array();

	
		if(empty($filename)){
			$filename=$this->createFileName($path."/".$file['name'],1);
		}else{
			$filename=$this->createFileName($path."/".$filename,1);
		}

		if(!$this->check($file['name']))
			return -2;
		
		if(move_uploaded_file($file['tmp_name'],$filename)){
			$result['full_path']=$filename;
			$result['name']=$this->getNameFile($filename);
			$result['size']=$file['size'];
			$result['type']=$file['type'];
		}

		return $result;
	}

	public function uploadMulti($inputname,$path){
		$result=array();
		for($i=0;$i<count($_FILES[$inputname]['name']);$i++){
			$filename=$this->createFileName($path."/".$_FILES[$inputname]['name'][$i],1);
			
			$flag=false;
			if($this->check($_FILES[$inputname]['name'][$i])){
				if(move_uploaded_file($_FILES[$inputname]['tmp_name'][$i],$filename)){
					$result[]=array(
						'full_path'=>$filename,
						'name'=>$this->getNameFile($filename),
						'size'=>$_FILES[$inputname]['size'][$i],
						'type'=>$_FILES[$inputname]['type'][$i]
					);

				}
			}
			
		}

		return $result;
	}

	private function getNameFile($full){
		$arr=explode('/', $full);

		return $arr[count($arr)-1];
	}

	private function check($name){
		
		$validate=new Validate();
		switch ($this->Type) {
			case UploadTyPe::IMAGE:
				return $validate->isImage($name);
				break;
			
			case UploadTyPe::VIDEO:
				return $validate->isVideo($name);
				break;
			case UploadTyPe::AUDIO:
				return $validate->isAudio($name);
				break;
		}

		return true;
	}



	private function createFileName($path,$count){
		if(file_exists($path)){
			$arr=explode(".", $path);

			$length=count($arr)-1;
			$path='';
			for($i=0;$i<$length;$i++)
				$path.=$arr[$i];
			$path.="(".$count.")";
			$path.=".".$arr[$length];

			return $this->createFileName($path,$count+1);
		}

		return $path;
	}
}