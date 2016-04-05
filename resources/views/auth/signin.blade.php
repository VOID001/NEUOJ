<!doctype html>
<html>
<head>
    <title>Sign in</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script>
    	$(document).ready(function(){
    		$('#captcha').click(function(){
    			this.src=this.src+'?'+new Date();
    		});
    	});
    </script>
</head>
<body style="background-color: #d2d6de">
<br>
<br>
<h2 class="text-center">Sign in</h2>
<br>
<br>
<div align="center">
    <div class="signin_box">
        <p class="signin_box_msg">Sign in to start your code</p>
        <form action="/auth/signin" method="POST">
        {{csrf_field()}}
            <div class="form-group signin_box_userbox">
                <span class="glyphicon glyphicon-user signin_box_gly"></span>
                <input type="text" name="username" value="@if(!isset($username)){{ old('username') }}@else{{ $username }}@endif" class="form-control signin_box_user" placeholder="username"tabindex="1">
            </div>
            <div class="form-group signin_box_userbox ">
                <span class="glyphicon glyphicon-lock signin_box_gly"></span>
                <input type="password" name="pass" class="form-control signin_box_user" placeholder="password"tabindex="2">
            </div>
            <div class="form-group signin_box_userbox signin_box_cptbox">
                <span class="glyphicon glyphicon-exclamation-sign signin_box_gly"></span>
                <input type="text" name="captcha" class="form-control signin_box_user" placeholder="captcha" tabindex="3"/>
                <img src="{{ captcha_src("flat") }}" class="signin_box_cpt_p" alt="Captcha" id="captcha"/>
            </div>
            <div class="row signin_box_row signin_box_warning">
                @if(count($errors) > 0 )
                    <span class="label label-danger" style="font-size: 12px"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{$errors->all()[0]}}</span>
                @elseif(isset($loginError))
                    <span class="label label-danger" style="font-size: 12px"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{ $loginError }}</span>
                @endif
            </div>
            <div class="row signin_box_row">
                <input type="submit" value="Sign in" style="padding-top: 3px" class="btn btn-info pull-right">
            </div>
        </form>
        <br>
        <div class="row" style="width: 80%">
            <a href="/auth/request" class="pull-left">I forget my password</a>
            <br/>
            <a href="/auth/signup" class="pull-left">Register a new membership</a>
        </div>

    </div>
    <!--
<div class="panel panel-default" style="width: 400px;height: 80px">
    <div class="panel-heading text-left">第三方登陆</div>
    <div class="panel-body">
        <img class="third_logo1" src="/image/qq.PNG">
        <img class="third_logo2" src="/image/github.PNG">
    </div>
    </div>
</div>
    -->
</body>
</html>
