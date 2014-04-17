<?php

class WorkerSkillController extends BaseController {

	public function queryWorkerSkill($loginId){
		$skills = workerSkillModel::whereRaw('login_id = ?',array($loginId))->get();
		return $skills;
	}

}