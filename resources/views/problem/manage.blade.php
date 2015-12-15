Please Desgin the Pattern as your wish
<div>{{ $status or "" }}</div>
<a href="/dashboard/problem/add/">Add Problem</a>
@foreach($problems as $problem)
    <div>{{ $problem->title }}</div>
    <div>{{ $problem->problem_id }}</div>
    <div>{{ $problem->visiblity_locks }}</div>
    <div>{{ $problem->created_at }}</div>
    <div>{{ $problem->updated_at }}</div>
    <a href="/dashboard/problem/{{ $problem->problem_id }}">Edit Problem</a>
    <form action="/dashboard/problem/{{ $problem->problem_id }}" method="POST">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <input type="submit" value="Delete"/>
    </form>
@endforeach
