<!doctype html>
<html>
<head>
    <title>Problem List</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/problem.css">
    <script type="text/javascript">
        $(function(){
            $("#problem").addClass("active");
        })
    </script>
</head>
<body>
    @include("layout.header")

    <h3 class="text-center">Problem List</h3>
    <div class="main">
        <form class="form-inline">
            <span style="font-size: 18px">Search:</span><input class="form-control" style="width: 200px;" aria-controls="problemset" placeholder="请输入关键字" type="text">
            <input type="submit" class="btn btn-info form-control" value="&nbsp;&nbsp;Go&nbsp;&nbsp; ">
        </form>

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
                        <tr>
                            <td class="text-center"><a href="/problem/{{ $problem->problem_id }}">{{ $problem->problem_id }}</a></td>
                            <td class="text-left" id="problem_title_author_el"><a href="/problem/{{ $problem->problem_id }}"><nobr>{{ $problem->title }}</nobr></a></td>
                            <td class="text-center">{{ $problem->difficulty }}</td>
                            <td class="text-center">{{ $problem->ac_count. "/" . $problem->submission_count }}</td>
                            <td class="text-center" id="problem_title_author_el"><nobr>{{ $problem->author }}</nobr></td>
                            {{--<td class="text-center">{{ $problem->visibility_locks }}</td>--}}
                        </tr>
                @endforeach
            @endif
            </table>


        <ul class="pager" role="fanye">
            @if(!isset($firstPage))
                 <li ><a href="/problem/p/{{ $page_id - 1 }}">&laquo;Previous</a></li>
            @endif
            @if(!isset($lastPage))
                <li><a href="/problem/p/{{ $page_id + 1 }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
            @endif
        </ul>

        <div>

        </div>
    </div>

    <div style="padding-bottom: 40px">
    </div>

    @include("layout.footer")
</body>
</html>