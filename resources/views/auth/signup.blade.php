@if(count($errors) > 0)
    <div>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif
<form action="/auth/signup" method="POST">
{{ csrf_field() }}
    <table>
        <th>Sign up</th>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username"/></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="pass"/></td>
            </tr>
            <tr>
                <td>Re-type Password</td>
                <td><input type="password" name="pass_confirmation"/></td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td><input type="text" name="email"/></td>
            </tr>
            <tr>
                <td><input type="submit" value="Sign up"></td>
            </tr>
    </table>
</form>