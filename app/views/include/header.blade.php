<header id="header" class="navbar bs-docs-nav" role="banner" ng-controller="headCtrl" style="height:90px;">
    <div class="navbar navbar-default" role="navigation" style="background:#fff;border:none;">
        <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" style="position:relative;top:10px;">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a href="{{URL::to('/')}}" class="navbar-brand">
                <img class="logo img-responsive" src= "{{URL::to('/')}}/assets/img/coozilla_logo.png" /></a>
            </div>
            <div class="navbar-collapse collapse">
            @if(!Session::has("login"))
            <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right ">
                    <li>
                      <a href="javascript:;" ng-click="signIn()" style="padding-top:25px;">Sign in</a>
                    </li>
                </ul>
            </nav>
            @endif
            <ul class="nav navbar-nav navbar-right" style="padding-top:15px;">
                <li>
                    <!-- 雇佣者 -->
                    @if(Session::has("login") && Session::get("login")["member_category"] == 1)
                    <div class="btn-group">
                        <a class="dropdown-toggle btn btn-link btn-lg" style="text-decoration:none" 
                            href="javascript:void(0)" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user" ></span> 
                            {{Session::get("login")["member_account"]}} &nbsp;&nbsp;
                            <span class="icon-caret-down icon-large" ></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a ng-click="home()" class="hover">Coozilla</a></li>
                            <li><a ng-click="profile()" class="hover">Profile</a></li>
                            <li><a ng-click="resumeManage()" class="hover">Resume manage</a></li>
                            <li class="divider"></li>
                            <li><a ng-click="logout()" class="hover">Sign out</a></li>
                        </ul>
                    </div> 
                    @endif
                    <!-- 程序员 -->
                    @if(Session::has("login") && Session::get("login")["member_category"] == 2)
                        <div class="btn-group">
                            <a class="dropdown-toggle btn btn-link btn-lg" style="text-decoration:none" href="javascript:void(0)" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-user" ></span> 
                                {{Session::get("login")["member_account"]}} &nbsp;&nbsp;
                                <span class="icon-caret-down icon-large" ></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a ng-click="home()" class="hover">Coozilla</a></li>
                                <li><a ng-click="profile()" class="hover">Profile</a></li>
                                <li><a ng-click="jobManage()" class="hover">Job manage</a></li>
                                <li class="divider"></li>
                                <li><a ng-click="logout()" class="hover">Sign out</a></li>
                            </ul>
                        </div>
                    @endif
                </li>
            </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      
    </nav>
    
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">

    </nav>
  </div>
</header>
