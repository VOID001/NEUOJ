<!--[if lte IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header role="banner" >
    <div class="navbar navbar-default" role="navigation" style="background-color: #3f51b5">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand neuoj_theme"><img src="/image/neuacmlogo.PNG" class="neuacmlogo"/><span class="neuacmlogotext">NEUOJ<span></a>
            </div>
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li id="home"><a href="/"><paper-button>Home<div class="activebar"></div></paper-button></a></li>
                    <li id="problem"><a href="/problem"><paper-button>Problem<div class="activebar"></div></paper-button></a></li>
                    <li id="status"><a href="/status"><paper-button>Status<div class="activebar"></div></paper-button></a></li>
                    <li id="contest"><a href="/contest"><paper-button>Contest<div class="activebar"></div></paper-button></a></li>
                    <li id="discuss"><a href="/discuss/0"><paper-button>Discuss<div class="activebar"></div></paper-button></a></li>
                    <li id="rating"><a href="#"><paper-button>Rating<div class="activebar"></div></a></paper-button></li>
                </ul>

                @if(Request::session()->get('username')!="")
                    <div class="btn-group" id="dashboard">
                        <button class="btn btn-grey" onclick="javascript:window.location.href='/dashboard'">{{Request::session()->get('username')}}</button>
                        <button class="btn btn-grey dropdown-toggle btn-caret" type="button" data-toggle="dropdown""><span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="/dashboard"><img src="/avatar/{{Request::session()->get('uid')}}" style="width:118px;height:118px;"/></a></li>
                            <li role="presentation" class="divider"></li>
                            <li><a href="/dashboard">Dashboard</a></li>
                            <li role="presentation" class="divider"></li>
                            <li><a href="/auth/logout">Log Out</a></li>
                        <ul>
                    </div>
                @else
                    <a href="/auth/ssologin" id="ssologin"><paper-button raised  class="mdbtn-white">SSO</paper-button></a>
                    <a href="/auth/signin" id="signin"><paper-button raised class="mdbtn-white">Sign in</paper-button></a>
                    <a href="/auth/signup" id="signup"><paper-button raised class="mdbtn-white">Sign Up</paper-button></a>
                @endif


            </div>
        </div>
    </div>
</header>

