<?php

class HirerController extends BaseController {

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

	public function save(){
		$data = Input::all();

		$HirerModel = new HirerModel;
		$id = md5(uniqid(mt_rand(), true));
		$HirerModel->id = $id;
		$HirerModel->login_id = $data['login_id'];
		$HirerModel->hirer_orgname = $data['hirer_orgname'];
		$HirerModel->hirer_orgsite = Input::has('hirer_orgsite') ? $data['hirer_orgsite'] : null;
		$HirerModel->hirer_orgaddress = Input::has('hirer_orgaddress') ?$data['hirer_orgaddress'] : null;
		$HirerModel->hirer_image = Input::has('hirer_image') ? $data['hirer_image'] : null;
		$HirerModel->save();

		return $id;
	}

	//修改
	public function update(){
		$data = Input::all();
		$HirerModel = HirerModel::find($data['id']);
		$HirerModel->login_id = $data['login_id'];
		$HirerModel->hirer_orgname = $data['hirer_orgname'];
		$HirerModel->hirer_orgsite = Input::has('hirer_orgsite') ? $data['hirer_orgsite'] : null;
		$HirerModel->hirer_orgaddress = Input::has('hirer_orgaddress') ? $data['hirer_orgaddress'] : null;
		$HirerModel->hirer_image = Input::has('hirer_image') ? $data['hirer_image'] : null;
		$HirerModel->save();
	}

	public function updateExceptImg(){
		$data = Input::all();
		//修改hirer
		if(Input::has('id')){
			$HirerModel = HirerModel::find($data['id']);
			$HirerModel->login_id = $data['login_id'];
			$HirerModel->hirer_orgname = $data['hirer_orgname'];
			$HirerModel->hirer_orgsite = Input::has('hirer_orgsite') ? $data['hirer_orgsite'] : null;
			$HirerModel->hirer_orgaddress = Input::has('hirer_orgaddress') ? $data['hirer_orgaddress'] : null;
			$HirerModel->save();
		}else{
			//如果不存在id 新增
			$HirerModel = new HirerModel;
			$id = md5(uniqid(mt_rand(), true));
			$HirerModel->id = $id;
			$HirerModel->login_id = $data['login_id'];
			$HirerModel->hirer_orgname = $data['hirer_orgname'];
			$HirerModel->hirer_orgsite = Input::has('hirer_orgsite') ? $data['hirer_orgsite'] : null;
			$HirerModel->hirer_orgaddress = Input::has('hirer_orgaddress') ?$data['hirer_orgaddress'] : null;
			$HirerModel->save();
		}
	}
	
	public function updateName(){
		$login = Session::get('login');
		$hirerModel = HirerModel::where('login_id','=',$login['id'])->first();
		if(!$hirerModel){
			$hirerModel = new HirerModel;
			$hirerModel->id = md5(uniqid(mt_rand(), true));
			$hirerModel->login_id = $login['id'];
		}
		$hirerModel->hirer_orgname = Input::has('name') ? Input::get('name') : null;
		$hirerModel->save();
	}

	public function updateUrl(){
		$login = Session::get('login');
		$hirerModel = HirerModel::where('login_id','=',$login['id'])->first();
		if(!$hirerModel){
			$hirerModel = new HirerModel;
			$hirerModel->id = md5(uniqid(mt_rand(), true));
			$hirerModel->login_id = $login['id'];
		}
		$hirerModel->hirer_orgsite = Input::has('url') ? Input::get('url') : null;
		$hirerModel->save();
	}

	public function updateAddress(){
		$login = Session::get('login');
		$hirerModel = HirerModel::where('login_id','=',$login['id'])->first();
		if(!$hirerModel){
			$hirerModel = new HirerModel;
			$hirerModel->id = md5(uniqid(mt_rand(), true));
			$hirerModel->login_id = $login['id'];
		}
		$hirerModel->hirer_orgaddress = Input::has('address') ? Input::get('address') : null;
		$hirerModel->save();
	}
}