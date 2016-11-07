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
            <li class="three-d" id="rating" onClick="window.location.href = '/ranklist/p/1';">
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
                <a class="btn btn-info" id="personal-username-btn" onClick="window.location.href = '/dashboard';">{{Request::session()->get('username')}}</a>
                <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></a>
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
        @if(Request::session()->has('username'))
        <div class="chatroom-box">
            <a class="chatroom-btn" role="button"><span class="chatroom-bubble"></span><img class="img-circle" src="/image/chatroom_btn.jpg" width="35px"></a>
            <br>
            <div class="chatroom-content" style="display: none">
                <div class="chatroom-header">
                    Chatroom
                </div>
                <div class="chatroom-body">
                    <div id="message-list"></div>
                </div>
                <div class="chatroom-footer">
                    <form class="chatroom-form" action="">
                        {{csrf_field()}}
                        <input class="chatroom-input" id="chatroom-input" name="message" type="text" placeholder="Not empty">
                        @if(isset($contest))
                        <input name="contest" value="{{ $contest->contest_id }}" hidden/>
                        @elseif(isset($contest_id))
                        <input name="contest" value="{{ $contest_id }}" hidden/>
                        @else
                        <input name="contest" value="0" hidden/>
                        @endif
                        <button class="chatroom-send-btn btn btn-warning">send</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</header>