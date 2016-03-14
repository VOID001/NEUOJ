@inject('roleController', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Contest {{ $contest->contest_id }}</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <meta http-equiv="Refresh" content="20">
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
        })
    </script>
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
</head>
<body class="home_body">
@include("layout.header")
    <div class="main">
        <h1 class="text-center">{{ $contest->contest_name }}
            @if($contest->status=="Running")
                <span class="badge contest_single_status_running">{{ $contest->status }}</span></h1>
            @elseif($contest->status=="Ended")
                <span class="badge contest_single_status_ended">{{ $contest->status }}</span></h1>
            @else
            <span class="badge contest_single_status_ended">{{ $contest->status }}</span></h1>
            @endif
        <span class="contest_single_begintime text-right" id="contest_single_begintime">Begin Time: {{ $contest->begin_time }}</span>
        <span class="contest_single_endtime text-left"id="contest_single_endtime">End Time: {{ $contest->end_time }}</span>
        <div class="text-center">
            <span id="contest_countdown_text">Time Remaining:</span>
            <span class="badge countdown">
                <strong id="day_show">0天</strong>
                <strong id="hour_show">0时</strong>
                <strong id="minute_show">00分</strong>
                <strong id="second_show">00秒</strong>
            </span>
        </div>
        <div class="text-center contest_single_nav">
            <a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/status">Status</a>
            <a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
            <a class="btn btn-default" href="#">BBS Not Available</a>
        </div>
        <div>
            <!--
            We will add customize view of contest later
            <form action="{{ Request::server('REQUEST_URI') }}" method="get">
                <button name="desc" value="1">Sort the problem in AC Ratio Desc</button>
            </form>
            -->
        </div>
        <table class="table table-striped table-bordered table-hover contest_list_single contest_index_table" width="100%">
            <thead>
            <th class="text-center" id="contest_index_status">
	       Status
	    </th>
            <th class="text-center" id="contest_index_problem_id">
                Problem ID
            </th>
            <th class="text-center" id="contest_index_short_name">
                Short Name
            </th>
            <th id="contest_index_problem_name">
                Problem Name
            </th>
            <th class="text-center" id="contest_index_ac">
                AC/Total(Ratio)
            </th>
            @if($roleController->is('admin'))
                <th class="text-center" id="contest_index_rejudge">
                    Rejudge
                </th>
            @endif
            </thead>
            @foreach($problems as $problem)
                <tr
                @if($problem->realProblemName !== -1 && ($roleController->is("admin") || $contest->status != "Pending"))
                class="table_row" onclick="javascript:window.location.href='/contest/{{ $contest->contest_id }}/problem/{{ $problem->contest_problem_id }}'"
                @endif
                >
                    <td class="text-center table_row_td">
                        @if($problem->thisUserFB)
                            <span class="glyphicon glyphicon-flag" style="color: #5cb85c"></span>
                            <span class="glyphicon glyphicon-map-marker"></span>
                        @elseif($problem->thisUserAc)
                            <span class="glyphicon glyphicon-map-marker"></span>
                        @endif
                     </td>
                    <td class="text-center table_row_td">
                        {{ $problem->contest_problem_id }}
                    </td>
                    <td class="text-center table_row_td">
                        {{ $problem->problem_title }}
                    </td>
                    <td class="table_row_td" id="contest_index_problem_name_el">
                        <nobr>{{ $problem->realProblemName === -1 ? "[Error] Problem Deleted!" : $problem->realProblemName }}</nobr>
                    </td>
                    <td class="text-center table_row_td">
                        @if($problem->acSubmissionCount != 0)
                            {{ $problem->acSubmissionCount }} / {{ $problem->totalSubmissionCount }}({{ intval($problem->acSubmissionCount/$problem->totalSubmissionCount * 100) }}%)
                        @else
                            0 / 0
                        @endif
                    </td>
                    @if($roleController->is('admin'))
                        <td class="text-center">
                            <form method="post" action="/rejudge/{{ $contest->contest_id }}/{{ $problem->contest_problem_id }}">
                                {{ csrf_field() }}
                                <input class="btn btn-danger" id="contest_index_rejudge_btn" type="submit" value="Rejudge"/>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
<div style="padding-bottom: 40px">
@include("layout.footer")
</body>
</html>
