<!doctype html>
<html>
<head>
    <title>Sign in</title>
    <?php require("./UI/head.php");?>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php require("./UI/header.php");?>
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
        <div class="panel-heading">第三方登陆</div>
        <div class="panel-body">QQ图标在哪里</div>
    </div>

</div>
    <?php require("./UI/footer.php");?>
</body>
</html>