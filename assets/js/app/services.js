angular.module('services',['ngRoute'])

.factory('LoginService',['$http','$location',function($http,$location){
	return {
		// 效验帐号  返回状态码  0不存在, 1有效, 2无效 禁用, 3未激活, 4锁定
		checkAccount:function(Account){
			return $http.get('LoginController/LoginService/checkAccount/'+Account);
		},
		// 获取login  如果session中存在login 返回login 否则返回空
		isExistLogin:function(){
			return $http.get('LoginController/LoginService/isExistLogin');
		},
		//登录 登录成功返回login   否则无返回
		login:function(login){
			return $http.post('LoginController/LoginService/login',login);
		},
		putLoginInSession:function(loginId){
			return $http.post('LoginController/LoginService/putLoginInSession',{loginId:loginId});	
		},
		//注销 无返回
		loginOut:function(){
			return $http.get('LoginController/LoginService/loginOut')
		},
		//通过session关联hirer 返回hirer
		relationHirer:function(){
			return $http.get('LoginController/LoginService/relationHirer');
		},
		//通过loginId 返回hirer
		relationHirerById:function(loginId){
			return $http.get('LoginController/LoginService/relationHirerById/'+loginId);
		},
		//保存login 返回login
		save:function(login){
			return $http.post('LoginController/LoginService/save',login);
		},
		//根据loginId查询login 返回login
		find:function(loginId){
			return $http.get('LoginController/LoginService/find/'+loginId);
		},
		update:function(login){
			return $http.post('LoginController/LoginService/update',login);
		},
		//通过loginId 返回worker
		relationWorkerById:function(loginId){
			return $http.get('LoginController/LoginService/relationWorkerById/'+loginId);
		},
		//修改密码  返回修改后的login
		resetPwd:function(login){
			return $http.post('LoginController/LoginService/resetPwd',login);
		},
		//效验随机码是否存在 如果存在 return 1 否则 return 0;
		checkRandomcode:function(randomcode){
			return $http.post('LoginController/LoginService/checkRandomcode',{randomcode:randomcode});	
		}
	}

}])

.factory('CommonService',['$http','$location',function($http,$location){
	return {
		//查询字典表所有技能  返回skill数组
		queryAllSkill:function(){
			return $http.get('CommonController/CommonService/queryAllSkill');
		}
	}
}])
.factory('WorkerRegisterService',['$http','$location',function($http,$location){
	return {
		//保存注册的worker信息
		saveWorkerInfo:function(worker){
			return $http.post('WorkerController/WorkerRegisterService/workerRegister',worker);
		},
		checkEmail:function(member_email){
			return $http.get('WorkerController/WorkerRegisterService/checkEmail/'+member_email);
		},
		checkAccount:function(member_account){
			return $http.get('WorkerController/WorkerRegisterService/checkAccount/'+member_account);
		},
		//保存login 返回login
		save:function(worker){
			return $http.post('WorkerController/WorkerRegisterService/save',worker);
		},
		queryWorkerInfo:function(loginId){
			return $http.get('WorkerController/WorkerRegisterService/queryWorkerInfo/'+loginId);
		},
		queryWorkerSkill:function(loginId){
			return $http.get('WorkerController/WorkerRegisterService/queryWorkerSkill/'+loginId);
		},
		update:function(worker){
			return $http.post('WorkerController/WorkerRegisterService/update',worker);
		},
		findWorkerInfo:function(loginId){
			return $http.get('WorkerController/WorkerRegisterService/findWorkerInfo/'+loginId);
		},
		relationWorkerSkillArray:function(loginId){
			return $http.get('WorkerController/WorkerRegisterService/relationWorkerSkillArray/'+loginId);
		}

	} 

}])
.factory('WorkerSkillService',['$http','$location',function($http,$location){
	return{
		queryWorkerSkill:function(loginId){
			return $http.get('WorkerSkillController/WorkerSkillService/queryWorkerSkill/'+loginId);
		}
	}
}])
.factory('JobService',['$http','$location',function($http,$location){
	return {
		//保存job 无返回
		save:function(job){
			return $http.post('JobController/JobService/save',job);
		},
		//删除工作 和 相关的技能
		deleteJob:function(jobId){
			return $http.post('JobController/JobService/deleteJob',{jobId:jobId});
		},
		//修改job 无返回
		update:function(job){
			return $http.post('JobController/JobService/update',job);
		},
		//发布工作
		publish:function(jobId){
			return $http.post('JobController/JobService/publish',{jobId:jobId});
		},
		//查询最新job 每类查十条
		queryAll2Console:function(){
			return $http.get('JobController/JobService/queryAll2Console');
		},
		queryPublishJobsWithPage:function(pageNo,pageSize){
			return $http.get('JobController/JobService/queryPublishJobsWithPage/'+pageNo+'/'+pageSize);
		},
		queryUnpublishedJobsWithPage:function(pageNo,pageSize){
			return $http.get('JobController/JobService/queryUnpublishedJobsWithPage/'+pageNo+'/'+pageSize);
		},
		//根据jobId查询详细 job
		findJob:function(jobId){
			return $http.get('JobController/JobService/findJob/'+jobId);
		},
		//一对多关联jobSkill 返回jobSkill 数组
		relationJobSkillArray:function(jobId){
			return $http.get('JobController/JobService/relationJobSkillArray/'+jobId);
		},
		//一对多关联jobSkill 再一对一关联CommonCode 返回所有skillValue数组
		relationJobSkill:function(jobId){
			return $http.get('JobController/JobService/relationJobSkill/'+jobId);
		},
		search:function(searchText){
			return $http.post('JobController/JobService/search',{searchText:searchText});	
		},
		putJobIdInSession:function(jobId){
			return $http.post('JobController/JobService/putJobIdInSession',{jobId:jobId});
		},
		getJobIdWithSession:function(){
			return $http.post('JobController/JobService/getJobIdWithSession');
		},
		removeJobIdWithSession:function(){
			return $http.post('JobController/JobService/removeJobIdWithSession');
		}
	}
}])

.factory('HirerService',['$http','$location',function($http,$location){
	return {
		//保存hirer 无返回
		save:function(hirer){
			return $http.post('HirerController/HirerService/save',hirer);
		},
		update:function(hirer){
			return $http.post('HirerController/HirerService/update',hirer);
		},
		updateExceptImg:function(hirer){
			return $http.post('HirerController/HirerService/updateExceptImg',hirer);	
		},
		updateName:function(name){
			return $http.post('HirerController/HirerService/updateName',{name:name});	
		},
		updateUrl:function(url){
			return $http.post('HirerController/HirerService/updateUrl',{url:url});	
		},
		updateAddress:function(address){
			return $http.post('HirerController/HirerService/updateAddress',{address:address});	
		}
	}
}])

.factory('PushMailService',['$http','$location',function($http,$location){
	return {
		//发送激活邮件 
		sendActiveMail:function(login){
			return $http.post('PushMailController/PushMailService/sendActiveMail',login);
		},
		pushMailWithJob:function(jobId){
			return $http.post('PushMailController/PushMailService/pushMailWithJob',{jobId:jobId})
		},
		pushMailWithWorker:function(loginId){
			return $http.post('PushMailController/PushMailService/pushMailWithWorker',{loginId:loginId})
		},
		forget:function(email){
			return $http.post('PushMailController/PushMailService/forget',{email:email})
		}
	}
}])

.factory('WorkerService',['$http','$location',function($http,$location){
	return {
		//保存hirer 无返回
		/*save:function(worker){
			return $http.post('HirerController/HirerService/save',hirer);
		},*/
		find:function(workerId){
			return $http.post('WorkerController/WorkerService/find',{workerId:workerId});
		},
		update:function(worker){
			return $http.post('WorkerController/WorkerService/update',worker);
		},
		updateName:function(name){
			return $http.post('WorkerController/WorkerService/updateName',{name:name});
		},
		updateAddress:function(address){
			return $http.post('WorkerController/WorkerService/updateAddress',{address:address});
		},
		updateTelephone:function(telephone){
			return $http.post('WorkerController/WorkerService/updateTelephone',{telephone:telephone});
		},
		updateExperience:function(experience){
			return $http.post('WorkerController/WorkerService/updateExperience',{experience:experience});
		},
		updateWorkerSkills:function(worker){
			return $http.post('WorkerController/WorkerService/updateWorkerSkills',worker);
		},
		putWorkerIdInSession: function(workerId){
			return $http.post('WorkerController/WorkerService/putWorkerIdInSession',{workerId:workerId});	
		},
		getWorkerIdWithSession: function(){
			return $http.post('WorkerController/WorkerService/getWorkerIdWithSession');	
		},
		removeWorkerIdWithSession :function(){
			return $http.post('WorkerController/WorkerService/removeWorkerIdWithSession');	
		}

	}
}])
;