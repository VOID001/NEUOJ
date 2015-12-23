<!doctype html>
<html>
<head>
    <title>Problem</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <meta http-equiv="Refresh" content="20"> <!-- Do not refresh too frequently -->
    <script type="text/javascript">
        $(function(){
            $("#status").addClass("active");
            $('.selectpicker').selectpicker();
        })
    </script>
</head>
<body>
    @include("layout.header")

    @if(isset($contest))
        <a href="/contest/{{ $contest->contest_id }}">Back</a>
        <a href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
        [JS CountDown here please]
    @endif
    <h3 class="text-center">Status List</h3>
<div class="status_main">
    @if(!isset($contest))
        <form action="/status/p/1" method="GET" class="form-inline">
    @else
        <form action="/contest/{{ $contest->contest_id }}/status/p/1" method="GET" class="form-inline">
    @endif
        <span style="font-size: 15px">Username:</span>
        <input class="form-control" style="width: 150px;"type="text" name="username"/>
        <span style="font-size: 15px;margin-left: 10px">Problem ID:</span>
        <input class="form-control" style="width: 150px;"type="text" name="pid"/>
        <span style="font-size: 15px;margin-left: 10px">Language:</span>
        <select name="lang" class="form-control">
            <option name="all">All</option>
            <option name="c">C</option>
            <option name="java">Java</option>
            <option name="cpp">C++</option>
            <option name="cpp11">C++11</option>
        </select>
        <span style="font-size: 15px;margin-left: 10px">Result:</span>
        <select name="result" class="form-control">
            <option name="all">All</option>
            <option name="wt">Pending</option>
            <option name="ac">Accepted</option>
            <option name="wa">Wrong Answer</option>
            <option name="re">Runtime Error</option>
            <option name="pe">Presentation Error</option>
            <option name="tle">Time Limit Exceed</option>
            <option name="mle">Memory Limit Exceed</option>
            <option name="ole">Output Limit Exceed</option>
            <option name="je">Judge Error</option>
        </select>
        <input type="submit" value="Filter" class="btn btn-info form-control"/>
    </form>

    <table class="table table-striped table-bordered table-hover" id="statuslist" width="100%" style="margin-top: 5px">
    <thead>
            <td class="text-center">Run ID</td>
            <td class="text-center">
                Submit Time
            </td>
            <td class="text-center">
                User ID
            </td>
            <td class="text-center">
                Username
            </td>
            <td class="text-left">
                Problem Title
            </td>
            <td class="text-center">
                Result
            </td>
            <td class="text-center">
                Exec_mem
            </td>
            <td class="text-center">
                Exec_time
            </td>
            <td class="text-center">
                View Code
            </td>
    </thead>
    @if($submissions != NULL)
        @foreach($submissions as $submission)
            <tr>
                <td class="text-center">{{ $submission->runid }}</td>
                <td class="text-center">{{ $submission->submit_time }}</td>
                <td class="text-center">{{ $submission->uid }}</td>
                <td class="text-center">{{ $submission->userName }}</td>
                <td class="text-left">{{ $submission->problemTitle }}</td>
                @if($submission->result=="Accepted")
                    <td class="text-center"><span class="label label-success" style="font-size: 15px"><span class="glyphicon glyphicon-ok " style="color: #000"></span>Accepted</span></td>
                @elseif($submission->result=="Compile Error")
                        <td class="text-center"><span class="label label-default" style="font-size: 13px">Compile Error</span></td>
                @elseif($submission->result=="Wrong Answer")
                    <td class="text-center"><span class="label label-danger" style="font-size: 13px">Wrong Answer</span></td>
                @elseif($submission->result=="Pending")
                    <td class="text-center"><span class="label label-info" style="font-size: 13px">Pending</span></td>
                @else
                    <td class="text-center"><span class="label label-warning" style="font-size: 13px">{{$submission->result}}</span></td>
                @endif
                <td class="text-center">{{ $submission->exec_mem }}</td>
                <td class="text-center">{{ $submission->exec_time }}</td>
                <td class="text-center">
                    @if(Request::session()->get('uid') == $submission->uid || (session('uid') && session('uid') <= 2))
                        @if(!isset($contest))
                            <a href="/status/{{ $submission->runid }}">View Source</a>
                        @else
                            <a href="/contest/{{ $contest->contest_id }}/status/{{ $submission->runid }}">View Source</a>
                        @endif
                    @else
                        View Source
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </table>

    <ul class="pager" role="fanye">
        @if(!isset($firstPage))
            @if(!isset($contest))
                <li><a href="/status/p/{{ $page_id - 1 }}{{ $queryStr }}">&laquo;Previous</a></li>
            @else
                <li><a href="/contest/{{ $contest->contest_id }}/status/p/{{ $page_id - 1 }}{{ $queryStr }}">&laquo;Previous</a></li>
            @endif
        @endif
        @if(!isset($lastPage))
            @if(!isset($contest))
                <li><a href="/status/p/{{ $page_id + 1 }}{{ $queryStr }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
            @else
                <li><a href="/contest/{{ $contest->contest_id }}/status/p/{{ $page_id + 1 }}{{ $queryStr }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
            @endif
        @endif
    </ul>

    </div>

    <div style="padding-bottom: 40px">
    @include("layout.footer")
</body>
</html>
