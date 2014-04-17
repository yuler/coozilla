<?php

class TimerController extends BaseController {

	public function timer(){
		$date = date('Y-m-d H:i:s',strtotime('-30 day'));
		JobModel::whereRaw('job_state = ? and publish_at < ?',array(1,$date))->update(array('job_state'=>'-2'));
		return 1;
	}

	// // public static $run=1;
	// public function open(){
	// 	$filename = dirname(__FILE__)."/TimerConfig.php";//数据文件
	// 	$fp=fopen($filename,'r+');//将数据文件读入
	// 	$fileContext = "<?php \n \n return 1; \n \n";
	// 	fputs($fp,$fileContext);
	// 	fclose($fp);
	// 	// // cron定时计划
	// 	ignore_user_abort(); // run script. in background
	// 	set_time_limit(0); // run script. forever
	// 	$interval=1; // do every 1 hour...
	// 	// // echo $this->run;
	// 	do{
	// 		//todo
	// 		$run = include 'TimerConfig.php';
	// 		$date =  date('Y-m-d H:i:s');
	// 		$fp = fopen('filename.txt', 'a');
	// 		fwrite($fp,$run.$date."\n");
	// 		fclose($fp);
	// 		sleep($interval); // wait 1 hour
	// 	}while($run);
	// 	// $date = date('Y-m-d H:i:s',strtotime('-30 day'));
	// 	// JobModel::whereRaw('job_state = ? and publish_at < ?',array(1,$date))->update(array('job_state'=>'-2'));
	// }

	// public function close(){
	// 	// self::$run = 0;
	// 	// echo self::$run;
	// 	$filename = dirname(__FILE__)."/TimerConfig.php";//数据文件
	// 	$fp=fopen($filename,'r+');//将数据文件读入
	// 	$fileContext = "<?php \n \n return 0; \n \n";
	// 	fputs($fp,$fileContext);
	// 	fclose($fp);
	// }
}