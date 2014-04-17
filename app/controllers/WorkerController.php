<?php

class WorkerController extends BaseController {

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

	//校验邮箱是否存在
	public function checkEmail($member_email){
		$loginModel = LoginModel::whereRaw('member_email = ?',array($member_email))->first();
			
		if(!$loginModel){
			//有效帐号
			return  array('state' =>'true','msg'=>"ok");
		}else{
			return  array('state' =>'false','msg'=>"The email is already existing");
		}
	}

	//校验帐号是否存在
	public function checkAccount($member_account){
		$loginModel = LoginModel::whereRaw('member_account = ?',array($member_account))->first();

		if(!$loginModel){
			return array('state' =>'true','msg'=>"ok");
		}else{
			return array('state' =>'false','msg'=>'The account is already existing');
		}
	}
	public function queryWorkerInfo($loginId){
		//$workerModel = workerModel::find($loginId);
		$workerModel = WorkerModel::whereRaw('login_id = ?',array($loginId))->first();
		return $workerModel;
	}
	public function queryWorkerSkill($loginId){
		$skills = WorkerSkillModel::whereRaw('login_id = ?',array($loginId))->get();
		return $skills;
	}

	//保存注册的worker信息
	public function saveWorkerInfo(){
		$data=Input::all();
		//保存worker表字段的信息
		$workerModel = new WorkerModel;
		$workerId = md5(uniqid(mt_rand(), true));
		$workerModel->id = $workerId;
		$workerModel->login_id = $data['login_id'];
		$workerModel->worker_name = $data['worker_name'];
		$workerModel->worker_experience = Input::has('worker_experience')?$data['worker_experience']:null;
		$workerModel->worker_address = Input::has('worker_address')?$data['worker_address']:null;
		$workerModel->worker_telephone = Input::has('worker_telephone')?$data['worker_telephone']:null;
		$workerModel->worker_image = Input::has('worker_image')?$data['worker_image']:null;
		$workerModel->worker_state = Input::has('worker_state')?$data['worker_state']:0;
		$workerModel->save();

		//保存worker的技能
		if(Input::has('skills')){
			for($i =0 ; $i<count($data['skills']) ;$i++){
				$workerSkillModel = new WorkerSkillModel;
				$workerSkillId = md5(uniqid(mt_rand(), true));
				$workerSkillModel->id = $workerSkillId;
				$workerSkillModel->login_id = $data['login_id'];
				$workerSkillModel->skill_key = $data['skills'][$i]['code_key'];
				$workerSkillModel->save();
			}
		}
		return WorkerModel::find($workerId);
	}

	//更新worker的信息
	public function update(){
		$data = Input::all();

		$workerModel = WorkerModel::find($data['id']);
		$workerModel->login_id = $data['login_id'];
		$workerModel->worker_name = $data['worker_name'];
		$workerModel->worker_experience = Input::has('worker_experience')?$data['worker_experience']:null;
		$workerModel->worker_address = Input::has('worker_address')?$data['worker_address']:null;
		$workerModel->worker_telephone = Input::has('worker_telephone')?$data['worker_telephone']:null;
		$workerModel->worker_image = Input::has('worker_image')?$data['worker_image']:null;
		$workerModel->worker_state = Input::has('worker_state')?$data['worker_state']:0;
		$workerModel->save();

		//删除以前的关联
		WorkerSkillModel::where('login_id','=',$data['login_id'])->delete();

		if(Input::has('skills')){
			//保存worker的技能
			for($i =0 ; $i<count($data['skills']) ;$i++){
				$workerSkillModel = new WorkerSkillModel;
				$workerSkillId = md5(uniqid(mt_rand(), true));
				$workerSkillModel->id = $workerSkillId;
				$workerSkillModel->login_id = $data['login_id'];
				$workerSkillModel->skill_key = $data['skills'][$i]['code_key'];
				$workerSkillModel->save();
			}
		}
	}

	//回退是查看worker的信息
	public function findWorkerInfo($loginId){
		$worker = WorkerModel::whereRaw('login_id = ?',array($loginId))->first();
		return $worker;
	}

	//相关技能
	public function relationWorkerSkillArray($loginId){
		/*$jobSkills = JobModel::find($jobId)->jobSkill;
		$result = array();
		foreach ($jobSkills as $jobSkill ) {
			array_push($result,CodeModel::where('code_key','=',$jobSkill['skill_key'])->first()->toArray());
		}
		return $result;*/

		//$skills = workerSkillModel::whereRaw('login_id = ?',array($loginId))->get();
		$workerSkills = LoginModel::find($loginId)->workerSkill;
		$result = array();
		foreach ($workerSkills as $workerSkill ) {
			array_push($result,CodeModel::where('code_key','=',$workerSkill['skill_key'])->first()->toArray());
		}
		return $result;
	}

	//修改
	public function updateInfo(){
		$data = Input::all();
		$WorkerModel = WorkerModel::find($data['id']);
		$WorkerModel->login_id = $data['login_id'];
		$WorkerModel->worker_name = $data['worker_name'];
		$WorkerModel->worker_telephone = Input::has('worker_telephone') ? $data['worker_telephone']:null;
		$WorkerModel->worker_address = Input::has('worker_address') ? $data['worker_address']:null;
		$WorkerModel->worker_experience = Input::has('worker_experience') ? $data['worker_experience']:null;
		$WorkerModel->worker_state = Input::has('worker_state') ? $data['worker_state'] : 0 ;
		$WorkerModel->save();

		//删除以前的关联
		WorkerSkillModel::where('login_id','=',$data['login_id'])->delete();

		if(Input::has('skills')){
			//保存worker的技能
			for($i = 0 ; $i<count($data['skills']) ;$i++){
				$workerSkillModel = new WorkerSkillModel;
				$workerSkillId = md5(uniqid(mt_rand(), true));
				$workerSkillModel->id = $workerSkillId;
				$workerSkillModel->login_id = $data['login_id'];
				$workerSkillModel->skill_key = $data['skills'][$i]['code_key'];
				$workerSkillModel->save();
			}
		}
	}

	public function updateName(){
		$login = Session::get('login');
		$workerModel = WorkerModel::where('login_id','=',$login['id'])->first();
		if(!$workerModel){
			$workerModel = new WorkerModel;
			$workerModel->id = md5(uniqid(mt_rand(), true));
			$workerModel->login_id = $login['id'];
		}
		$workerModel->worker_name = Input::has('name') ? Input::get('name') : null;
		$workerModel->save();
	}

	public function updateAddress(){
		$login = Session::get('login');
		$workerModel = WorkerModel::where('login_id','=',$login['id'])->first();
		if(!$workerModel){
			$workerModel = new WorkerModel;
			$workerModel->id = md5(uniqid(mt_rand(), true));
			$workerModel->login_id = $login['id'];
		}
		$workerModel->worker_address = Input::has('address') ? Input::get('address') : null;
		$workerModel->save();
	}

	public function updateTelephone(){
		$login = Session::get('login');
		$workerModel = WorkerModel::where('login_id','=',$login['id'])->first();
		if(!$workerModel){
			$workerModel = new WorkerModel;
			$workerModel->id = md5(uniqid(mt_rand(), true));
			$workerModel->login_id = $login['id'];
		}
		$workerModel->worker_telephone = Input::has('telephone') ? Input::get('telephone') : null;
		$workerModel->save();
	}

	public function updateExperience(){
		$login = Session::get('login');
		$workerModel = WorkerModel::where('login_id','=',$login['id'])->first();
		if(!$workerModel){
			$workerModel = new WorkerModel;
			$workerModel->id = md5(uniqid(mt_rand(), true));
			$workerModel->login_id = $login['id'];
		}
		$workerModel->worker_experience = Input::has('experience') ? Input::get('experience') : null;
		$workerModel->save();
	}

	public function updateWorkerSkills(){
		$login = Session::get('login');
		$data =  Input::all();
		$workerModel = WorkerModel::where('login_id','=',$login['id'])->first();

		//删除技能
		if($workerModel){
			WorkerSkillModel::where('login_id','=',$login['id'])->delete();
		}
		//保存worker的技能
		if(Input::has('skills')){
			for($i =0 ; $i<count($data['skills']) ;$i++){
				$workerSkillModel = new WorkerSkillModel;
				$workerSkillId = md5(uniqid(mt_rand(), true));
				$workerSkillModel->id = $workerSkillId;
				$workerSkillModel->login_id = $data['login_id'];
				$workerSkillModel->skill_key = $data['skills'][$i]['code_key'];
				$workerSkillModel->save();
			}
		}

	}

	public function putWorkerIdInSession(){
		$workerId = Input::get('workerId');
		Session::put('workerId',$workerId);
	}

	public function getWorkerIdWithSession(){
		return Session::get('workerId');
	}

	public function removeWorkerIdWithSession(){
		Session::forget('workerId');
	}


	public function find(){
		$workerId = Input::get('workerId');
		return WorkerModel::find($workerId);
	}
}