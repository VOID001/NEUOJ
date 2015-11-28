@if(count($errors) > 0 || isset($loginError))
    <div>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        @if(isset($loginError))
            <li>{{ $loginError }}</li>
        @endif
    </div>
@endif
<form action="/auth/signin" method="POST">
{{ csrf_field() }}
    <table>
        <th>Sign in</th>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" value="@if(!isset($username)){{ old('username') }}@else{{ $username }}@endif"/></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="pass"/></td>
            </tr>
            <tr>
                <td><div>Forget Password? Click <a href="/auth/request">here</a> to reset</div></td>
            </tr>
            <tr>
                <td><div>Not signed up? <a href="/auth/signup">Sign Up Now!</a></div></td>
            </tr>
            <tr>
                <td><input type="submit" value="Sign in"></td>
            </tr>
    </table>
</form>