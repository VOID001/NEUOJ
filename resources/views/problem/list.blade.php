<!doctype html>
<html>
<head>
    <title>Problem List</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/problem.css">
    <script src="/js/extendPagination.js"></script>
    <script src="/js/searchFunction.js"></script>
    <script>
        $(function(){
            $("#problem").addClass("active");
            var targetHerf = "/problem/p/";
            $("#callBackPager").extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
            $.ajax({
                url: '/ajax/problem_title',
                type: 'GET',
                async: true,
                dataType: 'json',
                success: function(result) {
                    bindSearchFunction(result);
                }
            });
        })
    </script>
</head>
<body>
    @include("layout.header")

    <h3 class="text-center">Problem List</h3>
    <div class="main">
        <div class="form-inline">
            <form action="/problem/quick_access">
                <span style="font-size: 18px">Quick Access: </span>
                <div class="search-container">
                    <input class="form-control search-title problem-id" name="query" style="width: 320px;" aria-controls="problemset" placeholder="Input ProblemID or Problem Title to Search" type="text" autocomplete="off" />
                    <div class="search-option hidden"></div>
                </div>
                <input type="submit" class="btn btn-info form-control" value="&nbsp;&nbsp;Go&nbsp;&nbsp;">
            </form>
        </div>

        <table class="table table-striped table-bordered table-hover problem_table" id="problemlist" width="100%">
            <thead>
                <tr>
                    <th class="text-center" id="problem_id">Problem ID</th>
                    <th class="text-left" id="problem_title">Title</th>
                    <th class="text-center" id="problem_difficulty">Difficulty</th>
                    <th class="text-center" id="problem_acratio">AC/Submit</th>
                    <th class="text-center" id="problem_author">Author</th>
                    {{--<th class="text-center">Visibility_Lock(use for debug version)</th>--}}
                </tr>
            </thead>
            @if($problems != NULL)
                @foreach($problems as $problem)
                        <tr class="table_row">
                            <td><a href="/problem/{{ $problem->problem_id }}" class="text-center table_row_td"><paper-button>{{ $problem->problem_id }}</paper-button></a></td>
                            <td id="problem_title_author_el"><a href="/problem/{{ $problem->problem_id }}" class="text-left table_row_td"><paper-button><nobr>&nbsp;{{ $problem->title }}</nobr></paper-button></a></td>
                            <td class="text-center">{{ $problem->difficulty }}</td>
                            <td><a href="/status/p/1?pid={{ $problem->problem_id }}" class="text-center table_row_td"><paper-button>{{ $problem->ac_count. "/" . $problem->submission_count }}</paper-button></a></td>
                            <td class="text-center" id="problem_title_author_el"><nobr>{{ $problem->author }}</nobr></td>
                        </tr>
                @endforeach
            @endif
            </table>
    </div>
    <div class="text-center" id="callBackPager"></div>
    <div style="padding-bottom: 40px">
    </div>
    @include("layout.footer")
</body>
</html>