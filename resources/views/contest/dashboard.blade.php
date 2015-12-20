<h1>Contest Dashboard</h1>
<a href="/dashboard/contest/add"><button>New Contest</button></a>
@if(isset($problems))
    @foreach($problems as $problem)

    @endforeach
@endif
