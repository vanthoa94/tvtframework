<?php 

	$length=(int)$_GET['l'];
	$bg=array((int)$_GET['b1'],(int)$_GET['b2'],(int)$_GET['b3']);
	$height=(int)$_GET['h'];
	$s_name=$_GET['n'];

$md5="";
		
		$str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
		ob_start();
		$heig=$height/2;
		$image=ImageCreate($length*($heig+2),$height);
    	$while=ImageColorAllocate($image,$bg[0],$bg[1],$bg[2]);
    	ImageFill($image,0,0,$while);
    	$gray=imagecolorallocate($image,128,128,128);

    	for($i=10;$i<$length*($heig+2);$i+=30){
    		imageline($image, $i*2, 0, $i-10*2, $height, $gray);
    	}


		if(file_exists("C:/Windows/Fonts/Arial.TTF")){
	        for($i=0;$i<$length;$i++){
	        	$randcolors = imagecolorallocate($image,rand(100,255),rand(200,255),rand(200,255));
				$char=$str[rand(0,61)];
				imagettftext($image,$heig,rand(-20-($length-$i),30-$i),($i*$heig)+$length,($heig)+$height/4,$randcolors,"C:/Windows/Fonts/Arial.TTF",$char);
				$md5.=$char;
	        }
    	}else{
    		$x=$length;
    		for($i=0;$i<$length;$i++){
    			$char=$str[rand(0,61)];
    			$md5.=$char;
    			$y = rand(0 , $heig);
    			$randcolors = imagecolorallocate($image,rand(100,255),rand(200,255),rand(200,255));
				imagestring($image, $heig, ($i*$heig+2)+$length, $y, $char, $randcolors);
    		}
    		
    	}
    	if(isset($_COOKIE[$s_name]))
    		setcookie($s_name, null, time()-3600);
    	setcookie($s_name, $md5, time()+3600);

		
    	ImageJpeg($image);
    	ImageDestroy($image);
    	$image_data= "data:image/jpeg;base64,".base64_encode(ob_get_clean());
?>
<div style="float:left">
	<img id="image_captcha" src="<?php echo $image_data ?>" alt="" />
</div>
<div style="float:left;margin-top:<?php echo ($height/2)-8 ?>px;margin-left:5px">
<a href="#refresh" id="refresh_captcha" title="refresh">
	<img src="data:image/gif;base64,R0lGODlhEAAQAPeAAJ/P/9Hp/yd71f3+/2uz7+b0/3S+/5bM/1my/7zi/yqX/73g/6LR/yd708Tb9KDT/42y4UJ8x+31/EqS1uvy/JjP/0qHyJSx3UqMzjqh/0+u/z16yI7J/xhcvRBYsUqH1UqG21J+wiyE0Uii62O3/7fc/zp/zZ7P/1aR3bDf/yBxzyp50Fqk6lKj4ViOzSyL5VSb12uc1ZvM/nW+/xqA7BqI3MXi/3nB/4jK/6zE6drm9mCm7Pj5/JC+53CNx73W81Kf4xR0xkit/7DU83W//5TM/yqV/Iq1487k97Hg/8Dk/0N5w63C5IOn237D/yZ4zxhuzo+66Lrk/6HU/7zl/4jC77TM6oWp4J2+5kyf6Xa3+HSj3GWb3Xax5yKA1m+q5ByQ+IXC/dbm9vb7/8Td9m6q31ew/9bh9IWl1uXz/6XA5kuh4USa4DSL4uPr+TyK1qrM7G+c2k+J1v7+/6HK76TS/26n3EKQ30On/87d8z2n/6DZ/yR72Dd3ySqA0iqC2v///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAIAALAAAAAAQABAAAAizAAEJHEiwoMEebLz4wWDF4EACYIxkwENEABaHdGpUGdLlhZkbK86gAEHwS4GBZNrMwOFBRh+HAyFoKVIhxZKBYwYQpIDGxAMGSUIIhLOmxYgsQJDE2ENFSQIpPgTaSWOjxBQnZfJwkfPBQgQmAmEEqANAxBEJBOfwGDhhwYkDO6LAFPgmTBAOBv44GOjmCsE7LMQ8IYGAz5YcTTqocfijgQYhehTQiDNXhwsVUDZcmMtZYEAAOw==" />
	</a>
</div>
<div style="clear:left"></div>
<style type="text/css">
	body{
		padding: 0;
		margin: 0;
	}
</style>
<script type="text/javascript">
	document.getElementById('refresh_captcha').onclick=function(){
		window.location.reload();
	};
</script>