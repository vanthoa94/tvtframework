<?php 

class Device{
	public function isMobile(){
		if(isset($_COOKIE['mobile'])){
			if($_COOKIE['mobile']=='0' || strtolower($_COOKIE['mobile'])=='false')
				return false;
			else{
				if($_COOKIE['mobile']=='1' || strtolower($_COOKIE['mobile'])=='true')
					return true;
			}
		}
		return preg_match("/(android|iphone|ipad|blackberry|nokia|opera mini|windows mobile|windows phone|iemobile)/i", $_SERVER['HTTP_USER_AGENT']);
	}
}