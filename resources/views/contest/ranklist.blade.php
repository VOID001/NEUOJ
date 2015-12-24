<h1>Board</h1>

<table border="1">
    <thead>
        <th>
            User Name
        </th>
        <th>
            Penalty
        </th>
        @foreach($problems as $problem)
            <th>
                {{ $problem->problem_title }}
            </th>
        @endforeach
    </thead>
    @foreach($users as $user)
        <tr>
            <td>
                {{ $user->username }}
            </td>
            <td>
                {{ date('H:i:s', $user->infoObj->totalPenalty) }}
            </td>
            @foreach($problems as $problem)
                <td>
                    @if(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "First Blood")
                        FB! <br/>{{ $user->infoObj->time[$problem->contest_problem_id] }}<br/>{{ $user->infoObj->penalty[$problem->contest_problem_id] }}
                    @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "Accepted")
                        OK! <br/>{{ $user->infoObj->time[$problem->contest_problem_id] }}<br/>{{ $user->infoObj->penalty[$problem->contest_problem_id] }}
                    @elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && ($user->infoObj->result[$problem->contest_problem_id] == "Rejudging" || $user->infoObj->result[$problem->contest_problem_id] == "Pending"))
                        Pending/Rejudging
                    @elseif(isset($user->infoObj->result[$problem->contest_problem_id]))
                        Wrong QWQ <br/> {{ $user->infoObj->penalty[$problem->contest_problem_id] }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
</table>