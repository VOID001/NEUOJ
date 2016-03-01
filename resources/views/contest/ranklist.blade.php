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
        })
    </script>
</head>
<body class="home_body">
@include("layout.header")

    <h3 class="text-center">Ranklist</h3>

    <div class="contest_ranklist_table">
    <table class="table table-striped table-bordered table-hover contest_ranklist" style="table-layout:fixed; overflow:hidden">
        <div class="contest_ranklist_nav">
            <a class="btn btn-info" href="/contest/{{ $contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
        </div>
        <thead>
            <th class="contest_ranklist_user text-center" style="width:5%">
                Rank
            </th>
            <th class=" text-center" style="width:5%">
                Avatar
            </th>
            <th class=" text-center" style="width:10%">
                Nick name
            </th>
            <th class="contest_ranklist_username text-center" style="width:5%">
                Solved
            </th>
            <th class="contest_ranklist_penalty text-center" style="width:5%">
                Penalty
            </th>
            @foreach($problems as $problem)
                <th class="contest_ranklist text-center" style="width:5%">
                    {{ $problem->problem_title }}
                </th>
            @endforeach
        </thead>
        <?php $rrr = 1; ?>
        @foreach($users as $user)
            <tr>

                <td class="contest_ranklist_penalty_td text-center" style="padding: 5px;">
                    {{ $rrr++ }}
                </td>
                <td class="text-center">
                    <img src="/avatar/{{$user->uid}}" style="width:35px; height:35px"/>
                </td>
                <td class="contest_ranklist_username_td text-center">
                    <a href="/profile/{{ $user->uid }}">
                    {{ $user->nick_name }}
                    </a>
                </td>
                <td class="contest_ranklist_username_td text-center" style="padding: 8px">
                    {{ $user->infoObj->totalAC }}
                </td>
                <td class="contest_ranklist_penalty_td text-center" style="padding: 5px;">
            {{ intval($user->infoObj->totalPenalty / 60 / 60) }} :
            {{ intval($user->infoObj->totalPenalty % 3600 / 60) }}:
            {{ intval($user->infoObj->totalPenalty % 60)  }}
            {{--{{ $user->infoObj->totalPenalty  }}--}}
                </td>
                @foreach($problems as $problem)
                    <td>
                        @if(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "First Blood")
            <div class="btn btn-primary"style="width: 100%; font-size:12px">
            {{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }} :
            {{ intval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60) }}:
            {{ intval($user->infoObj->time[$problem->contest_problem_id] % 60)  }}
    ({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
            </div>
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "Accepted")
            <div class="btn btn-success"style="width: 100%; font-size:12px">
            {{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }} :
            {{ intval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60) }}:
            {{ intval($user->infoObj->time[$problem->contest_problem_id] % 60)  }}
    ({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
            </div>
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && ($user->infoObj->result[$problem->contest_problem_id] == "Rejudging" || $user->infoObj->result[$problem->contest_problem_id] == "Pending"))
                            <div class="btn btn-default"style="width: 100%;font-size:12px">
                                Pending/Rejudging
                            </div>
                        @elseif(isset($user->infoObj->result[$problem->contest_problem_id]))
                            <div class="btn btn-danger text-center"style="width: 100%;font-size:12px">
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
