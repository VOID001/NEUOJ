<!doctype html>
<html>
<head>
	<title>Sign in</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script>
		$(function() {
			$('#captcha').click(function() {
				this.src = this.src + '?' + new Date();
			});
		});
	</script>
</head>
<body id="signin-body">
	<h2 class="custom-heading">Sign in</h2>
	<div id="signin-box">
		<p class="text-center">Sign in to start your code</p>
		<form action="/auth/signin" method="POST">
		{{csrf_field()}}
			<div class="form-group signin-box-userbox">
				<span class="glyphicon glyphicon-user signin-box-gly"></span>
				<input class="form-control signin-box-user" name="username"  type="text" value="@if(!isset($username)){{ old('username') }}@else{{ $username }}@endif" placeholder="username" tabindex="1" />
			</div>
			<div class="form-group signin-box-userbox">
				<span class="glyphicon glyphicon-lock signin-box-gly"></span>
				<input class="form-control signin-box-user" name="pass" type="password" placeholder="password" tabindex="2" />
			</div>
			<div class="form-group signin-box-userbox" id="signin-box-captcha">
				<span class="glyphicon glyphicon-exclamation-sign signin-box-gly"></span>
				<input class="form-control signin-box-user" name="captcha" type="text" placeholder="captcha" tabindex="3" autocomplete="off" />
				<img id="captcha" src="{{ captcha_src('flat') }}" alt="Captcha"/>
			</div>
			@if(count($errors) > 0 )
				<span class="label label-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{$errors->all()[0]}}</span>
			@elseif(isset($loginError))
				<span class="label label-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{ $loginError }}</span>
			@endif
			<div>
				<input class="btn btn-info pull-right" type="submit" value="Sign in" />
			</div>
		</form>
		<div id="signin-text">
			<p><a href="/auth/request">I forget my password</a></p>
			<p><a href="/auth/signup">Register a new membership</a></p>
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