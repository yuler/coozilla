<?php

class UploadController extends BaseController {

	public function updateHirerImg(){
		$data = Input::all();
		if(Input::has('fileOld')){
			$hirerModel = HirerModel::where('hirer_image','=',$data['fileOld'])->first();
			$hirerModel->hirer_image = $data['fileNew'];
			$hirerModel->save();
		}else{
			$login_id = Session::get('login')['id'];
			$hirerModel = HirerModel::where('login_id','=',$login_id)->first();
			if($hirerModel){
				$hirerModel->hirer_image = $data['fileNew'];
				$hirerModel->save();	
			}else{
				$hirerModel = new HirerModel;
				$hirerModel->id = md5(uniqid(mt_rand(), true));
				$hirerModel->login_id = Session::get('login')['id'];
				$hirerModel->hirer_image = $data['fileNew'];
				$hirerModel->save();
			}
			
		}	
	}

	public function updateWorkerImg(){
		$data = Input::all();
		if(Input::has('fileOld')){
			$workerModel = workerModel::where('worker_image','=',$data['fileOld'])->first();
			$workerModel->worker_image = $data['fileNew'];
			$workerModel->save();
		}else{
			$login_id = Session::get('login')['id'];
			$workerModel = workerModel::where('login_id','=',$login_id)->first();
			if(!$workerModel){
				$workerModel = new workerModel;
				$workerModel->id = md5(uniqid(mt_rand(), true));
				$workerModel->login_id = Session::get('login')['id'];
			}
			$workerModel->worker_image = $data['fileNew'];
			$workerModel->save();
		}
	}

}
