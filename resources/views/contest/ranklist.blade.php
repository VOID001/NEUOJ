@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Ranklist</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
//            var wid=parseInt($("#ccc").css("width"));
//            if(wid<70){
//                $(".contest_ranklist td div").css("font-size","12px");
//                $(".contest_ranklist td div").css("padding","0px");
//            }
        })
    </script>
</head>
<body class="home_body">
@include("layout.header")

    <h2 class="text-center">Ranklist</h2>

    <div class="contest_ranklist_table">
    <table class="table table-striped table-bordered table-hover contest_ranklist contest_ranklist_table" >
        <div class="contest_ranklist_nav">
            <a class="btn btn-info" href="/contest/{{ $contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
        </div>
        <thead>
            <th class="contest_ranklist_user text-center" id="contest_ranklist_rank">
                Rank
            </th>
            <th class=" text-center" id="contest_ranklist_avatar">
                @if($roleCheck->is("admin"))
                    学号
                @else
                    Avatar
                @endif
            </th>
            <th class=" text-center" id="contest_ranklist_nickname">
                @if($roleCheck->is("admin"))
                    真实姓名
                @else
                    Nick name
                @endif
            </th>
            <th class="contest_ranklist_username text-center" id="contest_ranklist_solve">
                Solve
            </th>
            <th class="contest_ranklist_penalty text-center" id="contest_ranklist_penalty">
                Penalty
            </th>
            @foreach($problems as $problem)
                <th class="contest_ranklist text-center" id="contest_ranklist_problem">
                    {{ $problem->problem_title }}
                </th>
            @endforeach
        </thead>
        @foreach($users as $user)
            <tr class="table_row">
                <td class="contest_ranklist_penalty_td text-center" style="padding: 5px;">
                    {{ $counter++ }}
                </td>
                <td>
					<a href="/profile/{{ $user->uid }}" class="text-center table_row_td"><paper-button>
                    @if($roleCheck->is("admin"))
                        {{ $user->info->stu_id }}
                    @else
                        <img src="/avatar/{{$user->uid}}" style="width:35px; height:35px"/>
                    @endif
					</paper-button></a>
                </td>
                <td class="contest_ranklist_username_td" id="contest_index_problem_name_el" onclick="javascript:window.location.href=''">
					<a href="/profile/{{ $user->uid }}" class="text-center table_row_td"><paper-button>
                    @if($roleCheck->is("admin"))
                        <nobr>{{ $user->info->realname }}</nobr>
                    @else
                        <nobr>{{ $user->info->nickname }}</nobr>
                    @endif
					</paper-button></a>
                </td>
                <td class="contest_ranklist_username_td text-center" style="padding: 8px">
                    {{ $user->infoObj->totalAC }}
                </td>
                <td class="contest_ranklist_penalty_td text-center" style="padding: 5px;">
                    {{--the total penalty--}}
                    @if(intval($user->infoObj->totalPenalty / 60 / 60)<=9)
                        0{{intval($user->infoObj->totalPenalty / 60 / 60)}}@else{{intval($user->infoObj->totalPenalty / 60 / 60)}}@endif<strong>:</strong>{{ substr(strval($user->infoObj->totalPenalty % 3600 / 60 + 100), 1, 2) }}<strong>:</strong>{{ substr(strval($user->infoObj->totalPenalty % 60 + 100), 1, 2) }}
                </td>
                {{--every problem's result--}}
                @foreach($problems as $problem)
                    <td>
                        {{--first blood--}}
                        @if(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "First Blood")
            <div class="btn btn-primary"style="width: 100%; font-size:10px">
            @if( intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) <= 9)0{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}@else{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}@endif<strong>:</strong>{{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60 + 100), 1, 2) }}<strong>:</strong>{{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 60 + 100), 1, 2) }}
    @if($user->infoObj->penalty[$problem->contest_problem_id])
        ({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
    @endif
            </div>
                        {{--only accepted--}}
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "Accepted")
            <div class="btn btn-success"style="width: 100%; font-size:10px">
            @if( intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) <=9)0{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}@else{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}@endif<strong>:</strong>{{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60 + 100), 1, 2) }}<strong>:</strong>{{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 60 + 100), 1, 2) }}
    @if($user->infoObj->penalty[$problem->contest_problem_id])
        ({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
    @endif
            </div>
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && ($user->infoObj->result[$problem->contest_problem_id] == "Rejudging" || $user->infoObj->result[$problem->contest_problem_id] == "Pending"))
                            <div class="btn btn-default"style="width: 100%;font-size:8px">
                                Pending/Rejudging
                            </div>
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]))
                            <div class="btn btn-danger text-center"style="width: 100%;font-size:10px">
                                ({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
                            </div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
    </div>

<div style="padding-bottom: 40px"></div>
@include("layout.footer")
</body>
</html>
