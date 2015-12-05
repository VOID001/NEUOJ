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
    <table id="signinTable">
        <tr >
            <td style="padding-left: 100px">Username</td>
            <td ><input type="text" name="username" class="form-control" value="@if(!isset($username)){{ old('username') }}@else{{ $username }}@endif"/></td>
            <td><div style="padding-left: 4px"><a href="/auth/signup">Sign Up Now!</a></div></td>
        </tr>
        <tr>
            <td style="padding-left: 100px">Password</td>
            <td ><input type="password" name="pass"class="form-control"/></td>
            <td><div style="padding-left: 4px">Click <a href="/auth/request">here</a> to reset</div></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right"><input type="submit" value="Sign in" class="btn btn-success"></td>
        </tr>
    </table>
    <div style="height: 20px">
    @if(count($errors) > 0 || isset($loginError))
        @foreach($errors->all() as $error)
            <div style="width: 400px;text-align: left" class="text-danger">{{ $error }}</div>
            <?php break;?>
        @endforeach
        @if(isset($loginError))
            <li>{{ $loginError }}</li>
        @endif
    @endif
    </div>
</form>
    <div class="panel panel-default" style="width: 400px;height: 80px">
        <div class="panel-heading text-left">第三方登陆</div>
        <div class="panel-body">
            <img class="third_logo1" src="/image/qq.PNG">
            <img class="third_logo2" src="/image/github.PNG">
        </div>
    </div>

</div>
    @include("layout.footer")
</body>
</html>