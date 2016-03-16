<h1>Password Reset</h1>

@if(isset($no_token))
    <div>Your token is invalid or expired</div>
@endif
@if(isset($reset_ok))
    <div>Your password has been reset! <a href="/auth/signin/">Click here to login with the new password</a></div>
@endif
    @if(isset($errors))
        <ul>
            @foreach($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="/auth/reset" method="post">
        <div>
            You are now resetting password for <b>{{ $username or "[Invalid Entry]"}}</b>
        </div>
        <input type="hidden" value="{{ $username or ""}}" name="username"/>
        <div>
            <label>Token(Normally You do not need to edit it)</label>
            <input type="text" name="token" value="{{ $token or ""}}"/>
        </div>
        <div>
            <label>Email(Normally You do not need to edit it)</label>
            <input type="text" name="email" value="{{ $email or ""}}"/>
        </div>
        <div>
            <label>New Password</label>
            <input type="password" name="new_password"/>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password"/>
        </div>
        {{ csrf_field() }}
        <div>
            <input type="submit" name="submit"/>
        </div>
    </form>

