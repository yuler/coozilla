<?php

class TimerCloseController extends BaseController {
	public function close(){
		$filename = dirname(__FILE__)."/TimerConfig.php";//数据文件
		$fp=fopen($filename,'r+');//将数据文件读入
		$fileContext = "<?php \n \n return 0; \n \n";
		fputs($fp,$fileContext);
		fclose($fp);
	}
}