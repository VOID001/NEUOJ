<!doctype html>
<html>
<head>
    <title>Ranklist</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/extendPagination.js"></script>
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
</head>
<body>
    @include("layout.header")
    <h3 align = center  >Ranklist</h3>
        <table align="center" border="1">
            <tr>
                <th>Rank</th>
                <th>Nickname</th>
                <th>Solved</th>
                <th>Submitted</th>
                <th>AC Ratio</th>
            </tr>
            @foreach($ranklist as $user)
                <tr>
                    <td>{{ $page_user * ($page_id - 1) + $counter++ }}</td>
                    <td>{{ $user['nickname'] }}</td>
                    <td>{{ $user['ac_count'] }}</td>
                    <td>{{ $user['submit_count'] }}</td>
                    <td>{{ $user['ac_ratio'] }}</td>
                </tr>
            @endforeach
        </table>
        <div class="text-center" id="callBackPager"></div>
        <div style="padding-bottom: 40px"></div>
@include("layout.footer")
</body>
</html>