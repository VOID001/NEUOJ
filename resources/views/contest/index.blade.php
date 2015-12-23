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
    <script type="text/javascript">

        var now=new Date().getTime();
        var end=new Date("{{$contest->end_time}}").getTime();
        var time=(end-now)/1000;
        var intDiff = parseInt(time);//倒计时总秒数量
        function timer(intDiff){
            window.setInterval(function(){
                var day=0,
                        hour=0,
                        minute=0,
                        second=0;//时间默认值
                if(intDiff > 0){
                    day = Math.floor(intDiff / (60 * 60 * 24));
                    hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                    minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                    second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                $('#day_show').html(day+"天");
                $('#hour_show').html('<s id="h"></s>'+hour+'时');
                $('#minute_show').html('<s></s>'+minute+'分');
                $('#second_show').html('<s></s>'+second+'秒');
                intDiff--;
            }, 1000);
        }
        $(function () {
            timer(intDiff);
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
            @endif
        <span class="contest_single_begintime text-right" id="contest_single_begintime">Begin Time: {{ $contest->begin_time }}</span>
        <span class="contest_single_endtime text-left"id="contest_single_endtime">End Time: {{ $contest->end_time }}</span>
        <div class="text-center">
            Time Remaining:
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
            <a class="btn btn-default" href="#">Discuss</a>
        </div>

        <div>
            <!--
            We will add customize view of contest later
            <form action="{{ Request::server('REQUEST_URI') }}" method="get">
                <button name="desc" value="1">Sort the problem in AC Ratio Desc</button>
            </form>
            -->
        </div>

        <table class="table table-striped table-bordered table-hover contest_list_single" width="100%">
            <thead>
            <th>
                AC/Total(Ratio)
            </th>
            <th>
                Problem ID
            </th>
            <th>
                Short Name
            </th>
            <th>
                Problem Name
            </th>
            </thead>

            @foreach($problems as $problem)
                <tr>
                    <td>
                        @if($problem->acSubmissionCount != 0)
                            {{ $problem->acSubmissionCount }} / {{ $problem->totalSubmissionCount }}({{ $problem->acSubmissionCount/$problem->totalSubmissionCount * 100 }}%)
                        @else
                            0 / 0()
                        @endif
                    </td>
                    <td>
                        @if($problem->thisUserFB)
                            [FB!]
                        @endif
                        @if($problem->thisUserAc)
                            [AC]
                        @endif
                            {{ $problem->contest_problem_id }}
                    </td>
                    <td>
                        @if((session('uid') && session('uid') <=2) || $contest->status != "Pending")
                            <a href="/contest/{{ $contest->contest_id }}/problem/{{ $problem->problem_id }}">
                        @endif
                            {{ $problem->problem_title }}
                        @if((session('uid') && session('uid') <=2) || $contest->status != "Pending")
                            </a>
                        @endif
                    </td>
                    <td>
                        {{ $problem->realProblemName }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

<div style="padding-bottom: 40px">
@include("layout.footer")
</body>
</html>
