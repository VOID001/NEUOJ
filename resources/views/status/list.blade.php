@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Status</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/status.css">
    <!--<meta http-equiv="Refresh" content="20"> <!-- Do not refresh too frequently -->
    <script type="text/javascript">
        $(function(){
            $("#status").addClass("active");
            //$('.selectpicker').selectpicker();
        })
    </script>
</head>
<body>
    @include("layout.header")
    <h3 class="text-center">Status List</h3>
<div class="status_main">
    @if(isset($contest))
        <div class="contest_single_nav">
             <a class="btn btn-info" href="/contest/{{ $contest->contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
             <a class="btn btn-info" href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
            <span id="contest_countdown_text">Time Remaining:</span>
            <span class="badge countdown">
                <strong id="day_show">0天</strong>
                <strong id="hour_show">0时</strong>
                <strong id="minute_show">00分</strong>
                <strong id="second_show">00秒</strong>
            </span>
        </div>
        <script type="text/javascript">
            var begin=new Date("{{$contest->begin_time}}").getTime();
            var now = new Date("{{ date('Y-m-d H:i:s') }}").getTime();
            var end=new Date("{{$contest->end_time}}").getTime();
            var wholetime=(end-begin)/1000;
            var pretime=(begin-now)/1000;
            var remaintime=(end-now)/1000;
            function timer(){
                window.setInterval(function(){
                    var day=0,
                            hour=0,
                            minute=0,
                            second=0;//时间默认值
                    if(pretime<=0){
                        $('#contest_countdown_text').html("Time Remaining:");
                        if(remaintime > 0){
                            day = Math.floor(remaintime / (60 * 60 * 24));
                            hour = Math.floor(remaintime / (60 * 60)) - (day * 24);
                            minute = Math.floor(remaintime/ 60) - (day * 24 * 60) - (hour * 60);
                            second = Math.floor(remaintime) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                        }
                        if (minute <= 9) minute = '0' + minute;
                        if (second <= 9) second = '0' + second;
                        $('#day_show').html(day+"天");
                        $('#hour_show').html('<s id="h"></s>'+hour+'时');
                        $('#minute_show').html('<s></s>'+minute+'分');
                        $('#second_show').html('<s></s>'+second+'秒');
                        remaintime--;
                    }
                    else{
                        $('#contest_countdown_text').html("Pending:");
                        day = Math.floor(pretime/ (60 * 60 * 24));
                        hour = Math.floor(pretime / (60 * 60)) - (day * 24);
                        minute = Math.floor(pretime / 60) - (day * 24 * 60) - (hour * 60);
                        second = Math.floor(pretime) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                        if (minute <= 9) minute = '0' + minute;
                        if (second <= 9) second = '0' + second;
                        $('#day_show').html(day+"天");
                        $('#hour_show').html('<s id="h"></s>'+hour+'时');
                        $('#minute_show').html('<s></s>'+minute+'分');
                        $('#second_show').html('<s></s>'+second+'秒');
                        pretime--;
//                    if(pretime==0)
//                    {
//                        location.reload();
//                    }
                    }
                }, 1000);
            }
            $(function () {
                timer();
            })
        </script>
    @endif
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
            <option name="ce">Compile Error</option>
        </select>
        <input type="submit" value="Filter" class="btn btn-info form-control"/>
    </form>
    <table class="table table-striped table-bordered table-hover status_table" id="statuslist" width="100%">
    <thead>
            <td class="text-center" id="status_run_id">Run ID</td>
            <td class="text-center" id="status_submit_time">
                Submit Time
            </td>
            <td class="text-center" id="status_user_id">
                User ID
            </td>
            <td class="text-center" id="status_username">
                Username
            </td>
            <td class="text-center" id="status_username">
                Nickname
            </td>
            <td class="text-left" id="status_problem_title">
                Problem Title
            </td>
            <td class="text-center" id="status_result">
                Result
            </td>
            <td class="text-center" id="status_language">
                Lang
            </td>
            <td class="text-center" id="status_exec_mem">
                Ex_mem
            </td>
            <td class="text-center" id="status_exec_time">
                Ex_time
            </td>
            @if($roleCheck->is('admin'))
                <td class="text-center" id="status_rejudge">
                    Rejudge
                </td>
            @endif
    </thead>
    @if($submissions != NULL)
        @foreach($submissions as $submission)
            <tr
            @if(Request::session()->get('uid') == $submission->uid)
            class="status_table_row table_row table_row_nohl"
            @elseif($roleCheck->is("admin"))
            class="table_row table_row_nohl"
            @else
            class="table_row_nohl"
            @endif
            >
                <td class="text-center" title="{{ $submission->runid }}">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="table_row_td"><paper-button>
                @endif
                {{ $submission->runid }}
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </paper-button></a>
                @endif
                </td>
                <td class="text-center">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="table_row_td"><paper-button>
                @endif
                {{ substr($submission->submit_time, 2) }}
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </paper-button></a>
                @endif
                </td>
                <td><a href="/profile/{{ $submission->uid }}" class="text-center table_row_td"><paper-button>{{ $submission->uid }}</paper-button></a></td>
                <td><a href="/profile/{{ $submission->uid }}" class="text-center table_row_td"><paper-button><nobr>{{ $submission->userName }}</nobr></paper-button></a></td>
                <td id="status_username_title_el"><a href="/profile/{{ $submission->uid }}" class="text-center table_row_td"><paper-button><nobr>{{ $submission->nickname }}</nobr></paper-button></a></td>
                <td class="text-left" id="status_username_title_el"><nobr>&nbsp;{{ $submission->problemTitle }}</nobr></td>
                <td class="text-center">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" style="font-size: 14px"
                @else
                <span style="font-size: 14px"
                @endif
                @if($submission->result=="Accepted")
                        class="label label-success"><span class="glyphicon glyphicon-ok " style="color: #000"></span>Accepted
                @elseif($submission->result=="Compile Error")
                    class="label label-default">Compile Error
                @elseif($submission->result=="Wrong Answer")
                    class="label label-danger">Wrong Answer
                @elseif($submission->result=="Pending")
                    class="label label-info">Pending
                @elseif($submission->result=="Rejudging")
                    class="label label-info">Rejudging
                @else
                    class="label label-warning">{{$submission->result}}
                @endif
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </a>
                @else
                </span>
                @endif
                @if($submission->result=="Accepted")
                    @if(isset($submission->sim->similarity) && $roleCheck->is("admin"))
                        <a class="label label-primary" id="status_list_sim_a" title="runid:{{ $submission->sim->sim_runid }}" href="/status/sim?left={{ $submission->sim->runid }}&right={{ $submission->sim->sim_runid }}" style="margin-left:5px">{{ $submission->sim->similarity }}%</a>
                    @elseif(isset($submission->sim->similarity))
                        <label class="label label-primary" id="status_list_sim_a" title="runid:{{ $submission->sim->sim_runid }}" style="margin-left:5px">{{ $submission->sim->similarity }}%</label>
                    @endif
                @endif
                </td>
                <td class="text-center">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="table_row_td"><paper-button>
                @endif{{ $submission->lang }}
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </paper-button></a>
                @endif
                </td>
                <td class="text-center">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="table_row_td"><paper-button>
                @endif
                @if($submission->exec_mem < 1024)
                    {{ $submission->exec_mem }} Byte
                @elseif($submission->exec_mem < 1024*1024)
                    {{ (int)($submission->exec_mem / 1024) }} KB
                @else
                    {{ (int)($submission->exec_mem / 1024 / 1024) }} MB
                @endif
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </paper-button></a>
                @endif
                </td>
                <td class="text-center">
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                <a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="table_row_td"><paper-button>
                @endif
                @if($submission->exec_time < 1)
                    {{ (int)($submission->exec_time * 1000) }}ms
                @else
                    {{ $submission->exec_time }}s
                @endif
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </paper-button></a>
                @endif
                </td>
                @if($roleCheck->is('admin'))
                    <td>
                        <form method="post" action="/rejudge/{{ $submission->runid }}">
                            {{ csrf_field() }}
                            <input class="btn btn-danger" id="status_rejudge_btn" type="submit" value="Rejudge"/>
                        </form>
                    </td>
                @endif
                @if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
                </a>
                @endif
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
    </div>
    @include("layout.footer")
<script type="text/javascript">

    function freshResult()
    {
        var tableObj = window.document.getElementById("statuslist")
        var rows = tableObj.rows;
        for(var i = 1; i < rows.length; i++)
        {
            var run_id = rows[i].cells[0].title;
            var result = rows[i].cells[6].innerHTML;
            var resultObj = rows[i].cells;
            if(result.indexOf('Pending') != -1 || result.indexOf('Rejudging') != -1)
            {
                fetchResult(run_id, resultObj);
            }
        }
    }

    function fetchResult(run_id, resultObj) {
        console.log("run_id = " + run_id);
        $.ajax({
            url: "/ajax/submission",
            type: "GET",
            data: {
                run_id: run_id
            },
            dataType: "json",
        }).done(function(json){
            console.log(json);
            var tmpResult;
            var tmpObj = resultObj[6].innerHTML;
            /* if it's a span, then do not add anchor, else add anchor href */
            if(tmpObj.charAt(1)=='s')
                tmpResult = "<span style=\"font-size: 14px\"";
            else
                tmpResult = "<a href=\"/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif\" style=\"font-size: 14px\"";

            if(json.result == "Accepted")
                tmpResult = tmpResult + "class=\"label label-success\"><span class='glyphicon glyphicon-ok ' style='color: #000'></span>Accepted";
            else if(json.result == "Wrong Answer")
                tmpResult = tmpResult + "class=\"label label-danger\">Wrong Answer";
            else if(json.result == "Compile Error")
                tmpResult = tmpResult + "class=\"label label-default\">Compile Error";
            else if(json.result == "Pending")
                tmpResult = tmpResult + "class=\"label label-info\">Pending";
            else if(json.result == "Rejudging")
                tmpResult = tmpResult + "class=\"label label-info\">Rejudging";
            else
                tmpResult = tmpResult + "class=\"label label-warning\">" + json.result;

            if(tmpObj.charAt(1)=='s')
                tmpResult = tmpResult + "</span>";
            else
                tmpResult = tmpResult + "</a>";

            resultObj[6].innerHTML = tmpResult;

            if(json.exec_mem > 1024 && json.exec_mem < 1024 * 1024)
                json.exec_mem = json.exec_mem / 1024 + " KB";
            else if(json.exec_mem >= 1024 * 1024)
                json.exec_mem = parseInt(json.exec_mem / 1024 / 1024 | 0) + "MB";
            else
                json.exec_mem = parseInt(json.exec_mem | 0) + " Byte";

            if(json.exec_time < 1)
                json.exec_time = json.exec_time * 1000 + "ms";
            else
                json.exec_time = json.exec_time + "s";
            resultObj[8].innerHTML = json.exec_mem;
            resultObj[9].innerHTML = json.exec_time;
        })
    }

    setInterval("freshResult()", 1000);

</script>
</body>
</html>
