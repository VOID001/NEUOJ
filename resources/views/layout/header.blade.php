<!--[if lte IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header>
    <div class="navbar">
        <a class="navbar-brand" id="navbar-logo"  href="/">
            <img src="/image/neuacmlogo.PNG"/>
            <span>NEUOJ<span>
        </a>
        <ul class="nav" id="navbar-meau">
            <li class="three-d" id="home" onClick="window.location.href = '/';">
                <span class="three-d-box">
                    <span class="nav-front">HOME</span>
                    <span class="nav-back">HOME</span>
                </span>
            </li>
            <li class="three-d" id="problem" onClick="window.location.href = '/problem';">
                <span class="three-d-box">
                    <span class="nav-front">PROBLEM</span>
                    <span class="nav-back">PROBLEM</span>
                </span>
            </li>
            <li class="three-d" id="status" onClick="window.location.href = '/status';">
                <span class="three-d-box">
                    <span class="nav-front">STATUS</span>
                    <span class="nav-back">STATUS</span>
                </span>
            </li>
            <li class="three-d"  id="contest" onClick="window.location.href = '/contest';">
                <span class="three-d-box">
                    <span class="nav-front">CONTEST</span>
                    <span class="nav-back">CONTEST</span>
                </span>
            </li>
            <li class="three-d" id="training" onClick="window.location.href = '/training';">
                <span class="three-d-box">
                    <span class="nav-front">TRAINING</span>
                    <span class="nav-back">TRAINING</span>
                </span>
            </li>
            <li class="three-d"  id="discuss" onClick="window.location.href = '/discuss/0';">
                <span class="three-d-box">
                    <span class="nav-front">DISCUSS</span>
                    <span class="nav-back">DISCUSS</span>
                </span>
            </li>
            <li class="three-d" id="rating" onClick="window.location.href = '#';">
                <span class="three-d-box">
                    <span class="nav-front">RATING</span>
                    <span class="nav-back">RATING</span>
                </span>
            </li>
            <li class="three-d" id="rating" onClick="window.location.href = '/hackme.html';">
                <span class="three-d-box">
                    <span class="nav-front">HACKME</span>
                    <span class="nav-back">HACKME</span>
                </span>
            </li>
        </ul>
        @if(Request::session()->get('username')!="")
            <div class="btn-group" id="personal-button">
                <button class="btn btn-info" onClick="window.location.href = '/dashboard';">{{Request::session()->get('username')}}</button>
                <button class="btn btn-info dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="/dashboard"><img src="/avatar/{{Request::session()->get('uid')}}"/></a></li>
                    <li class="divider"></li>
                    <li><a href="/auth/logout">Log Out</a></li>
                <ul>
            </div>
        @else
            <div id="sign-button-group">
                <a href="/auth/ssologin"><paper-button class="sign-button" raised>SSO</paper-button></a>
                <a href="/auth/signin"><paper-button class="sign-button" raised>Sign in</paper-button></a>
                <a href="/auth/signup"><paper-button class="sign-button" raised>Sign Up</paper-button></a>
            </div>
        @endif
    </div>
</header>