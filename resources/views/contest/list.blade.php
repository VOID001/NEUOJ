<h1>Contest List</h1>
<table border="1">
    <thead>
        <th>
            Contest ID
        </th>
        <th>
            Contest Title
        </th>
        <th>
            Start Time
        </th>
        <th>
            Type
        </th>
        @if(Request::session()->get('uid') <=2)
            <th>
                Management
            </th>
        @endif
    </thead>
    @if(isset($contests))
        @foreach($contests as $contest)
            <tr>
                <td>
                    <a href="/contest/{{ $contest->contest_id }}">
                        {{ $contest->contest_id }}
                    </a>
                </td>
                <td>
                    {{ $contest->contest_name }}
                </td>
                <td>
                    {{ $contest->begin_time }}
                </td>
                <td>
                    @if($contest->contest_type == 0)
                        Public
                    @elseif($contest->contest_type == 1)
                        Private
                    @elseif($contest->contest_type == 2)
                        Register
                    @endif
                </td>
                @if(Request::session()->get('uid') <=2)
                    <td>
                        <a href="/dashboard/contest/{{ $contest->contest_id }}"><button>Manage Contest</button></a>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif

</table>

@if(!isset($first_page))
    <a href="/contest/p/{{ $page_id - 1 }}">Previous Page</a>
@endif
@if(!isset($last_page))
    <a href="/contest/p/{{ $page_id + 1 }}">Next Page</a>
@endif
