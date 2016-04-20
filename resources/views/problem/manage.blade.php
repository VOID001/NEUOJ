<!doctype html>
<html>
<head>
    <title>Manage Problem</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/problem.css">
    <script src="/js/extendPagination.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#dashboard_problem").addClass("dashboard_subnav_active");
        })
        $(document).ready(function(){
            var targetHerf = "/dashboard/problem/p/";
            $("#callBackPager").extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
        });
    </script>
</head>
<body>
@include("layout.dashboard_nav")

<div class="col-xs-10">
    <div class="warning_box">
        <ol>
            @if(!$errors->isEmpty())
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            @endif
        </ol>
    </div>
    <h3 class="text-center">Problem</h3>
    <div class="dashboard_problem_table">
        <a class="btn btn-grey pull-left" href="/dashboard/problem/add/">Add Problem</a>
        <div>
            <form enctype="multipart/form-data" action="/dashboard/problem/import" method="POST">
                {{ csrf_field() }}
                <input type="submit" value="Import" class="form-control pull-right btn-grey" style="width: 10%;"/>
                <input type="file" name="xml" class="form-control pull-right" value="XML Format Data" style="width: 30%;"/>
            </form>
        </div>
        <div>{{ $status or "" }}</div>
        <table class="table table-bordered table-hover problem_manage_table" id="dashboard_problem_list">
            <thead>
                <tr>
                    <th class="text-center" id="problem_manage_id">Problem ID</th>
                    <th class="text-left" id="problem_manage_title">Title</th>
                    <th class="text-center" id="problem_manage_vl">Visibility_Lock</th>
                    <th class="text-center" id="problem_manage_created_at">Created_at</th>
                    <th class="text-center" id="problem_manage_updated_at">Updated_at</th>
                    <th class="text-center" id="problem_manage_operate">Operate</th>
                </tr>
            </thead>
            @foreach($problems as $problem)
                <tr>
                    <td class="text-center">{{ $problem->problem_id }}</td>
                    <td class="text-left" id="problem_title_author_el"><nobr>{{ $problem->title }}</nobr></td>
                    <td class="text-center">
                    @if($problem->visibility_locks == 0)
                    <a class="btn btn-success problem_manage_lock_size" href="/dashboard/problem/{{ $problem->problem_id }}/visibility">&nbsp;&nbsp;
                    Lock({{ $problem->used_times }})
                    @else
                    <a class="btn btn-danger problem_manage_lock_size" href="/dashboard/problem/{{ $problem->problem_id }}/visibility">&nbsp;&nbsp;
                    Unlock({{ $problem->used_times }})
                    @endif
                    &nbsp;&nbsp;</a></td>
                    <td class="text-center">{{ $problem->created_at }}</td>
                    <td class="text-center">{{ $problem->updated_at }}</td>
                    <td class="text-center"><a class="btn btn-grey" href="/dashboard/problem/{{ $problem->problem_id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>

                <form action="/dashboard/problem/{{ $problem->problem_id }}" method="POST"
                class="dashboard_problem_table_form">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input class="btn btn-grey" type="submit" value="Delete"/>
                </form></td></tr>
            @endforeach
        </table>
    </div>
    <div class="text-center" id="callBackPager"></div>
</div>
</body>
</html>
