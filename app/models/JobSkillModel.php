<?php
	
	class JobSkillModel extends Eloquent {
		public $table = 'tb_coozilla_jobinfo_skills';

		public function skillValue(){
        	return $this->hasOne('CodeModel','code_key','skill_key');
    	}

	}
?>