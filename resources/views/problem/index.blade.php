<h3>Problem: {{ $title }}</h3>
<div>Time limit: {{ $time_limit }}s Mem limit{{ $mem_limit }}K @if($is_spj == 1) <b>Special Judge</b>@endif</div>
<div><b>Description</b></div>
<div>{{ $description }}</div>

@if(Request::session()->get('username') != NULL)
    <div>You are logged in, you can submit the code</div>
    <div>Click <a href="/submit/{{ $problem_id }}">here</a> to submit your code</div>
@else
    <div>Sign in to Submit your code</div>
@endif
