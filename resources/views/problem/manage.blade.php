<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_problem").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10">
    <div>{{ $status or "" }}</div>
    <h3 class="text-center">Problem</h3>

    <div class="dashboard_problem_table">
        <a class="btn btn-default" href="/dashboard/problem/add/">Add Problem</a>
        <table class="table table-bordered table-hover" id="dashboard_problem_list">
            <thead>
                <tr>
                    <th class="text-left">Title</th>
                    <th class="text-center">Problem ID</th>
                    <th class="text-center">Visibility_Lock</th>
                    <th class="text-center">Created_at</th>
                    <th class="text-center">Updated_at</th>
                    <th class="text-center">Operate</th>
                </tr>
            </thead>
            @foreach($problems as $problem)
                <tr>
                    <td class="text-left">{{ $problem->title }}</td>
                    <td class="text-center">{{ $problem->problem_id }}</td>
                    <td class="text-center">{{ $problem->visiblity_locks }}</td>
                    <td class="text-center">{{ $problem->created_at }}</td>
                    <td class="text-center">{{ $problem->updated_at }}</td>
                    <td class="text-center"><a class="btn btn-default" href="/dashboard/problem/{{ $problem->problem_id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>

                <form action="/dashboard/problem/{{ $problem->problem_id }}" method="POST"
                class="dashboard_problem_table_form">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input class="btn btn-default" type="submit" value="Delete"/>
                </form></td></tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
