<?php

class LoginController extends BaseController {

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

	//效验帐号
	public function checkAccount($account){
		$login = LoginModel::whereRaw('member_account = ? or member_email = ?',array($account,$account))->first();

		if($login){
			//无效帐号
			return $login['member_state'];
		}else{
			return 0;
		}
	}

	//效验session 中是否有用户登录
	public function isExistLogin(){
		$login = Session::get('login');
		if($login){
			return $login;
		}
	}

	//登录
	public function login(){
		$data = Input::all();
		$login = LoginModel::whereRaw('(member_account = ? or member_email = ?) and member_pwd = ? and member_state = ? ',array($data["member_account"],$data["member_account"],md5($data["member_pwd"]),'1'))->first();

		if($login){
			Session::put('login',$login->toArray());
			return $login;
		}
	}

	public function putLoginInSession(){
		$data = Input::all();
		$login = LoginModel::find($data['loginId']);
		if($login){
			Session::put('login',$login->toArray());
			return $login;
		}
	}
	

	//激活账户
	public function activeAccount($loginId){
		LoginModel::where('id','=',$loginId)->update(array('member_state'=>1));
		$html = "
				<div align='center'>
					<div><h2>ActiveSuccess</h2></div>
					<div style=''>
						<h3>
						Automatically jump to Coozilla after
						<input type='text' id='second' size=100 style='font-family:Arial;
									font-weight:bolder; color:gray;
									 background-color:white; padding:0px; border-style:none;'>
						</h3>
					</div>	
					<div align=center>
					<input type=text id='chart' size=100 style='font-family:Arial;
							font-weight:bolder; color:gray;
							 background-color:white; padding:0px; border-style:none;'>

					<input type=text id='percent' size=46 style='font-family:Arial;
					color:gray; 
					border-width:medium; border-style:none;'>
					</div>
				</div>
				<script type='text/javascript'>
					var bar = 0
					var line = '||||';
					var amount ='';
					count();
					function count(){
						bar= bar+2
						amount =amount + line;
						document.getElementById('chart').value=amount;
						document.getElementById('percent').value=bar+'%';
						var second = (50 - bar/2)/10;
						document.getElementById('second').value=  second +' seconds'
						if (bar<100){
							 setTimeout('count()',50);
						}
						else{
							window.location ='http://".$_SERVER ['HTTP_HOST']."';
						}
					}
				</script>;
		";
		$login = LoginModel::find($loginId);
		Session::put('login',$login->toArray());
		return $html;
	}

	//注销帐号
	public function loginOut(){
		Session::forget('login');
	}
	//通过session 关联hirer
	public function relationHirer(){
		return LoginModel::find(Session::get('login')['id'])->hirer;
	}
	//通过loginId 关联
	public function relationHirerById($loginId){
		return LoginModel::find($loginId)->hirer;
	}

	//通过loginId 关联
	public function relationWorkerById($loginId){
		return LoginModel::find($loginId)->worker;
	}

	//保存login
	public function save(){
		$data = Input::all();
		$LoginModel =  new LoginModel;
		$id = md5(uniqid(mt_rand(), true));

		$LoginModel->id = $id;
		$LoginModel->member_state = '3';
		$LoginModel->member_category = $data['member_category'];
		$LoginModel->member_account = $data['member_account'];
		$LoginModel->member_email = $data['member_email'];
		$LoginModel->member_pwd = md5($data['member_pwd']);
		$LoginModel->save();

		return LoginModel::find($id);
	}

	//查询login
	public function find($loginId){
		return LoginModel::find($loginId);
	}

	//修改login
	public function update(){
		$data = Input::all();
		$LoginModel =  LoginModel::find($data['id']);
		$LoginModel->member_state = $data['member_state'];
		$LoginModel->member_category = $data['member_category'];
		$LoginModel->member_account = $data['member_account'];
		$LoginModel->member_email = $data['member_email'];
		$LoginModel->member_pwd = md5($data['member_pwd']);
		$LoginModel->save();
	}

	//遗忘密码
	public function resetPwd(){
		$randomcode = Input::get('randomcode');
		$loginModel = LoginModel::where('member_randomcode','=',$randomcode)->first();
		$loginModel->member_pwd = md5(Input::get('member_pwd'));
		$loginModel->member_randomcode = null;
		$loginModel->save();
		return LoginModel::find($loginModel->id);
	}
	//效验随机码是否存在
	public function checkRandomcode(){
		$randomcode = Input::get('randomcode');
		$loginModel = LoginModel::where('member_randomcode','=',$randomcode)->first();
		if($loginModel){
			return 1;
		}else{
			return 0;
		}
	}
}