<!--[if lte IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header>
    <div class="navbar navbar-dashboard navbar-fixed-top navbar-wide navbar border" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" id="navbar-logo"  href="/">
                    <img src="/image/neuacmlogo.PNG"/>
                 <span>NEUOJ<span>
                </a>
            </div>
            @if(Request::session()->get('username')!="")
                <div class="btn-group" id="personal-button">
                    <a class="btn btn-dashboard" id="personal-username-btn" onClick="window.location.href = '/dashboard';">{{Request::session()->get('username')}}</a>
                    <a class="btn btn-dashboard dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></a>
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
    </div>
</header>
