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
                <a href="##" class="navbar-brand">NEUOJ</a>
            </div>
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/" id="home">Home</a></li>
                    <li><a href="/problem" id="problem">Problem</a></li>
                    <li><a href="/status" id="status">Status</a></li>
                    <li><a href="/discuss" id="discuss">Discuss</a></li>
                    <li><a href="#" id="rating">Rating</a></li>
                </ul>

                <?php
                    if(!isset($noLogin)){
                        echo '<a href="/dashboard" class="btn btn-success" id="dashboard">Dash</a>
                             <a href="/auth/logout" class="btn btn-success" id="logout">logout</a>';
                    }else{
                        echo '<a href="/auth/signin" class="btn btn-success" id="signin">Sign in</a>
                             <a href="/auth/signup" class="btn btn-success" id="signup">Sign Up</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</header>
<div  class="container">
    <marquee  scrollamount=1 direction=left onMouseOver='this.stop()' onMouseOut='this.start()' class="text-muted">Welcome come to NEU online judge</marquee>
</div>
