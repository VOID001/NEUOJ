<h1>{{ $contest->contest_name }}</h1>
<div>
    Begin Time: {{ $contest->begin_time }}
</div>
<div>
    End Time: {{ $contest->end_time }}
</div>
<div>
    Time Remaining: [Please add a JS Countdown]
</div>
<div>
    Status: {{ $contest->status }}
</div>
<div>
    <a href="/contest/{{ $contest->contest_id }}/status">Status</a>
    <a href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
</div>

<div>
    <!--
    We will add customize view of contest later
    <form action="{{ Request::server('REQUEST_URI') }}" method="get">
        <button name="desc" value="1">Sort the problem in AC Ratio Desc</button>
    </form>
    -->
</div>

<table>
    <thead>
    <th>
        AC/Total(Ratio)
    </th>
    <th>
        Problem ID
    </th>
    <th>
        Short Name
    </th>
    <th>
        Problem Name
    </th>
    </thead>

    @foreach($problems as $problem)
        <tr>
            <td>
                @if($problem->acSubmissionCount != 0)
                    {{ $problem->acSubmissionCount }} / {{ $problem->totalSubmissionCount }}({{ $problem->acSubmissionCount/$problem->totalSubmissionCount * 100 }}%)
                @else
                    0 / 0()
                @endif
            </td>
            <td>
                @if($problem->thisUserFB)
                    [FB!]
                @endif
                @if($problem->thisUserAc)
                    [AC]
                @endif
                    {{ $problem->contest_problem_id }}
            </td>
            <td>
                @if((session('uid') && session('uid') <=2) || $contest->status != "Pending")
                    <a href="/contest/{{ $contest->contest_id }}/problem/{{ $problem->problem_id }}">
                @endif
                    {{ $problem->problem_title }}
                @if((session('uid') && session('uid') <=2) || $contest->status != "Pending")
                    </a>
                @endif
            </td>
            <td>
                {{ $problem->realProblemName }}
            </td>
        </tr>
    @endforeach
</table>
