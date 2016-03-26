<!doctype html>
<html>
<head>
    <title>Add Problem</title>
    @include("layout.head")
    @include("layout.wysiwyg_head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/problem.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_problem").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10">

    @if(!isset($error))
        @foreach($infos as $info)
            <div>{{ $info }}</div>
        @endforeach
    @else
        @foreach($errors as $error)
            <div>{{ $error }}</div>
        @endforeach
    @endif
        <h2 class="text-center">Add Problem</h2>

    <div class="form-group dashboard_problem_add_table">
        <form action="/dashboard/problem/add" method="post" enctype="multipart/form-data" class="form-inline">
            {{ csrf_field() }}
                <div class="dashboard_problem_add_span">Problem Title</div>
                <input class="form-control"type="text" value="" name="title" style="width: 100%"/>
                <div class="dashboard_problem_add_span">Problem Description</div>
                <textarea class="form-control problem_add_wsg" name="description"></textarea>
                <div class="dashboard_problem_add_span">Problem Visibility</div>
                <input type = "radio" name = "visibility_locks" value = "0" checked/>Unlock
                <input type = "radio" name = "visibility_locks" value = "1"/>Lock
                <div class="dashboard_problem_add_span">Memory Limit (KB)</div>
                <input class="form-control" type="text" name="mem_limit" value="65536"style="width: 100%"/>
                <div class="dashboard_problem_add_span">Time Limit (S)</div>
                <input class="form-control" type="text" name="time_limit" value="1000"style="width: 100%"/>
                <div class="dashboard_problem_add_span">Output Limit (KB)</div>
                <input class="form-control" type="text" name="output_limit" value="5120"style="width: 100%"/>
                <div class="dashboard_problem_add_span">Input</div>
            <textarea class="form-control problem_add_wsg" name="input"></textarea><br>
                <div class="dashboard_problem_add_span">Output</div>
                <textarea class="form-control problem_add_wsg" name="output"></textarea><br>
                <div class="dashboard_problem_add_span">Sample Input</div>
                <textarea class="form-control" name="sample_input"style="width: 100%;height: 100px"></textarea><br>
                <div class="dashboard_problem_add_span">Sample Output</div>
                <textarea class="form-control" name="sample_output"style="width: 100%;height: 100px"></textarea><br>
                <div class="dashboard_problem_add_span">Source</div>
                <textarea class="form-control" name="source"style="width: 100%;height: 100px"></textarea><br><br>
                <!-- Now only support single testcase -->
                @if($testcases == NULL)
                    <div class="dashboard_problem_add_span text-center" style="background: #95A5A6;">You do not have any testcase here</div>
                    <div class="dashboard_problem_add_span">Upload Input File</div>
                    <input class="form-control" type="file" name="input_file[]"style="width: 100%"/>
                    <div class="dashboard_problem_add_span">Upload Output File</div>
                    <input class="form-control"type="file" name="output_file[]"style="width: 100%"/>
                @else
                    @foreach($testcases as $testcase)
                        <div class="dashboard_problem_add_span">Upload Input File</div>
                        <input class="form-control"type="file" name="input_file[]"style="width: 100%"/>
                        <div class="dashboard_problem_add_span">Upload Output File</div>
                        <input class="form-control"type="file" name="output_file[]"style="width: 100%"/>
                    @endforeach
                @endif
                <div style="margin-top: 10px">
                    <input class="btn btn-default pull-right" type="submit" value="Save"/>
                </div>
        </form>
    </div>
    <div style="padding-bottom: 60px"></div>
</div>
    @include('layout.wysiwyg_foot')
</body>
</html>
