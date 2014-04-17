angular.module('app',[
	'ngRoute',
	'controllers',
	'services',
	'directives',
	'filters'
])
.config(function($routeProvider,$locationProvider){
	$locationProvider.hashPrefix('!');
	$routeProvider
	//Home
	.when('/',{
		controller:'HomeCtrl',
		templateUrl:'view/home.html',
		title : "CooZilla"
	})
	
	// .when('/worker/detail/',{
	// 	controller:'WorkerCtrl',
	// 	templateUrl:'view/work/workerhome.html'
	// })
	
	//post a worker
	.when('/post/work/new',{
		controller:'WorkCtrl',
		templateUrl:'view/work/new.html',
		title : "Create Resume"
	})
	.when('/post/work/preview',{
		controller:'WorkPreviewCtrl',
		templateUrl:'view/work/preview.html',
		title : "Preview Resume"
	})
	.when('/post/work/finished',{
		controller:'WorkFinishedCtrl',
		templateUrl:'view/work/finished.html',
		title : "Publish Resume"
	})
	//post a job
	.when('/job/detail/:jobId',{
		controller:'DetailCtrl',
		templateUrl:'view/job/detail.html',
		title : "Job Detail"
	})
	.when('/post/job/new',{
		controller:'JobNewCtrl',
		templateUrl:'view/job/new.html',
		title : "Create Job"
	})
	.when('/post/job/preview',{
		controller:'JobPreviewCtrl',
		templateUrl:'view/job/preview.html',
		title : "Preview Job"
	})
	.when('/post/job/publish',{
		controller:'JobPublishCtrl',
		templateUrl:'view/job/publish.html',
		title : "Publish Job"
	})
	
	//sign
	.when('/signIn',{
		controller:'signInCtrl',
		title : "Sign In",
		templateUrl:'view/sign/signIn.html'
	})
	.when('/signUp',{
		controller:'signUpCtrl',
		templateUrl:'view/sign/signUp.html',
		title : "Sign Up"
	})
	.when('/forget',{
		controller:'forgetCtrl',
		templateUrl:'view/sign/forget.html',
		title : "Forget Passwrod"
	})
	.when('/forget/:randomcode',{
		controller:'resetPasswordCtrl',
		templateUrl:'view/sign/resetPasswrod.html',
		title : "Reset Passwrod"
	})
	// .when('/home/hirer',{
	// 	controller:'homeHirerCtrl',
	// 	templateUrl:'view/home/hirer.html'
	// })
	// .when('/home/worker',{
	// 	controller:'homeWorkerCtrl',
	// 	templateUrl:'view/home/worker.html'
	// })

	//home
	.when('/home/profile',{
		controller:'ProfileCtrl',
		templateUrl:'view/home/profile.html',
		title : "Profile"
	})
	.when('/home/worker/resume',{
		controller:'ResumeCtrl',
		templateUrl:'view/home/worker/resume.html',
		title : "Resume"
	})
	.when('/home/hirer/jobManage',{
		controller:'JobManageCtrl',
		templateUrl:'view/home/hirer/jobManage.html',
		title : "Job Manage"
	})
	.when('/home/hirer/editJob/:jobId',{
		controller:'EditJobCtrl',
		templateUrl:'view/home/hirer/editJob.html',
		title : "Edit Job"
	})
	//bottom-info
	.when('/about',{
		templateUrl:'view/bottom-info/about.html'
	})
	.when('/contact',{
		templateUrl:'view/bottom-info/contact.html'
	})
	.when('/trems',{
		templateUrl:'view/bottom-info/trems.html'
	})
	.when('/privacy',{
		templateUrl:'view/bottom-info/privacy.html'
	})
	.when('/error',{
		controller:'ErrorCtrl',
		templateUrl:'view/error.html'
	})
	
	.otherwise({
		redirectTo:'/'
	});
})
.run(function($rootScope,$route){
	$rootScope.url_pattern="#!";
	$rootScope.$on("$routeChangeSuccess", function(currentRoute, previousRoute){
	    //Change page title, based on Route information
	    $rootScope.title = $route.current.title;
	});
});
