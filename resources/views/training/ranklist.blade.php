<!doctype html>
<html>
<head>
    <title>Train {{ $train_name }} Ranklist</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/extendPagination.js"></script>
    <script>
        $(function(){
            $("#training").addClass("active");
            var targetHerf = "/training/{{ $train_id }}/ranklist/p/";
            $("#callBackPager").extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
        })
    </script>
</head>
<body>
    @include("layout.header")
    <h3 class="custom-heading">Train <b class="text-success">{{$train_name}}</b> Ranklist</h3>
    <div class="front-container">
        <div class="front-time-box">
            <a class="btn btn-info" href="/training/{{$train_id}}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
        </div>
        <div id="contest-ranklist-table-responsive">
            <table class="table table-striped table-bordered custom-list">
                <thead class="front-green-thead">
                    <th class="text-center" id="contest-ranklist-rank">Rank</th>
                    <th class="text-center" id="contest-ranklist-id">Username</th>
                    <th class=" text-center" id="contest-ranklist-name">Nickname</th>
                    <th class="text-center" id="contest-ranklist-solve">Chapter</th>
                    <th class="text-center" id="contest-ranklist-penalty">Finish Time</th>
                </thead>
                @foreach($ranklist as $user)
                    <tr>
                        <td>{{ $page_user * ($page_id - 1) + $counter++ }}</td>
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['nickname'] }}</td>
                        <td>{{ $user['chapter'] }}</td>
                        <td>{{ $user['submit_time'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="text-center" id="callBackPager"></div>
        <div style="padding-bottom: 40px"></div>
    </div>
@include("layout.footer")
</body>
</html>