<!doctype html>
<html>
<head>
    <title>Edit Problem</title>
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

    @if(!isset($error))
        @foreach($infos as $info)
            <div>{{ $info }}</div>
        @endforeach
    @else
        @foreach($errors as $error)
            <div>{{ $error }}</div>
        @endforeach
    @endif
    <h3 class="text-center">Edit Problem</h3>
    <div class="form-group dashboard_problem_add_table">
    <form action="/dashboard/problem/{{ $problem->problem_id }}" method="post" enctype="multipart/form-data" class="form-inline">
        {{ csrf_field() }}

        <div class="dashboard_problem_add_span">Problem Title</div>
        <input class="form-control"type="text" value="{{ $problem->title }}" name="title"style="width: 100%"/>
        <div class="dashboard_problem_add_span">Problem Description</div>
        <textarea class="form-control"name="description"style="width: 100%;height: 100px">{{ $problem->description or "" }}</textarea>
        <div class="dashboard_problem_add_span">Memory Limit</div>
        <input class="form-control"type="text" name="mem_limit" value="{{ $problem->mem_limit }}"style="width: 100%"/>
        <div class="dashboard_problem_add_span">Time Limit</div>
        <input class="form-control"type="text" name="time_limit" value="{{ $problem->time_limit }}"style="width: 100%"/>
        <div class="dashboard_problem_add_span">Output Limit</div>
        <input class="form-control"type="text" name="output_limit" value="{{ $problem->output_limit }}"style="width: 100%"/>
        <div class="dashboard_problem_add_span">Input</div>
        <textarea class="form-control"name="input"style="width: 100%;height: 100px">{{ $problem->input or "" }}</textarea>
        <div class="dashboard_problem_add_span">Output</div>
        <textarea class="form-control"name="output"style="width: 100%;height: 100px">{{ $problem->output or "" }}</textarea>
        <div class="dashboard_problem_add_span">Sample Input</div>
        <textarea class="form-control"name="sample_input"style="width: 100%;height: 100px">{{ $problem->sample_input or ""}}</textarea>
        <div class="dashboard_problem_add_span">Sample Output</div>
        <textarea class="form-control"name="sample_output"style="width: 100%">{{ $problem->sample_output or "" }}</textarea>
        <div class="dashboard_problem_add_span">Source</div>
        <textarea class="form-control"name="source"style="width: 100%;height: 100px">{{ $problem->source or "" }}</textarea><br><br>
        <!-- Now only support single testcase -->
        @if($testcases == NULL)
            <div class="text-center dashboard_problem_add_span"style="background: #95A5A6;">You do not have any testcase here</div>
            <div class="dashboard_problem_add_span">Upload Input File</div>
            <input class="form-control"type="file" name="input_file[]"style="width: 100%"/>
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
            <input class="btn btn-default pull-right"type="submit" value="Save"/>
        </div>
    </form>
    </div>
    <div style="padding-bottom: 60px"></div>
</div>
</body>
</html>
