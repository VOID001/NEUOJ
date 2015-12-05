<!doctype html>
<html>
<head>
    <title>Problem</title>
    <?php require("./UI/head.php");?>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php require("./UI/header.php");?>
    <h3 class="text-center">Problem: {{ $title }}</h3>
    <div class="text-center text-primary">Time limit: {{ $time_limit }}s&nbsp;&nbsp;&nbsp;&nbsp;Mem limit:{{ $mem_limit }}K @if($is_spj == 1) <b>Special Judge</b>@endif</div>
    <div class="panel panel-default main">

        <h3>Problem Description</h3>
        <div>{{ $description }}</div>
        <hr>
        <h3>Input</h3>
        <div>123</div>
        <hr>
        <h3>Output</h3>
        <div>123</div>
        <hr>
        <h3>Sample Input</h3>
        <div>123</div>
        <hr>
        <h3>Sample Output</h3>
        <div>123</div>
        <hr>
        <h3>Source</h3>
        <div>admin</div>
    </div>
        @if(Request::session()->get('username') != NULL)
            <div class="text-center" style="padding-bottom: 50px"><a href="/submit/{{ $problem_id }}" class="btn btn-success">submit</a></div>
        @else
            <div class="text-center" style="padding-bottom: 50px">Sign in to Submit your code</div>
        @endif



    <?php  require("./UI/footer.php");?>
</body>
<html>