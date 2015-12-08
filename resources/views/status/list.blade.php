<meta http-equiv="Refresh" content="20"> <!-- Do not refresh too frequently -->
<div>Status List</div>
<div>
    <div>Filter</div>
    <form action="/status/p/1" method="GET">
        <label>Username</label>
        <input type="text" name="username"/>
        <label>Problem ID</label>
        <input type="text" name="pid"/>
        <label>Language</label>
        <select name="lang">
            <option name="all">All</option>
            <option name="c">C</option>
            <option name="java">Java</option>
            <option name="cpp">C++</option>
            <option name="cpp11">C++11</option>
        </select>
        <label>Result</label>
        <select name="result">
            <option name="all">All</option>
            <option name="wt">Pending</option>
            <option name="ac">Accepted</option>
            <option name="wa">Wrong Answer</option>
            <option name="re">Runtime Error</option>
            <option name="pe">Presentation Error</option>
            <option name="tle">Time Limit Exceed</option>
            <option name="mle">Memory Limit Exceed</option>
            <option name="ole">Output Limit Exceed</option>
            <option name="je">Judge Error</option>
        </select>
        <input type="submit" value="filter"/>
    </form>
</div>
<table border="1">
    <th>
        <tr>
            <td>
                Run ID
            </td>
            <td>
                Submit Time
            </td>
            <td>
                User ID
            </td>
            <td>
                Username
            </td>
            <td>
                Problem Title
            </td>
            <td>
                Result
            </td>
            <td>
                Exec_mem
            </td>
            <td>
                Exec_time
            </td>
            <td>
                View Code
            </td>
        </tr>
    </th>
    @if($submissions != NULL)
        @foreach($submissions as $submission)
            <tr>
                <td>{{ $submission->runid }}</td>
                <td>{{ $submission->submit_time }}</td>
                <td>{{ $submission->uid }}</td>
                <td>{{ $submission->userName }}</td>
                <td>{{ $submission->problemTitle }}</td>
                <td>{{ $submission->result }}</td>
                <td>{{ $submission->exec_mem }}</td>
                <td>{{ $submission->exec_time }}</td>
                <td>
                    @if(Request::session()->get('uid') == $submission->uid)
                        <a href="/status/{{ $submission->runid }}">View Source</a>
                    @else
                        View Source
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
</table>

@if(!isset($firstPage))
    <a href="/status/p/{{ $page_id - 1 }}">Prev</a>
@endif

@if(!isset($lastPage))
    <a href="/status/p/{{ $page_id + 1 }}">Next</a>
@endif