angular.module('directives', [])

.directive('selectPicker', function() {
    return{
        require: '?ngModel',
        restrict: 'A',
        link: function (scope, element,attr) {
            element.selectpicker({

            });
            // setTimeout(function(){
            //     $(element).selectpicker('refresh')   
            // },1);
            scope.$watch(attr.watch,function(){
                setTimeout(function(){
                    $(element).selectpicker('refresh')   
                },1);
            })
            scope.refreshSelected=function(){
                scope.$watch(attr.watch,function (newValue,oldValue) {
                    $(element).selectpicker('refresh');
                });
            }
            // scope.$watch(attr.watch,function (newValue,oldValue) {
            //     $(element).selectpicker('refresh'); 
            // });
        }
    }
})

.directive('iCheck', function() {
    return{
        require: '?ngModel',
        restrict: 'A',
        link: function (scope, element,attr) {
            element.iCheck({
                checkboxClass: 'icheckbox_flat-red',
                radioClass: 'iradio_futurico',
                increaseArea: '20%' // optional
            });
        }
    }
})


.directive('coozillaPagination',function(){
    return{
        restrict:'E',
        template:'<div class="row">'+
            //totalPage and totalRecord
            '<div class="col-xs-4">'+
                '<ul class="pagination">'+
                    '<li>'+
                        '<a>'+
                            'Page : {{page.current_page}} / {{page.last_page}} &nbsp;&nbsp;&nbsp;'+
                            'Records : {{page.total}}'+
                        '</a>'+
                    '</li>'+
                '</ul>'+
            '</div>'+
            '<div class="col-xs-8">'+
                '<ul class="pagination">'+
                  '<li ng-show="page.current_page != 1 " >'+
                    // '<a href="#/{{href}}/1">First</a>'+
                    '<a ng-click="linkBtn(1)" href="">First</a>'+
                  '</li>'+
                  '<li ng-show="page.current_page != 1 " >'+
                    // '<a href="#/{{href}}/{{page.current_page - 1}}"><span class="glyphicon glyphicon-circle-arrow-left"></span> Prev</a>'+
                    '<a ng-click="linkBtn(page.current_page - 1)" href=""><span class="glyphicon glyphicon-circle-arrow-left"></span> Prev</a>'+
                  '</li>'+
                  '<li ng-show="page.current_page > 5">'+
                    '<a href="">......</a>'+
                  '</li>'+
                  '<li ng-class="{active:page.current_page == {{pageNo}} }" ng-repeat="pageNo in page.pages">'+
                    // '<a ng-show="pageNo >= ( page.current_page <= (page.last_page - 4) ? page.current_page - 4 : page.last_page - 8 ) &&  pageNo <= ( page.current_page <=5  ? 8 : (page.current_page + 3) )" href="#/{{href}}/{{pageNo}}">{{pageNo}}</a>'+
                    '<a href="" ng-show="pageNo >= ( page.current_page <= (page.last_page - 4) ? page.current_page - 4 : page.last_page - 8 ) &&  pageNo <= ( page.current_page <=5  ? 8 : (page.current_page + 3) )" ng-click="linkBtn(pageNo)">{{pageNo}}</a>'+
                  '</li>'+
                  '<li ng-show="page.current_page < page.last_page -3">'+
                    '<a href="">......</a>'+
                  '</li>'+
                  '<li ng-show="page.current_page != page.last_page " >'+
                    // '<a href="#/{{href}}/{{page.current_page + 1}}">Next <span class="glyphicon glyphicon-circle-arrow-right"></span></a>'+
                    '<a href="" ng-click="linkBtn(page.current_page + 1)">Next <span class="glyphicon glyphicon-circle-arrow-right"></span></a>'+
                  '</li>'+
                  '<li ng-show="page.current_page != page.last_page " >'+
                    // '<a href="#/{{href}}/{{page.last_page}}">Last</a>'+
                    '<a href="" ng-click="linkBtn(page.last_page)">Last</a>'+
                  '</li>'+
                '</ul>'+
            '</div>'+
        '</div>',
        replace:true
    }
})

/*<job-list category="Design Jobs" items="designs" show="design"></job-list>*/
.directive('jobList',function(){
    return{
        restrict:'E',
        scope:{
            show : '=show',
            category : '@',
            items : '='
        },
        link : function(scope, element,attr){
            scope.detailJob = function(jobId){
                window.location.href = contextPath + '/#/job/detail/'+jobId
            }
        },
        templateUrl:'view/directive/list.html'
    }
})

/*<job-detail job="job" hirer="hirer" login="login" jobDesciption="job.job_description"></job-detail>*/
.directive('jobDetail',function(){
    return{
        restrict:'E',
        scope:{
            job : '=',
            hirer : '=',
            login : '=',
        },
        // link:function(scope,element,attr){
        //     console.debug(scope);
        //     $('.jobDescription').append();
        // },
        templateUrl:'view/directive/detail.html'
    }
})


/*<text-editor ngModel="job.job_description"></text-editor>*/
.directive('textEditor',function(){
    return {
        restrict:'E',
        scope:{
            ngModel : '@'
        },
        link:function(scope,element,attr){
            angular.element('#editor').wysiwyg();
        },
        templateUrl:'view/directive/textEditor.html'
    }
})

.directive('summerNote',function(){
    return {
        restrict:'E',
        link:function(scope,element,attr){
            $(element).html(attr.innerText);
            $(element).attr("id",attr.forId);
            $(element).summernote({
                height: attr.height || 300,   
                focus: false,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']]
                  ]
            });
            if(attr.forWatch){
                scope.$watch(attr.forWatch,function(newValue,oldValue){
                    $(element).code(newValue);
                });
            }
        }
    }
})

//file_upload
/*
<input type="file" size="40" class="form-control"
          name="file_upload" id="fileUpload" file-upload
          >
*/
.directive('fileUpload',function(){
    return{
        restrict:'A',
        link:function(scope,element,attr,ngModel){
            element.uploadify({
                'auto':true,
                'removeCompleted' : false,
                'successTimeout':500,
                'fileSizeLimit':'2MB',
                'multi' : false,
                'uploadLimit' : 1,
                'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg ;',
                'method'   : 'post',
                'formData' : { 'someKey' : 'someValue' },
                'buttonText' : 'BROWSE...',
                // 'buttonImage' : 'thirdparty/uploadify/uploadify'
                'swf'      : 'thirdparty/uploadify/uploadify.swf',
                'uploader' : 'uploads/uploadify.php',
                'onUploadSuccess':function(file, data, response){
                    angular.element('#fileUploadImage').attr('src',data);
                    scope.fileAddress = data;
                }
            });
            angular.element(".cancel a").live("click", function() {
                element.uploadify({
                            'auto':true,
                            'removeCompleted' : false,
                            'successTimeout':500,
                            'fileSizeLimit':'2MB',
                            'multi' : false,
                            'uploadLimit' : 1,
                            'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg ;',
                            'method'   : 'post',
                            'formData' : { 'someKey' : 'someValue' },
                            'buttonText' : 'BROWSE...',
                            // 'buttonImage' : 'thirdparty/uploadify/uploadify'
                            'swf'      : 'thirdparty/uploadify/uploadify.swf',
                            'uploader' : 'uploads/uploadify.php',
                            'onUploadSuccess':function(file, data, response){
                                angular.element('#fileUploadImage').attr('src',data);
                                scope.fileAddress = data;
                            }
                });
                angular.element('#fileUploadImage').attr('src','');
                angular.element.ajax({
                    type: "POST",
                    url: "uploads/remove.php",
                    data: "file="+scope.fileAddress,
                    success: function(msg){
                        // console.log(msg);
                    }   
                 });
            });
        }
    }
})

// .directive('fileUpload',function(){
//     return {
//         restrict:'A',
//         link : function(scope, element,attr){
//             element.uploadify({
//                     'auto':true,
//                     'removeCompleted' : false,
//                     'successTimeout':500,
//                     'fileSizeLimit':'2MB',
//                     'multi' : false,
//                     // 'uploadLimit' : 1,
//                     'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg ;',
//                     'method'   : 'post',
//                     'formData' : { 'someKey' : 'someValue' },
//                     'buttonText' : 'BROWSE...',
//                     // 'buttonImage' : 'thirdparty/uploadify/uploadify'
//                     'swf'      : 'thirdparty/uploadify/uploadify.swf',
//                     'uploader' : 'uploads/uploadify.php',
//                     'onUploadSuccess':function(file, data, response){
//                         scope.uploadImg = data ;
//                         console.log(scope);
//                     }
//             });

//             scope.$watch(scope,function (newValue,oldValue) {
//                 alert('xxx');
//             });

//             angular.element(".cancel a").live("click", function() {
//                 //取得本次取消的上传文件ID号
//                 alert('xxxx');
//             });
//         }
//     }
// })

        



;

