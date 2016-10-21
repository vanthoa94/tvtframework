<?php 
/*
copy right 2015 by tran van thoa
*/
class Captcha{

	private $length=5;
	private $bg=array(0,0,0);
	private $height=30;
	private $showr=false;

	public function show($s_name='captcha'){
		if($this->showr){
    		require realpath(dirname(__FILE__).'/view').'/iframecaptcha.phtml'; 
    	}else{
    		echo '<img class="image_captcha" src="'.$this->create($s_name).'" alt="" />';
    	}
	}

	public function showWithRefresh($s_name='captcha'){
		$this->showr=true;
		$this->show($s_name);
	}

	public function getSrc($s_name='captcha'){
		return $this->create($s_name);
	}

	private function create($s_name){
		$md5="";
		
		$str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
		ob_start();
		$heig=$this->height/2;
		$image=ImageCreate($this->length*($heig+2),$this->height);
    	$while=ImageColorAllocate($image,$this->bg[0],$this->bg[1],$this->bg[2]);
    	ImageFill($image,0,0,$while);
    	$gray=imagecolorallocate($image,128,128,128);

    	for($i=10;$i<$this->length*($heig+2);$i+=30){
    		imageline($image, $i*2, 0, $i-10*2, $this->height, $gray);
    	}


		if(file_exists("C:/Windows/Fonts/Arial.TTF")){
	        for($i=0;$i<$this->length;$i++){
	        	$randcolors = imagecolorallocate($image,rand(100,255),rand(200,255),rand(200,255));
				$char=$str[rand(0,61)];
				imagettftext($image,$heig,rand(-20-($this->length-$i),30-$i),($i*$heig)+$this->length,($heig)+$this->height/4,$randcolors,"C:/Windows/Fonts/Arial.TTF",$char);
				$md5.=$char;
	        }
    	}else{
    		$x=$this->length;
    		for($i=0;$i<$this->length;$i++){
    			$char=$str[rand(0,61)];
    			$md5.=$char;
    			$y = rand(0 , $heig);
    			$randcolors = imagecolorallocate($image,rand(100,255),rand(200,255),rand(200,255));
				imagestring($image, $heig, ($i*$heig+2)+$this->length, $y, $char, $randcolors);
    		}
    		
    	}
    	if(isset($_COOKIE[$s_name]))
    		setcookie($s_name, null, time()-3600);
    	setcookie($s_name, $md5, time()+3600);

		
    	ImageJpeg($image);
    	ImageDestroy($image);
    	return "data:image/jpeg;base64,".base64_encode(ob_get_clean());
	}

	public function showRefresh($showr=true){
		$this->showr=$showr;
	}

	public function setLength($l){
		if(is_integer($l)){
			$this->length=$l;
		}else{
			return new TException("Lỗi function setLength trong Library captcha.<br /><br />Cấu trúc: <pre>public function setLength(number length)\n\nEX: setLength(5);</pre>",401);
		}
	}

	public function setHeight($height){
		if(is_integer($height)){
			$this->height=$height;
		}else{
			return new TException("Lỗi function setHeight cho captcha.<br /><br />Cấu trúc: <pre>public function setHeight(int height)\n\nEX: setHeight(30)</pre>",401);
		}
	}

	public function setBackground($bg){
		if(is_array($bg)){
			$this->bg=$bg;
		}else{
			return new TException("Lỗi function setBackground cho captcha.<br /><br />Cấu trúc: <pre>public function setBackground(array background)\n\nEX: setBackground(array(0,0,0))\n Với 0,0,0 là mã RGB</pre>",401);
		}
	}
}