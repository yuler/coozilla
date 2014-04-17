<?php
	
	class JobModel extends Eloquent {
		public $table = 'tb_coozilla_job_info';

		//一对多关联 jobSkill
		public function jobSkill(){
    		return $this->hasMany('JobSkillModel','job_id','id');
    	}

    	//反向关联 login
		public function login(){
    		return $this->belongsTo('LoginModel','login_id','id');
    	}
	}
?>
