<!doctype html>
<html>
<head>
    <title>Sign up</title>
    <?php require("./UI/head.php");?>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php require("./UI/header.php");?>

    <div align="center">
    <form action="/auth/signup" method="POST">
    {{ csrf_field() }}
        <h3 class="text-center">Sign up</h3>
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
                    <td class="text-right"><input type="submit" value="Sign up" class="btn btn-success"></td>
                </tr>
        </table>
        @if(count($errors) > 0)
            <div>
                @foreach($errors->all() as $error)
                    <div style="width: 360px;text-align: left;" class="text-danger" >{{ $error }}</div>
                    <?php break;?>
                @endforeach
            </div>
        @endif
    </form>
    </div>
    <div style="height: 192px"></div>
    <?php require("./UI/footer.php");?>
</body>
</html>