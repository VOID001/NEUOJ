<!--[if lte IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header role="banner" >
    <div class="navbar navbar-default" role="navigation" style="background: #336699">
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
                    <li id="home"><a href="/" >Home</a></li>
                    <li id="problem"><a href="/problem">Problem</a></li>
                    <li id="status"><a href="/status">Status</a></li>
                    <li id="contest"><a href="/contest">Contest</a></li>
                    <li id="discuss"><a href="#">Discuss</a></li>
                    <li id="rating"><a href="#">Rating</a></li>
                    <li id="temp_"><a href="/dashboard/profile">Dashboard</a></li>
                </ul>

                @if(Request::session()->get('username')!="")
                    <a href="/dashboard" class="text-right" id="dashboard" ><nobr>{{Request::session()->get('username')}}</nobr></a>
                    <a href="/auth/logout" class="btn btn-info" id="logout">logout</a>
                @else
                    <a href="/auth/ssologin" class="btn btn-info" id="ssologin">SSO</a>
                    <a href="/auth/signin" class="btn btn-info" id="signin">Sign in</a>
                    <a href="/auth/signup" class="btn btn-info" id="signup">Sign Up</a>
                @endif


            </div>
        </div>
    </div>
</header>
<div  class="container" style="height: 20px">
    <!--marquee  scrollamount=1 direction=left onMouseOver='this.stop()' onMouseOut='this.start()' class="text-muted">Welcome come to NEU online judge</marquee-->
</div>
