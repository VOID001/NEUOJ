<!--[if lte IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header role="banner" >
    <div class="navbar navbar-default" role="navigation" style="background: #a6e1ec">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand neuoj_theme"><img src="/image/neuacmlogo.PNG" class="neuacmlogo"/><span style="float: left">NEUOJ<span></a>
            </div>
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li id="home"><a href="/" >Home</a></li>
                    <li id="problem"><a href="/problem">Problem</a></li>
                    <li id="status"><a href="/status">Status</a></li>
                    <li id="discuss"><a href="/discuss">Discuss</a></li>
                    <li id="rating"><a href="#">Rating</a></li>
                    <li id="temp_"><a href="/dashboard/profile">Dashboard</a></li>
                </ul>

                @if(Request::session()->get('username')!="")
                    <a href="/dashboard" class="text-center" id="dashboard" >{{Request::session()->get('username')}}</a>
                    <a href="/auth/logout" class="btn btn-success" id="logout">logout</a>
                @else
                    <a href="/auth/signin" class="btn btn-success" id="signin">Sign in</a>
                             <a href="/auth/signup" class="btn btn-success" id="signup">Sign Up</a>
                @endif


            </div>
        </div>
    </div>
</header>
<div  class="container">
    <marquee  scrollamount=1 direction=left onMouseOver='this.stop()' onMouseOut='this.start()' class="text-muted">Welcome come to NEU online judge</marquee>
</div>
