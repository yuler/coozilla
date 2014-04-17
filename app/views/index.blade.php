<!doctype html>
<html ng-app="app">
  <head>
      <title ng-bind="$root.title">CooZilla</title>
      <!-- <title ng-bing = "Page.title()"></title> -->
      <meta name="fragment" content="!" />
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="baidu-site-verification" content="T5xEVPbsyO" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="CooZilla is a freelance job recommendation engine on programming, designing and system administration.">
      <meta name="keywords" content="coozilla, China, freelancer, jobs, opportunities, talent, web development, web design, programming, system administration">
      <meta name="author" content="">
      <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" /> 
      @if(Session::has("login"))
      <script type="text/javascript">
        var user_role = "{{Session::get('login')['member_category']}}";
      </script>
      @else
      <script type="text/javascript">
        var user_role = "";
      </script>
      @endif
     	@include('include/layout')
      @include('include/google/analyticstracking')
  </head>
  <body>
  	@include('include/header')
  	<div ng-view style="padding-top:20px;"></div>
    <br>
  	@include('include/footer')
  </body>
</html>
