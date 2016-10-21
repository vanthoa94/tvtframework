<?php 

class Validate{

	private $arr=array();

	public function isPhoneNumber($phone_number){
    	return preg_match("/((\+([0-9]{1,3})(\.|-| )?([0-9]{3,4}))|(0[0-9]{2,3}))(\.|-| )?([0-9]{3,4})(\.|-| )?([0-9]{3,4})$/", $phone_number);
    }

    public function isEmail($email){
    	return preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email);
    }

    public function isCharacter($str){
    	return preg_match("/^[\pL ]+$/u", $str);
    }

    public function isPrice($price){
    	return preg_match("/^[0-9]{1,3}( |-|\.|\,)?[0-9]{3}(( |-|\.|\,)?[0-9]{3})?(( |-|\.|\,)?[0-9]{3})?(( |-|\.|\,)?[0-9]{3})?$/", $price);
    }

    public function isNumber($number){
    	return preg_match("/^[0-9]+$/", $number);
    }

    public function isNotEmpty($str){
    	return rtrim($str)!='';
    }

    public function isEqual($str,$str2){
    	return $str==$str2;
    }

    public function isImage($file) {
	     $duoi=$this->getExFile($file);

	    switch ($duoi) {
	        case "jpg": case "png": case "gif": case "bimap": case "jpeg": case "ico": case "bmp":
	        case "jpe":
	            return true;
	        default:
	            return false;
	    }
	    return false;
	}

	

	public function isVideo($file) {
	    $duoi=$this->getExFile($file);

	    switch ($duoi) {
	        case "mp4": case "avi": case "flv": case "3gp":
	            return true;
	        default:
	            return false;
	    }
	    return false;
	}

	public function isAudio($file){
		 $duoi=$this->getExFile($file);
		switch ($duoi) {
	        case "mp3": case "3ga": case "flac": case "m4a": case "wav": case "wma":
	            return true;
	        default:
	            return false;
	    }
	    return false;
	}

	private function getExFile($full){
		$arr=explode('.', $full);

		return $arr[count($arr)-1];
	}

	public function convertStringToDate($date){
		$date=explode("/", $date);
		$day=(int)$date[0];
		$month=(int)$date[1];
		return $date[2].'-'.($month<10?"0":"").$month.'-'.($day<10?"0":"").$day;
	}

	public function isDate($date){
		$date=explode("/", $date);

		if(count($date)==3){
			$ngay=(int)$date[0];
			$thang=(int)$date[1];
			$nam=(int)$date[2];

			if($thang>0 && $thang<13){
				switch($thang){
					case 1: case 3: case 5: case 7: case 8: case 10: case 12:
						return $ngay<32 && $ngay>0;
					case 2:
						if(($nam%4==0 && $nam%100!=0) || $nam%400==0)
							return $ngay<30 && $ngay>0;
						return $ngay<29 && $ngay>0;
					default:
						return $ngay<31 && $ngay>0;
				}
			}
			return false;
		}

		return false;
	}

	public function add($method,$value,$message_or_value2=null,$message=null){
		$this->arr[]=array($method,$value,$message_or_value2,$message);
	}

	public function check(){
		foreach ($this->arr as $value) {

			if($value[0]!='isEqual'){
				if(!$this->$value[0]($value[1])){
					return ($value[2]==null)?'Có lỗi ở '.$value[0]:$value[2];
				}
			}else{
				if(!$this->isEqual($value[1],$value[2])){
					return ($value[3]==null)?'Có lỗi ở '.$value[0]:$value[3];
				}
			}
		}

		return '';
	}
}