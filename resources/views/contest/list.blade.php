<!doctype html>
<html>
<head>
    <title>Contest</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
        })
    </script>
</head>
<body class="home_body">
@include("layout.header")

    <h3 class="text-center">Contest List</h3>
    <div class="main">
    <table class="table table-striped table-bordered table-hover contest_list" width="100%">
        <thead>
            <th class="text-center">
                Contest ID
            </th>
            <th class="text-center">
                Contest Title
            </th>
            <th class="text-center">
                Start Time
            </th>
            <th class="text-center">
                Type
            </th>
            <th class="text-center">
                Status
            </th>
            @if(Request::session()->get('uid') <=2 && Request::session()->get('uid'))
                <th class="text-center">
                    Management
                </th>
            @endif
        </thead>
        @if(isset($contests))
            @foreach($contests as $contest)
                <tr>
                    <td class="text-center">
                        <a href="/contest/{{ $contest->contest_id }}">
                            {{ $contest->contest_id }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="/contest/{{ $contest->contest_id }}">
                            {{ $contest->contest_name }}
                        </a>
                    </td>
                    <td class="text-center">
                        {{ $contest->begin_time }}
                    </td>
                    <td class="text-center">
                        @if($contest->contest_type == 0)
                            Public
                        @elseif($contest->contest_type == 1)
                            Private
                        @elseif($contest->contest_type == 2)
                            Register
                        @endif
                    </td>
                    <td class="text-center">
                        @if($contest->status=="Running")
                            <span class="badge contest_list_status_running">{{ $contest->status }}</span>
                        @elseif($contest->status=="Ended")
                            <span class="badge contest_list_status_ended">{{ $contest->status }}</span>
                        @endif
                    </td>
                    @if(Request::session()->get('uid') <=2 && Request::session()->get('uid'))
                        <td>
                            <a href="/dashboard/contest/{{ $contest->contest_id }}"><button>Manage Contest</button></a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif

    </table>
    </div>

    @if(!isset($first_page))
        <a href="/contest/p/{{ $page_id - 1 }}">Previous Page</a>
    @endif
    @if(!isset($last_page))
        <a href="/contest/p/{{ $page_id + 1 }}">Next Page</a>
    @endif

    <div style="padding-bottom: 40px">
@include("layout.footer")
</body>
</html>