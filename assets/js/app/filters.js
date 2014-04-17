angular.module('filters',[])

//工作分类过滤器
.filter('jobCategoryFilter',function(){
	return function(input){
		var result ;
		if(input == 1){
			result = 'Design'
		}else if(input == 2){
			result = 'programmer'
		}else if(input == 3){
			result = 'System administration'
		}
		return result;
	};
})

//日期过滤器
.filter('dateFilter',function($filter){
	return function(input){
		var result; 
		var date = new Date(input);
		result = $filter('date')(date.getTime(),'MMM dd EEE');
		return result;
	};
});