<!doctype html>
<html>
<head>
    <title>Password Reset</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    @include("layout.header")
    <h3 class="text-center">Password Reset</h3>
    @if(isset($no_token))
        <div class="text-center">Your token is invalid or expired</div>
    @endif
    @if(isset($reset_ok))
        <div class="text-center">Your password has been reset! <a href="/auth/signin/">Click here to login with the new password</a></div>
    @endif
    @if(isset($errors))
        <ul class="text-center">
            @foreach($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="/auth/reset" method="post" id="resetForm">
        <table id="resetTable" >
            <div class="text-center">
                You are now resetting password for <b>{{ $username or "[Invalid Entry]"}}</b>
            </div>
            <input type="hidden" value="{{ $username or ""}}" name="username"/>
            <tr >
                <td>Token</td>
                <td><input type="text" class="form-control" name="token" value="{{ $token or ""}}"/></td>
                <td>(Normally You do not need to edit it)</td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text"  class="form-control" name="email" value="{{ $email or ""}}"/></td>
                <td>(Normally You do not need to edit it)</td>
            </tr>
            <tr>
                <td>New Password</td>
                <td><input type="password" class="form-control" name="new_password"/></td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td><input type="password" class="form-control" name="confirm_password"/></td>
            </tr>
            {{ csrf_field() }}
            <tr>
                <td></td>
                <td class="text-right"><input type="submit" class="btn btn-info" name="submit" value="Reset"/></td>
            </tr>
        </table>
    </form>
    @include("layout.footer")
</body>
</html>
