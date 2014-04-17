<?php
	
	class LoginModel extends Eloquent {

		public $table = 'tb_coozilla_mbship_login';

		//一对一   关联 表 tb_coozilla_mbship_hirer 
		public function hirer(){
        	return $this->hasOne('HirerModel','login_id','id');
    	}

    	public function worker(){
    		return $this->hasOne('WorkerModel','login_id','id');
    	}
		
		//一对多关联	tb_coozilla_mbship_worker_skills 表
		public function workerSkill(){
			return $this->hasMany('WorkerSkillModel','login_id','id');
		}

		
	}
?>