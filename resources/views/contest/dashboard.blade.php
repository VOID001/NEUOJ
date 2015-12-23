<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_contest").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10">
    <h3 class="text-center">Contest</h3>
    <div class="dashboard_problem_table">
    <a class="btn btn-default" href="/dashboard/contest/add">New Contest</a>
    @if(isset($contests))
        <table class="table table-bordered table-hover" id="dashboard_problem_list">
            <thead>
                <th class="text-center">Contest ID</th>
                <th class="text-center">Contest Name</th>
                <th class="text-center">Type</th>
                <th class="text-center">Status</th>
                <th class="text-center">Begin Time</th>
                <th class="text-center">End Time</th>
                <th class="text-center">Operation</th>
            </thead>
            @foreach($contests as $contest)
                <tr>
                    <td class="text-center">
                        {{ $contest->contest_id }}
                    </td>
                    <td class="text-center">
                        {{ $contest->contest_name }}
                    </td>
                    <td class="text-center">
                        @if($contest->type == 0)
                            Public
                        @elseif($contest->type == 1)
                            Private
                        @else
                            Register
                        @endif
                    </td>
                    <td class="text-center">
                        @if(time() > $contest->begin_time)
                            Pending
                        @elseif(time() >= $contest->begin_time && time() <= $contest->end_time)
                            Running
                        @else
                            Stopped
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $contest->begin_time }}
                    </td>
                    <td class="text-center">
                        {{ $contest->end_time }}
                    </td>
                    <td class="text-center">
                        <a class="btn btn-default" href="/dashboard/contest/{{ $contest->contest_id }}">Edit Contest</a>
                        <form method="post" action="/dashboard/contest/{{ $contest->contest_id }}"class="dashboard_problem_table_form">
                            {{ method_field('DELETE') }}
                            <input type="submit"class="btn btn-default" value="delete contest"/>
                        </form>

                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    </div>
</div>
</body>
</html>