<!doctype html>
<html>
<head>
    <title>Train {{ $train_name }} Ranklist</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/extendPagination.js"></script>
    <script>
        $(function(){
            $("#problem").addClass("active");
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
    <table border = 1 align="center">
        <caption>Train {{ $train_name }} Ranklist</caption>
        <tr><th>rank</th><th>nickname</th><th>chapter</th><th>finish time</th></tr> 
        @foreach($ranklist as $user)
            <tr>
                <td>{{ $page_user * ($page_id - 1) + $counter++ }}</td>
                <td>{{ $user['nickname'] }}</td>
                <td>{{ $user['chapter'] }}</td>
                <td>{{ $user['submit_time'] }}</td>
            </tr>
        @endforeach
    </table>
    <div class="text-center" id="callBackPager"></div>
    <div style="padding-bottom: 40px">
    </div>
    @include("layout.footer")
</body>
</html>