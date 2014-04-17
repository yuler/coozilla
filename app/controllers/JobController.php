<?php

class JobController extends BaseController {

	public function save(){
		$data = Input::all();
		$JobModel =  new JobModel;
		$id = md5(uniqid(mt_rand(), true));
		$JobModel->id = $id;
		$JobModel->login_id = $data['login_id'];
		$JobModel->job_title = $data['job_title'];
		$JobModel->job_category = $data['job_category'];
		$JobModel->job_description = $data['job_description'];
		$JobModel->job_state = '0';

		$JobModel->save();

		for($i = 0 ; $i<count($data['skills']);$i++){
			$JobSkillModel = new JobSkillModel;
			$JobSkillModel->id = md5(uniqid(mt_rand(), true));
			$JobSkillModel->job_id = $id;
			$JobSkillModel->skill_key = $data['skills'][$i]['code_key'];
			$JobSkillModel->save();
		}
		
		return $id;
	}
	
	public function update(){
		$data = Input::all();
		$JobModel = JobModel::find($data['id']);
		$JobModel->login_id = $data['login_id'];
		$JobModel->job_title = $data['job_title'];
		$JobModel->job_category = $data['job_category'];
		$JobModel->job_description = $data['job_description'];
		$JobModel->job_state = $data['job_state'];
		$JobModel->save();

		//删除以前的关联
		JobSkillModel::where('job_id','=',$data['id'])->delete();
		//保存新关联
		for($i = 0 ; $i<count($data['skills']);$i++){
			$JobSkillModel = new JobSkillModel;
			$JobSkillModel->id = md5(uniqid(mt_rand(), true));
			$JobSkillModel->job_id = $data['id'];
			$JobSkillModel->skill_key = $data['skills'][$i]['code_key'];
			$JobSkillModel->save();
		}
	}
	
	public function deleteJob(){
		$data = Input::all();
		JobModel::where('id','=',$data['jobId'])->update(array('job_state' => -1));
		//删除以前的关联
		// JobSkillModel::where('job_id','=',$data['jobId'])->update(array('job_state' => -1));
	}

	public function publish(){
		$jobId = Input::get('jobId');
		JobModel::where('id','=',$jobId)->update(array('job_state'=>'1','publish_at'=>date('y-m-d H:i:s')));
	}

	public function queryAll2Console(){
		$jobModel = array();
		$jobC1 = JobModel::whereRaw('job_state = ? and job_category = ?',array(1,1))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		$jobC2 = JobModel::whereRaw('job_state = ? and job_category = ?',array(1,2))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		$jobC3 = JobModel::whereRaw('job_state = ? and job_category = ?',array(1,3))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		
		for($i=0;$i<count($jobC1);$i++){
			$hirer = LoginModel::find($jobC1[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC1[$i]['created_at']);
			$jobC1[$i]['created_at'] = $dateTime->format('M dS l');
		 	$jobC1[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}
		for($i=0;$i<count($jobC2);$i++){
			$hirer = LoginModel::find($jobC2[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC2[$i]['created_at']);
			$jobC2[$i]['created_at'] = $dateTime->format('M dS l');
			$jobC2[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}
		for($i=0;$i<count($jobC3);$i++){
			$hirer = LoginModel::find($jobC3[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC3[$i]['created_at']);
			$jobC3[$i]['created_at'] = $dateTime->format('M dS l');
		 	$jobC3[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}

		array_push($jobModel, $jobC1);
		array_push($jobModel, $jobC2);
		array_push($jobModel, $jobC3);
		return $jobModel;
	}

	public function queryPublishJobsWithPage($pageNo,$pageSize){
		$loginId = Session::get('login')['id'];
		DB::getPaginator()->setCurrentPage($pageNo);
		$result = JobModel::whereRaw('login_id = ? and job_state = ?',array($loginId,1))->orderBy('created_at','desc')->paginate($pageSize);
		return $result;
	}

	public function queryUnpublishedJobsWithPage($pageNo,$pageSize){
		$loginId = Session::get('login')['id'];
		DB::getPaginator()->setCurrentPage($pageNo);
		$result = JobModel::whereRaw('login_id = ? and job_state = ?',array($loginId,0))->orderBy('created_at','desc')->paginate($pageSize);
		return $result;
	}

	public function search(){
		$searchText = Input::get('searchText');
		$jobC1 = JobModel::whereRaw('job_state = ? and job_category = ? and job_title like ?',array(1,1,'%'.$searchText.'%'))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		$jobC2 = JobModel::whereRaw('job_state = ? and job_category = ? and job_title like ?',array(1,2,'%'.$searchText.'%'))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		$jobC3 = JobModel::whereRaw('job_state = ? and job_category = ? and job_title like ?',array(1,3,'%'.$searchText.'%'))->orderBy('created_at','desc')->skip(0)->take(10)->get()->toArray();
		for($i=0;$i<count($jobC1);$i++){
			$hirer = LoginModel::find($jobC1[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC1[$i]['created_at']);
			$jobC1[$i]['job_title'] = str_ireplace($searchText, "<span style='color: red;'>".$searchText."</span>", $jobC1[$i]['job_title']);
			$jobC1[$i]['created_at'] = $dateTime->format('M dS l');
		 	$jobC1[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}
		for($i=0;$i<count($jobC2);$i++){
			$hirer = LoginModel::find($jobC2[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC2[$i]['created_at']);
			$jobC2[$i]['job_title'] = str_ireplace($searchText, "<span style='color: red;'>".$searchText."</span>", $jobC2[$i]['job_title']);
			$jobC2[$i]['created_at'] = $dateTime->format('M dS l');
			$jobC2[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}

		for($i=0;$i<count($jobC3);$i++){
			$hirer = LoginModel::find($jobC3[$i]['login_id'])->hirer;
			$dateTime = new DateTime($jobC3[$i]['created_at']);
			$jobC3[$i]['job_title']  = str_ireplace($searchText, "<span style='color: red;'>".$searchText."</span>", $jobC3[$i]['job_title']);
			$jobC3[$i]['created_at'] = $dateTime->format('M dS l');
		 	$jobC3[$i]['hirer_orgname'] = $hirer['hirer_orgname'];
		}
		$jobModel = array();
		array_push($jobModel, $jobC1);
		array_push($jobModel, $jobC2);
		array_push($jobModel, $jobC3);
		return $jobModel;
	}

	public function findJob($jobId){
		return JobModel::find($jobId);
	}

	public function relationJobSkill($jobId){
		$jobSkills = JobModel::find($jobId)->jobSkill;
		$result = array();
		foreach ($jobSkills as $jobSkill ) {
			$skill_key = CodeModel::select('code_value')->whereRaw('code_key = ?',array($jobSkill['skill_key']))->first()->toArray();
			array_push($result,$skill_key["code_value"]);
		}
		return $result;
	}

	public function relationJobSkillArray($jobId){
		$jobSkills = JobModel::find($jobId)->jobSkill;
		$result = array();
		foreach ($jobSkills as $jobSkill ) {
			array_push($result,CodeModel::where('code_key','=',$jobSkill['skill_key'])->first()->toArray());
		}
		return $result;
	}

	public function putJobIdInSession(){
		$jobId = Input::get('jobId');
		Session::put('jobId',$jobId);
	}

	public function getJobIdWithSession(){
		return Session::get('jobId');
	}

	public function removeJobIdWithSession(){
		Session::forget('jobId');
	}
}