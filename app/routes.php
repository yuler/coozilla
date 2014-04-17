<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//预发布页面

// Route::get('/', function() {return View::make('betaIndex'); });


//跟路径

Route::get('/', function() {return View::make('index'); });
Route::get('/index', function() {return View::make('index'); });

// Route::get('/signIn', function() {return View::make('signIn'); });
// Route::get('/signUp', function() {return View::make('signUp'); });
Route::get('/checkCode', function() {return View::make('include/checkCode'); });


//updateDB
Route::post('/uploads/updateHirerImg','UploadController@updateHirerImg');
Route::post('/uploads/updateWorkerImg','UploadController@updateWorkerImg');

//worker
Route::post('/WorkerController/WorkerRegisterService/workerRegister','WorkerController@saveWorkerInfo');
Route::get('/WorkerController/WorkerRegisterService/checkEmail/{member_email}','WorkerController@checkEmail');
Route::get('/WorkerController/WorkerRegisterService/checkAccount/{member_account}','WorkerController@checkAccount');
Route::post('/WorkerController/WorkerRegisterService/save','WorkerController@save');
Route::get('/WorkerController/WorkerRegisterService/queryLoginInfo/{loginId}','WorkerController@queryLoginInfo');
Route::get('/WorkerController/WorkerRegisterService/queryWorkerInfo/{loginId}','WorkerController@queryWorkerInfo');
Route::get('/WorkerSkillController/WorkerSkillService/queryWorkerSkill/{loginId}','WorkerController@queryWorkerSkill');
Route::post('/WorkerController/WorkerRegisterService/update','WorkerController@update');
Route::get('/WorkerController/WorkerRegisterService/findWorkerInfo/{loginId}','WorkerController@findWorkerInfo');
Route::get('/WorkerController/WorkerRegisterService/relationWorkerSkillArray/{loginId}','WorkerController@relationWorkerSkillArray');

Route::post('/WorkerController/WorkerService/find','WorkerController@find');
Route::post('/WorkerController/WorkerService/update','WorkerController@updateInfo');
Route::post('/WorkerController/WorkerService/updateName','WorkerController@updateName');
Route::post('/WorkerController/WorkerService/updateAddress','WorkerController@updateAddress');
Route::post('/WorkerController/WorkerService/updateTelephone','WorkerController@updateTelephone');
Route::post('/WorkerController/WorkerService/updateExperience','WorkerController@updateExperience');
Route::post('/WorkerController/WorkerService/updateWorkerSkills','WorkerController@updateWorkerSkills');
Route::post('/WorkerController/WorkerService/putWorkerIdInSession','WorkerController@putWorkerIdInSession');
Route::post('/WorkerController/WorkerService/getWorkerIdWithSession','WorkerController@getWorkerIdWithSession');
Route::post('/WorkerController/WorkerService/removeWorkerIdWithSession','WorkerController@removeWorkerIdWithSession');


//Route::post('/WorkerController/HomeWorkerService/findWorkerPwd','WorkerController');
//Login
Route::get('/LoginController/LoginService/checkAccount/{account}','LoginController@checkAccount');
Route::get('/LoginController/LoginService/isExistLogin','LoginController@isExistLogin');
Route::post('/LoginController/LoginService/login','LoginController@login');
Route::post('/LoginController/LoginService/putLoginInSession','LoginController@putLoginInSession');
Route::get('/LoginController/LoginService/loginOut','LoginController@loginOut');
Route::get('/LoginController/LoginService/relationHirer','LoginController@relationHirer');
Route::get('/LoginController/LoginService/relationHirerById/{loginId}','LoginController@relationHirerById');
Route::post('/LoginController/LoginService/save','LoginController@save');
Route::post('/LoginController/LoginService/update','LoginController@update');
Route::get('/LoginController/LoginService/activeAccount/{loginId}','LoginController@activeAccount');
Route::get('/LoginController/LoginService/find/{loginId}','LoginController@find');
Route::get('/LoginController/LoginService/relationWorkerById/{loginId}','LoginController@relationWorkerById');
Route::post('/LoginController/LoginService/resetPwd','LoginController@resetPwd');
Route::post('/LoginController/LoginService/checkRandomcode','LoginController@checkRandomcode');
//Common
Route::get('/CommonController/CommonService/queryAllSkill','CommonController@queryAllSkill');



//Job
Route::post('/JobController/JobService/save','JobController@save');
Route::post('/JobController/JobService/update','JobController@update');
Route::post('/JobController/JobService/deleteJob','JobController@deleteJob');
Route::post('/JobController/JobService/publish','JobController@publish');
Route::get('/JobController/JobService/queryAll2Console','JobController@queryAll2Console');
Route::get('/JobController/JobService/queryPublishJobsWithPage/{pageNo}/{pageSize}','JobController@queryPublishJobsWithPage');
Route::get('/JobController/JobService/queryUnpublishedJobsWithPage/{pageNo}/{pageSize}','JobController@queryUnpublishedJobsWithPage');
Route::post('/JobController/JobService/search','JobController@search');
Route::get('/JobController/JobService/findJob/{jobId}','JobController@findJob');
Route::get('/JobController/JobService/relationJobSkill/{jobId}','JobController@relationJobSkill');
Route::get('/JobController/JobService/relationJobSkillArray/{jobId}','JobController@relationJobSkillArray');
Route::post('/JobController/JobService/putJobIdInSession','JobController@putJobIdInSession');
Route::post('/JobController/JobService/getJobIdWithSession','JobController@getJobIdWithSession');
Route::post('/JobController/JobService/removeJobIdWithSession','JobController@removeJobIdWithSession');


//Hirer
Route::post('/HirerController/HirerService/save','HirerController@save');
Route::post('/HirerController/HirerService/update','HirerController@update');
Route::post('/HirerController/HirerService/updateExceptImg','HirerController@updateExceptImg');
Route::post('/HirerController/HirerService/updateName','HirerController@updateName');
Route::post('/HirerController/HirerService/updateUrl','HirerController@updateUrl');
Route::post('/HirerController/HirerService/updateAddress','HirerController@updateAddress');



//PushMail
Route::post('/PushMailController/PushMailService/sendActiveMail','PushMailController@sendActiveMail');

Route::post('/PushMailController/addSubscriber',function(){
	$apiKey = '46621512001f6082fa7fa1064e823bc3-us3';
	$listId = 'daafeff159';

	$email = array(
	                "email" => $_POST["email"],
	                "euid" =>  "",
	                "leid" =>  ""
	);
	$merge_vars=array("FNAME" => "","LNAME" => "");
	$submit_url = "https://us3.api.mailchimp.com/2.0/lists/subscribe.json";
	$data = array(
	    'email'=>$email,
	    'apikey'=>$apiKey,
	    'merge_vars'=>$merge_vars,
	    'id' => $listId
	    // 'double_optin' => false,
	    // 'send_welcome' => true,
	    // 'email_type' => "html"
	);
	// We still need to build our HTML query string to send
	$post_query_string = http_build_query($data);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $submit_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query_string);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
});

Route::post('/PushMailController/PushMailService/sendActiveMail','PushMailController@sendActiveMail');
Route::post('/PushMailController/PushMailService/pushMailWithJob','PushMailController@pushMailWithJob');
Route::post('/PushMailController/PushMailService/pushMailWithWorker','PushMailController@pushMailWithWorker');
Route::post('/PushMailController/PushMailService/forget','PushMailController@forget');
Route::get('/mail/{type}',function($type){
	//$email = '393342914@qq.com';
	//$email = 'shihang2@gmail.com';
	//$email = 'zyh19880605@gmail.com';
	$email = '498821924@qq.com';
	//$email = 'jodooshi@qq.com';
	//$email = 'zhangyh@trht.com.cn';
	$name = 'shihang';
	$randomcode = '462371f680e7d21ded4beacd501252bd';
	$mailData = array(    'name' => $name
						 ,'time'=>date('D F j Y')
						 ,'id'=> $randomcode
						 ,'randomcode'=> $randomcode
						);
	$jobAdviceMailData = array(
						  'name' => $name
						 ,'job_title'=>'Email anywhere. On any device.'
						 ,'hirer_orgname'=>'zhangyh'
						 ,'job_description'=>'Reach out your tentacles to a broad range of people who subscribe to your emails. Our CSS framework helps you craft HTML emails that can be read anywhere on any device. Gone are the days where you had to choose between Outlook and email optimized for smartphones and tablets. Ink\'s responsive, 12-column grid blends flexibility and stability so your readers can view your emails perfectly from wherever they may be.'
						 ,'member_email'=>'zyh@gmail.com'
						 ,'hirer_orgaddress'=> 'beijing trht'
						 ,'hirer_orgsite'=> 'beijing trht'
						 ,'jobId'=> '462371f680e7d21ded4beacd501252bd'
						 ,'id'=> '462371f680e7d21ded4beacd501252bd'
						);
	switch ($type) {
		case 0:
			//test forget password
			Mail::send("emails.forgetPassword",$mailData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('Reset Password');
			});
			break;
		case 1:
			//test active account
			Mail::send("emails.activeAccount",$mailData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('Reset password');
			});
			break;
		case 2:
			//test job advice
			Mail::send("emails.jobAdvice",$jobAdviceMailData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('Job Opportunities');
			});
			break;
		case 3:
			$logins = WorkerModel::where("worker_name",'<>','123')->get();
			foreach ($logins as $login) {
				$user=LoginModel::where("id","=",$login->login_id)->first();
				$login->member_email=$user->member_email;
			}
			$jobFeedbackData = array(
				  'name' => $name
				 ,'job_title'=>'Email anywhere. On any device.'
				 ,'hirer_orgname'=>'zhangyh'
				 ,'logins' => $logins
				);
			//test job feedback
			Mail::send("emails.jobFeedback",$jobFeedbackData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('Candidates Notification');
			});
			break;
		case 4:
			$workerAdviceMailData = array(
				  'name' => $name
				 ,'job_title'=>'Email anywhere. On any device.'
				 ,'worker_name'=>'zhangyh'
				 ,'worker_experience'=>'Reach out your tentacles to a broad range of people who subscribe to your emails. Our CSS framework helps you craft HTML emails that can be read anywhere on any device. Gone are the days where you had to choose between Outlook and email optimized for smartphones and tablets. Ink\'s responsive, 12-column grid blends flexibility and stability so your readers can view your emails perfectly from wherever they may be.'
				 ,'member_email'=>'zyh@gmail.com'
				);
			//test worker advice hirer
			Mail::send("emails.workerAdviceHirer",$workerAdviceMailData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('New Candidate Notification');
			});
			break;
		case 5:
			$jobs = JobModel::where("job_title","not like","%123%")->where("job_title","not like","%test%")->get();
			$workerAdviceJobsMailData = array(
				  'name' => $name
				 ,'job_title'=>'Email anywhere. On any device.'
				 ,'worker_name'=>'zhangyh'
				 ,'worker_experience'=>'Reach out your tentacles to a broad range of people who subscribe to your emails. Our CSS framework helps you craft HTML emails that can be read anywhere on any device. Gone are the days where you had to choose between Outlook and email optimized for smartphones and tablets. Ink\'s responsive, 12-column grid blends flexibility and stability so your readers can view your emails perfectly from wherever they may be.'
				 ,'member_email'=>'zyh@gmail.com'
				 ,'jobs'=>$jobs
				);
			//test worker advice jobs
			Mail::send("emails.workerAdviceJobs",$workerAdviceJobsMailData, function($message) use (&$email,$name){
				$message->from('yule@trht.com.cn','Coozilla');
				$message->to($email,$name)->subject('Job Opportunities Notification');
			});
			break;
		default:
			break;
	}
	return "success";
});

Route::get('/timer','TimerController@timer');
// Route::get('/open','TimerOpenController@open');
// Route::get('/close','TimerOpenController@close');
