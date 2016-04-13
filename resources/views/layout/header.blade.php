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
                    <li id="home" class="three-d">
                        <a href="/">
                            HOME
                            <span class="three-d-box"><span class="nav-front" >HOME</span><span class="nav-back">HOME</span></span>
                        </a>
                    </li>
                    <li id="problem" class="three-d">
                        <a href="/problem">
                            PROBLEM
                            <span class="three-d-box"><span class="nav-front">PROBLEM</span><span class="nav-back">PROBLEM</span></span>
                        </a>
                    </li>
                    <li id="status" class="three-d">
                        <a href="/status">
                            STATUS
                            <span class="three-d-box"><span class="nav-front">STATUS</span><span class="nav-back">STATUS</span></span>
                        </a>
                    </li>
                    <li id="contest" class="three-d">
                        <a href="/contest">
                            CONTEST
                            <span class="three-d-box"><span class="nav-front">CONTEST</span><span class="nav-back">CONTEST</span></span>
                        </a>
                    </li>
                    <li id="discuss" class="three-d">
                        <a href="/discuss/0">
                            DISCUSS
                            <span class="three-d-box"><span class="nav-front">DISCUSS</span><span class="nav-back">DISCUSS</span></span>
                        </a>
                    </li>
                    <li ID="rating" class="three-d">
                        <a href="#">
                            RATING
                            <span class="three-d-box"><span class="nav-front">RATING</span><span class="nav-back">RATING</span></span>
                        </a>
                    </li>
                    <li ID="rating" class="three-d">
                        <a href="/hackme.html">
                            HACKME
                            <span class="three-d-box"><span class="nav-front">HACKME</span><span class="nav-back">HACKME</span></span>
                        </a>
                    </li>
                </ul>

                @if(Request::session()->get('username')!="")
                    <div class="btn-group" id="dashboard">
                        <button class="btn btn-info" onclick="javascript:window.location.href='/dashboard'">{{Request::session()->get('username')}}</button>
                        <button class="btn btn-info dropdown-toggle btn-caret" type="button" data-toggle="dropdown""><span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="/dashboard"><img src="/avatar/{{Request::session()->get('uid')}}" style="width:118px;height:118px;object-fit:cover;"/></a></li>
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

