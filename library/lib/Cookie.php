<?php 

class Cookie{

	public function set($name='cookie', $v='thoa', $time='1d',$path=null) {
		$time=strtoupper($time);

		$match_char=$this->splitCharacter($time);
		$match_num=$this->splitNumber($time);
		
		$expire=time();

		foreach ($match_char[0] as $key => $value) {
			$expire+=$this->createExpire($value,$match_num[0][$key]);
		}
		if(empty($path))
			setcookie($name, $v, $expire);
		else
			setcookie($name, $v, $expire,$path);
		$_COOKIE[$name]=$v;
	}

	private function createExpire($value,$value2){
		switch ($value) {
			case 'D':
				return 60*60*24*$value2;
				break;
			case 'H':
				return 60*60*$value2;
				break;
			case 'M':
				return 60*$value2;
				break;
			case 'S':
				return $value2;
				break;
			default:
				return 0;
		}
	}

	private function splitCharacter($value){
		$match=null;

		preg_match_all("/([A-Z]+)/", $value, $match);

		return $match;
	}

	private function splitNumber($value){
		$match=null;

		preg_match_all("/([0-9]+)/", $value, $match);

		return $match;
	}

	public function has($name='cookie') {
		return isset($_COOKIE[$name]);
	}

	public function get($name='cookie') {
		if(isset($_COOKIE[$name])){
			return $_COOKIE[$name];
		}
		return null;
	}
	public function remove($name='cookie',$path=null) {
		if(isset($_COOKIE[$name])){
			unset($_COOKIE[$name]);
			if(empty($path))
				setcookie($name, null, -1);
			else
				setcookie($name, null, -1,$path);
		}
	}

	public function removeAll(){
		if (isset($_SERVER['HTTP_COOKIE'])) {
		    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		    foreach($cookies as $cookie) {
		        $parts = explode('=', $cookie);
		        $name = trim($parts[0]);
		        setcookie($name, '', time()-1000);
		        setcookie($name, '', time()-1000, '/');
		    }
		}
	}
}