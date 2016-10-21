<?php 
//vui lòng không xóa những thông số này
define("SYSTEM_PATH","library/");
define("EX_VIEW_FILE",".phtml");
define("ASSET_FOLDER","public");
/*
 + development: Dùng khi lập trình(Khi có lỗi trong quá trình sử lý sẽ thông báo cụ thể lỗi).
 + production: Dùng khi đã publish website(Không hiện code. 
 			   VD Không tìm thấy controller sẽ thông báo lỗi page not found).
*/

define("ENVIRONMENT",'development');

require SYSTEM_PATH."application.php";

$app=new Application();
$app->run();