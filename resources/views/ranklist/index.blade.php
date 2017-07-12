<!doctype html>
<html>
<head>
    <title>Ranklist</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/extendPagination.js"></script>
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <script>
        $(function(){
            $("#rating").addClass("active");
            var targetHerf = "/ranklist/p/";
            $("#callBackPager").extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
        })
    </script>
    <style>
        .custom-list thead {
            background-color: #5bc0de;
        }
        .top3 td {
            color: #fff;
            background-color: rgb(242, 192, 86);
        }
        .top10 td{
            background-color: rgb(233, 233, 216);
        }
        .top30 td{
            color: #fff;
            background-color: rgb(186, 110, 64);
        }
        .top3 .stu_id {
            color: rgb(233, 233, 216);
        }
        .top10 .stu_id{
            color: rgb(186, 110, 64);
        }
        .top30 .stu_id{
            color: rgb(242, 192, 86);
        }
        .stu_id {
            cursor: pointer;
            color: #e8554e;
        }
        .show_inf {
            position: absolute;
            width: 150px;
            height: 150px;
            background: #fff;
            padding: 16px 3px 18px 3px;
            text-align: center;
            webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: none;
            border-radius: 3px;
            background-color: #f3f3f3;
        }
        .show_inf p {
            font-size:12px;
            color: rgb(186, 110, 64);
        }
    </style>
</head>
<body>
    @include("layout.header")
    <h3 class="custom-heading">Ranklist</h3>
    <div class="front-big-container">
        <table  class="table table-striped table-bordered custom-list">
            <thead>
                <tr>
                    <th class="text-center" width="5%">Rank</th>
                    <th class="text-center" width="8%">Student ID</th>
                    @if($roleCheck->is('admin'))
                        <th class="text-center" width="8%">TureName</th>
                    @endif
                    <th class="text-center" width="30%">Nickname</th>
                    <th class="text-center" width="8%">Solved</th>
                    <th class="text-center" width="8%">Submitted</th>
                    <th class="text-center" width="8%">AC Ratio</th>
                </tr>
            </thead>
            @foreach($ranklist as $user)
                @if($page_user * ($page_id - 1) + $counter <= 3)
                <tr class="top3">
                    <td>
                        <i class="fa fa-hand-peace-o" style="color:#fff" aria-hidden="true"></i>
                        {{ $page_user * ($page_id - 1) + $counter++ }}
                    </td>
                    @if($user['stu_id'] == null)
                        <td><a class="stu_id" id="user{{$page_user * ($page_id - 1) + $counter}}">Null</a>
                    @else
                        <td><a class="stu_id" id="user{{$page_user * ($page_id - 1) + $counter}}">{{ $user['stu_id'] }}</a>
                            @endif
                            <div class="show_inf" id="user{{$page_user * ($page_id - 1) + $counter}}_inf">
                                <img src="/avatar/{{$user['uid']}}" class="img-circle" width="80px" height="80px">
                                <br/>
                                <br/>
                                <p class="custom-word">School: {{$user['school']}}</p>
                            </div>
                        </td>
                        @if($roleCheck->is('admin'))
                            <td>{{ $user['realname'] }}</td>
                        @endif

                    <td class="custom-word"><a href="/profile/{{$user['uid']}}">{{ $user['nickname'] }}</a></td>
                    <td>{{ $user['ac_count'] }}</td>
                    <td>{{ $user['submit_count'] }}</td>
                    <td>{{ $user['ac_ratio'] }}</td>
                </tr>
                @else
                    @if($page_user * ($page_id - 1) + $counter <= 10)
                    <tr class="top10">
                    @elseif($page_user * ($page_id - 1) + $counter <= 30)
                    <tr class="top30">
                    @endif
                        <td>
                            {{ $page_user * ($page_id - 1) + $counter++ }}
                        </td>
                        @if($user['stu_id'] == null)
                            <td><a class="stu_id" id="user{{$page_user * ($page_id - 1) + $counter}}">Null</a>
                        @else
                            <td><a class="stu_id" id="user{{$page_user * ($page_id - 1) + $counter}}">{{ $user['stu_id'] }}</a>
                        @endif
                            <div class="show_inf" id="user{{$page_user * ($page_id - 1) + $counter}}_inf">
                                <img src="/avatar/{{$user['uid']}}" class="img-circle" width="80px" height="80px">
                                <br/>
                                <br/>
                                <p class="custom-word">School: {{$user['school']}}</p>
                            </div>
                        </td>
                            @if($roleCheck->is('admin'))
                                <td>{{ $user['realname'] }}</td>
                            @endif
                        <td class="custom-word"><a href="/profile/{{$user['uid']}}">{{ $user['nickname'] }}</a></td>
                        <td>{{ $user['ac_count'] }}</td>
                        <td>{{ $user['submit_count'] }}</td>
                        <td>{{ $user['ac_ratio'] }}</td>
                    </tr>
                @endif
                <script>
                    $("#user{{$page_user * ($page_id - 1) + $counter}}").mouseover(function(){
                        $("#user{{$page_user * ($page_id - 1) + $counter}}_inf").fadeIn(500);
                    })
                    $("#user{{$page_user * ($page_id - 1) + $counter}}").mouseout(function(){
                        $("#user{{$page_user * ($page_id - 1) + $counter}}_inf").fadeOut(500);
                    })
                </script>
            @endforeach
        </table>
    </div>
    <div class="text-center" id="callBackPager"></div>
    <div style="padding-bottom: 40px"></div>
@include("layout.footer")
</body>
</html>