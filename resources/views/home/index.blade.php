@if(!isset($noLogin))
    <div>Welcome {{ $username }} !</div>
    <div>You are currently logged in</div>
    @if(!isset($lastlogin_ip))
        <div>This is your first time to log in</div>
    @elseif(isset($lastlogin_ip))
        <div>Your last login ip is {{ $lastlogin_ip }}</div>
    @endif
    <a href="/dashboard">Dashboard</a>
    <a href="/auth/logout">logout</a>
@else
    <div>Welcome Guest!</div>
    <div>You are currently not logged in</div>
    <a href="/auth/signin">Sign in</a>
    <a href="/auth/signup">Sign Up</a>
@endif

<a href="/problem">Problem</a>
<a href="/status">Status</a>
<a href="/discuss">Discuss Board</a>

<h3>Announcement</h3>
<div>Something here</div>
