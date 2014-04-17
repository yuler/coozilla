<?php

class PushMailController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	//发送激活邮件
	public function sendActiveMail(){
		$data = Input::all();
		$mailData = array("name"=>$data["member_account"],"id"=>$data["id"],'time'=>date('D F j Y'));
		Mail::send("emails.activeAccount",$mailData, function($message) use (&$data){
			$message->from('noreply@coozilla.com','Coozilla');
    		$message->to($data["member_email"],$data["member_account"])->subject('Welcome to coozilla!');
		});
	}


	//匹配推送  用户注册后的匹配推送
	public static function pushMailWithWorker(){
			$loginId = Input::get('loginId');
			$skills = PushMailController::queryWorkerSkillsByLoginId($loginId);
			$jobIds = PushMailController::queryJobsByWorkSkills($skills);
			if($jobIds){
				PushMailController::sendMailToWorkerWithJobs($jobIds,$loginId);
				//回馈
				PushMailController::sendMailToHirerWithWorker($jobIds,$loginId);
			}
			return $jobIds;
	}
		//根据登录id 查询用户拥有的技能集合
		public static function queryWorkerSkillsByLoginId($loginId){
			$workSkills = LoginModel::find($loginId)->workerSkill;
			$skills = array();
			for ($i=0; $i < $workSkills->count() ; $i++) { 
				$skills[$i] = $workSkills->get($i)['skill_key'];
			}
			return $skills;
		}

		//根据技能集合 匹配出  jobInofId 集合
		public static function queryJobsByWorkSkills($skills){
			$jobInfos = array();
			$result = array();
			//定义二维数组存储符合jobId
			for($i = 0 ; $i < count($skills) ; $i++) {
				$JobInfoList = JobSkillModel::where('skill_key','=',$skills[$i])->get();
				for ($j=0; $j < count($JobInfoList) ; $j++) { 
					array_push($jobInfos,$JobInfoList->get($j)['job_id']);
				}
			}
			//除去重复
			if($jobInfos){
				$jobInfos_unique = array_unique($jobInfos);
				$jobInfos_unique = array_values($jobInfos_unique);
				for($i = 0; $i< count($jobInfos_unique) ;$i++){
					$jobSkill = JobModel::find($jobInfos_unique[$i])->jobSkill;
					for ($j=0; $j < $jobSkill->count() ; $j++) { 
						$skill =  $jobSkill->get($j)['skill_key'];
						if(!in_array($skill, $skills)){
							continue 2;
						}
					}
					array_push($result, $jobInfos_unique[$i]);
				}
			}
			return $result;
		}

		public static function sendMailToWorkerWithJobs($jobIds,$loginId){
			$login = LoginModel::find($loginId);
			$worker = LoginModel::find($loginId)->worker;
			$jobs = array();
			for($i = 0;$i<count($jobIds); $i++){
				$job = JobModel::find($jobIds[$i]);
				$Joblogin = JobModel::find($jobIds[$i])->login;
				if($Joblogin){
					$Jobhirer = LoginModel::find($Joblogin['id'])->hirer;
					$job['hirer_orgname'] = $Jobhirer['hirer_orgname'];
					$job['member_email'] = $Joblogin['member_email'];
					array_push($jobs,$job);	
				}
			}
			$mailData = array("name" => $worker["worker_name"]
							  ,"jobs" => $jobs
							  ,"job_title" => $job['job_title']
							  ,'time'=>date('D F j Y'));
			Mail::send("emails.workerAdviceJobs",$mailData, function($message) use (&$login,&$worker){
				$message->from('noreply@coozilla.com','Coozilla');
	    		$message->to($login["member_email"],$worker["worker_name"])->subject('Job Opportunities Notification');
			});
		}

		public static function sendMailToHirerWithWorker($jobIds,$loginId){
			for($i = 0;$i<count($jobIds); $i++){
				PushMailController::sendMailToHirerWithWorkerInfo($jobIds[$i],$loginId);
			}
		}

		public static function sendMailToHirerWithWorkerInfo($jobId,$loginId){
			$login = JobModel::find($jobId)->login;
			if($login){
				$hirer = LoginModel::find($login["id"])->hirer;
				$worker = LoginModel::find($loginId)->worker;
				$worker['member_email'] = LoginModel::find($loginId)['member_email'];
				$job_title = JobModel::find($jobId)['job_title'];
				$mailData = array("name" => $hirer["hirer_orgname"]
								,"worker_name" => $worker["worker_name"]
								,"worker_experience" => $worker["worker_experience"]
								,"member_email" => $worker["member_email"]
								,"job_title" => $job_title
								,'time'=>date('D F j Y')
								);
				Mail::send("emails.workerAdviceHirer",$mailData, function($message) use (&$login,&$hirer){
					$message->from('noreply@coozilla.com','Coozilla');
		    		$message->to($login["member_email"],$hirer["hirer_orgname"])->subject('New Candidate Notification');
				});
			}
		}

	//匹配推送 用户发布工作后的匹配推送
	public static function pushMailWithJob(){
		$jobId = Input::get('jobId');
		$skills = PushMailController::queryJobSkillsByJobId($jobId);
		$loginIds = PushMailController::queryLoginIdsByjobSkill($skills);
		if($loginIds){
			PushMailController::sendWorkersWithJob($loginIds,$jobId);
			// 回馈
			PushMailController::sendHirerWithWorkers($loginIds,$jobId);
		}
		return $loginIds;
	}
		//查询一个工作机会所需要的技能集合
		public static function queryJobSkillsByJobId($jobId){
			$jobSkills =  JobModel::find($jobId)->jobSkill;
			// JobModel::whereRaw('id = ? and ')
			$skills = array();
			for ($i=0; $i < $jobSkills->count() ; $i++) { 
				$skills[$i] = $jobSkills->get($i)['skill_key'];
			}
			return $skills;
		}
		//根据工作的技能集合查询符合技能集合的worker的id的集合
		public static function queryLoginIdsByjobSkill($skills){
			//定义二维数组存储符合每个技能的工人loginid
			$workers = array();
			for($i = 0 ; $i < count($skills) ; $i++) {
				$workerSkillList = WorkerSkillModel::where('skill_key','=',$skills[$i])->get();
				if(count($workerSkillList) > 0 ){
					for ($j=0; $j < count($workerSkillList) ; $j++) { 
						$workers[$i][$j] = $workerSkillList->get($j)['login_id'];
					}
				//如果不存在符合要求的技能的login	
				}else{
					$workers[$i] = [];
				}
				
			}
			if(count($workers) > 0){
				//定义一维数组存储符合所有技能的 loginId
				$result = $workers[0];
				for ($i = 1; $i < count($workers); $i++){
					//交集函数
					$result = array_intersect($result, $workers[$i]);
				}
			}
			return array_values($result);
		}
		//根据LoginIds集合 和 jobInfo 发送邮件
		public static function sendWorkersWithJob($loginIds,$jobId){
			$job = JobModel::find($jobId);
			for($i=0;$i<count($loginIds);$i++) {
				PushMailController::sendWorker($loginIds[$i],$job);
			}
		}
		//根据LoginId 和jobInfo 发送邮件给工作者
		public static function sendWorker($loginId,$job){
			$jobSkills = $job->jobSkill;
			$jobLogin = LoginModel::find($job["login_id"]);
			$hirer = LoginModel::find($job["login_id"])->hirer;
			$login = LoginModel::find($loginId);
			if($login){
				$worker = LoginModel::find($loginId)->worker;	
				$mailData = array("name" => $worker["worker_name"]
								,"jobId"=>$job["id"]
								,"job_title"=>$job["job_title"]
								,"job_description"=>$job["job_description"]
								,"job_category"=>$job["job_category"]
								,"jobSkill"=>$jobSkills
								,'member_email'=>$jobLogin["member_email"]
								,'hirer_orgaddress'=>$hirer["hirer_orgaddress"]
								,'hirer_orgsite'=>$hirer["hirer_orgsite"]
								,'hirer_orgname'=>$hirer["hirer_orgname"]
								,'time'=>date('D F j Y')
								);
				Mail::send("emails.jobAdvice",$mailData, 
					function($message) use (&$login,&$worker){
						$message->from('noreply@coozilla.com','Coozilla');
		    			$message->to($login["member_email"],$worker["worker_name"])->subject('New Job Notification!');
				});
			}
		}

		public static function sendHirerWithWorkers($loginIds,$jobId){
			$logins = array();
			for($i=0;$i<count($loginIds);$i++){
				$login = LoginModel::find($loginIds[$i]);
				if($login){
					$worker = LoginModel::find($loginIds[$i])->worker;
					$login['worker_name'] = $worker['worker_name'];
					$login['worker_experience'] = $worker['worker_experience'];
					array_push($logins,$login);	
				}
			}
			$login = JobModel::find($jobId)->login;
			$hirer = LoginModel::find($login["id"])->hirer;
			$job_title = JobModel::find($jobId)['job_title'];
			$mailData = array( "name" => $hirer["hirer_orgname"]
							 ,"logins" => $logins
							 ,"job_title"=>$job_title
							 ,'time'=>date('D F j Y')
							 );
			Mail::send("emails.jobFeedback",$mailData, function($message) use (&$login,&$hirer){
				$message->from('noreply@coozilla.com','Coozilla');
	    		$message->to($login["member_email"],$hirer["hirer_orgname"])->subject('Candidates Notification');
			});
		}

	public function forget(){
		$email = Input::get('email');
		$loginModel = LoginModel::where('member_email','=',$email)->first();
		$randomcode = md5(uniqid(mt_rand(), true));
		$loginModel->member_randomcode = $randomcode; 
		$loginModel->save();
		
		$name = '';
		if($loginModel['member_category'] == 1){
			$worker = LoginModel::find($loginModel['id'])->worker;
			$name= $worker['worker_name'];
		}else if($loginModel['member_category'] == 1){
			$hirer = LoginModel::find($loginModel['id'])->hirer;
			$name = $hirer['hirer_orgname'] ;
		}
		
		$mailData = array('name' => $name
						 ,'time'=>date('D F j Y')
						 ,'randomcode'=> $randomcode
						);
		Mail::send("emails.forgetPassword",$mailData, function($message) use (&$email,$name){
			$message->from('noreply@coozilla.com','Coozilla');
    		$message->to($email,$name)->subject('Reset password');
		});
	}

	public function testMail(){
		PushMailController::pushMailWithWorker();
	}
}