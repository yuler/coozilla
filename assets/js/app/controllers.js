angular.module('controllers',[])

.controller('HomeCtrl',function($scope,$timeout,$location,$filter,JobService,LoginService){
	if(user_role == ''){
		$scope.jobBtn = true;
		$scope.resumeBtn = true;
	}else if(user_role == 1){
		$scope.jobBtn = false;
		$scope.resumeBtn = false;
	}else if(user_role == 2){
		$scope.jobBtn = true;
		$scope.resumeBtn = false;
	}
	//发布工作
	$scope.postJob = function(){
		$location.path('post/job/new');
	}
	//注册worker
	$scope.postWork = function(){
		$location.path('post/work/new');
	}
})
.controller('DetailCtrl',function($scope,$location,$routeParams,JobService,LoginService){
	JobService.findJob($routeParams.jobId).success(function(job){
		$scope.job = job;
		$('.jobDescription').append($scope.job.job_description);
		JobService.relationJobSkill($scope.job.id).success(function(data){
			$scope.job.skills = data;
		});

		LoginService.relationHirerById(job.login_id).success(function(hirer){
			$scope.hirer = hirer;
		});

		LoginService.find(job.login_id).success(function(login){
			$scope.login = login;
		});
	});
})

//worker new
.controller('WorkCtrl',function($rootScope,$scope,$location,WorkerRegisterService,CommonService,WorkerService,LoginService,PushMailService,WorkerService){

	if($scope.worker != undefined && $scope.worker.worker_experience != undefined){
		angular.element('#editor').append($scope.worker.worker_experience);	
	}

	//校验邮箱是否存在
	$scope.checkEmail = function(){
		if($scope.login != undefined && $scope.login.member_email != undefined && $scope.login.member_email!=''){
			//是否合法
			var myreg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!myreg.test($scope.login.member_email)){
                $scope.emailmsg = 'Email address format is not correct ';
           	}else{
           		WorkerRegisterService.checkEmail($scope.login.member_email).success(function(data){
					$scope.emailmsg = data.msg;
					if(data.state=='true'){
						$scope.emailmsg = '';
					}else{
						$scope.emailmsg ='Is exist this mail';
					}
				})
           	}
			
		}	
	};
	//校验帐号是否存在
	$scope.checkAccount = function(){
		if($scope.login != undefined && $scope.login.member_account != undefined){
			WorkerRegisterService.checkAccount($scope.login.member_account).success(function(data){
				$scope.accountmsg = data.msg;
				if(data.state == 'true'){
					$scope.accountmsg = '';
				}else if(data.state == 'false'){
					$scope.accountmsg = 'Is exist this account';
				}
			})
		}
	};
	//校验两次输入的密码是否一致
	$scope.checkPassword = function(){
		if($scope.login != undefined && $scope.login.member_pwd1 != undefined){
			if($scope.login.member_pwd != $scope.login.member_pwd1){
				//$scope.passwordstyle = 'glyphicon glyphicon-remove';
				$scope.passwordmsg = 'Your passwords do not match'
			}else if($scope.login.member_pwd == $scope.login.member_pwd1){
				//$scope.passwordstyle = 'glyphicon glyphicon-ok';
				$scope.passwordmsg = '';
			}
		}
	};

	//如果存在workerId
	WorkerService.getWorkerIdWithSession().success(function(workerId){
		if(workerId){
			WorkerService.find(workerId).success(function(worker){
				WorkerRegisterService.findWorkerInfo(worker.login_id).success(function(data){
					$scope.worker = data;
					//通过jquery 给skill 赋上值
					WorkerRegisterService.relationWorkerSkillArray(worker.login_id).success(function(data){
						$scope.worker.skills = data;
						$scope.refreshSelected();
					});
				});
				//login
				LoginService.find(worker.login_id).success(function(data){
					$scope.login = data;
					$scope.login.member_pwd1 = $scope.login.member_pwd;
				});
			});
		}else{
			$location.path('/post/work/new')
		}
	});
	

	//绑定注册合约程序员的方法
	$scope.registerAccount = function(){
		//upload图片地址绑定到model中
		//$scope.worker.login_id = $scope.login.id;
		if($scope.worker != undefined){
			$scope.worker.worker_experience = angular.element('#editor').code();
			$scope.worker.worker_image = angular.element('#fileUploadImage').attr('src');
		}

		$scope.emailmsg = '';
		$scope.accountmsg = '';
		$scope.passwordmsg1 = '';
		$scope.passwordmsg = '';
		$scope.nameErrorMsg = '';
		$scope.addressErrorMsg = '';
		$scope.telephoneErrorMsg = '';
		$scope.skillsErrorMsg = '';
		$scope.continueErrorMsg = '';
		$scope.experienceErrorMsg = '';

		//非空验证
		if($scope.login == undefined || $scope.login.member_email == undefined || $scope.login.member_email == ''){
			$scope.emailmsg = 'Please enter your email.';
		}
		if($scope.login == undefined || $scope.login.member_account == undefined || $scope.login.member_account == ''){
			$scope.accountmsg = 'Please enter your account.';
		}
		if($scope.login == undefined || $scope.login.member_pwd == undefined || $scope.login.member_pwd == ''){
			$scope.passwordmsg = 'Please enter your password.';
		}
		if($scope.login == undefined || $scope.login.member_pwd1 == undefined || $scope.login.member_pwd1 == ''){
			$scope.passwordmsg1 = 'Please enter your repassword.';
		}
		if($scope.worker == undefined || $scope.worker.worker_name == undefined || $scope.worker.worker_name == ''){
			$scope.nameErrorMsg = 'Please enter your name.';
		}
		if($scope.worker == undefined || $scope.worker.worker_address == undefined || $scope.worker.worker_address == ''){
			$scope.addressErrorMsg = 'Please enter address.';
		}
		if($scope.worker == undefined || $scope.worker.worker_telephone == undefined || $scope.worker.worker_telephone == ''){
			$scope.telephoneErrorMsg = 'Please enter telephone.';
		}
		if($scope.worker == undefined || $scope.worker.worker_experience == undefined || $scope.worker.worker_experience == ''){
			$scope.experienceErrorMsg = 'Please enter experience.';
		}
		if($scope.worker == undefined || $scope.worker.skills == undefined || $scope.worker.skills.length < 0){
			$scope.skillsErrorMsg = 'Please enter your skill.';
		}

		//在没与错误的条件下才执行下一步
		if($scope.nameErrorMsg == '' && $scope.addressErrorMsg == '' && $scope.telephoneErrorMsg == '' && $scope.skillsErrorMsg == '' ){
			//没有错误则保存登录信息和保存worker的信息
			$scope.login.member_category = '1';
			//用户id存在则进行修改操作不存在则进行保存操作
			WorkerService.getWorkerIdWithSession().success(function(workerId){
				if(workerId){
					//修改登录信息
					LoginService.update($scope.login).success(function(data){
						WorkerRegisterService.update($scope.worker);//修改
						$location.path('/post/work/preview/');
					});
				}else{
					LoginService.save($scope.login).success(function(data){
						$scope.login = data;
						$scope.worker.login_id = $scope.login.id;
						//保存worker的信息
						WorkerRegisterService.saveWorkerInfo($scope.worker).success(function(worker){
							WorkerService.putWorkerIdInSession(worker.id).success(function(){
								PushMailService.sendActiveMail($scope.login).success(function(){
									$location.path('/post/work/preview/');//发送激活邮件
								});
							});
						})
					});
				}
			});
		}
		else{
			$scope.continueErrorMsg = 'Please check your inputs with red tips';
		}
		
	};

	//技能下拉初始化
	CommonService.queryAllSkill().success(function(data){
		$scope.skillsOption = data;
		$scope.refreshSelected();
	});
	//选择技能
	$scope.selectSkill = function(){
       $('#skill').selectpicker('refresh');
       //alert($scope.worker.skills.length);
	};
	//每次删除一个技能
	$scope.removeSkill = function(index){
		//$scope.skills = {};
		$scope.worker.skills.splice(index,1);
		$scope.refreshSelected();
	};
	//删除所有的技能
	$scope.removeAllSelect = function(){
		$scope.worker.skills = {};
		$scope.refreshSelected();
	};

	//邮箱激活帐号
	$scope.sendActiveMail = function(){
		//设置login 类型为 雇佣者
		//$scope.login.member_category = '1';
		if($scope.login != undefined && $scope.login.member_email!=undefined && $scope.login.member_account!=undefined&& $scope.login.member_pwd!=undefined&& $scope.login.member_pwd1!=undefined && $scope.login.member_pwd == $scope.login.member_pwd1){
			//alert('undefined');
				//将表单控制为禁用
			$scope.accountInput = true;
			//将按钮控制为禁用
			$scope.sendActiveMailClass = true;
			$scope.login.member_category = '1';
			LoginService.save($scope.login).success(function(data){
				$scope.login = data;
				PushMailService.sendActiveMail($scope.login);
			});
		}if($scope.login == undefined || $scope.login.member_email == undefined || $scope.login.member_email == ''){
			$scope.emailmsg = 'Please enter the email address';
		}if($scope.login == undefined || $scope.login.member_account == undefined || $scope.login.member_account == ''){
			$scope.accountmsg = 'Please enter the account';
		}if($scope.login == undefined || $scope.login.member_pwd == undefined || $scope.login.member_pwd == ''){
			$scope.passwordmsg1 = 'Please enter the password';
			if($scope.login == undefined || $scope.login.member_pwd1 == undefined || $scope.login.member_pwd1 == ''){
				$scope.passwordmsg = 'Please enter the password again';
			}
		}if($scope.login == undefined || $scope.login.member_pwd == undefined && $scope.login.member_pwd != $scope.login.member_pwd1){
			$scope.passwordmsg = 'Your passwords do not match'
		}
		
	};

})

//worker预览WorkPreviewCtrl
.controller('WorkPreviewCtrl',function($scope,$rootScope,$location,WorkerRegisterService,WorkerService,LoginService,WorkerSkillService){

	WorkerService.getWorkerIdWithSession().success(function(workerId){
		if(workerId){
			WorkerService.find(workerId).success(function(worker){
			//查找登录表的信息
			LoginService.find(worker.login_id).success(function(data){
					$scope.loginModel = data;
				});
				//查找worker的信息
				WorkerRegisterService.queryWorkerInfo(worker.login_id).success(function(data1){
					$scope.workerModel = data1;
					angular.element('.experience').append($scope.workerModel.worker_experience);
				});
				//查找worker拥有的技能
				/*WorkerSkillService.queryWorkerSkill($routeParams.loginId).success(function(data){
					$scope.skills =data;
				});*/

				//通过jquery 给skill 赋上值
				WorkerRegisterService.relationWorkerSkillArray(worker.login_id).success(function(data){
					if($scope.worker == undefined){
						$scope.worker = {};
					}
					if($scope.worker.skills){
						$scope.worker.skills = {};
					}
					$scope.worker.skills = data;
					//$scope.refreshSelected();
				});
			})

			//angular.element('.experience').append($scope.worker.worker_experience);

			//购买完成程序员信息
			$scope.finishAccount = function(){

				//检查用户的状态，若用户的状态没激活则提示用户激活，否则跳转到下一个页面
				LoginService.checkAccount($scope.loginModel.member_account).success(function(stateCode){
					if(stateCode == 3){
						$scope.activeErrorMsg = 'Please active your account' ;
					}else{
						//$location.path('/post/job/publish/'+$scope.job.id);
						$location.path('/post/work/finished');
					}
				});
			};

			//返回到填写简历的页面，即第一步
			$scope.backCreate = function(){
				$location.path('/post/work/new/');
			};
		}else{
			window.location.href = contextPath + '/#!/post/work/new';
		}
	});
	
})

//Worker注册完成WorkFinishedCtrl
.controller('WorkFinishedCtrl',function($scope,$rootScope,$location,WorkerRegisterService,WorkerService,LoginService,PushMailService){
	WorkerService.getWorkerIdWithSession().success(function(workerId){
		if(workerId){
			//保存注册信息
			$scope.saveAccount = function(){
				WorkerService.find(workerId).success(function(worker){
					worker.worker_state = 1;
					WorkerRegisterService.relationWorkerSkillArray(worker.login_id).success(function(data){
						worker.skills = data;
						WorkerService.update(worker).success(function(){});
					});

					LoginService.putLoginInSession(worker.login_id).success(function(){
						PushMailService.pushMailWithWorker(worker.login_id).success(function(){

						});
						WorkerService.removeWorkerIdWithSession().success(function(){
							window.location.href = contextPath +'/#!/home/profile';
							window.location.reload();
						});
					});
				});
			};	
			//返回到预览
			$scope.backPreview = function(){
				$location.path('/post/work/preview');
			};
		}else{
			$location.path('/post/work/new')
		}
	})

	
})

//JobCtrl
.controller('JobNewCtrl',function($rootScope,$scope,$location,JobService,LoginService,CommonService,HirerService,PushMailService){
	
	//如果存在jobId
	JobService.getJobIdWithSession().success(function(jobId){
		if(jobId){
			JobService.findJob(jobId).success(function(job){
				$scope.job = job;

				//通过jquery 给category 赋上值
				angular.element('#job_category').val($scope.job.job_category);
				angular.element('#job_category').selectpicker('refresh');
				//通过jquery 给skill 赋上值
				JobService.relationJobSkillArray($scope.job.id).success(function(data){
					// angular.element('#job_skills').selectpicker('refresh');
					$scope.job.skills = data;
					$scope.refreshSelected();
				});
				//hirer
				LoginService.relationHirerById(job.login_id).success(function(hirer){
					$scope.hirer = hirer;
				});
				//login
				LoginService.find(job.login_id).success(function(login){
					$scope.login = login;
					$scope.login.member_pwd2 = $scope.login.member_pwd;
				});	
			});	
		}else{
			$location.path('/post/job/new');
		}
	});
	
	$scope.titleErrorMsg = '';
	$scope.categoryErrorMsg ='';
	$scope.descriptionErrorMsg = '';
	$scope.skillsErrorMsg  = '';
	$scope.accountErrorMsg ='';
	$scope.emailErrorMsg = '';
	$scope.pwdErrorMsg = '';
	$scope.pwd2ErrorMsg = '';
	$scope.nameErrorMsg = '';
	$scope.continueErrorMsg = '';

	//技能下拉初始化
	CommonService.queryAllSkill().success(function(job){
		$scope.skillsOption = job;
		$scope.refreshSelected();
	});
	$scope.selectSkill = function(){
		if($scope.job.skills.length >= 5){
			$('#job_skills').prop('disabled',true);
         	$('#job_skills').selectpicker('refresh');
		}
	};
	$scope.removeSkill = function(index){
		if($scope.job.skills.length < 6){
			$('#job_skills').prop('disabled',false);
		}
		$scope.job.skills.splice(index,1);
		$scope.refreshSelected();
	};
	$scope.removeAllSelect = function(){
		$scope.job.skills = {};
		$('#job_skills').prop('disabled',false);
		$scope.refreshSelected();
	};

	//判断是否存在用户      控制显示
	LoginService.isExistLogin().success(function(login){
		if(login.member_category == 2){
			$scope.existAccount = true;
			$scope.login = login;
			LoginService.relationHirer().success(function(hirer){
				$scope.hirer = hirer;
			});
		}else{
			$scope.existAccount = false;
		}
	});
	
	//Account nav 切换
	$scope.active_new = true;
	$scope.activeNew = function(){
		//样式
		$scope.active_new = true;
		$scope.active_old = false;
	};
	$scope.activeOld = function(){
		//样式
		$scope.active_new = false;
		$scope.active_old = true;
	};

	//新用户
	//绑定表单验证
	//帐号是否存在验证
	$scope.isExistAccount = function(){
		if($scope.login != undefined && $scope.login.member_account != undefined && $scope.login.member_account !=''){
			LoginService.checkAccount($scope.login.member_account).success(function(data){
				if(data != 0){
					$scope.accountErrorMsg ='Is exist this account.';
				}else{
					$scope.accountErrorMsg ='';
				}
			});
		}
	}
	//邮箱验证
	$scope.checkMail = function(){
		if($scope.login != undefined && $scope.login.member_email != undefined && $scope.login.member_email != ''){
			//是否合法
			var myreg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!myreg.test($scope.login.member_email)){
                $scope.emailErrorMsg = 'Email address format is error.';
           	}else{
           			//是否存在验证
				LoginService.checkAccount($scope.login.member_email).success(function(data){
					if(data != 0){
						$scope.emailErrorMsg = 'Is exist this mail.';
					}else{
						$scope.emailErrorMsg = '';
					}
				});
           	}
		}
	}
	//密码重复校验
	$scope.checkPwd = function(){
		if($scope.login != undefined && $scope.login.member_pwd2 !='' && $scope.login.member_pwd != $scope.login.member_pwd2){
			$scope.pwd2ErrorMsg = 'Entered passwords differ';
		}else{
			$scope.pwd2ErrorMsg = '';
		}
	}

	//老用户
	//效验帐号
	$scope.checkAccount = function(){
		//如果不存在就不发送请求
		if($scope.login != undefined && $scope.login.member_account != undefined && $scope.login.member_account !=''){
			LoginService.checkAccount($scope.login.member_account).success(function(data){
				if(data == 0){
					$scope.checkAccountMsg = 'Account does not exist';
					$scope.checkAccountClass='icon-question-sign';
				}else if(data == 1){
					$scope.checkAccountMsg = ''
					$scope.checkAccountClass='icon-ok-sign';
				}else if(data == 2){
					$scope.checkAccountMsg = 'Account is invalid';
					$scope.checkAccountClass='icon-ban-circle';
				}else if(data == 3){
					$scope.checkAccountMsg = 'Account not activated';
					$scope.checkAccountClass='icon-exclamation-sign';
				}else if(data == 4){
					$scope.checkAccountMsg = 'Account is locked';
					$scope.checkAccountClass='icon-lock';
				}
			});
		}
	};
	//登录
	$scope.loginAccount = function(){
		LoginService.login($scope.login).success(function(login){
			if(login.member_category == 2){
				$scope.login = login;
				LoginService.relationHirer().success(function(hirer){
					$scope.hirer = hirer;
					$scope.existAccount = true;
				});
			}else if(login.member_category == 1){
				LoginService.loginOut();
				$scope.checkPasswordMsg = 'This account is worker.';
				$scope.checkPasswordClass = 'icon-remove-sign';
			}
			else{
				$scope.checkPasswordMsg = 'Password error.';
				$scope.checkPasswordClass = 'icon-remove-sign';
			}
		});
	};
	//注销
	$scope.loginOut = function(){
		LoginService.loginOut();
		//控制显示
		$scope.existAccount = false;
		//情况相关元素
		$scope.login = {};
		$scope.hirer = {};
		$scope.checkAccountMsg = '';
		$scope.checkAccountClass = '';
	};
	

	//step2  下一步
	$scope.continueClass = false;
	

	$scope.continueBtn = function(){
		//upload图片地址绑定到model中
		if($scope.hirer == undefined){
			$scope.hirer = {};
		}
		$scope.hirer.hirer_image = angular.element('#fileUploadImage').attr('src');

		//将editor中的内容绑定到model中
		if($scope.job == undefined){
			$scope.job = {};
		}
		$scope.job.job_description = angular.element('#editor').code();

		$scope.titleErrorMsg = '';
		$scope.categoryErrorMsg ='';
		$scope.descriptionErrorMsg = '';
		$scope.skillsErrorMsg  = '';
		$scope.nameErrorMsg = '';
		$scope.pwdErrorMsg = '';
		$scope.continueErrorMsg = '';
		//非空验证
		if($scope.job == undefined || $scope.job.job_title == undefined || $scope.job.job_title == ''){
			$scope.titleErrorMsg = 'Please enter the job title';
		}
		if($scope.job == undefined || $scope.job.job_category == undefined || $scope.job.job_category == ''){
			$scope.categoryErrorMsg = 'Please select the job category';
		}
		if($scope.job == undefined || $scope.job.job_description == undefined || $scope.job.job_description == ''){
			$scope.descriptionErrorMsg = 'Please enter the job description';
		}
		if($scope.job == undefined || $scope.job.skills == undefined || $scope.job.skills.length == undefined){
			$scope.skillsErrorMsg = 'Please select job skill';
		}
		if($scope.login == undefined || $scope.login.member_account == undefined || $scope.login.member_account == ''){
			$scope.accountErrorMsg = 'Please enter your account';
		}
		if($scope.login == undefined || $scope.login.member_email == undefined || $scope.login.member_email == ''){
			$scope.emailErrorMsg = 'Please enter your Email';
		}
		if($scope.login == undefined || $scope.login.member_pwd == undefined || $scope.login.member_pwd == ''){
			$scope.pwdErrorMsg = 'Please enter your password';
		}
		if($scope.login == undefined || $scope.login.member_pwd2 == undefined || $scope.login.member_pwd2 == ''){
			$scope.pwd2ErrorMsg = 'Please enter your password';
		}
		if($scope.hirer == undefined || $scope.hirer.hirer_orgname == undefined || $scope.hirer.hirer_orgname == ''){
			$scope.nameErrorMsg = 'Please enter your name';
		}

		//没有错误条件的时候才执行下一步
		if($scope.titleErrorMsg == '' && $scope.categoryErrorMsg == '' && $scope.descriptionErrorMsg == '' && $scope.skillsErrorMsg == '' ){
			//判断用户是否登录
			$scope.continueClass = true;
			//loading....
			angular.element('#loadingModal').modal({
				keyboard: true,
				backdrop:'static'
			});
			LoginService.isExistLogin().success(function(login){
				if(login){
					$scope.login = login;
					$scope.job.login_id = $scope.login.id;
					//修改job信息
					if($scope.job.id){
						LoginService.update($scope.login);
						HirerService.update($scope.hirer);
						JobService.update($scope.job);
						JobService.putJobIdInSession($scope.job.id).success(function(){
							window.location.href = contextPath + '/#!/post/job/preview/';
							window.location.reload();
						});
					//新增job信息
					}else{
						JobService.save($scope.job).success(function(jobId){
							$scope.job.id = jobId;
							JobService.putJobIdInSession($scope.job.id).success(function(){
								window.location.href = contextPath + '/#!/post/job/preview/';
								window.location.reload();
							});
						});
					}
					
				}else{
					if($scope.accountErrorMsg == '' && $scope.emailErrorMsg == '' &&  $scope.pwdErrorMsg == '' && $scope.pwd2ErrorMsg ==''){
						$scope.login.member_category = '2';
						//修改job
						if($scope.job.id){
							LoginService.update($scope.login);
							HirerService.update($scope.hirer);
							JobService.update($scope.job);
							JobService.putJobIdInSession($scope.job.id).success(function(){
								window.location.href = contextPath + '/#!/post/job/preview/';
								window.location.reload();
							});
						}
						//新增job
						else{
							LoginService.save($scope.login).success(function(login){
								$scope.login = login;
								$scope.hirer.login_id = $scope.login.id;
								//如果用户未激活 发送激活邮件
								if($scope.login.member_state == 3){
									PushMailService.sendActiveMail($scope.login);
								}
								HirerService.save($scope.hirer).success(function(hirerId){
									$scope.hirer.id = hirerId;
									$scope.job.login_id = $scope.login.id;
									JobService.save($scope.job).success(function(jobId){
										$scope.job.id = jobId;
										JobService.putJobIdInSession($scope.job.id).success(function(){
											window.location.href = contextPath + '/#!/post/job/preview/';
											window.location.reload();
										});
									});
								});
							});
						}
					}else{
						$scope.continueErrorMsg = 'Please check your inputs with red tips';
					}
				}
			});
		}else{
			$scope.continueErrorMsg = 'Please check your inputs with red tips';
		}
	};

})

//JobPreviewCtrl
.controller('JobPreviewCtrl',function($rootScope,$scope,$location,LoginService,JobService){
	JobService.getJobIdWithSession().success(function(jobId){
		if(jobId){
			JobService.findJob(jobId).success(function(job){
				$scope.job = job;
				//通过jquery 赋上html值
				angular.element('.jobDescription').append($scope.job.job_description);

				JobService.relationJobSkill($scope.job.id).success(function(data){
					$scope.job.skills = data
				});

				LoginService.relationHirerById(job.login_id).success(function(hirer){
					$scope.hirer = hirer;
				});

				LoginService.find(job.login_id).success(function(login){
					$scope.login = login;
				});

				$scope.continueBtn = function(){
					LoginService.checkAccount($scope.login.member_account).success(function(stateCode){
						if(stateCode == 3){
							$scope.activeErrorMsg = 'Please go to the email to activate your account' ;
						}else{
							//loading....
							angular.element('#loadingModal').modal({
								keyboard: true,
								backdrop:'static'
							});
							window.location.href = contextPath+'/#!/post/job/publish/';
							window.location.reload();
						}
					})
				}
				$scope.backLink = function(){
					//注销session
					LoginService.loginOut();
					//loading....
					angular.element('#loadingModal').modal({
						keyboard: true,
						backdrop:'static'
					});
					window.location.href = contextPath+'/#!/post/job/new/';
					window.location.reload();
				}
			});
		}else{
			$location.path('/post/job/new/');
		}
	});
	

})

//JobPublishCtrl
.controller('JobPublishCtrl',function($scope,$location,LoginService,JobService,HirerService,PushMailService){
	JobService.getJobIdWithSession().success(function(jobId){
		if(jobId){
			//上一步
			$scope.previousStep	= function(){
				//loading....
				angular.element('#loadingModal').modal({
					keyboard: true,
					backdrop:'static'
				});
				// $location.path('/post/job/preview/'+$routeParams.jobId);
				window.location.href = contextPath+'/#!/post/job/preview/'
				window.location.reload();
			}

			//发布工作
			$scope.publish = function(){
				$scope.continueClass = true;
				//loading....
				angular.element('#loadingModal').modal({
					keyboard: true,
					backdrop:'static'
				});
				JobService.findJob(jobId).success(function(job){
					LoginService.putLoginInSession(job.login_id).success(function(){
						JobService.publish(jobId).success(function(data){
							JobService.removeJobIdWithSession().success(function(){});
							PushMailService.pushMailWithJob(jobId).success(function(data){
								
							});
							window.location.href = contextPath+'/#!/home/hirer/jobManage';
							window.location.reload();
						});
					});
				});
			};
		}else{
			$location.path('/post/job/new');
		}
	})
})

//headCtrl
.controller('headCtrl',function($scope,$location,LoginService){
	$scope.logout = function(){
		LoginService.loginOut().success(function(){
			window.location.href = contextPath;
		});
	};
	$scope.home = function(){
		LoginService.isExistLogin().success(function(login){
			$location.path('/');
		})
	};
	$scope.profile = function(){
		LoginService.isExistLogin().success(function(login){
			if(login){
				$location.path('/home/profile');
			}
		})
	};
	$scope.resumeManage = function(){
		LoginService.isExistLogin().success(function(login){
			if(login){
				$location.path('/home/worker/resume')
			}
		})
	};
	$scope.jobManage = function(){
		LoginService.isExistLogin().success(function(login){
			if(login){
				$location.path('/home/hirer/jobManage')
			}
		})	
	}
	$scope.signIn = function(){
		$location.path('/signIn')
	}
	$scope.jEmployer = function(){
		$location.path('/signUp')
	}
	$scope.jEmployee = function(){
		$location.path('/post/work/new')
	}
})

//signInCtrl
.controller('signInCtrl',function($scope,$location,LoginService,PushMailService){
	$scope.blur_username = function(){
		if($scope.login != undefined && $scope.login.member_account != undefined && $scope.login.member_account != ''){
			LoginService.checkAccount($scope.login.member_account).success(function(stateCode){
				if(stateCode == 3){
					$scope.usernameInput = true;
					$scope.usernameErrorMsg = 'The account inactive.';
				}else if(stateCode != 1){
					$scope.usernameInput = true;
					$scope.usernameErrorMsg = 'The account does not exist.';
				}else{
					$scope.usernameInput = false;
					$scope.usernameErrorMsg = '';
				}
			});
		}else{
			$scope.usernameInput = false;
			$scope.usernameErrorMsg = '';
		}
	};
	$scope.signIn = function(){
		if($scope.login && $scope.login.member_account && $scope.login.member_pwd){
			if($scope.usernameErrorMsg == ''){
				LoginService.login($scope.login).success(function(login){
					if(!login){
						$scope.passwordInput = true;
						$scope.passwordErrorMsg = 'Password error.';
					}else{
						window.location.href = contextPath+"#!/home/profile";
						window.location.reload();
					}
				});
			}
		}else{
			alert('Please enter your account info.');
		}
	};
})

//signUpCtrl
.controller('signUpCtrl',function($scope,$location,LoginService,PushMailService){
	//switch
	$scope.login = {};
	$scope.login.member_category = 2;
	angular.element('#member_category').bootstrapSwitch();  
	angular.element('#member_category').on('switch-change', function (e, data) {
		$scope.login.member_category =  data.value ? 2: 1;
		if($scope.login.member_category == 1){
			window.location.href = contextPath+'/#!/post/work/new';
		}
	});

	
	//check
	$scope.blur_account = function(){
		if($scope.login.member_account){
			var reg = /^[a-zA-Z0-9_-]{6,16}$/;
			if(reg.test($scope.login.member_account)){
				$scope.accountErrorMsg = '';
				$scope.accountInput = false;
				LoginService.checkAccount($scope.login.member_account).success(function(data){
					if(data != 0){
						$scope.accountErrorMsg = 'Is exist this account.';
						$scope.accountInput = true;
					}
				});		
			}else{
				$scope.accountErrorMsg = 'Illegal account.';
				$scope.accountInput = true;
			}
		}
		
	}
	$scope.blur_email = function(){
		if($scope.login.member_email){
			var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(reg.test($scope.login.member_email)){
				$scope.mailErrorMsg = '';
				$scope.mailInput = false;
				LoginService.checkAccount($scope.login.member_email).success(function(data){
					if(data == 1){
						$scope.mailErrorMsg = 'Is exist this mail.';
						$scope.mailInput = true;
					}
				});		
			}else{
				$scope.mailErrorMsg = 'Email address format.';
				$scope.mailInput = true;
			}
		}
		
	}
	$scope.blur_pwd = function(){
		if($scope.login.member_pwd){
			var reg = /^[a-zA-Z0-9_-]{6,18}$/;
			if(!reg.test($scope.login.member_pwd)){
				$scope.pwdErrorMsg = 'invalid password';
				$scope.pwdInput = true;
			}else{
				$scope.pwdErrorMsg = '';
				$scope.pwdInput = false;
			}
		}
		
	}
	$scope.blur_pwd2 = function(){
		if($scope.login.member_pwd != $scope.login.member_pwd2){
			$scope.pwd2ErrorMsg = 'Password not the same';
			$scope.pwd2Input = true;
		}else{
			$scope.pwd2ErrorMsg = '';
			$scope.pwd2Input = false;
		}
	}
	//sign up
	$scope.signUp = function(){
		if($scope.accountErrorMsg == '' && $scope.mailErrorMsg == '' && $scope.pwdErrorMsg == '' && $scope.pwd2ErrorMsg == ''){
			LoginService.save($scope.login).success(function(login){
				PushMailService.sendActiveMail(login).success(function(){});
				$location.path('/error');
			});
			
		}
	}
})

.controller('homeHirerCtrl',function($scope,$location,$routeParams,LoginService,HirerService,JobService,CommonService){
	LoginService.isExistLogin().success(function(login){
		if(login.member_category == 2){
			LoginService.checkAccount(login.member_account).success(function(stateCode){
				//未激活
				if(stateCode == 3){
					$scope.menu = false;
					$scope.errorMsg = 'Please go to your email box to activate your account.'
					$scope.templateUrl = 'view/error.html';
				}else{
					$scope.menu = true;
					$scope.login = login;
					LoginService.relationHirerById($scope.login.id).success(function(hirer){
						$scope.hirer = hirer;
					});
					$scope.templateUrl = 'view/home/hirer/default.html';
					//menu
					$scope.infoView = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							LoginService.find(Sessionlogin.id).success(function(login){
								$scope.login = login;
								LoginService.relationHirerById($scope.login.id).success(function(hirer){
									$scope.hirer = hirer;
								});
							})
						});
						$scope.templateUrl = 'view/home/hirer/info_view.html';
					};
					$scope.infoUpdate = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							LoginService.find(Sessionlogin.id).success(function(login){
								$scope.login = login;
								LoginService.relationHirerById($scope.login.id).success(function(hirer){
									if(hirer){
										$scope.hirer = hirer;
									}else{
										$scope.hirer = {'login_id':$scope.login.id};
									}
								});
							})
						});
						$scope.update = function(){
							LoginService.update($scope.login).success(function(){
								HirerService.updateExceptImg($scope.hirer).success(function(){});
							});
						};
						$scope.templateUrl = 'view/home/hirer/info_update.html';
					};
					$scope.publishJobsView = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							JobService.queryPublishJobsWithPage(1).success(function(page){
								$scope.jobs = page.data;
								$scope.pageData = page;
								var pages = new Array();
								for (var i = 1; i <= $scope.pageData.last_page ; i++) {
									pages.push(i);
								};
								$scope.pageData.pages = pages;
								$scope.link = function(pageNo){
									JobService.queryPublishJobsWithPage(pageNo).success(function(page){
										$scope.jobs = page.data;
										$scope.pageData = page;
										var pages = new Array();
										for (var i = 1; i <= $scope.pageData.last_page ; i++) {
											pages.push(i);
										};
										$scope.pageData.pages = pages;
									});
								};
							});
							$scope.detail = function(jobId){
								JobService.findJob(jobId).success(function(job){
									$scope.job = job;
									angular.element('#job_category').val($scope.job.job_category);
									setTimeout("angular.element('#job_category').selectpicker('refresh')",1);
									angular.element('#editor').append($scope.job.job_description);
									angular.element('#editor').wysiwyg();
									JobService.relationJobSkillArray(jobId).success(function(data){
										$scope.job.skills = data;
										angular.element('#job_skills').selectpicker('refresh');
									})
								});
								$scope.selectSkill = function(){
									if($scope.job.skills.length >= 5){
										angular.element('#job_skills').prop('disabled',true);
							         	angular.element('#job_skills').selectpicker('refresh');
									}
								};
								$scope.removeAllSelect = function(){
									$scope.job.skills = {};
									angular.element('#job_skills').prop('disabled',false);
									setTimeout("angular.element('#job_skills').selectpicker('refresh')",1);
								};
								CommonService.queryAllSkill().success(function(data){
									$scope.skillsOption = data;
									angular.element('#job_skills').selectpicker('refresh');
								});	
								$scope.update = function(){
									$scope.job.job_description = angular.element('#editor').html();
									JobService.update($scope.job);
									$scope.publishJobsView();
								}
								$scope.templateUrl = 'view/home/hirer/jobDetail_view.html';
							};
						});
						$scope.click_checkbox = function(jobId,index){
							if($scope.jobs[index].checkbox == undefined || $scope.jobs[index].checkbox == false){
								$scope.jobs[index].checkbox = true;
								angular.element("input[name='"+jobId+"']").parent().addClass('checked');
							}else{
								$scope.jobs[index].checkbox = false ;
								angular.element("input[name='"+jobId+"']").parent().removeClass('checked');
							}
						}
						$scope.checkAll = function(){
							for (var i = 0; i < $scope.jobs.length; i++) {
								$scope.jobs[i].checkbox = true;
								angular.element("input[name='"+$scope.jobs[i].id+"']").parent().addClass('checked');
							};
						}
						$scope.checkNone = function(){
							for (var i = 0; i < $scope.jobs.length; i++) {
								$scope.jobs[i].checkbox = false;
								angular.element("input[name='"+$scope.jobs[i].id+"']").parent().removeClass('checked');
							};
						}
						$scope.deleteJob = function(){
							var count=0;
							for (var i = 0; i < $scope.jobs.length; i++){
								if($scope.jobs[i].checkbox == true){
									JobService.deleteJob($scope.jobs[i].id);
									$scope.publishedJobsView();
								}else{
									count ++;
								}
							};
							if(count == $scope.jobs.length){
								alert('Please check the job.');
							}
						}
						$scope.newJob = function(){
							$location.path('/post/job/new');
						}
						$scope.templateUrl = 'view/home/hirer/publishJobs_view.html';
					};
					$scope.unpublishedJobsView = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							JobService.queryUnpublishedJobsWithPage(1).success(function(page){
								$scope.jobs = page.data;
								$scope.pageData = page;
								var pages = new Array();
								for (var i = 1; i <= $scope.pageData.last_page ; i++) {
									pages.push(i);
								};
								$scope.pageData.pages = pages;
								$scope.link = function(pageNo){
									JobService.queryUnpublishedJobsWithPage(pageNo).success(function(page){
										$scope.jobs = page.data;
										$scope.pageData = page;
										var pages = new Array();
										for (var i = 1; i <= $scope.pageData.last_page ; i++) {
											pages.push(i);
										};
										$scope.pageData.pages = pages;
									});
								};
							});
							$scope.detail = function(jobId){
								JobService.findJob(jobId).success(function(job){
									$scope.job = job;
									angular.element('#job_category').val($scope.job.job_category);
									setTimeout("angular.element('#job_category').selectpicker('refresh')",1);
									angular.element('#editor').append($scope.job.job_description);
									angular.element('#editor').wysiwyg();
									JobService.relationJobSkillArray(jobId).success(function(data){
										$scope.job.skills = data;
										angular.element('#job_skills').selectpicker('refresh');
									});
								});
								$scope.selectSkill = function(){
									if($scope.job.skills.length >= 5){
										angular.element('#job_skills').prop('disabled',true);
							         	angular.element('#job_skills').selectpicker('refresh');
									}
								};
								$scope.removeAllSelect = function(){
									$scope.job.skills = {};
									angular.element('#job_skills').prop('disabled',false);
									setTimeout("angular.element('#job_skills').selectpicker('refresh')",1);
								};
								CommonService.queryAllSkill().success(function(data){
									$scope.skillsOption = data;
									angular.element('#job_skills').selectpicker('refresh');
								});	
								$scope.update = function(){
									$scope.job.job_description = angular.element('#editor').html();
									JobService.update($scope.job);
									$scope.unpublishedJobsView();
								}
								$scope.templateUrl = 'view/home/hirer/jobDetail_view.html';
							};
						});
						$scope.click_checkbox = function(jobId,index){
							if($scope.jobs[index].checkbox == undefined || $scope.jobs[index].checkbox == false){
								$scope.jobs[index].checkbox = false;
								$scope.jobs[index].checkbox = !$scope.jobs[index].checkbox ;
								angular.element("input[name='"+jobId+"']").parent().addClass('checked');
							}else{
								$scope.jobs[index].checkbox = !$scope.jobs[index].checkbox ;
								angular.element("input[name='"+jobId+"']").parent().removeClass('checked');
							}
						}
						$scope.checkAll = function(){
							for (var i = 0; i < $scope.jobs.length; i++) {
								$scope.jobs[i].checkbox = true;
								angular.element("input[name='"+$scope.jobs[i].id+"']").parent().addClass('checked');
							};
						}
						$scope.checkNone = function(){
							for (var i = 0; i < $scope.jobs.length; i++) {
								$scope.jobs[i].checkbox = false;
								angular.element("input[name='"+$scope.jobs[i].id+"']").parent().removeClass('checked');
							};
						}
						$scope.deleteJob = function(){
							var count=0;
							for (var i = 0; i < $scope.jobs.length; i++){
								if($scope.jobs[i].checkbox == true){
									JobService.deleteJob($scope.jobs[i].id);
									$scope.unpublishedJobsView();
								}else{
									count ++;
								}
							};
							if(count == $scope.jobs.length){
								alert('Please check the job.');
							}
						}
						$scope.newJob = function(){
							$location.path('/post/job/new');
						}
						$scope.templateUrl = 'view/home/hirer/unpublishedJobs_view.html';
					};
				}
			});	
		}else if(!login){
			$scope.menu = false;
			$scope.errorMsg = 'Sorry.  Your have not sign in.';
			$scope.templateUrl = 'view/error.html';
		}else{
			$scope.menu = false;
			$scope.errorMsg = 'Sorry.  Your have sign in worker.';
			$scope.templateUrl = 'view/error.html';
		}
		
	});
	
})

.controller('homeWorkerCtrl',function($scope,LoginService,WorkerService,WorkerRegisterService,CommonService){
	LoginService.isExistLogin().success(function(login){
		if(login.member_category == 1){
			LoginService.checkAccount(login.member_account).success(function(stateCode){
				//未激活
				if(stateCode == 3){
					$scope.menu = false;
					$scope.errorMsg = 'Sorry.  First,please according email to activate your account.'
					$scope.templateUrl = 'view/error.html';
				}else{
					$scope.menu = true;
					$scope.login = login;
					LoginService.relationWorkerById($scope.login.id).success(function(worker){
						$scope.worker = worker;
						//console.debug($scope.worker);
						WorkerRegisterService.relationWorkerSkillArray($scope.login.id).success(function(data){
							$scope.worker.skills = data;
						});
						angular.element('.experience').append($scope.worker.worker_experience);
					});
					
					$scope.templateUrl = 'view/home/worker/default.html';
					//menu
					$scope.infoView = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							LoginService.find(Sessionlogin.id).success(function(login){
								$scope.login = login;
								LoginService.relationWorkerById($scope.login.id).success(function(worker){
									$scope.worker = worker;
									WorkerRegisterService.relationWorkerSkillArray($scope.login.id).success(function(data){
										$scope.worker.skills = data;
									});
									/*if($scope.worker.worker_experience){
										$scope.worker.worker_experience = {};
									}*/
									angular.element('.experience').append($scope.worker.worker_experience);																	
								});
								
							})
						});
						$scope.templateUrl = 'view/home/worker/info_view.html';
					}
					$scope.infoUpdate = function(){
						LoginService.isExistLogin().success(function(Sessionlogin){
							LoginService.find(Sessionlogin.id).success(function(login){
								$scope.login = login;
								LoginService.relationWorkerById($scope.login.id).success(function(worker){
									$scope.worker = worker;
									WorkerRegisterService.relationWorkerSkillArray($scope.login.id).success(function(data){
										$scope.worker.skills = data;
									});
									angular.element('#editor').append($scope.worker.worker_experience);
								});
								angular.element('#editor').wysiwyg();
							});
							CommonService.queryAllSkill().success(function(data){
								$scope.skillsOption = data;
								setTimeout("angular.element('#skill').selectpicker('refresh');",1)
							});
						});
						$scope.update = function(){
							LoginService.update($scope.login).success(function(){
								$scope.worker.worker_experience = angular.element('#editor').html();
								WorkerService.update($scope.worker).success(function(){});

							});
						}
						$scope.removeAllSelect = function(){
							$scope.worker.skills = {};
							angular.element('#worker_skills').prop('disabled',false);
							setTimeout("angular.element('#worker_skills').selectpicker('refresh')",1);
						};
						//技能下拉初始化
						
						$scope.templateUrl = 'view/home/worker/info_update.html';

					}
					$scope.passUpdate = function(){
						$scope.templateUrl = 'view/home/worker/pass_update.html';
					}
				}
			});
		}else if(!login){
			$scope.menu = false;
			$scope.errorMsg = 'Sorry.  Your have not sign in.';
			$scope.templateUrl = 'view/error.html';
		}else{
			$scope.menu = false;
			$scope.errorMsg = 'Sorry.  Your have sign in hirer.';
			$scope.templateUrl = 'view/error.html';
		}
		
	});
	
})

//forgetCtrl
.controller('forgetCtrl',function($scope,$location,LoginService,PushMailService){
	$scope.blur_email = function(){
		if($scope.member_email){
			var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(reg.test($scope.member_email)){
				$scope.mailErrorMsg = '';
				$scope.mailInput = false;
				LoginService.checkAccount($scope.member_email).success(function(data){
					if(data != 1){
						$scope.mailErrorMsg = 'The mail is not exist.';
						$scope.mailInput = true;
					}
				});		
			}else{
				$scope.mailErrorMsg = 'Email address format.';
				$scope.mailInput = true;
			}
		}
	};

	$scope.forget = function(){
		setTimeout(function(){
			if($scope.mailErrorMsg == '' && $scope.member_email){
				PushMailService.forget($scope.member_email).success(function(){
				});
				$location.path('/signIn');
			}
		},1000);
		
	};
})


//resetPasswordCtrl
.controller('resetPasswordCtrl',function($scope,$location,$routeParams,LoginService){
	LoginService.checkRandomcode($routeParams.randomcode).success(function(data){
		if(data == 0){
			$location.path('/forget');
			setTimeout(function(){
				var interval;
				$('#title').grumble({
						angle: 130,
						hideAfter: 10000,
						text: 'This password reset link is invalid.',
						distance: 10,
						hideOnClick: true,
						onShow: function(){
							var angle = 130, dir = 1;
							interval = setInterval(function(){
								(angle > 190 ? (dir=-1, angle--) : ( angle < 130 ? (dir=1, angle++) : angle+=dir));
								$('#title').grumble('adjust',{angle: angle});
							},25);
						},
						onHide: function(){
							clearInterval(interval);
						}
					}
				);
			},1000);
		}
	})
	$scope.blur_pwd = function(){
		if($scope.login && $scope.login.member_pwd){
			var reg = /^[a-zA-Z0-9_-]{6,18}$/;
			if(!reg.test($scope.login.member_pwd)){
				$scope.pwdErrorMsg = 'invalid password';
				$scope.pwdInput = true;
			}else{
				$scope.pwdErrorMsg = '';
				$scope.pwdInput = false;
			}
		}
		
	}
	$scope.blur_pwd2 = function(){
		if($scope.login && $scope.login.member_pwd && $scope.login.member_pwd2){
			if($scope.login.member_pwd != $scope.login.member_pwd2){
			$scope.pwd2ErrorMsg = 'Password not the same';
			$scope.pwd2Input = true;
			}else{
				$scope.pwd2ErrorMsg = '';
				$scope.pwd2Input = false;
			}
		}
	}

	$scope.resetPwd = function(){
		if($scope.login && $scope.login.member_pwd && $scope.login.member_pwd2 && $scope.pwd2ErrorMsg == '' && $scope.pwdErrorMsg == ''){
			$scope.login.randomcode = $routeParams.randomcode;
			LoginService.resetPwd($scope.login).success(function(login){
				LoginService.login(login).success(function(){
					if(login.member_category == 2){
						window.location.href = contextPath+"#!/home/hirer";
					}else if(login.member_category == 1){
						window.location.href = contextPath+"#!/home/worker";
					}
				});
			});
		}
	}	
})

//ProfileCtrl
.controller('ProfileCtrl',function($scope,$location,LoginService,HirerService,WorkerService){
	if(user_role == ''){
		$location.path('/')
	}else{
		LoginService.isExistLogin().success(function(sessionLogin){
			$scope.login = sessionLogin;
			if($scope.login.member_category == 2){
				LoginService.relationHirer().success(function(hirer){
					if(hirer){
						$scope.hirer = hirer;
					}else{
						$scope.warningMsg = 'Please improve your profile.';
						$scope.name = !$scope.name;
						$scope.url = !$scope.url;
						$scope.address = !$scope.address;
					}
				})
				$scope.changeAccount = function(){
					$scope.account = !$scope.account;
				};
				$scope.changePassword = function(){
					$scope.password = !$scope.password;
				};
				$scope.changeEmail = function(){
					$scope.email = !$scope.email;
				};
				$scope.changeName = function(){
					$scope.name = !$scope.name;
					HirerService.updateName($scope.hirer.hirer_orgname).success(function(){
						LoginService.relationHirer().success(function(hirer){
							$scope.hirer.hirer_orgname = hirer.hirer_orgname;
						})
					});
				};
				$scope.changeUrl = function(){
					$scope.url = !$scope.url;
					HirerService.updateUrl($scope.hirer.hirer_orgsite).success(function(){
						LoginService.relationHirer().success(function(hirer){
							$scope.hirer.hirer_orgsite = hirer.hirer_orgsite;
						})
					});
				};
				$scope.changeAddress = function(){
					$scope.address = !$scope.address;
					HirerService.updateAddress($scope.hirer.hirer_orgaddress).success(function(){
						LoginService.relationHirer().success(function(hirer){
							$scope.hirer.hirer_orgaddress = hirer.hirer_orgaddress;
						})
					});
				};
			}else if($scope.login.member_category == 1){
				LoginService.relationWorkerById($scope.login.id).success(function(worker){
					if(worker){
						$scope.worker = worker;
					}else{
						$scope.warningMsg = 'Please improve your profile and resume.';
						$scope.name = !$scope.name;
						$scope.address = !$scope.address;
						$scope.telephone = !$scope.telephone;
					}
				});
				$scope.changeName = function(){
					$scope.name = !$scope.name;
					WorkerService.updateName($scope.worker.worker_name).success(function(){
						LoginService.relationWorkerById($scope.login.id).success(function(worker){
							$scope.worker.worker_name = worker.worker_name;
						})
					})
				};
				$scope.changeAddress = function(){
					$scope.address = !$scope.address;
					WorkerService.updateAddress($scope.worker.worker_address).success(function(){
						LoginService.relationWorkerById($scope.login.id).success(function(worker){
							$scope.worker.worker_address = worker.worker_address;
						})
					})
				};
				$scope.changeTelephone = function(){
					$scope.telephone = !$scope.telephone;
					WorkerService.updateTelephone($scope.worker.worker_telephone).success(function(){
						LoginService.relationWorkerById($scope.login.id).success(function(worker){
							$scope.worker.worker_telephone = worker.worker_telephone;
						})
					})
				};
				
			}
		});
	}
})

//ResumeCtrl
.controller('ResumeCtrl',function($scope,$location,LoginService,WorkerService,WorkerSkillService,CommonService,WorkerRegisterService){
	if(user_role == ''){
		$location.path('/')
	}else{
		LoginService.isExistLogin().success(function(sessionLogin){
			$scope.login = sessionLogin;
			LoginService.relationWorkerById($scope.login.id).success(function(worker){
				$scope.worker = worker;
				angular.element('#experience').html($scope.worker.worker_experience);

				WorkerRegisterService.relationWorkerSkillArray($scope.login.id).success(function(data){
					$scope.worker.skills = data;
				})
			});
		});
		
		CommonService.queryAllSkill().success(function(data){
			$scope.skillsOption = data;
			$scope.refreshSelected();
		})

		$scope.removeSkill = function(index){
			$scope.worker.skills.splice(index,1);
			$scope.refreshSelected();
		}
		$scope.removeAll = function(){
			$scope.worker.skills = {};
			$scope.refreshSelected();
		}

		$scope.changeExperience = function(){
			$scope.experience = !$scope.experience;
			WorkerService.updateExperience(angular.element('#editor').code()).success(function(){
				LoginService.relationWorkerById($scope.login.id).success(function(worker){
					$scope.worker = worker;
					angular.element('#experience').html($scope.worker.worker_experience);

					WorkerRegisterService.relationWorkerSkillArray($scope.login.id).success(function(data){
						$scope.worker.skills = data;
					})
				})
			})
		};
		$scope.changeWorkerSkills = function(){
			$scope.workerSkills = !$scope.workerSkills;
			WorkerService.updateWorkerSkills($scope.worker).success(function(data){
				
			});
		}
	}
})


//JobManageCtrl
.controller('JobManageCtrl',function($scope,$location,JobService){
	if(user_role == ''){
		$location.path('/')
	}else{
			var pageSize = 10;

			$scope.publishJobsBtn = function(){
				JobService.queryPublishJobsWithPage(1,pageSize).success(function(page){
					$scope.page = page;
					var pages = new Array();
		            for (var i = 1; i <= $scope.page.last_page ; i++) {
		                pages.push(i);
		            };
		            $scope.page.pages = pages;
		            $scope.linkBtn = function(pageNo){
				        JobService.queryPublishJobsWithPage(pageNo,pageSize).success(function(page){
							$scope.page = page;
							var pages = new Array();
				            for (var i = 1; i <= $scope.page.last_page ; i++) {
				                pages.push(i);
				            };
				            $scope.page.pages = pages;
						});
				    }
				});
				$scope.deleteJob = function(){
					var count=0;
					for (var i = 0; i < $scope.page.data.length; i++){
						if($scope.page.data[i].checkbox == true){
							JobService.deleteJob($scope.page.data[i].id);
						}else{
							count ++;
						}
					};
					
					if(count == $scope.page.data.length){
						alert('Please check the job.');
					}else{
						$scope.publishJobsBtn();
					}
				}
			};


			$scope.publishJobsBtn();

			$scope.unpublishJobsBtn = function(){
				JobService.queryUnpublishedJobsWithPage(1,pageSize).success(function(page){
					$scope.page = page;
					var pages = new Array();
		            for (var i = 1; i <= $scope.page.last_page ; i++) {
		                pages.push(i);
		            };
		            $scope.page.pages = pages;
		            $scope.linkBtn = function(pageNo){
				        JobService.queryUnpublishedJobsWithPage(pageNo,pageSize).success(function(page){
							$scope.page = page;
							var pages = new Array();
				            for (var i = 1; i <= $scope.page.last_page ; i++) {
				                pages.push(i);
				            };
				            $scope.page.pages = pages;
						});
				    }
				});

				$scope.deleteJob = function(){
					var count=0;
					for (var i = 0; i < $scope.page.data.length; i++){
						if($scope.page.data[i].checkbox == true){
							JobService.deleteJob($scope.page.data[i].id);
						}else{
							count ++;
						}
					};

					if(count == $scope.page.data.length){
						alert('Please check the job.');
					}else{
						$scope.unpublishJobsBtn();
					}
				}
			};

			$scope.edit = function(jobId){
		    	$location.path('/home/hirer/editJob/'+jobId);
		    };

		    $scope.click_checkbox = function(jobId,index){
		    	// if($scope.page.data[index].checkbox == undefined || $scope.page.data[index].checkbox == false){
		    	// 	$scope.page.data[index].checkbox = false;
		    	// 	$scope.page.data[index].checkbox = !$scope.page.data[index].checkbox ;
		    	// 	angular.element("input[name='"+jobId+"']").parent().addClass('checked');
		    	// }else{
		    	// 	$scope.page.data[index].checkbox = !$scope.page.data[index].checkbox ;
		    	// 	angular.element("input[name='"+jobId+"']").parent().removeClass('checked');
		    	// }
		    	$scope.page.data[index].checkbox = !$scope.page.data[index].checkbox;

		    	// $scope.job.checkbox = !$scope.job.checkbox;
		    }
		    $scope.checkAll = function(){
		    	// for (var i = 0; i < $scope.page.data.length; i++) {
		    	// 	$scope.page.data.checkbox = true;
		    	// 	angular.element("input[name='"+$scope.page.data[i].id+"']").parent().addClass('checked');
		    	// };
		    	for(var i = 0 ; i< $scope.page.data.length ; i++){
		    		$scope.page.data[i].checkbox = true;
		    	}
		    }
		    $scope.checkInvert = function(){
		    	// for (var i = 0; i < $scope.page.data.length; i++) {
		    	// 	$scope.page.data[i].checkbox = false;
		    	// 	angular.element("input[name='"+$scope.page.data[i].id+"']").parent().removeClass('checked');
		    	// };
		    	for(var i = 0 ; i< $scope.page.data.length ; i++){
		    		if(typeof($scope.page.data[i].checkbox) != undefined){
		    			$scope.page.data[i].checkbox = !$scope.page.data[i].checkbox;
		    		}else{
		    			$scope.page.data[i].checkbox = true;
		    		}
		    	}
		    }
		  
		    $scope.newJob = function(){
		    	$location.path('/post/job/new');
		    }
	}
})

//EditJobCtrl
.controller('EditJobCtrl',function($scope,$routeParams,$location,JobService,CommonService){
	if(user_role == ''){
		$location.path('/')
	}else{
		JobService.findJob($routeParams.jobId).success(function(job){
			$scope.job = job ;
			angular.element('#job_category').val($scope.job.job_category);
			setTimeout("angular.element('#job_category').selectpicker('refresh')",1);

			JobService.relationJobSkillArray($routeParams.jobId).success(function(skills){
				$scope.job.skills = skills;
			})
		})

		CommonService.queryAllSkill().success(function(data){
			$scope.skillsOption = data;
			$scope.refreshSelected();
		})

		$scope.removeAllSelect = function(){
			$scope.job.skills = {};
			$scope.refreshSelected();
		}

		$scope.save = function(){
			$scope.job.job_description = angular.element('#editor').code();
			JobService.update($scope.job).success(function(data){
				$location.path('/home/hirer/jobManage')
			});
		}
	}
})

.controller('ErrorCtrl',function($scope){
	$scope.errorMsg = 'Please go to your email box to activate your account.'
})
;

