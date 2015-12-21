<h1>Contest Dashboard</h1>
<a href="/dashboard/contest/add"><button>New Contest</button></a>
@if(isset($contests))
    <table>
        <thead>
            <th>Contest ID</th>
            <th>Contest Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Begin Time</th>
            <th>End Time</th>
            <th>Operation</th>
        </thead>
        @foreach($contests as $contest)
            <tr>
                <td>
                    {{ $contest->contest_id }}
                </td>
                <td>
                    {{ $contest->contest_name }}
                </td>
                <td>
                    @if($contest->type == 0)
                        Public
                    @elseif($contest->type == 1)
                        Private
                    @else
                        Register
                    @endif
                </td>
                <td>
                    @if(time() > $contest->begin_time)
                        Pending
                    @elseif(time() >= $contest->begin_time && time() <= $contest->end_time)
                        Running
                    @else
                        Stopped
                    @endif
                </td>
                <td>
                    {{ $contest->begin_time }}
                </td>
                <td>
                    {{ $contest->end_time }}
                </td>
                <td>
                    <form method="post" action="/dashboard/contest/{{ $contest->contest_id }}">
                        {{ method_field('DELETE') }}
                        <input type="submit" value="delete contest"/>
                    </form>
                    <a href="/dashboard/contest/{{ $contest->contest_id }}"><button>Edit Contest</button></a>
                </td>
            </tr>
        @endforeach
    </table>
@endif
