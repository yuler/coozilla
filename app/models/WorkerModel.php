<?php
	
	class WorkerModel extends Eloquent {
		public $table = 'tb_coozilla_mbship_worker';

		public function user(){
        	return $this->hasOne('LoginModel','id','login_id');
    	}
	}
?>