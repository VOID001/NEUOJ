<!doctype html>
<html>
<head>
    <title>Sign in</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    @include("layout.header")
<div align="center">
<form action="/auth/signin" method="POST">
{{ csrf_field() }}
    <h3 class="text-center">Sign in</h3>
    <div style="height: 20px">
        @if(count($errors) > 0 )
            <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
        @elseif(isset($loginError))
            <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{ $loginError }}</div></div>
        @endif
    </div>
    <table id="signinTable">
        <tr >
            <td style="padding-left: 100px">Username</td>
            <td ><input type="text" name="username" class="form-control" value="@if(!isset($username)){{ old('username') }}@else{{ $username }}@endif" tabindex="1"/></td>
            <td><div style="padding-left: 4px"><a href="/auth/signup">Sign Up Now!</a></div></td>
        </tr>
        <tr>
            <td style="padding-left: 100px">Password</td>
            <td ><input type="password" name="pass"class="form-control" tabindex="2"/></td>
            <td><div style="padding-left: 4px">Click <a href="/auth/request">here</a> to reset</div></td>
        </tr>
            <td style="padding-left: 100px">Captcha(Ignore Case)</td>
            <td><input type="text" name="captcha" class="form-control"/></td>
            <td>{!! Captcha::img('flat') !!}</td>
        <tr>
        </tr>
        <tr>
            <td></td>
            <td class="text-right"><input type="submit" value="Sign in" class="btn btn-success"></td>
        </tr>
    </table>
</form>
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
    @include("layout.footer")
</body>
</html>