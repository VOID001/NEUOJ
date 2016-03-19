<!doctype html>
<html>
<head>
    <title>Sign up</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    @include("layout.header")

    <div align="center">
    <form action="/auth/signup" method="POST">
    {{ csrf_field() }}
        <h3 class="text-center">Sign up</h3>
        <div style="height: 20px;padding-left: 38px">
            @if(count($errors) > 0)
                <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
            @endif
        </div>
        <table id="signupTable">
                <tr>
                    <td style="padding-left: 20px">Username</td>
                    <td><input type="text" name="username" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Password</td>
                    <td><input type="password" name="pass" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Re-type Password</td>
                    <td><input type="password" name="pass_confirmation" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Email Address</td>
                    <td><input type="text" name="email" class="form-control"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right"><input type="submit" value="Sign up" class="btn btn-info"></td>
                </tr>
        </table>
    </form>
    </div>
    <div style="height: 192px"></div>

    @include("layout.footer")
</body>
</html>
