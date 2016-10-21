<?php 

require SYSTEM_PATH."lib/mail/phpmailer.php";
require SYSTEM_PATH."lib/mail/smtp.php";
require SYSTEM_PATH."lib/mail/phpmailerexception.php";

class Mail extends PHPMailer{
	public function send($option=array()){
		$this->check($option,'username');
		$this->check($option,'password');
		$this->check($option,'to');
		$this->check($option,'subject');
		$this->check($option,'content');
		$this->isSMTP(); 
		if(isset($option['host']))
			$this->Host = $option['host'];
		else{
			$this->Host = 'smtp.gmail.com'; 
		}
		$this->SMTPAuth = true;   
		if(isset($option['port']))
			$this->Port = $option['port'];
		else
			$this->Port=587;
		$this->SMTPSecure = 'tls';
		$this->Username = $option['username'];
		$this->Password = $option['password'];

		$this->WordWrap = 50; 

		if(isset($option['from']))
			$this->From = $option['from'];
		else
			$this->From = $option['username'];
		if(isset($option['fromname']))
			$this->FromName = $option['fromname'];
		else
			$this->FromName = $_SERVER['SERVER_NAME'];

		if(!isset($option['name'])){
			$option['name']='';
		}
		$this->addAddress($option['to'], $option['name']);
		$this->isHTML(true);         

		$this->Subject = $option['subject'];
		$this->Body= $option['content'];
		if(!parent::send()){
			echo $this->ErrorInfo;
			return false;
		}
		return true;
	}

	public function addAttachFile($path_to_file){
		$this->addAttachment($path_to_file);
	}

	private function check($option,$key){
		if(!isset($option[$key])){
			return new TException('Thông số send mail thiếu <b>'.$key.'</b>. Thông số đầy đủ là: <pre>
				array(
					host=>host, //VD: smtp.gmail.com. mặc định là gmail
					port=>port, //VD: 587
					username=>email của bạn, (*)
					password=>password của email, (*)
					from=>tên người gửi,
					fromname=>tên người gửi,
					to=>email nhận, (*)
					name=>tên người nhận,
					subject=>tiêu đề email, (*)
					content=>nội dung email (*)
				)
				p/s: (*) là buộc phải điền.</pre>',401);
		}
	}
}