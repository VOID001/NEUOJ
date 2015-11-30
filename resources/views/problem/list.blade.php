<h1>Problem List</h1>

<h3>Problems</h3>

<table border="1">
<th>
    <tr>
        <td>Problem ID</td>
        <td>Title</td>
        <td>Difficulty</td>
        <td>AC/Submit</td>
        <td>Author</td>
        <td>Visibility_Lock(use for debug version)</td>
    </tr>
</th>

<form action="{{ Request::getUri() }}" method="POST">
    {{ csrf_field() }}
    <label>How many problems to show on one page </label>
    <input type="text" value="{{ $problemPerPage }}" name="problem_per_page"/>
    <input type="submit" value="show"/>
</form>

@if($problems != NULL)
    @foreach($problems as $problem)
        <tr>
            <td><a href="/problem/{{ $problem->problem_id }}">{{ $problem->problem_id }}</a></td>
            <td>{{ $problem->title }}</td>
            <td>{{ $problem->difficulty }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ $problem->visibility_locks }}</td>
        </tr>
    @endforeach
@endif
</table>

<div>
    @if(!isset($firstPage))
        <a href="/problem/p/{{ $page_id - 1 }}">Previous Page</a>
    @endif
    @if(!isset($lastPage))
        <a href="/problem/p/{{ $page_id + 1 }}">Next Page</a>
    @endif
</div>

