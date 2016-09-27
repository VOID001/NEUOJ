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
            $("#ranklist").addClass("active");
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
            background-color: #2ecc71 !important;
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
                    <th class="text-center" width="30%">Nickname</th>
                    <th class="text-center" width="9%">Solved</th>
                    <th class="text-center" width="9%">Submitted</th>
                    <th class="text-center" width="9%">AC Ratio</th>
                </tr>
            </thead>
            @foreach($ranklist as $user)
                @if($page_user * ($page_id - 1) + $counter <= 3)
                <tr class="top3 text-warning">
                    <td>
                        <i class="fa fa-hand-peace-o" style="color: #aaff00" aria-hidden="true"></i>
                        {{ $page_user * ($page_id - 1) + $counter++ }}
                    </td>
                    <td class=" custom-word"><a href="/profile/{{$user['uid']}}">{{ $user['nickname'] }}</a></td>
                    <td>{{ $user['ac_count'] }}</td>
                    <td>{{ $user['submit_count'] }}</td>
                    <td>{{ $user['ac_ratio'] }}</td>
                </tr>
                @else
                    <tr >
                        <td>
                            {{ $page_user * ($page_id - 1) + $counter++ }}
                        </td>
                        <td class=" custom-word"><a href="/profile/{{$user['uid']}}">{{ $user['nickname'] }}</a></td>
                        <td>{{ $user['ac_count'] }}</td>
                        <td>{{ $user['submit_count'] }}</td>
                        <td>{{ $user['ac_ratio'] }}</td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
    <div class="text-center" id="callBackPager"></div>
    <div style="padding-bottom: 40px"></div>
@include("layout.footer")
</body>
</html>